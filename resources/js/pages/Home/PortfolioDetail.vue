<script setup>
import { router, Head } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import NavMenu from '@/components/NavMenu.vue';
import Footer from '@/components/Footer.vue';
import { Calendar, Users, Building, ArrowLeft, User, ExternalLink, Code } from 'lucide-vue-next';

import { onMounted, onUnmounted, ref, computed } from 'vue';

// Function to strip HTML tags from text
const stripHtml = (html) => {
  const tmp = document.createElement('div');
  tmp.innerHTML = html;
  return tmp.textContent || tmp.innerText || '';
};

// Generate meta description from portfolio content
const generateMetaDescription = (portfolio) => {
  const cleanDesc = stripHtml(portfolio.description || '');
  const excerpt = cleanDesc.length > 160 ? cleanDesc.substring(0, 160) + '...' : cleanDesc;
  return `Detail proyek survey geofisika: ${portfolio.title}. ${excerpt}`;
};

// Generate keywords from portfolio data
const generateKeywords = (portfolio) => {
  const baseKeywords = ['survey seismik', 'GPR', 'georadar', 'survey geofisika', 'PT. Intergeo Mitigasi'];
  const techKeywords = portfolio.technologies ? portfolio.technologies.join(', ') : '';
  const clientKeywords = portfolio.client ? `survey ${portfolio.client}` : '';
  return [...baseKeywords, techKeywords, clientKeywords].filter(Boolean).join(', ');
};

const wasDarkMode = ref(false);

onMounted(() => {
    const htmlElement = document.documentElement;
    // Cek apakah dark mode aktif saat komponen dimuat
    if (htmlElement.classList.contains('dark')) {
        wasDarkMode.value = true;
        // Hapus class 'dark' untuk memaksa light mode
        htmlElement.classList.remove('dark');
    }
});

onUnmounted(() => {
    // Saat meninggalkan halaman, kembalikan class 'dark' jika sebelumnya aktif
    if (wasDarkMode.value) {
        document.documentElement.classList.add('dark');
    }
});

const props = defineProps({
  portfolio: {
    type: Object,
    required: true
  }
});

const goBack = () => {
  router.visit('/portfolio');
};

const zoomedImage = ref(null);
const openZoom = (img) => { zoomedImage.value = img; };
const closeZoom = () => { zoomedImage.value = null; };
</script>

<template>
  <Head>
    <title>{{ portfolio.title }} - Portfolio Survey Geofisika | PT. Intergeo Mitigasi</title>
    <meta name="description" :content="generateMetaDescription(portfolio)" />
    <link rel="canonical" :href="`https://surveyseismikgpr.com/portfolio/${portfolio.id}`" />
    <meta name="keywords" :content="generateKeywords(portfolio)" />
    <meta property="og:title" :content="`${portfolio.title} - Portfolio Survey Geofisika | PT. Intergeo Mitigasi`" />
    <meta property="og:description" :content="generateMetaDescription(portfolio)" />
    <meta property="og:image" :content="portfolio.images && portfolio.images[0] ? portfolio.images[0] : 'https://surveyseismikgpr.com/images/og-image.jpg'" />
    <meta property="og:url" :content="`https://surveyseismikgpr.com/portfolio/${portfolio.id}`" />
    <meta property="og:type" content="article" />
    <meta property="og:site_name" content="PT. Intergeo Mitigasi" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" :content="`${portfolio.title} - Portfolio Survey Geofisika | PT. Intergeo Mitigasi`" />
    <meta name="twitter:description" :content="generateMetaDescription(portfolio)" />
    <meta name="twitter:image" :content="portfolio.images && portfolio.images[0] ? portfolio.images[0] : 'https://surveyseismikgpr.com/images/og-image.jpg'" />
  </Head>
  <NavMenu />
  <div class="min-h-screen bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 py-8">
      <button @click="goBack" class="mb-6 flex items-center text-blue-600 hover:text-blue-700">
        <ArrowLeft class="mr-2 h-4 w-4" />
        Kembali ke Portfolio
      </button>
      <div class="flex items-center gap-4 mb-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ portfolio.title }}</h1>
      </div>
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Project Images Gallery -->
          <Card v-if="portfolio.images && portfolio.images.length > 0" class="bg-white">
            <CardHeader>
              <CardTitle>Galeri Foto Survey</CardTitle>
            </CardHeader>
            <CardContent>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div
                  v-for="(image, index) in portfolio.images"
                  :key="index"
                  class="aspect-video rounded-lg overflow-hidden border border-gray-200"
                >
                  <img
                    :src="image"
                    :alt="`${portfolio.title} - Foto Survey ${index + 1}`"
                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-200"
                    @click="openZoom(image)"
                    style="cursor:zoom-in"
                  />
                </div>
              </div>
            </CardContent>
          </Card>

          <!-- Project Description -->
          <Card class="bg-white">
            <CardHeader>
              <CardTitle>Detail Proyek Survey</CardTitle>
            </CardHeader>
            <CardContent class="prose prose-sm prose-invert">
              <div v-html="portfolio.description"></div>
            </CardContent>
          </Card>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
          <!-- Project Details -->
          <Card class="bg-white">
            <CardHeader>
              <CardTitle>Informasi Proyek</CardTitle>
            </CardHeader>
            <CardContent class="space-y-4">
              <div v-if="portfolio.client" class="flex items-center gap-2">
                <User class="h-4 w-4 text-muted-foreground" />
                <div>
                  <p class="text-sm font-medium">Klien</p>
                  <p class="text-sm text-muted-foreground">{{ portfolio.client }}</p>
                </div>
              </div>
              <div v-if="portfolio.project_date" class="flex items-center gap-2">
                <Calendar class="h-4 w-4 text-muted-foreground" />
                <div>
                  <p class="text-sm font-medium">Tanggal Survey</p>
                  <p class="text-sm text-muted-foreground">
                    {{ new Date(portfolio.project_date).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) }}
                  </p>
                </div>
              </div>
              <div v-if="portfolio.project_url" class="flex items-center gap-2">
                <ExternalLink class="h-4 w-4 text-muted-foreground" />
                <div>
                  <p class="text-sm font-medium">Link Proyek</p>
                  <a 
                    :href="portfolio.project_url" 
                    target="_blank" 
                    rel="noopener noreferrer"
                    class="text-sm text-blue-600 hover:underline"
                  >
                    Lihat Proyek
                  </a>
                </div>
              </div>
            </CardContent>
          </Card>

          <!-- Technologies -->
          <Card v-if="portfolio.technologies && portfolio.technologies.length > 0" class="bg-white">
            <CardHeader>
              <CardTitle class="flex items-center gap-2">
                <Code class="h-4 w-4" />
                Teknologi Survey
              </CardTitle>
            </CardHeader>
            <CardContent>
              <div class="flex flex-wrap gap-2">
                <Badge 
                  v-for="tech in portfolio.technologies" 
                  :key="tech" 
                  variant="outline"
                >
                  {{ tech }}
                </Badge>
              </div>
            </CardContent>
          </Card>
        </div>
      </div>
    </div>

    <!-- CTA Section -->
    <section class="py-16 bg-blue-900 text-white">
      <div class="max-w-4xl mx-auto px-6 text-center space-y-6">
        <h2 class="text-3xl font-bold">
          Butuh Survey <span class="text-yellow-400">Seismik atau GPR?</span>
        </h2>
        <p class="text-xl text-blue-100">
          Konsultasikan kebutuhan survey geofisika Anda dengan tim ahli kami. Dapatkan solusi terbaik untuk proyek Anda.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <button
              @click="() => router.visit('/contact')"
              class="bg-yellow-400 text-blue-900 hover:bg-yellow-300 px-8 py-3 rounded-md text-lg font-semibold transition-colors cursor-pointer"
          >
            Konsultasi Survey Gratis
          </button>
          <button
              @click="() => router.visit('/portfolio')"
              class="border-white text-white hover:bg-white hover:text-blue-900 px-8 py-3 rounded-md text-lg font-semibold transition-colors border"
          >
            Lihat Portfolio Lainnya
          </button>
        </div>
      </div>
    </section>
  </div>
  <Footer />

  <!-- Tambahkan overlay zoom -->
  <div v-if="zoomedImage" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-80" @click.self="closeZoom">
    <img :src="zoomedImage" class="max-h-[90vh] max-w-[90vw] rounded-lg shadow-lg" style="background:white" />
    <button @click="closeZoom" class="absolute top-4 right-4 text-white text-3xl font-bold">&times;</button>
  </div>
</template> 