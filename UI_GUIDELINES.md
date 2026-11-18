# SORASA — UI & Styling Guidelines

Dokumen panduan standar desain antarmuka (UI), tipografi, palet warna, dan kepadatan tata letak (layout density) untuk seluruh modul aplikasi **SORASA (Sistem Operasional & POS Open PO Makanan)**.

---

## 1. Tipografi & Skala Ukuran (Nyaman Dibaca)

Aplikasi SORASA menggunakan skala teks yang proporsional, nyaman dibaca tanpa membuat mata lelah, dan tajam di berbagai resolusi layar (laptop mau pun desktop).

| Elemen UI | Kelas Tailwind CSS | Keterangan & Contoh |
| :--- | :--- | :--- |
| **Font Sans Utama** | `font-sans` (`Plus Jakarta Sans`) | Digunakan untuk seluruh teks antarmuka umum (400, 500, 600, 700). |
| **Font Monospace** | `font-mono` (`JetBrains Mono`) | Khusus untuk kode invoice (`INV-20261118-0001`), kode PO (`PO-202611-01`), dan nomor telepon/WA. |
| **Judul Halaman** | `text-xl font-semibold tracking-tight text-slate-900 dark:text-white` | Header utama setiap halaman / modul kerja. |
| **Subjudul Halaman** | `text-sm text-slate-500 dark:text-gray-400 mt-0.5` | Keterangan ringkas di bawah judul halaman. |
| **Judul Card / Section** | `text-base font-semibold text-slate-800 dark:text-white` | Header kartu panel, modal dialog, atau section pembagi. |
| **Subjudul Section** | `text-xs sm:text-sm text-slate-500 dark:text-gray-400` | Deskripsi pendukung di dalam kartu atau modal. |
| **Header Tabel (`<thead>`)** | `text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-gray-400` | Judul kolom tabel data. |
| **Isi Tabel (`<tbody>`)** | `text-sm text-slate-700 dark:text-gray-300 leading-6` | **Wajib:** Jangan gunakan `text-xs` atau `text-[13px]` untuk data utama baris tabel. |
| **Teks Sekunder / Meta Data** | `text-xs text-slate-400 dark:text-gray-500` | Tanggal pembuatan, jam kirim, keterangan kecil di bawah nama utama. |
| **Menu Sidebar** | `text-sm font-medium` | Menu navigasi dengan padding `py-2 px-3`. |
| **Judul Grup Sidebar** | `text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 px-3 mb-2` | Header pengelompokan menu di sidebar. |
| **Angka Keuangan & Statistik** | `tabular-nums` | **Wajib:** Tambahkan `tabular-nums` pada seluruh nominal Rupiah, jumlah kuantitas, persentase margin, dan tanggal agar sejajar lurus secara vertikal. |

---

## 2. Warna & Komponen (Tema Biru Profesional)

Identitas visual SORASA berpusat pada warna biru terpercaya dengan palet netral abu-abu dingin (`slate`) yang bersih dan bebas efek "pelangi dekoratif".

### A. Surface & Canvas (Latar Belakang)
- **Latar Belakang Aplikasi:** `bg-slate-50 dark:bg-gray-950`
- **Permukaan Kartu / Card / Panel:** `bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-800 shadow-xs`
- **Sidebar & Topbar:** `bg-white dark:bg-gray-900 border-r border-slate-200 dark:border-gray-800` (Sidebar) dan `border-b border-slate-200 dark:border-gray-800 bg-white/95 backdrop-blur-md` (Topbar).

### B. Aksen Biru Utama (`blue-600`)
- **Logo Brand SORASA (Sidebar):** Kotak ikon `bg-blue-600 text-white rounded-lg w-10 h-10 flex items-center justify-center font-bold text-base`.
- **Tombol Aksi Utama:** `bg-blue-600 hover:bg-blue-700 text-white font-medium shadow-xs transition-colors`.
- **Menu Sidebar Aktif:** `bg-blue-50/80 text-blue-700 font-semibold dark:bg-blue-900/30 dark:text-blue-300` dengan ikon aktif `text-blue-600 dark:text-blue-400`.
- **Menu Sidebar Non-Aktif:** `text-slate-600 hover:bg-slate-100/70 hover:text-slate-900 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-white`.
- **Pill Indikator Batch Open PO Aktif (Topbar):** `bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950/40 dark:border-blue-800 dark:text-blue-300` dengan titik indikator `bg-blue-600`.
- **Warna Grafik Analitik (ApexCharts):** Gunakan `#2563eb` (`blue-600`) untuk seri data penjualan / omzet.

### C. Radius Sudut (Standar Komponen)
- **Card & Panel Kontainer:** `rounded-lg`
- **Tombol Aksi & Navigasi:** `rounded-md`
- **Input Teks, Datepicker & Select Dropdown:** `rounded-md`
- **Badge Status & Pill:** `rounded-md`

---

## 3. Kepadatan Tata Letak (Spacing & Layout Density)

Hindari layout yang terlalu rapat/sesak maupun ruang kosong berlebihan. Antarmuka harus memberikan ruang bernapas yang cukup untuk kenyamanan pemakaian jangka panjang:

### A. Padding Kontainer
- **Padding Kartu / Card:** `p-5` (atau `p-5 sm:p-6` untuk konten kompleks).
- **Padding Header Kartu:** `px-5 py-4 border-b border-slate-100 dark:border-gray-800`.
- **Padding Halaman Utama (`<main>`):** `p-4 sm:p-6 lg:p-8`.

### B. Padding & Spacing Tabel Operasional
- **Header Tabel (`<th>`):** `py-3 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider`.
- **Sel Baris Tabel (`<td>`):** `py-3 px-4 text-sm text-slate-700 leading-6`. Baris terasa lega dan tidak bertumpuk.
- **Pemisah Baris:** `divide-y divide-slate-100 dark:divide-gray-800`.
- **Hover Baris:** `hover:bg-slate-50/60 dark:hover:bg-gray-800/40 transition-colors`.

### C. Form, Input, & Kontrol Interaktif
- **Tinggi Nyaman Input & Select:** `py-2 px-3 text-sm rounded-md border border-slate-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-slate-800 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600`.
- **Dropdown Status di Tabel:** `py-1.5 px-2.5 text-xs font-medium rounded-md border border-slate-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-slate-700 dark:text-gray-300 cursor-pointer`.
- **Jarak Antar Elemen Form / Baris:** `space-y-4` atau `gap-4`.
- **Toolbar Filter:** `p-4 rounded-lg bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-800 shadow-xs`.

---

## 4. Pola Badge Status Standar

Gunakan komponen `<x-badge>` dengan varian semantik yang konsisten:

| Status Transaksi / Order | Varian Badge | Gaya Visual |
| :--- | :--- | :--- |
| **Lunas / Selesai / Terkirim** | `variant="emerald"` atau `variant="success"` | `bg-emerald-50 text-emerald-700 border-emerald-200/80` dengan titik hijau |
| **DP (Uang Muka) / Sedang Kirim** | `variant="blue"` atau `variant="info"` | `bg-blue-50 text-blue-700 border-blue-200/80` dengan titik biru |
| **Dijadwalkan / Menunggu Review** | `variant="amber"` atau `variant="warning"` | `bg-amber-50 text-amber-700 border-amber-200/80` dengan titik amber |
| **Belum Bayar / Defisit / Batal** | `variant="rose"` atau `variant="danger"` | `bg-rose-50 text-rose-700 border-rose-200/80` dengan titik rose |
| **Draft / Menunggu Konfirmasi** | `variant="slate"` (default) | `bg-slate-50 text-slate-700 border-slate-200` dengan titik slate |

---

## 5. Ringkasan Checklist Sebelum Finalisasi UI

1. [ ] Apakah judul halaman menggunakan `text-xl font-semibold` dan subjudul `text-sm text-slate-500`?
2. [ ] Apakah isi tabel menggunakan `text-sm text-slate-700` dengan padding sel `py-3 px-4`?
3. [ ] Apakah seluruh nominal Rupiah dan angka statistik menggunakan kelas `tabular-nums`?
4. [ ] Apakah seluruh kontrol input/select menggunakan `py-2 px-3 text-sm rounded-md`?
5. [ ] Apakah tombol aksi utama menggunakan warna `blue-600` (`hover:bg-blue-700`)?
6. [ ] Apakah card panel memiliki padding `p-5` dan radius `rounded-lg`?
7. [ ] Apakah kontras warna di mode gelap (`dark:`) tetap tajam dan mudah dibaca?
