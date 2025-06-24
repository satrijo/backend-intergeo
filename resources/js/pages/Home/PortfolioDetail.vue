<script setup>
import { router } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import NavMenu from '@/components/NavMenu.vue';
import Footer from '@/components/Footer.vue';
import { Calendar, Users, Building, ArrowLeft, User, ExternalLink, Code } from 'lucide-vue-next';

import { onMounted, onUnmounted, ref, computed } from 'vue';


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
              <CardTitle>Project Images</CardTitle>
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
                    :alt="`${portfolio.title} - Image ${index + 1}`"
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
              <CardTitle>Project Description</CardTitle>
            </CardHeader>
            <CardContent>
              <div v-html="portfolio.description"></div>
            </CardContent>
          </Card>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
          <!-- Project Details -->
          <Card class="bg-white">
            <CardHeader>
              <CardTitle>Project Details</CardTitle>
            </CardHeader>
            <CardContent class="space-y-4">
              <div v-if="portfolio.client" class="flex items-center gap-2">
                <User class="h-4 w-4 text-muted-foreground" />
                <div>
                  <p class="text-sm font-medium">Client</p>
                  <p class="text-sm text-muted-foreground">{{ portfolio.client }}</p>
                </div>
              </div>
              <div v-if="portfolio.project_date" class="flex items-center gap-2">
                <Calendar class="h-4 w-4 text-muted-foreground" />
                <div>
                  <p class="text-sm font-medium">Project Date</p>
                  <p class="text-sm text-muted-foreground">
                    {{ new Date(portfolio.project_date).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) }}
                  </p>
                </div>
              </div>
              <div v-if="portfolio.project_url" class="flex items-center gap-2">
                <ExternalLink class="h-4 w-4 text-muted-foreground" />
                <div>
                  <p class="text-sm font-medium">Project URL</p>
                  <a 
                    :href="portfolio.project_url" 
                    target="_blank" 
                    rel="noopener noreferrer"
                    class="text-sm text-blue-600 hover:underline"
                  >
                    Visit Project
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
                Technologies Used
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
  </div>
  <Footer />

  <!-- Tambahkan overlay zoom -->
  <div v-if="zoomedImage" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-80" @click.self="closeZoom">
    <img :src="zoomedImage" class="max-h-[90vh] max-w-[90vw] rounded-lg shadow-lg" style="background:white" />
    <button @click="closeZoom" class="absolute top-4 right-4 text-white text-3xl font-bold">&times;</button>
  </div>
</template> 