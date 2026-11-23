<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FlipPlay - Nonton Anime & Drama, Baca Manga & Light Novel</title>
  <meta name="description" content="FlipPlay menyatukan streaming anime dan drama dengan bacaan manga dan light novel dalam satu aplikasi web.">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,800&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">

  <!-- Tailwind CSS via CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            display: ['"Bricolage Grotesque"', 'sans-serif'],
            sans: ['"Plus Jakarta Sans"', 'sans-serif']
          },
          colors: { ink: '#16112E', iris: '#6C4CF1', blush: '#FF4F81', sun: '#FFC93C', mist: '#F4F2FF' }
        }
      }
    }
  </script>
  <link rel="stylesheet" href="/css/style.css">
</head>

<body class="bg-white font-sans text-ink antialiased">

  <!-- ===== NAVBAR ===== -->
  <header class="sticky top-0 z-50 bg-ink/95 text-white backdrop-blur">
    <nav class="mx-auto flex max-w-6xl items-center justify-between px-5 py-4">
      <a href="#beranda" class="flex items-center gap-2 font-display text-xl font-extrabold">
        <span class="grid h-8 w-8 place-items-center rounded-lg bg-blush" aria-hidden="true">
          <svg viewBox="0 0 24 24" class="h-4 w-4 fill-white"><path d="M8 5v14l11-7z"/></svg>
        </span>
        FlipPlay
      </a>

      <ul class="hidden items-center gap-8 text-sm font-medium md:flex">
        <li><a href="#vibe-grid" class="text-white/80 transition hover:text-white">The Vibe Grid</a></li>
        <li><a href="#fitur" class="text-white/80 transition hover:text-white">Fitur</a></li>
        <li><a href="#kontak" class="text-white/80 transition hover:text-white">Kontak</a></li>
        <li><a href="#kontak" class="rounded-full bg-sun px-5 py-2 font-semibold text-ink transition hover:bg-white">Gabung akses awal</a></li>
      </ul>
    </nav>
  </header>

  <main>
    <!-- ===== HERO ===== -->
    <section id="beranda" class="overflow-hidden bg-ink bg-[radial-gradient(60%_60%_at_85%_0%,rgba(108,76,241,.6),transparent)] text-white">
      <div class="mx-auto grid max-w-6xl items-center gap-14 px-5 py-16 md:py-24 lg:grid-cols-2">
        <div>
          <h1 class="font-display text-4xl font-extrabold leading-[1.05] tracking-tight sm:text-5xl lg:text-6xl">
            Nonton animenya, lanjut baca manganya.
          </h1>
          <p class="mt-6 max-w-lg text-base leading-relaxed text-white/75 sm:text-lg">
            FlipPlay menyatukan streaming anime dan drama dengan bacaan manga dan light novel dalam satu aplikasi web.
          </p>
          <div class="mt-9 flex flex-col gap-3 sm:flex-row">
            <a href="#vibe-grid" class="rounded-full bg-blush px-7 py-3.5 text-center font-semibold transition hover:bg-pink-400">Jelajahi konten</a>
            <a href="#kontak" class="rounded-full px-7 py-3.5 text-center font-semibold ring-1 ring-white/30 transition hover:bg-white/10">Hubungi tim</a>
          </div>
        </div>

        <div class="relative mx-auto w-full max-w-md">
          <div class="overflow-hidden rounded-3xl bg-white text-ink shadow-2xl shadow-black/40 ring-1 ring-white/10">
            <div class="relative aspect-video bg-gradient-to-br from-iris via-fuchsia-500 to-blush">
              <span class="absolute left-4 top-4 rounded-full bg-ink/60 px-3 py-1 text-xs font-semibold text-white">Sedang diputar: Episode 12</span>
              <span class="absolute left-1/2 top-1/2 grid h-16 w-16 -translate-x-1/2 -translate-y-1/2 place-items-center rounded-full bg-white/95 shadow-lg">
                <svg viewBox="0 0 24 24" class="ml-1 h-7 w-7 fill-ink"><path d="M8 5v14l11-7z"/></svg>
              </span>
            </div>
            <div class="p-5">
              <p class="font-display text-sm font-semibold">Lanjut baca: bab 48</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ===== THE VIBE GRID ===== -->
    <section id="vibe-grid" class="bg-mist py-16 md:py-24">
      <div class="mx-auto max-w-6xl px-5">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
          <div>
            <h2 class="font-display text-3xl font-extrabold tracking-tight sm:text-4xl">The Vibe Grid</h2>
            <p class="mt-3 max-w-xl text-ink/70">Cari judul favoritmu, atau pilih kategori & filter genre yang cocok denganmu.</p>
          </div>

          <div class="flex flex-wrap items-center gap-2.5">
            <select id="userSelect" aria-label="Pilih pengguna aktif" class="rounded-full border border-ink/15 bg-white px-4 py-2.5 text-xs font-bold text-ink shadow-sm outline-none focus:border-iris">
              <option value="1">Koleksi: Jatmiko Eka Pratama</option>
            </select>
            <button type="button" onclick="openMediaModal()" class="rounded-full bg-iris px-5 py-2.5 text-xs font-bold text-white shadow-md transition hover:bg-ink">
              + Tambah Media
            </button>
          </div>
        </div>

        <!-- Kategori Tabs -->
        <div id="categoryButtons" class="mt-6 flex flex-wrap gap-2.5"></div>

        <!-- Search Input -->
        <div class="mt-5">
          <input id="search" type="search" placeholder="Cari judul anime, drama, manga, atau light novel..."
            class="w-full rounded-full border border-ink/15 bg-white px-6 py-3.5 outline-none transition focus:border-iris focus:ring-2 focus:ring-iris/30 sm:max-w-lg">
        </div>

        <!-- Filter Genre -->
        <div class="mt-4 flex flex-wrap items-center gap-2">
          <span class="text-xs font-bold uppercase tracking-wider text-ink/60">FILTER:</span>
          <div id="auraButtons" class="flex flex-wrap gap-2"></div>
        </div>
        <p id="auraInfo" class="mt-2 text-sm font-medium text-iris"></p>

        <div id="toastBanner" class="mt-4 hidden rounded-2xl px-5 py-3 text-sm font-semibold shadow-sm"></div>
        <p id="resultInfo" class="mt-6 hidden text-sm font-semibold text-ink/70"></p>

        <div id="loadingBox" class="mt-8"></div>
        <p id="emptyMsg" class="mt-6 hidden rounded-2xl bg-white p-8 text-center font-medium text-ink/70 shadow-sm"></p>

        <!-- Grid Container -->
        <div id="grid" class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4"></div>
      </div>
    </section>

    <!-- ===== FITUR & KONTAK ===== -->
    <section id="fitur" class="py-16 md:py-24">
      <div class="mx-auto grid max-w-6xl gap-10 px-5 lg:grid-cols-5 lg:gap-16">
        <div class="lg:col-span-2">
          <h2 class="font-display text-3xl font-extrabold tracking-tight sm:text-4xl">Satu akun untuk menonton dan membaca</h2>
        </div>
        <div class="divide-y divide-ink/10 lg:col-span-3">
          <article class="pb-8">
            <h3 class="font-display text-xl font-bold">Streaming tanpa ribet</h3>
            <p class="mt-2 text-ink/70">Tonton anime dan drama langsung dari browser dengan ringan.</p>
          </article>
          <article class="py-8">
            <h3 class="font-display text-xl font-bold">Baca di mana saja</h3>
            <p class="mt-2 text-ink/70">Manga dan light novel tersedia dengan tampilan baca yang rapi.</p>
          </article>
        </div>
      </div>
    </section>

    <section id="kontak" class="bg-iris py-16 text-white md:py-24">
      <div class="mx-auto grid max-w-6xl items-start gap-10 px-5 lg:grid-cols-2">
        <div>
          <h2 class="font-display text-3xl font-extrabold tracking-tight">Jadi yang pertama mencoba FlipPlay</h2>
          <p class="mt-4 text-white/85">Tinggalkan pesan, tim kami akan menghubungi kamu saat akses dibuka.</p>
        </div>
        <form id="contactForm" class="space-y-5 rounded-3xl bg-white p-6 text-ink shadow-xl sm:p-8">
          <div>
            <label class="block text-sm font-semibold">Nama</label>
            <input id="nama" type="text" required class="mt-2 w-full rounded-xl border border-ink/15 px-4 py-3 outline-none focus:border-iris">
          </div>
          <div>
            <label class="block text-sm font-semibold">Email</label>
            <input id="email" type="email" required class="mt-2 w-full rounded-xl border border-ink/15 px-4 py-3 outline-none focus:border-iris">
          </div>
          <div>
            <label class="block text-sm font-semibold">Pesan</label>
            <textarea id="pesan" rows="3" required class="mt-2 w-full rounded-xl border border-ink/15 px-4 py-3 outline-none focus:border-iris"></textarea>
          </div>
          <button type="submit" class="w-full rounded-full bg-ink px-6 py-3.5 font-semibold text-white transition hover:bg-iris">Kirim pesan</button>
          <p id="formStatus" class="text-center text-sm font-semibold text-emerald-600"></p>
        </form>
      </div>
    </section>
  </main>

  <footer class="bg-ink py-8 text-center text-sm text-white/70">
    <p>&copy; 2026 FlipPlay. Hak cipta dilindungi.</p>
  </footer>

  <!-- ===== MODAL TAMBAH / EDIT MEDIA (POST & PATCH /api/media) ===== -->
  <div id="mediaModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-ink/70 p-4 backdrop-blur-sm">
    <div class="w-full max-w-lg rounded-3xl bg-white p-6 text-ink shadow-2xl sm:p-8">
      <div class="flex items-center justify-between">
        <h3 id="mediaModalTitle" class="font-display text-2xl font-extrabold">Tambah Media Baru</h3>
        <button type="button" onclick="closeMediaModal()" class="rounded-full p-2 text-ink/60 hover:bg-mist hover:text-ink">&times;</button>
      </div>

      <form id="mediaForm" class="mt-5 space-y-4">
        <input type="hidden" id="editMediaId">
        <div>
          <label class="block text-sm font-semibold">Judul Media *</label>
          <input id="mediaTitle" type="text" placeholder="Contoh: Black Clover" class="mt-1.5 w-full rounded-xl border border-ink/15 px-4 py-2.5 outline-none focus:border-iris">
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold">Kategori *</label>
            <select id="mediaType" class="mt-1.5 w-full rounded-xl border border-ink/15 px-4 py-2.5 outline-none focus:border-iris">
              <option value="anime">Anime</option>
              <option value="manga">Manga</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-semibold">Skor (%)</label>
            <input id="mediaScore" type="number" step="0.1" min="0" max="100" placeholder="88.5" class="mt-1.5 w-full rounded-xl border border-ink/15 px-4 py-2.5 outline-none focus:border-iris">
          </div>
        </div>

        <div>
          <label class="block text-sm font-semibold">Genre *</label>
          <input id="mediaGenre" type="text" placeholder="Action, Fantasy" class="mt-1.5 w-full rounded-xl border border-ink/15 px-4 py-2.5 outline-none focus:border-iris">
        </div>

        <div id="animeFieldGroup">
          <label class="block text-sm font-semibold">Jumlah Episode</label>
          <input id="mediaEpisodes" type="number" min="1" placeholder="170" class="mt-1.5 w-full rounded-xl border border-ink/15 px-4 py-2.5 outline-none focus:border-iris">
        </div>

        <div id="mangaFieldGroup" class="hidden grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold">Jumlah Bab</label>
            <input id="mediaChapters" type="number" min="1" placeholder="368" class="mt-1.5 w-full rounded-xl border border-ink/15 px-4 py-2.5 outline-none focus:border-iris">
          </div>
          <div>
            <label class="block text-sm font-semibold">Jumlah Volume</label>
            <input id="mediaVolumes" type="number" min="1" placeholder="36" class="mt-1.5 w-full rounded-xl border border-ink/15 px-4 py-2.5 outline-none focus:border-iris">
          </div>
        </div>

        <div>
          <label class="block text-sm font-semibold">URL Cover Gambar (Opsional)</label>
          <input id="mediaCover" type="url" placeholder="https://..." class="mt-1.5 w-full rounded-xl border border-ink/15 px-4 py-2.5 outline-none focus:border-iris">
        </div>

        <div>
          <label class="block text-sm font-semibold">URL Halaman AniList (Opsional)</label>
          <input id="mediaSiteUrl" type="url" placeholder="https://anilist.co/anime/..." class="mt-1.5 w-full rounded-xl border border-ink/15 px-4 py-2.5 outline-none focus:border-iris">
        </div>

        <p id="modalErrorMsg" class="hidden rounded-xl bg-rose-50 px-4 py-2.5 text-xs font-semibold text-rose-600"></p>

        <div class="flex justify-end gap-3 pt-2">
          <button type="button" onclick="closeMediaModal()" class="rounded-full px-5 py-2.5 text-sm font-semibold text-ink/70 hover:bg-mist">Batal</button>
          <button type="submit" class="rounded-full bg-ink px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-iris">Simpan Media</button>
        </div>
      </form>
    </div>
  </div>

  <!-- ===== MODAL DETAIL MEDIA & PENGGUNA KOLEKSI (GET /api/media/{id}) ===== -->
  <div id="detailModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-ink/70 p-4 backdrop-blur-sm">
    <div class="w-full max-w-lg rounded-3xl bg-white p-6 text-ink shadow-2xl sm:p-8">
      <div class="flex items-start justify-between gap-4">
        <div>
          <span id="detailBadge" class="rounded-full bg-sun px-3 py-1 text-xs font-bold text-ink">Anime</span>
          <h3 id="detailTitle" class="mt-2 font-display text-2xl font-extrabold">Judul Media</h3>
          <p id="detailGenre" class="text-xs font-semibold text-ink/60">Genre</p>
        </div>
        <button type="button" onclick="closeDetailModal()" class="rounded-full p-2 text-ink/60 hover:bg-mist hover:text-ink">&times;</button>
      </div>

      <div class="mt-4 rounded-2xl bg-mist p-4 text-sm">
        <p id="detailSummary" class="font-semibold text-iris"></p>
      </div>

      <div class="mt-5">
        <h4 class="text-xs font-bold uppercase tracking-wider text-ink/60">Pengguna yang Menyimpan ke Koleksi:</h4>
        <ul id="detailUsersList" class="mt-2 space-y-2 text-sm"></ul>
      </div>

      <div class="mt-6 flex justify-end">
        <button type="button" onclick="closeDetailModal()" class="rounded-full bg-ink px-6 py-2.5 text-sm font-semibold text-white hover:bg-iris">Tutup</button>
      </div>
    </div>
  </div>

  <script src="/js/script.js"></script>
</body>
</html>