<script setup>
import { ref, computed, onMounted } from 'vue';
import { Calendar, Video, Play } from 'lucide-vue-next';
import NavMenu from '@/components/NavMenu.vue';
import Footer from '@/components/Footer.vue';
import { router, Head } from '@inertiajs/vue3';

const videos = ref([]);
const loading = ref(true);
const error = ref(null);
const currentPage = ref(1);
const videosPerPage = 6;

const filteredVideos = computed(() => {
  return videos.value;
});

const totalPages = computed(() => Math.ceil(filteredVideos.value.length / videosPerPage));

const currentVideos = computed(() => {
  const startIndex = (currentPage.value - 1) * videosPerPage;
  return filteredVideos.value.slice(startIndex, startIndex + videosPerPage);
});

const changePage = (pageNumber) => {
  if (pageNumber >= 1 && pageNumber <= totalPages.value) {
    currentPage.value = pageNumber;
  }
};

const goToVideoDetail = (video) => {
  router.visit(`/work-showcase/${video.id}`);
};

// Function to strip HTML tags from text
const stripHtml = (html) => {
  const tmp = document.createElement('div');
  tmp.innerHTML = html;
  return tmp.textContent || tmp.innerText || '';
};

const formatDate = (dateString) => {
  if (!dateString) return '-';
  const date = new Date(dateString);
  return date.toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
};

// Fetch videos from API
const fetchVideos = async () => {
  try {
    loading.value = true;
    error.value = null;
    const response = await fetch('/api/v1/video-portfolios');
    if (!response.ok) throw new Error('Gagal mengambil data video');
    const data = await response.json();
    const items = data.data || data;
    videos.value = items.map(item => {
      const cleanDesc = stripHtml(item.description || '');
      const excerpt = cleanDesc.length > 120 ? cleanDesc.substring(0, 120) + '...' : cleanDesc;
      // Generate thumbnail URL if not available
      let thumbnailUrl = item.thumbnail_url;
      if (!thumbnailUrl && item.youtube_id) {
        thumbnailUrl = `https://img.youtube.com/vi/${item.youtube_id}/hqdefault.jpg`;
      }
      return {
        id: item.id,
        title: item.title,
        description: excerpt,
        youtube_id: item.youtube_id,
        thumbnail_url: thumbnailUrl,
        published_at: item.published_at ? formatDate(item.published_at) : '-',
        created_at: item.created_at,
      };
    });
  } catch (err) {
    error.value = err.message;
    videos.value = [];
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchVideos();
});
</script>

<template>
  <Head title="Work Showcase - Video Portfolio" />
  
  <div class="min-h-screen flex flex-col bg-white">
    <NavMenu />
    
    <main class="flex-grow">
      <!-- Hero Section -->
      <section class="bg-gradient-to-r from-blue-900 to-blue-800 text-white py-20">
        <div class="max-w-7xl mx-auto px-6 text-center">
          <h1 class="text-4xl md:text-5xl font-bold mb-4">Work Showcase</h1>
          <p class="text-xl text-blue-100 max-w-2xl mx-auto">
            Dokumentasi video dari berbagai proyek dan pekerjaan yang telah kami selesaikan
          </p>
        </div>
      </section>

      <!-- Videos Section -->
      <section class="py-16">
        <div class="max-w-7xl mx-auto px-6">
          <div v-if="loading" class="text-center py-12">
            <div class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-blue-600 border-r-transparent"></div>
            <p class="mt-4 text-gray-600">Memuat video...</p>
          </div>
          <div v-else-if="error" class="text-center py-12">
            <p class="text-red-600 mb-4">{{ error }}</p>
            <button @click="fetchVideos" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
              Coba Lagi
            </button>
          </div>
          <div v-else>
            <div v-if="filteredVideos.length === 0" class="text-center py-12">
              <p class="text-gray-600 text-lg">Belum ada video yang dipublikasikan.</p>
              <p class="text-gray-500 mt-2">Silakan kembali lagi nanti.</p>
            </div>
            <div v-else class="grid lg:grid-cols-3 md:grid-cols-2 gap-8">
              <div
                  v-for="video in currentVideos"
                  :key="video.id"
                  class="overflow-hidden hover:shadow-lg transition-shadow bg-white rounded-lg border cursor-pointer"
                  @click="goToVideoDetail(video)"
              >
                <div class="aspect-video overflow-hidden relative bg-gray-900">
                  <img
                      v-if="video.thumbnail_url"
                      :src="video.thumbnail_url"
                      :alt="video.title"
                      class="w-full h-full object-cover"
                  />
                  <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                    <Video class="h-12 w-12" />
                  </div>
                  <!-- Play Button Overlay -->
                  <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30 hover:bg-opacity-40 transition-opacity">
                    <div class="bg-white bg-opacity-90 rounded-full p-4 hover:bg-opacity-100 transition-all">
                      <Play class="h-8 w-8 text-blue-600 ml-1" fill="currentColor" />
                    </div>
                  </div>
                </div>
                <div class="p-6">
                  <h3 class="text-xl font-bold text-gray-900 mb-2">{{ video.title }}</h3>
                  <p class="text-gray-600 text-sm leading-relaxed mb-4">
                    {{ video.description }}
                  </p>
                  <div class="flex items-center space-x-2 text-sm text-gray-600">
                    <Calendar class="w-4 h-4 flex-shrink-0" />
                    <span>{{ video.published_at }}</span>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Pagination -->
            <div v-if="totalPages > 1" class="mt-12 flex justify-center space-x-2">
              <button
                @click="changePage(currentPage - 1)"
                :disabled="currentPage === 1"
                class="rounded-md border border-gray-300 bg-white px-4 py-2 font-medium text-gray-700 transition-colors hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-50"
              >
                Sebelumnya
              </button>

              <button
                v-for="pageNumber in totalPages"
                :key="pageNumber"
                @click="changePage(pageNumber)"
                :class="[
                  'rounded-md border px-4 py-2 font-medium transition-colors',
                  currentPage === pageNumber
                    ? 'bg-blue-600 text-white border-blue-600'
                    : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100'
                ]"
              >
                {{ pageNumber }}
              </button>

              <button
                @click="changePage(currentPage + 1)"
                :disabled="currentPage === totalPages"
                class="rounded-md border border-gray-300 bg-white px-4 py-2 font-medium text-gray-700 transition-colors hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-50"
              >
                Selanjutnya
              </button>
            </div>
          </div>
        </div>
      </section>
    </main>

    <Footer />
  </div>
</template>

