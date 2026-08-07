import { db } from "@/lib/db";

export async function GET(req) {
  try {
    const url = new URL(req.url);
    const type = url.searchParams.get("type");

    let query = "";
    if (type === "kategori") {
      query = "SELECT * FROM master_kategori ORDER BY nama_kategori ASC";
    } else if (type === "satuan") {
      query = "SELECT * FROM master_satuan ORDER BY nama_satuan ASC";
    } else {
      return new Response(
        JSON.stringify({ error: "Invalid type. Use type=kategori or type=satuan" }),
        { status: 400 }
      );
    }

    const [rows] = await db.query(query);

    return new Response(
      JSON.stringify({ data: rows }),
      { status: 200, headers: { "Content-Type": "application/json" } }
    );

  } catch (err) {
    console.error("Error fetching master data:", err);
    return new Response(JSON.stringify({ error: "Failed to fetch master data" }), { status: 500 });
  }
}
