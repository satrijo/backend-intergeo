<script setup>
import { shallowRef } from 'vue';
import { CheckCircle, Award, Users, Calendar, Target, Eye, Heart } from 'lucide-vue-next';
import NavMenu from '@/components/NavMenu.vue';
import Footer from '@/components/Footer.vue';

const values = shallowRef([
  {
    icon: Target,
    title: "Profesionalisme",
    description: "Kami berkomitmen memberikan layanan terbaik dengan standar profesional tinggi dalam setiap proyek."
  },
  {
    icon: Eye,
    title: "Transparansi",
    description: "Keterbukaan dalam setiap proses kerja dan komunikasi yang jelas dengan klien."
  },
  {
    icon: Heart,
    title: "Kepercayaan",
    description: "Membangun hubungan jangka panjang berdasarkan kepercayaan dan hasil yang memuaskan."
  }
]);

const team = shallowRef([
  {
    name: "Dr. Ahmad Wijaya",
    role: "Founder & CEO",
    experience: "20+ tahun",
    image: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300&h=300&fit=crop&crop=face",
    description: "Ahli perencanaan kota dengan pengalaman internasional"
  },
  {
    name: "Ir. Sari Kusuma",
    role: "Lead Consultant",
    experience: "15+ tahun",
    image: "https://images.unsplash.com/photo-1494790108755-2616b612b5fc?w=300&h=300&fit=crop&crop=face",
    description: "Spesialis struktur bangunan dan analisis tanah"
  },
  {
    name: "Budi Santoso, S.T.",
    role: "Project Manager",
    experience: "12+ tahun",
    image: "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=300&h=300&fit=crop&crop=face",
    description: "Expert dalam manajemen proyek dan perizinan"
  }
]);

const stats = shallowRef([
  { icon: Users, number: "500+", label: "Klien Puas" },
  { icon: CheckCircle, number: "1000+", label: "Proyek Selesai" },
  { icon: Award, number: "15+", label: "Tahun Pengalaman" },
  { icon: Calendar, number: "24/7", label: "Layanan Support" }
]);

const scrollToContact = () => {
  const section = document.getElementById('contact'); // Pastikan ada elemen dengan id="contact" di halaman Anda
  if (section) {
    section.scrollIntoView({ behavior: 'smooth' });
  } else {
    // Mungkin navigasi ke halaman kontak jika berbeda halaman
    // router.push('/contact'); // Jika menggunakan Vue Router
    console.warn('Elemen dengan ID "contact" tidak ditemukan untuk scroll.');
  }
};

const scrollToPortfolio = () => {
  const section = document.getElementById('portfolio'); // Pastikan ada elemen dengan id="portfolio" di halaman Anda
  if (section) {
    section.scrollIntoView({ behavior: 'smooth' });
  } else {
    // Mungkin navigasi ke halaman portfolio jika berbeda halaman
    // router.push('/portfolio'); // Jika menggunakan Vue Router
    console.warn('Elemen dengan ID "portfolio" tidak ditemukan untuk scroll.');
  }
};

// Komponen Button dan Card dari '@/components/ui/*' di React
// perlu diimplementasikan sebagai komponen Vue kustom jika ingin digunakan
// dengan cara yang sama (misalnya, <Button> atau <Card>).
// Dalam contoh ini, struktur Card direplikasi dengan <div> dan styling Tailwind,
// dan Button menggunakan elemen <button> HTML standar dengan styling Tailwind.
</script>

<template>
    <NavMenu />
    <div class="min-h-screen">
      <section class="relative py-20 bg-gradient-to-br from-blue-900 via-blue-800 to-blue-700 text-white">
        <div class="absolute inset-0 opacity-10">
          <div
              class="absolute inset-0 bg-blue-800"
              :style="{
              backgroundImage: `radial-gradient(circle at 25px 25px, rgba(255,255,255,0.1) 2px, transparent 0)`,
              backgroundSize: '50px 50px'
            }"
          ></div>
        </div>
  
        <div class="relative max-w-7xl mx-auto px-6 text-center">
          <h1 class="text-5xl lg:text-6xl font-bold mb-6">
            Tentang <span class="text-yellow-400">Intergeo Mitigasi</span>
          </h1>
          <p class="text-xl text-blue-100 max-w-3xl mx-auto leading-relaxed">
            Dipercaya selama lebih dari 15 tahun sebagai mitra terbaik dalam konsultasi
            tanah dan bangunan di Indonesia
          </p>
        </div>
      </section>
  
      <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6">
          <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div class="space-y-8">
              <h2 class="text-4xl lg:text-5xl font-bold text-gray-900">
                Perjalanan <span class="text-blue-600">Kami</span>
              </h2>
              <div class="space-y-6 text-lg text-gray-700 leading-relaxed">
                <p>
                  Didirikan pada tahun 2009, Intergeo Mitigasi lahir dari visi untuk
                  memberikan solusi terbaik dalam bidang konsultasi tanah dan bangunan.
                  Kami memulai perjalanan dengan tim kecil yang berpengalaman dan
                  komitmen tinggi untuk memberikan layanan berkualitas.
                </p>
                <p>
                  Seiring berjalannya waktu, kami terus berkembang dan memperluas
                  jangkauan layanan. Dari proyek-proyek kecil hingga mega proyek,
                  kami telah membuktikan kemampuan dalam menangani berbagai tantangan
                  dengan solusi inovatif dan efektif.
                </p>
                <p>
                  Hari ini, Intergeo Mitigasi telah menjadi nama terpercaya dengan
                  lebih dari 500 klien puas dan 1000+ proyek yang berhasil diselesaikan
                  di seluruh Indonesia.
                </p>
              </div>
            </div>
  
            <div class="relative">
              <img
                  src="https://images.unsplash.com/photo-1486312338219-ce68d2c6f44d?w=600&h=500&fit=crop"
                  alt="Tim Intergeo Mitigasi"
                  class="w-full h-96 object-cover rounded-2xl shadow-2xl"
              />
              <div class="absolute -bottom-8 -left-8 bg-yellow-400 text-blue-900 p-6 rounded-xl shadow-xl">
                <div class="text-3xl font-bold">15+</div>
                <div class="text-sm font-medium">Tahun Pengalaman</div>
              </div>
            </div>
          </div>
        </div>
      </section>
  
      <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6">
          <div class="text-center mb-16">
            <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
              Nilai-Nilai <span class="text-blue-600">Kami</span>
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
              Prinsip-prinsip yang menjadi fondasi dalam setiap layanan yang kami berikan
            </p>
          </div>
  
          <div class="grid md:grid-cols-3 gap-8">
            <div
                v-for="(value, index) in values"
                :key="index"
                class="text-center hover:shadow-lg transition-shadow duration-300 bg-white rounded-lg p-8 border"
            >
              <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <component :is="value.icon" class="w-8 h-8 text-blue-600" />
              </div>
              <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ value.title }}</h3>
              <p class="text-gray-600 leading-relaxed">{{ value.description }}</p>
            </div>
          </div>
        </div>
      </section>
  
  
      <section class="py-20 bg-blue-900 text-white">
        <div class="max-w-7xl mx-auto px-6">
          <div class="text-center mb-16">
            <h2 class="text-4xl lg:text-5xl font-bold mb-6">
              Pencapaian <span class="text-yellow-400">Kami</span>
            </h2>
            <p class="text-xl text-blue-100 max-w-3xl mx-auto">
              Angka-angka yang menunjukkan dedikasi dan kualitas layanan kami
            </p>
          </div>
  
          <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
            <div
                v-for="(stat, index) in stats"
                :key="index"
                class="text-center group"
            >
              <div class="w-20 h-20 bg-yellow-400 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                <component :is="stat.icon" class="w-10 h-10 text-blue-900" />
              </div>
              <div class="text-4xl font-bold text-yellow-400 mb-2">{{ stat.number }}</div>
              <div class="text-blue-100">{{ stat.label }}</div>
            </div>
          </div>
        </div>
      </section>
  
      <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-6 text-center">
          <h2 class="text-4xl font-bold text-gray-900 mb-6">
            Siap Bekerja Sama dengan Kami?
          </h2>
          <p class="text-xl text-gray-600 mb-8 leading-relaxed">
            Mari diskusikan proyek Anda dan temukan solusi terbaik bersama tim ahli kami.
            Konsultasi gratis untuk evaluasi awal proyek Anda.
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button
                @click="scrollToContact"
                class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-md text-lg font-semibold transition-colors"
            >
              Konsultasi Gratis
            </button>
            <button
                @click="scrollToPortfolio"
                class="border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white px-8 py-3 rounded-md text-lg font-semibold transition-colors border"
            >
              Lihat Portfolio
            </button>
          </div>
        </div>
      </section>
    </div>
    <Footer />
  </template>
  
 
  
  <style scoped>
  /* Gaya spesifik komponen bisa ditambahkan di sini jika perlu */
  </style>