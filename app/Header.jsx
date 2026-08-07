export default function Header() {
    return (
        <>
            {/* Header biru dengan tombol di kanan */}
            <div className="bg-blue-600 text-white p-4 flex justify-end items-center gap-6">
                <button className="flex items-center gap-2 hover:opacity-80">
                    <span>🏠</span> Home
                </button>
                <button className="flex items-center gap-2 hover:opacity-80">
                    <span>🔒</span> Logout
                </button>
            </div>

            {/* Judul besar di bawah header, tanpa background biru */}
            <div className="p-6 text-3xl font-bold text-gray-800">
                ⟵ Master Barang
            </div>
        </>
    );
}
