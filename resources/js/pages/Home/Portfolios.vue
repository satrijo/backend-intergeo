<script setup>
import { ref, computed, onMounted } from 'vue';
import { MapPin, Calendar, Users, Building } from 'lucide-vue-next';
import NavMenu from '@/components/NavMenu.vue';
import Footer from '@/components/Footer.vue';
import { usePage, router, Head } from '@inertiajs/vue3';

const activeFilter = ref('all');
const projects = ref([]);
const loading = ref(true);
const error = ref(null);
const currentPage = ref(1);
const projectsPerPage = 6;

const categories = ref([{ id: 'all', label: 'Semua' }]);

const portfolioStats = ref([
  { number: '-', label: 'Total Proyek' },
  { number: '7+ Tahun', label: 'Tahun Pengalaman' },
  { number: '-', label: 'Client Unik' },
  { number: '-', label: 'Teknologi Unik' }
]);

const updateCategories = (items) => {
  const techSet = new Set();
  items.forEach(item => {
    (item.technologies || []).forEach(tech => techSet.add(tech));
  });
  categories.value = [
    { id: 'all', label: 'Semua' },
    ...Array.from(techSet).map(tech => ({ id: tech, label: tech }))
  ];
};

const filteredProjects = computed(() => {
  if (activeFilter.value === 'all') {
    return projects.value;
  }
  // Filter berdasarkan teknologi
  return projects.value.filter(project =>
    project.technologies && project.technologies.includes(activeFilter.value)
  );
});

const totalPages = computed(() => Math.ceil(filteredProjects.value.length / projectsPerPage));

const currentProjects = computed(() => {
  const startIndex = (currentPage.value - 1) * projectsPerPage;
  return filteredProjects.value.slice(startIndex, startIndex + projectsPerPage);
});

const setActiveFilter = (filterId) => {
  activeFilter.value = filterId;
  currentPage.value = 1; // Reset ke halaman pertama saat filter berubah
};

const changePage = (pageNumber) => {
  if (pageNumber >= 1 && pageNumber <= totalPages.value) {
    currentPage.value = pageNumber;
  }
};

const scrollToContact = () => {
  const section = document.getElementById('contact');
  if (section) {
    section.scrollIntoView({ behavior: 'smooth' });
  } else {
    window.location.href = '/contact';
  }
};

const goToPortfolioDetail = (project) => {
  router.visit(`/portfolio/${project.id}`);
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

// Fetch portfolios from API
const fetchPortfolios = async () => {
  try {
    loading.value = true;
    error.value = null;
    const response = await fetch('/api/v1/portfolios');
    if (!response.ok) throw new Error('Gagal mengambil data portfolio');
    const data = await response.json();
    const items = data.data || data;
    projects.value = items.map(item => {
      const cleanDesc = stripHtml(item.description || '');
      const excerpt = cleanDesc.length > 120 ? cleanDesc.substring(0, 120) + '...' : cleanDesc;
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
    updateCategories(projects.value);
  } catch (err) {
    error.value = err.message;
    projects.value = [];
    updateCategories([]);
  } finally {
    loading.value = false;
  }
};

const fetchPortfolioStats = async () => {
  try {
    const response = await fetch('/api/v1/portfolios/stats');
    if (!response.ok) throw new Error('Gagal mengambil statistik portfolio');
    const stats = await response.json();
    portfolioStats.value = [
      { number: stats.total_projects, label: 'Total Proyek' },
      { number: stats.years_experience + ' Tahun', label: 'Tahun Pengalaman' },
      { number: stats.unique_clients, label: 'Client' },
      { number: stats.unique_technologies, label: 'Teknologi' },
    ];
  } catch (err) {
    // Biarkan default jika gagal
  }
};

onMounted(() => {
  fetchPortfolios();
  fetchPortfolioStats();
});
</script>

<template>
  <Head>
    <title>Portfolio Proyek Survey Seismik & GPR | PT. Intergeo Mitigasi</title>
    <meta name="description" content="Lihat portfolio proyek-proyek survey seismik, GPR, dan georadar terbaik PT. Intergeo Mitigasi. Bukti nyata keahlian kami dalam survey geofisika untuk berbagai industri." />
    <link rel="canonical" href="https://surveyseismikgpr.com/portfolios" />
    <meta name="keywords" content="portfolio survey seismik, proyek GPR, proyek georadar, survey geofisika, PT. Intergeo Mitigasi, portfolio intergeo" />
    <meta property="og:title" content="Portfolio Proyek Survey Seismik & GPR | PT. Intergeo Mitigasi" />
    <meta property="og:description" content="Lihat portfolio proyek-proyek survey seismik, GPR, dan georadar terbaik PT. Intergeo Mitigasi." />
    <meta property="og:image" content="https://surveyseismikgpr.com/images/og-image.jpg" />
    <meta property="og:url" content="https://surveyseismikgpr.com/portfolios" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="PT. Intergeo Mitigasi" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Portfolio Proyek Survey Seismik & GPR | PT. Intergeo Mitigasi" />
    <meta name="twitter:description" content="Lihat portfolio proyek-proyek survey seismik, GPR, dan georadar terbaik PT. Intergeo Mitigasi." />
    <meta name="twitter:image" content="https://surveyseismikgpr.com/images/og-image.jpg" />
  </Head>
  <NavMenu />
  <div class="min-h-screen bg-gray-50 light-mode">
    <section class="bg-gradient-to-br from-blue-900 via-blue-800 to-blue-700 text-white py-20">
      <div class="max-w-7xl mx-auto px-6">
        <div class="text-center space-y-6">
          <h1 class="text-5xl font-bold">
            Portfolio <span class="text-yellow-400">Survey Geofisika</span>
          </h1>
          <p class="text-xl text-blue-100 max-w-3xl mx-auto">
            Lihat koleksi proyek-proyek survey seismik, GPR, dan georadar berkualitas tinggi yang telah kami selesaikan
            dengan teknologi terdepan dan standar profesional untuk berbagai industri.
          </p>
          <div class="flex justify-center">
            <button
                @click="() => router.visit('/')"
                class="border-white text-white hover:bg-white hover:text-blue-900 px-6 py-2 rounded-md font-semibold transition-colors border cursor-pointer"
            >
              Kembali ke Beranda
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- <section class="py-16 bg-white">
      <div class="max-w-7xl mx-auto px-6">
        <div class="grid md:grid-cols-4 gap-8">
          <div
              v-for="(stat, index) in portfolioStats"
              :key="index"
              class="text-center"
          >
            <div class="text-4xl font-bold text-blue-900">{{ stat.number }}</div>
            <div class="text-gray-600 mt-2">{{ stat.label }}</div>
          </div>
        </div>
      </div>
    </section> -->

    <section class="py-8 bg-gray-100">
      <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-wrap justify-center gap-4">
          <button
              v-for="category in categories"
              :key="category.id"
              @click="setActiveFilter(category.id)"
              :class="[
              'px-4 py-2 rounded-md font-medium transition-colors',
              activeFilter === category.id
                ? 'bg-blue-900 text-white hover:bg-blue-800'
                : 'bg-white text-gray-700 border border-gray-300 hover:bg-blue-50'
            ]"
          >
            {{ category.label }}
          </button>
        </div>
      </div>
    </section>

    <section class="py-16">
      <div class="max-w-7xl mx-auto px-6">
        <div v-if="loading" class="text-center py-12">
          <div class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-blue-600 border-r-transparent"></div>
          <p class="mt-4 text-gray-600">Memuat portfolio...</p>
        </div>
        <div v-else-if="error" class="text-center py-12">
          <p class="text-red-600 mb-4">{{ error }}</p>
          <button @click="fetchPortfolios" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
            Coba Lagi
          </button>
        </div>
        <div v-else>
          <div v-if="filteredProjects.length === 0" class="text-center py-12">
            <p class="text-gray-600 text-lg">Belum ada portfolio yang dipublikasikan.</p>
            <p class="text-gray-500 mt-2">Silakan kembali lagi nanti.</p>
          </div>
          <div v-else class="grid lg:grid-cols-3 md:grid-cols-2 gap-8">
            <div
                v-for="project in currentProjects"
                :key="project.id"
                class="overflow-hidden hover:shadow-lg transition-shadow bg-white rounded-lg border"
                @click="goToPortfolioDetail(project)"
                style="cursor:pointer"
            >
              <div class="aspect-video overflow-hidden">
                <img
                    :src="project.images[0]"
                    :alt="project.title"
                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                />
              </div>
              <div class="p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ project.title }}</h3>
                <p class="text-gray-600 text-sm leading-relaxed mb-4">
                  {{ project.description }}
                </p>
                <div class="space-y-3">
                  <div class="flex items-center space-x-2 text-sm text-gray-600">
                    <MapPin class="w-4 h-4 flex-shrink-0" />
                    <span>{{ project.client }}</span>
                  </div>
                  <div class="flex items-center space-x-2 text-sm text-gray-600">
                    <Calendar class="w-4 h-4 flex-shrink-0" />
                    <span>{{ project.project_date }}</span>
                  </div>
                  <div v-if="project.technologies && project.technologies.length" class="pt-2">
                    <h4 class="font-semibold text-sm mb-2 text-gray-800">Teknologi Survey:</h4>
                    <div class="flex flex-wrap gap-1">
                      <span
                          v-for="(technology, tIndex) in project.technologies"
                          :key="tIndex"
                          class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full"
                      >
                        {{ technology }}
                      </span>
                    </div>
                  </div>
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
                'rounded-md px-4 py-2 font-medium transition-colors',
                currentPage === pageNumber
                  ? 'bg-blue-600 text-white hover:bg-blue-700'
                  : 'border border-gray-300 bg-white text-gray-700 hover:bg-gray-100',
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

    <section class="py-16 bg-blue-900 text-white">
      <div class="max-w-4xl mx-auto px-6 text-center space-y-6">
        <h2 class="text-3xl font-bold">
          Siap Memulai Proyek <span class="text-yellow-400">Survey Geofisika?</span>
        </h2>
        <p class="text-xl text-blue-100">
          Konsultasikan kebutuhan survey seismik, GPR, atau georadar Anda dengan tim ahli geofisika kami.
        </p>
        <button
            @click="scrollToContact"
            class="bg-yellow-400 text-blue-900 hover:bg-yellow-300 px-8 py-3 rounded-md text-lg font-semibold transition-colors cursor-pointer"
        >
          Konsultasi Survey Gratis
        </button>
      </div>
    </section>
  </div>
  <Footer />
</template>

<style scoped>
.aspect-video {
  aspect-ratio: 16 / 9;
}

/* Force light mode for homepage */
.light-mode {
  color-scheme: light;
}
</style>