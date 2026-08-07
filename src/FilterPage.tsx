import {
  Form,
  useLoaderData,
  useNavigate,
  type LoaderFunctionArgs,
} from "react-router-dom";

/* ================= LOADER ================= */
export async function loader({ request }: LoaderFunctionArgs) {
  const res = await fetch("/data/indonesia_regions.json");
  const data = await res.json();

  const url = new URL(request.url);
  const provinceId = url.searchParams.get("province");
  const regencyId = url.searchParams.get("regency");
  const districtId = url.searchParams.get("district");

  const provinces = data.provinces;

  const regencies = provinceId
    ? data.regencies.filter((r: any) => r.province_id === Number(provinceId))
    : [];

  const districts = regencyId
    ? data.districts.filter((d: any) => d.regency_id === Number(regencyId))
    : [];

  const selectedProvince = provinces.find(
    (p: any) => p.id === Number(provinceId)
  );

  const selectedRegency = regencies.find(
    (r: any) => r.id === Number(regencyId)
  );

  const selectedDistrict = districts.find(
    (d: any) => d.id === Number(districtId)
  );

  return {
    provinces,
    regencies,
    districts,
    selectedProvince,
    selectedRegency,
    selectedDistrict,
    provinceId,
    regencyId,
    districtId,
  };
}

/* ================= COMPONENT ================= */
export default function FilterPage() {
  const {
    provinces,
    regencies,
    districts,
    selectedProvince,
    selectedRegency,
    selectedDistrict,
    provinceId,
    regencyId,
    districtId,
  } = useLoaderData() as any;

  const navigate = useNavigate();
  const handleReset = () => navigate("/");

  return (
    <div className="min-h-screen bg-[#F5F7FA] flex">
      {/* ================= SIDEBAR ================= */}
      <aside className="w-[280px] border-r border-gray-200 flex flex-col">
        {/* Sidebar Header */}
        <div className="h-16 border-b-0 border-gray-200 flex items-center px-6">
          <div className="flex items-center gap-3">
            <div className="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
              {/* Globe */}
              <svg
                xmlns="http://www.w3.org/2000/svg"
                className="w-5 h-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                strokeWidth={1.5}
              >
                <circle cx="12" cy="12" r="9" />
                <path d="M3 12h18M12 3c3 3 3 15 0 18M12 3c-3 3-3 15 0 18" />
              </svg>
            </div>
            <span className="font-semibold text-gray-800">
              Frontend Assessment
            </span>
          </div>
        </div>

        {/* Sidebar Content */}
        <div className="flex-1 px-8 py-10">
          <h2 className="text-[11px] tracking-[0.35em] text-gray-400 font-semibold mb-10">
            FILTER WILAYAH
          </h2>

          <Form method="get" className="space-y-8">
            {/* PROVINCE */}
            <div>
              <label className="block text-[11px] tracking-[0.25em] text-gray-400 font-semibold mb-3">
                PROVINSI
              </label>
              <div className="relative">
                <span className="absolute left-4 top-3 text-gray-400">
                  {/* Map Icon */}
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    className="w-4 h-4"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    strokeWidth={1.5}
                  >
                    <path
                      strokeLinecap="round"
                      strokeLinejoin="round"
                      d="M3 6l6-2 6 2 6-2v14l-6 2-6-2-6 2V6z"
                    />
                    <path
                      strokeLinecap="round"
                      strokeLinejoin="round"
                      d="M9 4v14M15 6v14"
                    />
                  </svg>
                </span>
                <select
                  name="province"
                  defaultValue={provinceId ?? ""}
                  onChange={(e) => e.currentTarget.form?.requestSubmit()}
                  className="w-full border border-gray-300 rounded-2xl pl-10 pr-4 py-3 text-sm"
                >
                  <option value="">Pilih Provinsi</option>
                  {provinces.map((p: any) => (
                    <option key={p.id} value={p.id}>
                      {p.name}
                    </option>
                  ))}
                </select>
              </div>
            </div>

            {/* REGENCY */}
            <div>
              <label className="block text-[11px] tracking-[0.25em] text-gray-400 font-semibold mb-3">
                KOTA/KABUPATEN
              </label>
              <div className="relative">
                <span className="absolute left-4 top-3 text-gray-400">
                  {/* Building Icon */}
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    className="w-4 h-4"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    strokeWidth={1.5}
                  >
                    <path
                      strokeLinecap="round"
                      strokeLinejoin="round"
                      d="M4 20V6a2 2 0 012-2h4v16M14 20V10a2 2 0 012-2h2v12M3 20h18"
                    />
                    <path
                      strokeLinecap="round"
                      strokeLinejoin="round"
                      d="M8 8h2M8 12h2M16 12h1M16 15h1"
                    />
                  </svg>
                </span>
                <select
                  name="regency"
                  defaultValue={regencyId ?? ""}
                  disabled={!provinceId}
                  onChange={(e) => e.currentTarget.form?.requestSubmit()}
                  className="w-full border border-gray-300 rounded-2xl pl-10 pr-4 py-3 text-sm"
                >
                  <option value="">Pilih Kota/Kabupaten</option>
                  {regencies.map((r: any) => (
                    <option key={r.id} value={r.id}>
                      {r.name}
                    </option>
                  ))}
                </select>
              </div>
            </div>

            {/* DISTRICT */}
            <div>
              <label className="block text-[11px] tracking-[0.25em] text-gray-400 font-semibold mb-3">
                KECAMATAN
              </label>
              <div className="relative">
                <span className="absolute left-4 top-3 text-gray-400">
                  {/* Pin Icon */}
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    className="w-4 h-4"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    strokeWidth={1.5}
                  >
                    <path d="M12 21s-6-5-6-10a6 6 0 1112 0c0 5-6 10-6 10z" />
                    <circle cx="12" cy="11" r="2" />
                  </svg>
                </span>
                <select
                  name="district"
                  defaultValue={districtId ?? ""}
                  disabled={!regencyId}
                  onChange={(e) => e.currentTarget.form?.requestSubmit()}
                  className="w-full border border-gray-300 rounded-2xl pl-10 pr-4 py-3 text-sm"
                >
                  <option value="">Pilih Kecamatan</option>
                  {districts.map((d: any) => (
                    <option key={d.id} value={d.id}>
                      {d.name}
                    </option>
                  ))}
                </select>
              </div>
            </div>

            {/* RESET */}
            <button
              type="button"
              onClick={handleReset}
              className="flex items-center justify-center gap-2 w-full border-2 border-blue-500 text-blue-500 rounded-2xl py-3 font-semibold hover:bg-blue-50 transition"
            >
              {/* Filter Slash */}
              <svg
                xmlns="http://www.w3.org/2000/svg"
                className="w-4 h-4"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                strokeWidth={1.5}
              >
                <path d="M4 6h16M6 6l6 7v5l4-2v-3l6-7" />
                <path d="M3 3l18 18" />
              </svg>
              RESET
            </button>
          </Form>
        </div>
      </aside>

      {/* ================= RIGHT PANEL ================= */}
      <div className="flex-1 flex flex-col">
        {/* Top Bar */}
        <div className="h-16 bg-white border-b border-gray-200 flex items-center px-10">
          <nav className="breadcrumb text-sm">
            <span
              className={
                !selectedProvince
                  ? "text-blue-500 font-medium"
                  : "text-gray-400"
              }
            >
              Indonesia
            </span>

            {selectedProvince && (
              <>
                <span className="text-gray-400"> &gt; </span>
                <span
                  className={
                    !selectedRegency
                      ? "text-blue-500 font-medium"
                      : "text-gray-400"
                  }
                >
                  {selectedProvince.name}
                </span>
              </>
            )}

            {selectedRegency && (
              <>
                <span className="text-gray-400"> &gt; </span>
                <span
                  className={
                    !selectedDistrict
                      ? "text-blue-500 font-medium"
                      : "text-gray-400"
                  }
                >
                  {selectedRegency.name}
                </span>
              </>
            )}

            {selectedDistrict && (
              <>
                <span className="text-gray-400"> &gt; </span>
                <span className="text-blue-500 font-medium">
                  {selectedDistrict.name}
                </span>
              </>
            )}
          </nav>
        </div>

        {/* Main Content */}
        <div className="flex-1 flex justify-center mt-10">
          <main className="text-center space-y-8">
            {selectedProvince && (
              <div className="space-y-3">
                <p className="text-[11px] tracking-[0.4em] text-blue-400 font-semibold">
                  PROVINSI
                </p>
                <h1 className="text-[64px] font-extrabold text-gray-900">
                  {selectedProvince.name}
                </h1>
              </div>
            )}

            {selectedRegency && (
              <div className="space-y-3">
                <div className="text-gray-200 text-3xl">↓</div>
                <p className="text-[11px] tracking-[0.4em] text-blue-400 font-semibold">
                  KOTA / KABUPATEN
                </p>
                <h2 className="text-[56px] font-extrabold text-gray-900">
                  {selectedRegency.name}
                </h2>
              </div>
            )}

            {selectedDistrict && (
              <div className="space-y-3">
                <div className="text-gray-200 text-3xl">↓</div>
                <p className="text-[11px] tracking-[0.4em] text-blue-400 font-semibold">
                  KECAMATAN
                </p>
                <h3 className="text-[48px] font-extrabold text-gray-900">
                  {selectedDistrict.name}
                </h3>
              </div>
            )}
          </main>
        </div>
      </div>
    </div>
  );
}