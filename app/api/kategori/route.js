// /app/api/kategori/route.js
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
    const kode = url.searchParams.get("kode_kategori") || "";
    const nama = url.searchParams.get("nama_kategori") || "";

    let where = "WHERE 1=1";
    let params = [];

    if (kode) {
      where += " AND kode_kategori LIKE ?";
      params.push(`%${kode}%`);
    }

    if (nama) {
      where += " AND nama_kategori LIKE ?";
      params.push(`%${nama}%`);
    }

    // ======================
    // SORTING (aman)
    // ======================
    const allowedSortFields = [
      "kode_kategori",
      "nama_kategori",
      "keterangan",
    ];

    let orderBy = "";
    if (allowedSortFields.includes(sort_by)) {
      const direction = sort_dir.toLowerCase() === "desc" ? "DESC" : "ASC";
      orderBy = `ORDER BY ${sort_by} ${direction}`;
    }

    // ======================
    // MAIN QUERY
    // ======================
    const query = `
      SELECT *
      FROM master_kategori
      ${where}
      ${orderBy}
      LIMIT ? OFFSET ?
    `;

    const [rows] = await db.query(query, [...params, limit, offset]);

    // ======================
    // TOTAL DATA
    // ======================
    const countQuery = `
      SELECT COUNT(*) AS total
      FROM master_kategori
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
    console.error("Error fetching kategori:", err);
    return new Response(JSON.stringify({ error: "Failed to fetch kategori" }), {
      status: 500,
    });
  }
}
