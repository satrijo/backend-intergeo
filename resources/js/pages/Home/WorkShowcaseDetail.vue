<script setup>
import { router, Head } from '@inertiajs/vue3';
import NavMenu from '@/components/NavMenu.vue';
import Footer from '@/components/Footer.vue';
import { Calendar, ArrowLeft, ExternalLink, Video } from 'lucide-vue-next';
import { onMounted, onUnmounted, ref, computed } from 'vue';

// Function to strip HTML tags from text
const stripHtml = (html) => {
  const tmp = document.createElement('div');
  tmp.innerHTML = html;
  return tmp.textContent || tmp.innerText || '';
};

// Generate meta description from video content
const generateMetaDescription = (video) => {
  const cleanDesc = stripHtml(video.description || '');
  const excerpt = cleanDesc.length > 160 ? cleanDesc.substring(0, 160) + '...' : cleanDesc;
  return `Video dokumentasi pekerjaan: ${video.title}. ${excerpt}`;
};

const wasDarkMode = ref(false);
const shareUrl = ref('');

onMounted(() => {
    const htmlElement = document.documentElement;
    // Cek apakah dark mode aktif saat komponen dimuat
    if (htmlElement.classList.contains('dark')) {
        wasDarkMode.value = true;
        // Hapus class 'dark' untuk memaksa light mode
        htmlElement.classList.remove('dark');
    }

    if (typeof window !== 'undefined') {
        shareUrl.value = window.location.href;
    }
});

onUnmounted(() => {
    // Saat meninggalkan halaman, kembalikan class 'dark' jika sebelumnya aktif
    if (wasDarkMode.value) {
        document.documentElement.classList.add('dark');
    }
});

const props = defineProps({
  video: {
    type: Object,
    required: true
  }
});

const goBack = () => {
  router.visit('/work-showcase');
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

const embedUrl = computed(() => {
  if (!props.video || !props.video.youtube_id) {
    return '';
  }
  return `https://www.youtube.com/embed/${props.video.youtube_id}`;
});

// Fallback thumbnail URL
const thumbnailUrl = computed(() => {
  if (props.video?.thumbnail_url) {
    return props.video.thumbnail_url;
  }
  if (props.video?.youtube_id) {
    return `https://img.youtube.com/vi/${props.video.youtube_id}/hqdefault.jpg`;
  }
  return null;
});
</script>

<template>
  <Head>
    <title>{{ video?.title || 'Video' }} - Work Showcase | PT. Intergeo Mitigasi</title>
    <meta name="description" :content="generateMetaDescription(video || {})" />
    <link rel="canonical" :href="`https://surveyseismikgpr.com/work-showcase/${video?.id}`" />
    <meta property="og:title" :content="`${video?.title || 'Video'} - Work Showcase | PT. Intergeo Mitigasi`" />
    <meta property="og:description" :content="generateMetaDescription(video || {})" />
    <meta property="og:image" :content="thumbnailUrl || 'https://surveyseismikgpr.com/images/og-image.jpg'" />
    <meta property="og:url" :content="`https://surveyseismikgpr.com/work-showcase/${video?.id}`" />
    <meta property="og:type" content="video.other" />
    <meta property="og:site_name" content="PT. Intergeo Mitigasi" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" :content="`${video?.title || 'Video'} - Work Showcase | PT. Intergeo Mitigasi`" />
    <meta name="twitter:description" :content="generateMetaDescription(video || {})" />
    <meta name="twitter:image" :content="thumbnailUrl || 'https://surveyseismikgpr.com/images/og-image.jpg'" />
  </Head>
  
  <NavMenu />
  <div class="min-h-screen bg-gray-50">
    <div v-if="!video" class="max-w-6xl mx-auto px-4 py-8">
      <div class="text-center py-12">
        <p class="text-gray-600 text-lg mb-4">Video tidak ditemukan</p>
        <button @click="goBack" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
          Kembali ke Work Showcase
        </button>
      </div>
    </div>
    
    <div v-else class="max-w-6xl mx-auto px-4 py-8">
      <button @click="goBack" class="mb-6 flex items-center text-blue-600 hover:text-blue-700">
        <ArrowLeft class="mr-2 h-4 w-4" />
        Kembali ke Work Showcase
      </button>
      
      <div class="flex items-center gap-4 mb-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ video.title }}</h1>
      </div>
      
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Video Embed -->
          <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="aspect-video bg-gray-900">
              <iframe
                  v-if="embedUrl"
                  :src="embedUrl"
                  frameborder="0"
                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                  allowfullscreen
                  class="w-full h-full"
              ></iframe>
              <div v-else class="w-full h-full flex items-center justify-center text-white">
                <p>Video tidak tersedia</p>
              </div>
            </div>
          </div>

          <!-- Video Description -->
          <div v-if="video.description" class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Deskripsi</h2>
            <div class="prose prose-sm max-w-none text-gray-700" v-html="video.description"></div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
          <!-- Video Details -->
          <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Detail Video</h2>
            <div class="space-y-4">
              <div v-if="video.published_at" class="flex items-center gap-2">
                <Calendar class="h-5 w-5 text-gray-400" />
                <div>
                  <p class="text-sm font-medium text-gray-700">Tanggal Publikasi</p>
                  <p class="text-sm text-gray-600">{{ formatDate(video.published_at) }}</p>
                </div>
              </div>

              <div v-if="video.youtube_url" class="flex items-center gap-2">
                <Video class="h-5 w-5 text-gray-400" />
                <div>
                  <p class="text-sm font-medium text-gray-700">YouTube</p>
                  <a 
                      :href="video.youtube_url" 
                      target="_blank" 
                      rel="noopener noreferrer"
                      class="text-sm text-blue-600 hover:underline flex items-center gap-1"
                  >
                    Tonton di YouTube
                    <ExternalLink class="h-3 w-3" />
                  </a>
                </div>
              </div>
            </div>
          </div>

          <!-- Share Section -->
          <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Bagikan</h2>
            <div class="flex gap-2">
              <a
                  :href="`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(shareUrl)}`"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-md text-center hover:bg-blue-700 transition-colors"
              >
                Facebook
              </a>
              <a
                  :href="`https://twitter.com/intent/tweet?url=${encodeURIComponent(shareUrl)}&text=${encodeURIComponent(video.title)}`"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="flex-1 bg-blue-400 text-white px-4 py-2 rounded-md text-center hover:bg-blue-500 transition-colors"
              >
                Twitter
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <Footer />
</template>

<style scoped>
.prose {
  color: #374151;
}

.prose p {
  margin-bottom: 1rem;
}

.prose a {
  color: #2563eb;
  text-decoration: underline;
}
</style>

