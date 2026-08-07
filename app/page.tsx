"use client";

import { useState, useEffect, useMemo } from "react";
import {
  Card,
  CardContent
} from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import {
  Tabs,
  TabsList,
  TabsTrigger,
  TabsContent
} from "@/components/ui/tabs";
import {
  Table,
  TableHeader,
  TableHead,
  TableRow,
  TableBody,
  TableCell
} from "@/components/ui/table";
import { Label } from "@/components/ui/label";
import { Filter, ArrowUpDown, ChevronUp } from "lucide-react";
import ClearableInput from "@/app/ClearableInput";
import ClearableSelect from "@/app/ClearableSelect";
import Header from "@/app/Header";

/**
 * Notes:
 * - This component assumes your /api endpoints accept the following query params:
 *   page, per_page, sort_by, sort_dir, and filter keys (different per tab).
 * - Pagination shows 10 items per page.
 * - Sorting and filters trigger a new fetch.
 * - Styling tailored to "corporate blue-mint" header (#e7eefb) and full-width filter bar.
 */

// ====== Helpers: safe fetchers that accept params ======
async function fetchApi(path, params = {}) {
  try {
    const url = new URL(path, location.origin);
    Object.entries(params).forEach(([k, v]) => {
      if (v !== undefined && v !== null && v !== "") url.searchParams.set(k, v);
    });
    const res = await fetch(url.toString(), { cache: "no-store" });
    if (!res.ok) {
      console.error("fetchApi non-ok", url.toString(), res.status);
      return { data: [], meta: { total: 0 } };
    }
    return await res.json();
  } catch (err) {
    console.error("fetchApi error", err);
    return { data: [], meta: { total: 0 } };
  }
}

export default function Page() {
  const [activeTab, setActiveTab] = useState("barang");
  const [showFilter, setShowFilter] = useState(false);

  const [kategoriList, setKategoriList] = useState([]);
  const [satuanList, setSatuanList] = useState([]);

  const [page, setPage] = useState(1);
  const PER_PAGE = 10;

  // Sort state: sortBy can be column key, sortDir 'asc'|'desc' or ''
  const [sortBy, setSortBy] = useState("");
  const [sortDir, setSortDir] = useState("");

  // Filters
  const [filters, setFilters] = useState({
    // barang
    kode: "",
    nama: "",    
    tgl_from: "",
    tgl_to: "",
    kategori: "",
    satuan: "",
    ada_stock: "",
    // kategori
    kode_kategori: "",
    nama_kategori: "",
    // stock
    stock_nama: "",
    stock_kategori: "",
    stock_from: "",
    stock_to: "",
    stock_satuan: ""
  });

  // Data states
  const [barang, setBarang] = useState([]);
  const [barangMeta, setBarangMeta] = useState({ total: 0 });

  const [kategori, setKategori] = useState([]);
  const [kategoriMeta, setKategoriMeta] = useState({ total: 0 });

  const [stock, setStock] = useState([]);
  const [stockMeta, setStockMeta] = useState({ total: 0 });

  // Build query params depending on activeTab
  const buildParams = () => {
    const base = {
      page,
      per_page: PER_PAGE,
      sort_by: sortBy,
      sort_dir: sortDir
    };

    if (activeTab === "barang") {
      return {
        ...base,
        kode: filters.kode,
        nama: filters.nama,
        tgl_from: filters.tgl_from,
        tgl_to: filters.tgl_to,
        kategori: filters.kategori,
        satuan: filters.satuan,
        ada_stock: filters.ada_stock
      };
    }

    if (activeTab === "kategori") {
      return {
        ...base,
        kode_kategori: filters.kode_kategori,
        nama_kategori: filters.nama_kategori
      };
    }

    if (activeTab === "stock") {
      return {
        ...base,
        nama: filters.stock_nama,
        kategori: filters.stock_kategori,
        stock_from: filters.stock_from,
        stock_to: filters.stock_to,
        satuan: filters.stock_satuan
      };
    }

    return base;
  };

  // Trigger to force re-fetch when filters applied
  const [filterTrigger, setFilterTrigger] = useState(0);

  // Fetch data whenever activeTab, page, sort, or filters change
  useEffect(() => {
    const params = buildParams();

    if (activeTab === "barang") {
      fetchApi("/api/barang", params).then((res) => {
        // expect response shape { data: [...], meta: { total } } or just array
        if (Array.isArray(res)) {
          setBarang(res);
          setBarangMeta({ total: res.length });
        } else {
          setBarang(res.data || []);
          setBarangMeta(res.meta || { total: (res.data || []).length });
        }
      });
    } else if (activeTab === "kategori") {
      fetchApi("/api/kategori", params).then((res) => {
        if (Array.isArray(res)) {
          setKategori(res);
          setKategoriMeta({ total: res.length });
        } else {
          setKategori(res.data || []);
          setKategoriMeta(res.meta || { total: (res.data || []).length });
        }
      });
    } else if (activeTab === "stock") {
      fetchApi("/api/stock", params).then((res) => {
        if (Array.isArray(res)) {
          setStock(res);
          setStockMeta({ total: res.length });
        } else {
          setStock(res.data || []);
          setStockMeta(res.meta || { total: (res.data || []).length });
        }
      });
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [activeTab, page, sortBy, sortDir, filterTrigger
    /* note: filters object referenced below changes by reference, but we want to re-run on its values  JSON.stringify(filters)*/]);

  // Reset page when tab changes
  useEffect(() => {
    setPage(1);
    setSortBy("");
    setSortDir("");
  }, [activeTab]);

  useEffect(() => {
    // ambil kategori
    fetchApi("/api/master-data?type=kategori").then((res) => {
      setKategoriList(res.data || res || []);
    });

    // ambil satuan
    fetchApi("/api/master-data?type=satuan").then((res) => {
      setSatuanList(res.data || res || []);
    });
  }, []);

  // Helpers: toggle sort when clicking a column header
  const handleSort = (key) => {
    if (sortBy !== key) {
      setSortBy(key);
      setSortDir("asc");
    } else {
      // cycle asc -> desc -> none
      if (sortDir === "asc") setSortDir("desc");
      else if (sortDir === "desc") {
        setSortBy("");
        setSortDir("");
      } else setSortDir("asc");
    }
    setPage(1);
  };

  // Apply filter button (just triggers a re-fetch by updating state used in effect)
  const applyFilter = () => {
    setPage(1);
    // effect will run because filters changed (we already mutate filters via setFilters)
    // nothing else required
    // setFilters({ ...filters }); // trigger refresh manual
    setFilterTrigger((n) => n + 1);
  };

  const resetFilter = () => {
    setFilters({
      kode: "",
      nama: "",
      tgl_from: "",
      tgl_to: "",
      kategori: "",
      satuan: "",
      ada_stock: "",
      kode_kategori: "",
      nama_kategori: "",
      stock_nama: "",
      stock_kategori: "",
      stock_from: "",
      stock_to: "",
      stock_satuan: ""
    });
    setPage(1);
    setSortBy("");
    setSortDir("");
    applyFilter();
  };

  // Small helper to render the thin up/down icon state (option C style)
  const SortIcon = ({ colKey }) => {
    const isActive = sortBy === colKey;
    return (
      <span className="inline-flex items-center ml-2">
        <ArrowUpDown className={`w-4 h-4 ${isActive ? "opacity-100" : "opacity-40"}`} />
        {/* If active, show small arrow indicating asc/desc */}
        {isActive && sortDir === "asc" && <span className="sr-only">sorted ascending</span>}
        {isActive && sortDir === "desc" && <span className="sr-only">sorted descending</span>}
      </span>
    );
  };

  // Derived totals for pagination UI
  const totalItems = useMemo(() => {
    if (activeTab === "barang") return barangMeta.total || 0;
    if (activeTab === "kategori") return kategoriMeta.total || 0;
    if (activeTab === "stock") return stockMeta.total || 0;
    return 0;
  }, [activeTab, barangMeta, kategoriMeta, stockMeta]);

  const totalPages = Math.max(1, Math.ceil((totalItems || 0) / PER_PAGE));

  // Table headers definitions (key maps to backend column names ideally)
  const barangHeaders = [
    { label: "No", key: "no", sortable: false },
    { label: "Kode Barang", key: "kode", sortable: true },
    { label: "Nama Barang", key: "nama", sortable: true },
    { label: "Tanggal", key: "tgl", sortable: true },
    { label: "Kategori", key: "kategori", sortable: true },
    { label: "Satuan", key: "satuan", sortable: true },
    { label: "Ada Stock", key: "ada_stock", sortable: true },
    { label: "Keterangan", key: "keterangan", sortable: false }
  ];

  const kategoriHeaders = [
    { label: "Kode Kategori", key: "kode_kategori", sortable: true },
    { label: "Nama Kategori", key: "nama_kategori", sortable: true },
    { label: "Keterangan", key: "keterangan", sortable: false }
  ];

  const stockHeaders = [
    { label: "Nama Barang", key: "nama", sortable: true },
    { label: "Kategori", key: "kategori", sortable: true },
    { label: "Stock", key: "stock", sortable: true },
    { label: "Satuan", key: "satuan", sortable: true }
  ];

  // Tailwind classes for the "PDF-like" header: light blue background
  const headerBg = "bg-[#e7eefb]";

  function handleStockChange(key, value) {
    // Jika user menghapus isi input → izinkan kosong
    if (value === "") {
      return setFilters({ ...filters, [key]: "" });
    }

    // Jika bukan angka → jangan update
    if (!/^[0-9]+$/.test(value)) {
      return;
    }

    // Hilangkan leading zero → contoh "030" → "30"
    const cleaned = value.replace(/^0+/, "");

    // Kalau hasil kosong (misal user ketik "0") → set "0"
    const finalValue = cleaned === "" ? "0" : cleaned;

    setFilters({ ...filters, [key]: finalValue });
  }

  return (
    <>
    <Header />
    <div className="p-6">
      <Card className="p-4 shadow-xl rounded-2xl">
        <div className="flex items-start justify-between gap-4">
          <div className="w-full">
            <Tabs value={activeTab} onValueChange={(v) => setActiveTab(v)}>
              <TabsList className="flex gap-4 mb-4 w-max mx-auto">
                <TabsTrigger value="barang" className="text-sm font-semibold">Master Barang</TabsTrigger>
                <TabsTrigger value="kategori" className="text-sm font-semibold">Master Kategori Barang</TabsTrigger>
                <TabsTrigger value="stock" className="text-sm font-semibold">Stock Barang</TabsTrigger>
              </TabsList>

              {/* Filter button sits in header area */}
              <div className="flex items-center justify-end gap-3 w-full mb-4">
                <Button
                  onClick={() => setShowFilter((s) => !s)}
                  className="flex items-center gap-2"
                >
                  <Filter size={16} /> Filter
                </Button>
                <div className="text-sm text-muted-foreground">Showing per {PER_PAGE} rows</div>
              </div>

              {/* Full-width filter bar*/}
              {showFilter && (
                <Card className="w-full mt-4 mb-4 border rounded-xl">
                  <CardContent className="py-4">
                    <div className="flex items-center justify-between mb-3">
                      <div className="font-semibold">Filter - {activeTab === "barang" ? "Master Barang" : activeTab === "kategori" ? "Master Kategori" : "Stock Barang"}</div>
                      <div className="flex items-center gap-2">
                        <Button size="sm" variant="ghost" onClick={() => setShowFilter(false)}><span className="text-sm">Sembunyikan</span>
  <ChevronUp className="w-4 h-4" /></Button>
                      </div>
                    </div>

                    {/* Filter inputs layout */}
                    <div className="grid grid-cols-2 gap-8">
                      {activeTab === "barang" && (
                        <>
                          <div className="flex items-center gap-3">
                            <Label className="w-40">Kode Barang</Label><ClearableInput className="flex-1" value={filters.kode} onChange={(e) => setFilters({ ...filters, kode: e.target.value })} />
                          </div>
                          <div className="flex items-center gap-3">
                            <Label className="w-40">Nama Barang</Label><ClearableInput className="flex-1" value={filters.nama} onChange={(e) => setFilters({ ...filters, nama: e.target.value })} />
                          </div>
                          <div className="flex items-center gap-3">
                            <Label className="w-40">Tanggal</Label>
                            {/* Tanggal Dari */}
                            <ClearableInput
                              type="date"
                              className="w-40"
                              value={filters.tgl_from}
                              onChange={(e) =>
                                setFilters({ ...filters, tgl_from: e.target.value })
                              }
                            />

                            <span className="text-sm">s/d</span>

                            {/* Tanggal Sampai */}
                            <ClearableInput
                              type="date"
                              className="w-40"
                              value={filters.tgl_to}
                              onChange={(e) =>
                                setFilters({ ...filters, tgl_to: e.target.value })
                              }
                            />
                          </div>
                          <div className="flex items-center gap-3">
                            <Label className="w-40">Kategori</Label>
                            <ClearableSelect
                              value={filters.kategori}
                              onChange={(e) => setFilters({ ...filters, kategori: e.target.value })}
                            >
                              <option value="">-- Semua Kategori --</option>
                              {kategoriList.map((k) => (
                                <option key={k.id} value={k.nama_kategori}>
                                  {k.nama_kategori}
                                </option>
                              ))}
                            </ClearableSelect>

                          </div>
                          <div className="flex items-center gap-3">
                            <Label className="w-40">Satuan</Label>
                            <ClearableSelect
                              value={filters.satuan}
                              onChange={(e) => setFilters({ ...filters, satuan: e.target.value })}
                            >
                              <option value="">-- Semua Satuan --</option>
                              {satuanList.map((s) => (
                                <option key={s.id} value={s.nama_satuan}>
                                  {s.nama_satuan}
                                </option>
                              ))}
                            </ClearableSelect>
                          </div>
                          <div className="flex items-center gap-3">
                            <Label className="w-40">Ada Stock</Label>
                            <ClearableSelect
                              value={filters.ada_stock}
                              onChange={(e) => setFilters({ ...filters, ada_stock: e.target.value })}
                            >
                              <option value="">Semua</option>
                              <option value="1">Yes</option>
                              <option value="0">No</option>
                            </ClearableSelect>
                          </div>
                        </>
                      )}

                      {activeTab === "kategori" && (
                        <>
                          <div className="flex items-center gap-3">
                            <Label className="w-40">Kode Kategori</Label><ClearableInput className="flex-1" value={filters.kode_kategori} onChange={(e) => setFilters({ ...filters, kode_kategori: e.target.value })} />
                          </div>
                          <div className="flex items-center gap-3">
                            <Label className="w-40">Nama Kategori</Label><ClearableInput className="flex-1" value={filters.nama_kategori} onChange={(e) => setFilters({ ...filters, nama_kategori: e.target.value })} />
                          </div>
                        </>
                      )}

                      {activeTab === "stock" && (
                        <>
                          <div className="flex items-center gap-3">
                            <Label className="w-40">Nama Barang</Label><ClearableInput className="flex-1" value={filters.stock_nama} onChange={(e) => setFilters({ ...filters, stock_nama: e.target.value })} />
                          </div>
                          <div className="flex items-center gap-3">
                            <Label className="w-40">Kategori</Label>
                            <ClearableSelect
                              value={filters.stock_kategori}
                              onChange={(e) => setFilters({ ...filters, kategori: e.target.value })}
                            >
                              <option value="">-- Semua Kategori --</option>
                              {kategoriList.map((k) => (
                                <option key={k.id} value={k.nama_kategori}>
                                  {k.nama_kategori}
                                </option>
                              ))}
                            </ClearableSelect>
                          </div>
                          <div className="flex items-center gap-3">
                            <Label className="w-40">Stock</Label>

                            {/* Stock Dari */}
                            <ClearableInput
                              type="number"
                              className="w-40"
                              value={filters.stock_from}
                              onChange={(e) =>
                                handleStockChange("stock_from", e.target.value)
                              }
                            />

                            <span className="text-sm">s/d</span>

                            {/* Stock Sampai */}
                            <ClearableInput
                              type="number"
                              className="w-40"
                              value={filters.stock_to}
                              onChange={(e) =>
                                handleStockChange("stock_to", e.target.value)
                              }
                            />
                          </div>
                          <div className="flex items-center gap-3">
                            <Label className="w-40">Satuan</Label>
                            <ClearableSelect
                              value={filters.stock_satuan}
                              onChange={(e) => setFilters({ ...filters, satuan: e.target.value })}
                            >
                              <option value="">-- Semua Satuan --</option>
                              {satuanList.map((s) => (
                                <option key={s.id} value={s.nama_satuan}>
                                  {s.nama_satuan}
                                </option>
                              ))}
                            </ClearableSelect>
                          </div>
                        </>
                      )}
                    </div>

                    <div className="flex items-center justify-end gap-3 w-full mt-4">
                      <Button variant="default" onClick={applyFilter}>Terapkan Filter</Button>
                      <Button variant="secondary" onClick={resetFilter}>Reset</Button>
                    </div>
                  </CardContent>
                </Card>
              )}

              {/* --- TAB CONTENTS --- */}
              <TabsContent value="barang">
                <div className="overflow-x-auto">
                  <Table className="min-w-full border rounded-xl">
                    <TableHeader>
                      <TableRow className={`${headerBg}`}>
                        {barangHeaders.map((h) => (
                          <TableHead key={h.key} className="py-3">
                            {h.sortable ? (
                              <button
                                onClick={() => handleSort(h.key)}
                                className="flex items-center text-sm font-medium w-full justify-start gap-2"
                                aria-label={`Sort by ${h.label}`}
                              >
                                <span>{h.label}</span>
                                <SortIcon colKey={h.key} />
                              </button>
                            ) : (
                              <span className="text-sm font-medium">{h.label}</span>
                            )}
                          </TableHead>
                        ))}
                      </TableRow>
                    </TableHeader>
                    <TableBody>
                      {barang.length === 0 ? (
                        <TableRow>
                          <TableCell colSpan={barangHeaders.length} className="text-center py-6">
                            Tidak ada data
                          </TableCell>
                        </TableRow>
                      ) : (
                        barang.map((b, i) => (
                          <TableRow key={b.id ?? `${i}`}>
                            <TableCell>{(page - 1) * PER_PAGE + i + 1}</TableCell>
                            <TableCell>{b.kode}</TableCell>
                            <TableCell>{b.nama}</TableCell>
                            <TableCell>{b.tgl}</TableCell>
                            <TableCell>{b.kategori}</TableCell>
                            <TableCell>{b.satuan}</TableCell>
                            <TableCell>{b.ada_stock ? "Yes" : "No"}</TableCell>
                            <TableCell>{b.keterangan}</TableCell>
                          </TableRow>
                        ))
                      )}
                    </TableBody>
                  </Table>
                </div>

                {/* Pagination */}
                <div className="flex items-center justify-center gap-3 mt-4">
                  <Button size="sm" disabled={page <= 1} onClick={() => setPage((p) => Math.max(1, p - 1))}>
                    Prev
                  </Button>
                  <div className="text-sm">
                    Page <strong>{page}</strong> of <strong>{totalPages}</strong>
                  </div>
                  <Button size="sm" disabled={page >= totalPages} onClick={() => setPage((p) => Math.min(totalPages, p + 1))}>
                    Next
                  </Button>
                </div>
              </TabsContent>

              <TabsContent value="kategori">
                <div className="overflow-x-auto">
                  <Table className="min-w-full border rounded-xl">
                    <TableHeader>
                      <TableRow className={`${headerBg}`}>
                        {kategoriHeaders.map((h) => (
                          <TableHead key={h.key} className="py-3">
                            {h.sortable ? (
                              <button onClick={() => handleSort(h.key)} className="flex items-center text-sm font-medium w-full justify-start gap-2">
                                <span>{h.label}</span>
                                <SortIcon colKey={h.key} />
                              </button>
                            ) : (
                              <span className="text-sm font-medium">{h.label}</span>
                            )}
                          </TableHead>
                        ))}
                      </TableRow>
                    </TableHeader>
                    <TableBody>
                      {kategori.length === 0 ? (
                        <TableRow>
                          <TableCell colSpan={kategoriHeaders.length} className="text-center py-6">
                            Tidak ada data
                          </TableCell>
                        </TableRow>
                      ) : (
                        kategori.map((k) => (
                          <TableRow key={k.id ?? k.kode_kategori}>
                            <TableCell>{k.kode_kategori}</TableCell>
                            <TableCell>{k.nama_kategori}</TableCell>
                            <TableCell>{k.keterangan}</TableCell>
                          </TableRow>
                        ))
                      )}
                    </TableBody>
                  </Table>
                </div>

                <div className="flex items-center justify-center gap-3 mt-4">
                  <Button size="sm" disabled={page <= 1} onClick={() => setPage((p) => Math.max(1, p - 1))}>
                    Prev
                  </Button>
                  <div className="text-sm">
                    Page <strong>{page}</strong> of <strong>{totalPages}</strong>
                  </div>
                  <Button size="sm" disabled={page >= totalPages} onClick={() => setPage((p) => Math.min(totalPages, p + 1))}>
                    Next
                  </Button>
                </div>
              </TabsContent>

              <TabsContent value="stock">
                <div className="overflow-x-auto">
                  <Table className="min-w-full border rounded-xl">
                    <TableHeader>
                      <TableRow className={`${headerBg}`}>
                        {stockHeaders.map((h) => (
                          <TableHead key={h.key} className="py-3">
                            {h.sortable ? (
                              <button onClick={() => handleSort(h.key)} className="flex items-center text-sm font-medium w-full justify-start gap-2">
                                <span>{h.label}</span>
                                <SortIcon colKey={h.key} />
                              </button>
                            ) : (
                              <span className="text-sm font-medium">{h.label}</span>
                            )}
                          </TableHead>
                        ))}
                      </TableRow>
                    </TableHeader>
                    <TableBody>
                      {stock.length === 0 ? (
                        <TableRow>
                          <TableCell colSpan={stockHeaders.length} className="text-center py-6">
                            Tidak ada data
                          </TableCell>
                        </TableRow>
                      ) : (
                        stock.map((s, i) => (
                          <TableRow key={s.nama ?? i}>
                            <TableCell>{s.nama}</TableCell>
                            <TableCell>{s.kategori}</TableCell>
                            <TableCell>{s.stock}</TableCell>
                            <TableCell>{s.satuan}</TableCell>
                          </TableRow>
                        ))
                      )}
                    </TableBody>
                  </Table>
                </div>

                <div className="flex items-center justify-center gap-3 mt-4">
                  <Button size="sm" disabled={page <= 1} onClick={() => setPage((p) => Math.max(1, p - 1))}>
                    Prev
                  </Button>
                  <div className="text-sm">
                    Page <strong>{page}</strong> of <strong>{totalPages}</strong>
                  </div>
                  <Button size="sm" disabled={page >= totalPages} onClick={() => setPage((p) => Math.min(totalPages, p + 1))}>
                    Next
                  </Button>
                </div>
              </TabsContent>
            </Tabs>
          </div>
        </div>
      </Card>
    </div>
    </>
  );
}