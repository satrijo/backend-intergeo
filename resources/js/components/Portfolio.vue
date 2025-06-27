<script setup>
import { ref, computed, onMounted } from 'vue';
import { MapPin, Calendar, ArrowRight, Eye } from 'lucide-vue-next';
import { router } from '@inertiajs/vue3';

const featuredProjects = ref([]);
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

const goToPortfolioDetail = (project) => {
  router.visit(`/portfolio/${project.id}`);
};

const goToAllPortfolios = () => {
  router.visit('/portfolios');
};

// Fetch featured portfolios from API
const fetchFeaturedPortfolios = async () => {
  try {
    loading.value = true;
    const response = await fetch('/api/v1/portfolios?limit=6');
    if (!response.ok) throw new Error('Gagal mengambil data portfolio');
    const data = await response.json();
    const items = data.data || data;
    
    // Take only the first 6 projects as featured
    featuredProjects.value = items.slice(0, 6).map(item => {
      const cleanDesc = stripHtml(item.description || '');
      const excerpt = cleanDesc.length > 100 ? cleanDesc.substring(0, 100) + '...' : cleanDesc;
      return {
        id: item.id,
        title: item.title,
        description: excerpt,
        images: item.images || [],
        client: item.client || (item.user?.name ?? '-'),
        technologies: item.technologies || [],
        status: item.status,
        project_date: item.project_date ? formatDate(item.project_date) : '-',
        created_at: item.created_at,
      };
    });
  } catch (err) {
    console.error('Error fetching portfolios:', err);
    featuredProjects.value = [];
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchFeaturedPortfolios();
});
</script>

<template>
  <section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6">
      <!-- Section Header -->
      <div class="text-center mb-16">
        <h2 class="text-4xl font-bold text-gray-900 mb-4">
          Portfolio <span class="text-blue-600">Terbaru</span>
        </h2>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
          Lihat proyek-proyek survey seismik, GPR, dan georadar terbaru yang telah kami selesaikan 
          dengan teknologi canggih dan standar profesional tinggi.
        </p>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center py-12">
        <div class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-blue-600 border-r-transparent"></div>
        <p class="mt-4 text-gray-600">Memuat portfolio...</p>
      </div>

      <!-- Portfolio Grid -->
      <div v-else-if="featuredProjects.length > 0" class="grid lg:grid-cols-3 md:grid-cols-2 gap-8 mb-12">
        <div
          v-for="project in featuredProjects"
          :key="project.id"
          class="group bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2"
          @click="goToPortfolioDetail(project)"
          style="cursor: pointer;"
        >
          <!-- Project Image -->
          <div class="relative overflow-hidden aspect-video">
            <img
              :src="project.images[0] || '/images/placeholder-project.jpg'"
              :alt="`Proyek ${project.title} - Survey Seismik GPR`"
              class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
            />
            <!-- Overlay -->
            <div class="absolute inset-0 group-hover:bg-opacity-40 transition-all duration-300 flex items-center justify-center">
              <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <div class="bg-white bg-opacity-90 rounded-full p-3">
                  <Eye class="w-6 h-6 text-blue-600" />
                </div>
              </div>
            </div>
          </div>

          <!-- Project Content -->
          <div class="p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors">
              {{ project.title }}
            </h3>
            <p class="text-gray-600 text-sm leading-relaxed mb-4">
              {{ project.description }}
            </p>
            
            <!-- Project Details -->
            <div class="space-y-2 mb-4">
              <div class="flex items-center space-x-2 text-sm text-gray-600">
                <MapPin class="w-4 h-4 flex-shrink-0 text-blue-500" />
                <span>{{ project.client }}</span>
              </div>
              <div class="flex items-center space-x-2 text-sm text-gray-600">
                <Calendar class="w-4 h-4 flex-shrink-0 text-blue-500" />
                <span>{{ project.project_date }}</span>
              </div>
            </div>

            <!-- Technologies -->
            <div v-if="project.technologies && project.technologies.length" class="mb-4">
              <div class="flex flex-wrap gap-2">
                <span
                  v-for="(technology, tIndex) in project.technologies.slice(0, 3)"
                  :key="tIndex"
                  class="px-3 py-1 bg-blue-100 text-blue-800 text-xs rounded-full font-medium"
                >
                  {{ technology }}
                </span>
                <span v-if="project.technologies.length > 3" class="px-3 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">
                  +{{ project.technologies.length - 3 }}
                </span>
              </div>
            </div>

            <!-- View Project Button -->
            <div class="flex items-center text-blue-600 font-semibold text-sm group-hover:text-blue-700 transition-colors">
              <span>Lihat Detail</span>
              <ArrowRight class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" />
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-12">
        <div class="bg-white rounded-lg p-8 shadow-lg">
          <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <Eye class="w-8 h-8 text-blue-600" />
          </div>
          <h3 class="text-xl font-semibold text-gray-900 mb-2">Portfolio Sedang Disiapkan</h3>
          <p class="text-gray-600 mb-6">
            Tim kami sedang menyiapkan portfolio proyek-proyek terbaru. 
            Silakan hubungi kami untuk melihat contoh proyek yang telah kami selesaikan.
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
      <div v-if="featuredProjects.length > 0" class="text-center">
        <button
          @click="goToAllPortfolios"
          class="inline-flex items-center bg-blue-600 text-white px-8 py-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors shadow-lg hover:shadow-xl"
        >
          <span>Lihat Semua Portfolio</span>
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
</style> 