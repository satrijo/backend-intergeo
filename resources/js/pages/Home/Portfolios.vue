<script setup>
import { ref, computed } from 'vue';
import { MapPin, Calendar, Users, Building } from 'lucide-vue-next';
import NavMenu from '@/components/NavMenu.vue';
import Footer from '@/components/Footer.vue';


const activeFilter = ref('all');

const projects = ref([
  {
    id: 1,
    title: 'Kompleks Perumahan Green Valley',
    category: 'residential',
    location: 'Jakarta Selatan',
    year: '2023',
    client: 'PT. Properti Nusantara',
    image: 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=600&h=400&fit=crop',
    description: 'Perencanaan dan pengembangan kompleks perumahan mewah dengan 150 unit rumah dan fasilitas lengkap.',
    services: ['Perencanaan Tata Ruang', 'Analisis Tanah', 'Manajemen Proyek']
  },
  {
    id: 2,
    title: 'Gedung Perkantoran Central Plaza',
    category: 'commercial',
    location: 'Jakarta Pusat',
    year: '2023',
    client: 'PT. Maju Bersama',
    image: 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=600&h=400&fit=crop',
    description: 'Konsultasi pembangunan gedung perkantoran 25 lantai dengan konsep green building.',
    services: ['Analisis Struktur', 'Perizinan', 'Supervisi Konstruksi']
  },
  {
    id: 3,
    title: 'Pusat Perbelanjaan Metro Square',
    category: 'commercial',
    location: 'Bandung',
    year: '2022',
    client: 'CV. Retail Indonesia',
    image: 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=600&h=400&fit=crop',
    description: 'Perencanaan dan konsultasi pengembangan pusat perbelanjaan modern dengan luas 50.000 m².',
    services: ['Studi Kelayakan', 'Desain Konsep', 'Analisis Investasi']
  },
  {
    id: 4,
    title: 'Kompleks Industri Tech Park',
    category: 'industrial',
    location: 'Karawang',
    year: '2022',
    client: 'PT. Industri Teknologi',
    image: 'https://images.unsplash.com/photo-1511467687858-23d96c32e4ae?w=600&h=400&fit=crop',
    description: 'Pengembangan kawasan industri teknologi dengan infrastruktur modern dan ramah lingkungan.',
    services: ['Masterplan', 'Analisis Lingkungan', 'Infrastruktur']
  },
  {
    id: 5,
    title: 'Apartemen Luxury Heights',
    category: 'residential',
    location: 'Surabaya',
    year: '2021',
    client: 'PT. Urban Living',
    image: 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=600&h=400&fit=crop',
    description: 'Konsultasi pengembangan apartemen mewah 40 lantai dengan fasilitas premium.',
    services: ['Analisis Pasar', 'Perencanaan Unit', 'Strategi Pemasaran']
  },
  {
    id: 6,
    title: 'Hotel Resort Pantai Indah',
    category: 'hospitality',
    location: 'Bali',
    year: '2021',
    client: 'PT. Hospitality Nusantara',
    image: 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=600&h=400&fit=crop',
    description: 'Perencanaan resort mewah tepi pantai dengan 200 kamar dan fasilitas rekreasi lengkap.',
    services: ['Konsep Desain', 'Analisis Site', 'Perizinan Pariwisata']
  }
]);

const categories = ref([
  { id: 'all', label: 'Semua Proyek' },
  { id: 'residential', label: 'Perumahan' },
  { id: 'commercial', label: 'Komersial' },
  { id: 'industrial', label: 'Industri' },
  { id: 'hospitality', label: 'Perhotelan' }
]);

const portfolioStats = ref([
  { number: "150+", label: "Proyek Selesai" },
  { number: "15+", label: "Tahun Pengalaman" },
  { number: "98%", label: "Kepuasan Klien" },
  { number: "25+", label: "Kota di Indonesia" }
]);

const filteredProjects = computed(() => {
  if (activeFilter.value === 'all') {
    return projects.value;
  }
  return projects.value.filter(project => project.category === activeFilter.value);
});

const setActiveFilter = (filterId) => {
  activeFilter.value = filterId;
};

const scrollToContact = () => {
  const section = document.getElementById('contact'); // Pastikan ID 'contact' ada di halaman Anda
  if (section) {
    section.scrollIntoView({ behavior: 'smooth' });
  } else {
    // router.push('/contact'); // Jika contact adalah halaman terpisah
    console.warn('Elemen dengan ID "contact" tidak ditemukan untuk scroll.');
  }
};
</script>

<template>
  <NavMenu />
  <div class="min-h-screen bg-gray-50 light-mode">
    <section class="bg-gradient-to-br from-blue-900 via-blue-800 to-blue-700 text-white py-20">
      <div class="max-w-7xl mx-auto px-6">
        <div class="text-center space-y-6">
          <h1 class="text-5xl font-bold">
            Portfolio <span class="text-yellow-400">Proyek</span>
          </h1>
          <p class="text-xl text-blue-100 max-w-3xl mx-auto">
            Lihat koleksi proyek-proyek berkualitas tinggi yang telah kami selesaikan
            dengan standar profesional dan kepuasan klien terbaik.
          </p>
          <div class="flex justify-center">
            <router-link to="/">
              <button
                  class="border-white text-white hover:bg-white hover:text-blue-900 px-6 py-2 rounded-md font-semibold transition-colors border"
              >
                Kembali ke Beranda
              </button>
            </router-link>
          </div>
        </div>
      </div>
    </section>

    <section class="py-16 bg-white">
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
    </section>

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
        <div class="grid lg:grid-cols-3 md:grid-cols-2 gap-8">
          <div
              v-for="project in filteredProjects"
              :key="project.id"
              class="overflow-hidden hover:shadow-lg transition-shadow bg-white rounded-lg border"
          >
            <div class="aspect-video overflow-hidden">
              <img
                  :src="project.image"
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
                  <span>{{ project.location }}</span>
                </div>
                <div class="flex items-center space-x-2 text-sm text-gray-600">
                  <Calendar class="w-4 h-4 flex-shrink-0" />
                  <span>{{ project.year }}</span>
                </div>
                <div class="flex items-center space-x-2 text-sm text-gray-600">
                  <Building class="w-4 h-4 flex-shrink-0" />
                  <span>{{ project.client }}</span>
                </div>
                <div class="pt-2">
                  <h4 class="font-semibold text-sm mb-2 text-gray-800">Layanan:</h4>
                  <div class="flex flex-wrap gap-1">
                    <span
                        v-for="(service, sIndex) in project.services"
                        :key="sIndex"
                        class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full"
                    >
                      {{ service }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="py-16 bg-blue-900 text-white">
      <div class="max-w-4xl mx-auto px-6 text-center space-y-6">
        <h2 class="text-3xl font-bold">
          Siap Memulai Proyek <span class="text-yellow-400">Impian Anda?</span>
        </h2>
        <p class="text-xl text-blue-100">
          Konsultasikan proyek Anda dengan tim ahli kami dan wujudkan visi Anda menjadi kenyataan.
        </p>
        <button
            @click="scrollToContact"
            class="bg-yellow-400 text-blue-900 hover:bg-yellow-300 px-8 py-3 rounded-md text-lg font-semibold transition-colors"
        >
          Konsultasi Gratis Sekarang
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