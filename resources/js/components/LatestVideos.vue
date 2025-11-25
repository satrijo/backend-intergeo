<script setup>
import { ref, onMounted } from 'vue';
import { Calendar, ArrowRight, Play, Video } from 'lucide-vue-next';
import { router } from '@inertiajs/vue3';

const latestVideos = ref([]);
const loading = ref(true);

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

const goToVideoDetail = (video) => {
  router.visit(`/work-showcase/${video.id}`);
};

const goToAllVideos = () => {
  router.visit('/work-showcase');
};

// Fetch latest videos from API
const fetchLatestVideos = async () => {
  try {
    loading.value = true;
    const response = await fetch('/api/v1/video-portfolios?per_page=4');
    if (!response.ok) throw new Error('Gagal mengambil data video');
    const data = await response.json();
    // Handle paginated response
    const items = data.data || (Array.isArray(data) ? data : []);
    
    // Map videos
    latestVideos.value = items.map(item => {
      const cleanDesc = stripHtml(item.description || '');
      const excerpt = cleanDesc.length > 100 ? cleanDesc.substring(0, 100) + '...' : cleanDesc;
      // Generate thumbnail URL if not available
      let thumbnailUrl = item.thumbnail_url;
      if (!thumbnailUrl && item.youtube_id) {
        thumbnailUrl = `https://img.youtube.com/vi/${item.youtube_id}/maxresdefault.jpg`;
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
    console.error('Error fetching videos:', err);
    latestVideos.value = [];
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchLatestVideos();
});
</script>

<template>
  <section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6">
      <!-- Section Header -->
      <div class="text-center mb-16">
        <h2 class="text-4xl font-bold text-gray-900 mb-4">
          Video <span class="text-blue-600">Terbaru</span>
        </h2>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
          Dokumentasi video dari berbagai proyek dan pekerjaan survey geofisika terbaru yang telah kami selesaikan.
        </p>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center py-12">
        <div class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-blue-600 border-r-transparent"></div>
        <p class="mt-4 text-gray-600">Memuat video...</p>
      </div>

      <!-- Videos Grid -->
      <div v-else-if="latestVideos.length > 0" class="grid lg:grid-cols-4 md:grid-cols-2 gap-8 mb-12">
        <div
          v-for="video in latestVideos"
          :key="video.id"
          class="group bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2"
          @click="goToVideoDetail(video)"
          style="cursor: pointer;"
        >
          <!-- Video Thumbnail -->
          <div class="relative overflow-hidden aspect-video bg-gray-900">
            <img
              v-if="video.thumbnail_url"
              :src="video.thumbnail_url"
              :alt="`Video ${video.title}`"
              class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
            />
            <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
              <Video class="h-12 w-12" />
            </div>
            <!-- Play Button Overlay -->
            <div class="absolute inset-0 bg-black bg-opacity-30 group-hover:bg-opacity-40 transition-all duration-300 flex items-center justify-center">
              <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <div class="bg-white bg-opacity-90 rounded-full p-4 hover:bg-opacity-100 transition-all">
                  <Play class="w-8 h-8 text-blue-600 ml-1" fill="currentColor" />
                </div>
              </div>
            </div>
          </div>

          <!-- Video Content -->
          <div class="p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors line-clamp-2">
              {{ video.title }}
            </h3>
            <p class="text-gray-600 text-sm leading-relaxed mb-4 line-clamp-2">
              {{ video.description }}
            </p>
            
            <!-- Video Details -->
            <div class="space-y-2 mb-4">
              <div class="flex items-center space-x-2 text-sm text-gray-600">
                <Calendar class="w-4 h-4 flex-shrink-0 text-blue-500" />
                <span>{{ video.published_at }}</span>
              </div>
            </div>

            <!-- View Video Button -->
            <div class="flex items-center text-blue-600 font-semibold text-sm group-hover:text-blue-700 transition-colors">
              <span>Lihat Video</span>
              <ArrowRight class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" />
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-12">
        <div class="bg-gray-50 rounded-lg p-8 shadow-lg">
          <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <Video class="w-8 h-8 text-blue-600" />
          </div>
          <h3 class="text-xl font-semibold text-gray-900 mb-2">Video Sedang Disiapkan</h3>
          <p class="text-gray-600 mb-6">
            Tim kami sedang menyiapkan dokumentasi video dari proyek-proyek terbaru. 
            Silakan hubungi kami untuk informasi lebih lanjut.
          </p>
          <button
            @click="() => router.visit('/contact')"
            class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors"
          >
            Hubungi Kami
          </button>
        </div>
      </div>

      <!-- View All Button -->
      <div v-if="latestVideos.length > 0" class="text-center">
        <button
          @click="goToAllVideos"
          class="inline-flex items-center bg-blue-600 hover:cursor-pointer text-white px-8 py-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors shadow-lg hover:shadow-xl"
        >
          <span>Lihat Semua Video</span>
          <ArrowRight class="w-5 h-5 ml-2" />
        </button>
      </div>
    </div>
  </section>
</template>

<style scoped>
.aspect-video {
  aspect-ratio: 16 / 9;
}

/* Custom hover effects */
.group:hover .group-hover\:scale-110 {
  transform: scale(1.1);
}

.group:hover .group-hover\:translate-x-1 {
  transform: translateX(0.25rem);
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>

