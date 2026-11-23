const API_BASE_URL = `${window.location.origin}/api`;
const DEFAULT_COVER = 'https://s4.anilist.co/file/anilistcdn/media/anime/cover/large/bx16498-73IhOXpJZiMF.jpg';

let mediaData = [];
let userCollectionIds = new Set();
let currentUserId = 1;
let currentCategory = 'Semua';
let currentSearch = '';
let currentFilter = '';

const searchInput = document.getElementById('search');
const grid = document.getElementById('grid');
const resultInfo = document.getElementById('resultInfo');
const emptyMsg = document.getElementById('emptyMsg');
const categoryButtonsContainer = document.getElementById('categoryButtons');
const auraButtonsContainer = document.getElementById('auraButtons');
const userSelect = document.getElementById('userSelect');
const toastBanner = document.getElementById('toastBanner');

function escapeHtml(value) {
  return String(value ?? '').replace(/[&<>"']/g, character => ({
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#39;'
  })[character]);
}

function showToast(message, isError = false) {
  if (!toastBanner) return;
  toastBanner.textContent = message;
  toastBanner.className = `mt-4 rounded-2xl px-5 py-3 text-sm font-semibold shadow-sm ${
    isError ? 'bg-rose-100 text-rose-700' : 'bg-white text-iris ring-1 ring-iris/20'
  }`;
  setTimeout(() => {
    toastBanner.className = 'mt-4 hidden rounded-2xl px-5 py-3 text-sm font-semibold shadow-sm';
  }, 4000);
}

function formatDetails(item) {
  if (item.type === 'anime') {
    return `${item.episodes ?? '?'} episode`;
  }
  return `${item.chapters ?? '?'} bab${item.volumes ? ` · ${item.volumes} volume` : ''}`;
}

function getAniListUrl(item) {
  if (item.site_url) return item.site_url;
  const section = item.type === 'manga' ? 'manga' : 'anime';
  return `https://anilist.co/search/${section}?search=${encodeURIComponent(item.title)}`;
}

function renderMedia() {
  const filtered = mediaData.filter(item => {
    const isCollected = userCollectionIds.has(item.id);
    const matchCategory =
      currentCategory === 'Semua' ||
      (currentCategory === 'Koleksi Saya' && isCollected) ||
      (currentCategory === 'Anime' && item.type === 'anime') ||
      (currentCategory === 'Manga' && item.type === 'manga');

    const matchFilter = !currentFilter || item.genre.toLowerCase().includes(currentFilter.toLowerCase());
    return matchCategory && matchFilter;
  });

  if (resultInfo) {
    resultInfo.textContent = `Menampilkan ${filtered.length} judul`;
    resultInfo.classList.remove('hidden');
  }

  if (filtered.length === 0) {
    if (grid) grid.classList.add('hidden');
    if (emptyMsg) {
      emptyMsg.textContent = 'Tidak ada judul yang sesuai dengan pencarian Anda.';
      emptyMsg.classList.remove('hidden');
    }
    return;
  }

  if (emptyMsg) emptyMsg.classList.add('hidden');
  if (grid) {
    grid.classList.remove('hidden');
    grid.innerHTML = filtered.map(item => {
      const isCollected = userCollectionIds.has(item.id);
      const badgeLabel = item.type === 'anime' ? 'Anime' : 'Manga';
      const actionLabel = item.type === 'anime' ? 'Tonton' : 'Baca';
      const coverUrl = item.cover_image || DEFAULT_COVER;
      const anilistUrl = getAniListUrl(item);

      return `
        <article class="group relative flex flex-col justify-between overflow-hidden rounded-3xl bg-white shadow-xl ring-1 ring-ink/15 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl">
          <a href="${escapeHtml(anilistUrl)}" target="_blank" rel="noopener noreferrer" class="relative block h-60 w-full overflow-hidden bg-slate-100">
            <img src="${escapeHtml(coverUrl)}" alt="${escapeHtml(item.title)}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
            <span class="absolute left-3 top-3 rounded-full bg-ink/80 px-3 py-1 text-xs font-semibold text-white backdrop-blur-md">
              ${actionLabel}
            </span>
            <span class="absolute right-3 top-3 rounded-full bg-sun px-2.5 py-1 text-xs font-bold text-ink">
              ${badgeLabel}
            </span>
          </a>
          <div class="flex flex-1 flex-col justify-between p-5">
            <div>
              <a href="${escapeHtml(anilistUrl)}" target="_blank" rel="noopener noreferrer" class="block font-display text-lg font-extrabold text-ink transition-colors group-hover:text-iris">
                ${escapeHtml(item.title)}
              </a>
              <p class="mt-1 text-xs font-semibold text-ink/60">${escapeHtml(item.genre)}</p>
            </div>
            <div class="mt-4 space-y-3 border-t border-ink/10 pt-3">
              <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-iris">${escapeHtml(formatDetails(item))}</span>
                <span class="text-xs font-bold text-ink">★ ${escapeHtml(item.score ?? 0)}%</span>
              </div>
              <div class="flex flex-wrap items-center gap-1.5">
                <button
                  type="button"
                  onclick="toggleCollection(${item.id})"
                  class="flex-1 rounded-full px-3 py-2 text-xs font-semibold transition ${
                    isCollected
                      ? 'bg-blush/15 text-blush hover:bg-blush/25'
                      : 'bg-ink text-white hover:bg-iris'
                  }">
                  ${isCollected ? '✓ Di Koleksi' : '+ Koleksi'}
                </button>
                <button
                  type="button"
                  onclick="openDetailModal(${item.id})"
                  title="Lihat detail & pengguna koleksi"
                  class="rounded-full border border-ink/15 px-2.5 py-2 text-xs font-semibold text-ink/70 transition hover:bg-mist hover:text-iris">
                  Info
                </button>
                <button
                  type="button"
                  onclick="openEditModal(${item.id})"
                  title="Edit media"
                  class="rounded-full border border-ink/15 px-2.5 py-2 text-xs font-semibold text-ink/70 transition hover:bg-mist hover:text-iris">
                  Edit
                </button>
                <button
                  type="button"
                  onclick="deleteMedia(${item.id})"
                  title="Hapus media"
                  class="rounded-full border border-ink/15 px-2.5 py-2 text-xs font-semibold text-ink/70 transition hover:bg-rose-50 hover:text-rose-600">
                  Hapus
                </button>
              </div>
            </div>
          </div>
        </article>
      `;
    }).join('');
  }
}

async function fetchUsers() {
  try {
    const res = await fetch(`${API_BASE_URL}/users`, { headers: { Accept: 'application/json' } });
    const json = await res.json();
    if (res.ok && Array.isArray(json.data) && userSelect) {
      userSelect.innerHTML = json.data
        .map(u => `<option value="${u.id}">Koleksi: ${escapeHtml(u.name)}</option>`)
        .join('');
      currentUserId = Number(userSelect.value) || 1;
    }
  } catch (_) {}
}

async function fetchUserCollection() {
  try {
    const res = await fetch(`${API_BASE_URL}/users/${currentUserId}/collections`, {
      headers: { Accept: 'application/json' }
    });
    const json = await res.json();
    if (res.ok && json.data && Array.isArray(json.data.collections)) {
      userCollectionIds = new Set(json.data.collections.map(m => m.id));
    }
  } catch (_) {
    userCollectionIds = new Set();
  }
}

async function loadMedia() {
  const loadingBox = document.getElementById('loadingBox');
  if (loadingBox) {
    loadingBox.innerHTML = '<p class="mt-8 text-sm font-semibold text-ink/70">Memuat anime dan manga dari FlipPlay API...</p>';
  }

  try {
    const url = new URL(`${API_BASE_URL}/media`);
    if (currentSearch) {
      url.searchParams.set('title', currentSearch);
    }

    const [mediaRes] = await Promise.all([
      fetch(url.toString(), { headers: { Accept: 'application/json' } }),
      fetchUserCollection()
    ]);

    if (!mediaRes.ok) {
      throw new Error(`FlipPlay API merespons dengan status ${mediaRes.status}`);
    }

    const result = await mediaRes.json();
    mediaData = result.data || [];

    if (loadingBox) loadingBox.innerHTML = '';
    renderMedia();
  } catch (error) {
    if (loadingBox) loadingBox.innerHTML = '';
    if (grid) grid.classList.add('hidden');
    if (emptyMsg) {
      emptyMsg.textContent = `Data FlipPlay API tidak dapat dimuat. ${error.message}`;
      emptyMsg.classList.remove('hidden');
    }
  }
}

async function toggleCollection(mediaId) {
  const isCollected = userCollectionIds.has(mediaId);
  try {
    const endpoint = isCollected
      ? `${API_BASE_URL}/users/${currentUserId}/collections/${mediaId}`
      : `${API_BASE_URL}/users/${currentUserId}/collections`;

    const res = await fetch(endpoint, {
      method: isCollected ? 'DELETE' : 'POST',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json'
      },
      body: isCollected ? null : JSON.stringify({ media_id: mediaId })
    });

    const json = await res.json();
    if (!res.ok) {
      showToast(json.message || 'Gagal memperbarui koleksi.', true);
      return;
    }

    showToast(json.message || 'Koleksi berhasil diperbarui.');
    await fetchUserCollection();
    renderMedia();
  } catch (error) {
    showToast(`Gagal memproses koleksi: ${error.message}`, true);
  }
}

async function deleteMedia(mediaId) {
  if (!confirm('Hapus media ini dari katalog FlipPlay?')) return;
  try {
    const res = await fetch(`${API_BASE_URL}/media/${mediaId}`, {
      method: 'DELETE',
      headers: { Accept: 'application/json' }
    });
    const json = await res.json();
    if (!res.ok) {
      showToast(json.message || 'Gagal menghapus media.', true);
      return;
    }
    showToast(json.message || 'Media berhasil dihapus.');
    await loadMedia();
  } catch (error) {
    showToast(`Error: ${error.message}`, true);
  }
}

const mediaModal = document.getElementById('mediaModal');
const mediaForm = document.getElementById('mediaForm');
const mediaTypeSelect = document.getElementById('mediaType');
const animeFieldGroup = document.getElementById('animeFieldGroup');
const mangaFieldGroup = document.getElementById('mangaFieldGroup');
const modalErrorMsg = document.getElementById('modalErrorMsg');

function toggleTypeInputs(type) {
  if (type === 'anime') {
    animeFieldGroup.classList.remove('hidden');
    mangaFieldGroup.classList.add('hidden');
    mangaFieldGroup.classList.remove('grid');
  } else {
    animeFieldGroup.classList.add('hidden');
    mangaFieldGroup.classList.remove('hidden');
    mangaFieldGroup.classList.add('grid');
  }
}

if (mediaTypeSelect) {
  mediaTypeSelect.addEventListener('change', (e) => toggleTypeInputs(e.target.value));
}

function openMediaModal() {
  document.getElementById('mediaModalTitle').textContent = 'Tambah Media Baru';
  document.getElementById('editMediaId').value = '';
  mediaForm.reset();
  toggleTypeInputs('anime');
  modalErrorMsg.classList.add('hidden');
  mediaModal.classList.remove('hidden');
  mediaModal.classList.add('flex');
}

function openEditModal(mediaId) {
  const item = mediaData.find(m => m.id === mediaId);
  if (!item) return;

  document.getElementById('mediaModalTitle').textContent = 'Edit Media';
  document.getElementById('editMediaId').value = item.id;
  document.getElementById('mediaTitle').value = item.title;
  document.getElementById('mediaType').value = item.type;
  document.getElementById('mediaScore').value = item.score ?? '';
  document.getElementById('mediaGenre').value = item.genre;
  document.getElementById('mediaEpisodes').value = item.episodes ?? '';
  document.getElementById('mediaChapters').value = item.chapters ?? '';
  document.getElementById('mediaVolumes').value = item.volumes ?? '';
  document.getElementById('mediaCover').value = item.cover_image ?? '';
  const siteUrlInput = document.getElementById('mediaSiteUrl');
  if (siteUrlInput) siteUrlInput.value = item.site_url ?? '';

  toggleTypeInputs(item.type);
  modalErrorMsg.classList.add('hidden');
  mediaModal.classList.remove('hidden');
  mediaModal.classList.add('flex');
}

function closeMediaModal() {
  mediaModal.classList.add('hidden');
  mediaModal.classList.remove('flex');
}

if (mediaForm) {
  mediaForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const editId = document.getElementById('editMediaId').value;
    const type = document.getElementById('mediaType').value;
    const siteUrlInput = document.getElementById('mediaSiteUrl');

    const payload = {
      title: document.getElementById('mediaTitle').value.trim(),
      type,
      genre: document.getElementById('mediaGenre').value.trim(),
      score: document.getElementById('mediaScore').value ? Number(document.getElementById('mediaScore').value) : null,
      episodes: type === 'anime' && document.getElementById('mediaEpisodes').value
        ? Number(document.getElementById('mediaEpisodes').value)
        : null,
      chapters: type === 'manga' && document.getElementById('mediaChapters').value
        ? Number(document.getElementById('mediaChapters').value)
        : null,
      volumes: type === 'manga' && document.getElementById('mediaVolumes').value
        ? Number(document.getElementById('mediaVolumes').value)
        : null,
      cover_image: document.getElementById('mediaCover').value.trim() || null,
      site_url: siteUrlInput ? (siteUrlInput.value.trim() || null) : null,
    };

    try {
      const endpoint = editId ? `${API_BASE_URL}/media/${editId}` : `${API_BASE_URL}/media`;
      const method = editId ? 'PATCH' : 'POST';

      const res = await fetch(endpoint, {
        method,
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json'
        },
        body: JSON.stringify(payload)
      });

      const json = await res.json();
      if (!res.ok) {
        const firstErr = json.errors ? Object.values(json.errors)[0][0] : json.message;
        modalErrorMsg.textContent = firstErr || 'Validasi gagal.';
        modalErrorMsg.classList.remove('hidden');
        return;
      }

      closeMediaModal();
      showToast(json.message || 'Media berhasil disimpan.');
      await loadMedia();
    } catch (error) {
      modalErrorMsg.textContent = `Gagal menghubungi API: ${error.message}`;
      modalErrorMsg.classList.remove('hidden');
    }
  });
}

const detailModal = document.getElementById('detailModal');

async function openDetailModal(mediaId) {
  try {
    const res = await fetch(`${API_BASE_URL}/media/${mediaId}`, {
      headers: { Accept: 'application/json' }
    });
    const json = await res.json();
    if (!res.ok) return;

    const item = json.data;
    document.getElementById('detailBadge').textContent = item.type === 'anime' ? 'Anime' : 'Manga';
    document.getElementById('detailTitle').textContent = item.title;
    document.getElementById('detailGenre').textContent = item.genre;
    document.getElementById('detailSummary').textContent = item.details;

    const usersList = document.getElementById('detailUsersList');
    if (Array.isArray(item.users) && item.users.length > 0) {
      usersList.innerHTML = item.users
        .map(u => `<li class="rounded-xl bg-mist px-3.5 py-2 font-semibold text-ink">• ${escapeHtml(u.name)} <span class="text-xs font-normal text-ink/60">(${escapeHtml(u.email)})</span></li>`)
        .join('');
    } else {
      usersList.innerHTML = '<li class="text-xs text-ink/60">Belum ada pengguna yang menambahkan media ini ke koleksinya.</li>';
    }

    detailModal.classList.remove('hidden');
    detailModal.classList.add('flex');
  } catch (_) {}
}

function closeDetailModal() {
  detailModal.classList.add('hidden');
  detailModal.classList.remove('flex');
}

let searchTimeout;
if (searchInput) {
  searchInput.addEventListener('input', (e) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
      currentSearch = e.target.value.trim();
      loadMedia();
    }, 250);
  });
}

if (userSelect) {
  userSelect.addEventListener('change', async (e) => {
    currentUserId = Number(e.target.value);
    await fetchUserCollection();
    renderMedia();
  });
}

const categories = ['Semua', 'Anime', 'Manga', 'Koleksi Saya'];
if (categoryButtonsContainer) {
  categoryButtonsContainer.innerHTML = categories.map(cat => `
    <button type="button" data-category="${cat}" class="category-btn rounded-full px-5 py-2.5 text-sm font-semibold transition ${cat === 'Semua' ? 'bg-ink text-white shadow-md' : 'bg-white text-ink hover:bg-ink/10 ring-1 ring-ink/15'}">
      ${cat}
    </button>
  `).join('');

  categoryButtonsContainer.querySelectorAll('.category-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
      categoryButtonsContainer.querySelectorAll('.category-btn').forEach(b => {
        b.className = 'category-btn rounded-full px-5 py-2.5 text-sm font-semibold transition bg-white text-ink hover:bg-ink/10 ring-1 ring-ink/15';
      });
      e.target.className = 'category-btn rounded-full px-5 py-2.5 text-sm font-semibold transition bg-ink text-white shadow-md';
      currentCategory = e.target.getAttribute('data-category');
      renderMedia();
    });
  });
}

const filters = [
  { name: 'Semua', value: '' },
  { name: 'Action ⚔️', value: 'Action' },
  { name: 'Fantasy 🪄', value: 'Fantasy' },
  { name: 'Romance ❤️', value: 'Romance' }
];

if (auraButtonsContainer) {
  auraButtonsContainer.innerHTML = filters.map(f => `
    <button type="button" data-filter="${f.value}" class="filter-btn rounded-xl px-3.5 py-2 text-xs font-semibold transition ${f.value === '' ? 'bg-iris text-white shadow-md' : 'bg-white text-ink hover:bg-ink/10 ring-1 ring-ink/15'}">
      ${f.name}
    </button>
  `).join('');

  auraButtonsContainer.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
      auraButtonsContainer.querySelectorAll('.filter-btn').forEach(b => {
        b.className = 'filter-btn rounded-xl px-3.5 py-2 text-xs font-semibold transition bg-white text-ink hover:bg-ink/10 ring-1 ring-ink/15';
      });
      e.target.className = 'filter-btn rounded-xl px-3.5 py-2 text-xs font-semibold transition bg-iris text-white shadow-md';
      currentFilter = e.target.getAttribute('data-filter');
      const auraInfo = document.getElementById('auraInfo');
      if (auraInfo) {
        auraInfo.textContent = currentFilter ? `Filter aktif: ${e.target.textContent}` : '';
      }
      renderMedia();
    });
  });
}

const contactForm = document.getElementById('contactForm');
const formStatus = document.getElementById('formStatus');
if (contactForm) {
  contactForm.addEventListener('submit', (e) => {
    e.preventDefault();
    if (formStatus) formStatus.textContent = 'Terima kasih! Pesan berhasil dikirim.';
    contactForm.reset();
    setTimeout(() => { if (formStatus) formStatus.textContent = ''; }, 4000);
  });
}

document.addEventListener('DOMContentLoaded', async () => {
  await fetchUsers();
  await loadMedia();
});