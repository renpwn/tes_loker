// /app/api/barang/route.js
import { db } from "@/lib/db";

export async function GET(req) {
  try {
    const url = new URL(req.url);

    const page = parseInt(url.searchParams.get("page") || "1");
    const limit = 10;
    const offset = (page - 1) * limit;

    const sort_by = url.searchParams.get("sort_by") || "";   // nama kolom
    const sort_dir = url.searchParams.get("sort_dir") || ""; // asc/desc

    // Ambil filter
    const kode = url.searchParams.get("kode") || "";
    const nama = url.searchParams.get("nama") || "";
    const tgl_from = url.searchParams.get("tgl_from") || "";
    const tgl_to = url.searchParams.get("tgl_to") || "";
    const kategori = url.searchParams.get("kategori") || "";
    const satuan = url.searchParams.get("satuan") || "";
    const ada_stock = url.searchParams.get("ada_stock") || "";

    // Build WHERE
    let where = "WHERE 1=1";
    let params = [];

    if (kode) {
      where += " AND kode LIKE ?";
      params.push(`%${kode}%`);
    }

    if (nama) {
      where += " AND nama LIKE ?";
      params.push(`%${nama}%`);
    }

    if (tgl_from) {
      where += " AND tgl >= ?";
      params.push(tgl_from);
    }

    if (tgl_to) {
      where += " AND tgl <= ?";
      params.push(tgl_to);
    }

    if (kategori) {
      where += " AND kategori LIKE ?";
      params.push(`%${kategori}%`);
    }

    if (satuan) {
      where += " AND satuan LIKE ?";
      params.push(`%${satuan}%`);
    }

    if (ada_stock) {
      where += " AND ada_stock = ?";
      params.push(ada_stock === "true" ? 1 : 0);
    }

    // ======================
    // SORTING (AMAN!)
    // ======================
    const allowedSortFields = [
      "kode",
      "nama",
      "tgl",
      "kategori",
      "satuan",
      "ada_stock",
      "keterangan"
    ];

    let orderBy = "";
    if (allowedSortFields.includes(sort_by)) {
      const direction = sort_dir.toLowerCase() === "desc" ? "DESC" : "ASC";
      orderBy = `ORDER BY ${sort_by} ${direction}`;
    }

    const query = `
      SELECT *
      FROM vmaster_barang
      ${where}
      ${orderBy}
      LIMIT ? OFFSET ?
    `;

    function buildFinalSQL(query, params) {
      let i = 0;
      return query.replace(/\?/g, () => {
        const val = params[i++];
        if (val === null) return "NULL";
        if (typeof val === "number") return val;
        return `'${val}'`;
      });
    }

    function formatDate(date) {
      const d = new Date(date);
      const year = d.getFullYear();
      const month = String(d.getMonth() + 1).padStart(2, "0");
      const day = String(d.getDate()).padStart(2, "0");
      return `${year}-${month}-${day}`;
    }


    const finalSQL = buildFinalSQL(query, [...params, limit, offset]);
    console.log("FINAL SQL:", finalSQL);

    const [rows] = await db.query(query, [...params, limit, offset]);
    rows.forEach(r => {
      r.tgl = formatDate(r.tgl);
    });
    return Response.json(rows);

  } catch (err) {
    console.error("Error fetching barang:", err);
    return new Response(JSON.stringify({ error: "Failed to fetch barang" }), {
      status: 500,
    });
  }
}
