// /app/api/stock/route.js
import { db } from "@/lib/db";

export async function GET(req) {
  try {
    const url = new URL(req.url);

    // Pagination
    const page = parseInt(url.searchParams.get("page") || "1");
    const limit = 10;
    const offset = (page - 1) * limit;

    // Sorting
    const sort_by = url.searchParams.get("sort_by") || "";
    const sort_dir = url.searchParams.get("sort_dir") || "";

    // Filtering
    const nama = url.searchParams.get("nama") || "";
    const kategori = url.searchParams.get("kategori") || "";
    const stock_from = url.searchParams.get("stock_from") || "";
    const stock_to = url.searchParams.get("stock_to") || "";
    const satuan = url.searchParams.get("satuan") || "";

    let where = "WHERE ada_stock = 1";
    let params = [];

    if (nama) {
      where += " AND nama LIKE ?";
      params.push(`%${nama}%`);
    }

    if (kategori) {
      where += " AND kategori LIKE ?";
      params.push(`%${kategori}%`);
    }

    if (stock_from) {
      where += " AND stock >= ?";
      params.push(stock_from);
    }

    if (stock_to) {
      where += " AND stock <= ?";
      params.push(stock_to);
    }

    if (satuan) {
      where += " AND satuan LIKE ?";
      params.push(`%${satuan}%`);
    }

    // ======================
    // SORTING (aman)
    // ======================
    const allowedSortFields = ["nama", "kategori", "stock", "satuan"];

    let orderBy = "";
    if (allowedSortFields.includes(sort_by)) {
      const direction = sort_dir.toLowerCase() === "desc" ? "DESC" : "ASC";
      orderBy = `ORDER BY ${sort_by} ${direction}`;
    }

    // ======================
    // MAIN QUERY
    // ======================
    const query = `
      SELECT nama, kategori, stock, satuan
      FROM vmaster_barang
      ${where}
      ${orderBy}
      LIMIT ? OFFSET ?
    `;

    const [rows] = await db.query(query, [...params, limit, offset]);

    // ======================
    // TOTAL DATA (untuk pagination)
    // ======================
    const countQuery = `
      SELECT COUNT(*) AS total
      FROM master_barang
      ${where}
    `;

    const [countRows] = await db.query(countQuery, params);
    const total = countRows[0]?.total ?? 0;

    return Response.json({
      data: rows,
      meta: {
        total,
        page,
        per_page: limit,
        total_pages: Math.ceil(total / limit),
      },
    });

  } catch (err) {
    console.error("Error fetching stock:", err);
    return new Response(JSON.stringify({ error: "Failed to fetch stock" }), {
      status: 500,
    });
  }
}
