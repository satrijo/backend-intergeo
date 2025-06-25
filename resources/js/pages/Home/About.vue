<script setup>
import { shallowRef } from 'vue';
import { CheckCircle, Award, Users, Calendar, Target, Eye, Heart } from 'lucide-vue-next';
import NavMenu from '@/components/NavMenu.vue';
import Footer from '@/components/Footer.vue';
import { Head } from '@inertiajs/vue3';


const values = shallowRef([
  {
    icon: Target,
    title: "Profesionalisme Survey",
    description: "Kami berkomitmen memberikan layanan survey geofisika terbaik dengan standar profesional tinggi dan teknologi canggih."
  },
  {
    icon: Eye,
    title: "Akurasi Data",
    description: "Hasil survey seismik, GPR, dan georadar yang akurat dan detail untuk analisis yang tepat."
  },
  {
    icon: Heart,
    title: "Kepercayaan Klien",
    description: "Membangun hubungan jangka panjang berdasarkan kepercayaan dan hasil survey yang memuaskan."
  }
]);

const team = shallowRef([
  {
    name: "Dr. Ahmad Wijaya",
    role: "Founder & CEO",
    experience: "20+ tahun",
    image: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300&h=300&fit=crop&crop=face",
    description: "Ahli survey geofisika dengan pengalaman internasional"
  },
  {
    name: "Ir. Sari Kusuma",
    role: "Lead Survey Consultant",
    experience: "15+ tahun",
    image: "https://images.unsplash.com/photo-1494790108755-2616b612b5fc?w=300&h=300&fit=crop&crop=face",
    description: "Spesialis survey seismik dan analisis struktur bawah permukaan"
  },
  {
    name: "Budi Santoso, S.T.",
    role: "Survey Project Manager",
    experience: "12+ tahun",
    image: "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=300&h=300&fit=crop&crop=face",
    description: "Expert dalam manajemen proyek survey dan interpretasi data"
  }
]);

const stats = shallowRef([
  { icon: Users, number: "30+", label: "Klien Survey" },
  { icon: CheckCircle, number: "50+", label: "Proyek Survey Selesai" },
  { icon: Award, number: "7+", label: "Tahun Pengalaman" },
  { icon: Calendar, number: "24/7", label: "Layanan Support" }
]);

const scrollToContact = () => {
  const section = document.getElementById('contact');
  if (section) {
    section.scrollIntoView({ behavior: 'smooth' });
  } else {
    window.location.href = '/contact';
  }
};

const scrollToPortfolio = () => {
  const section = document.getElementById('portfolio');
  if (section) {
    section.scrollIntoView({ behavior: 'smooth' });
  } else {
    window.location.href = '/portfolio';
  }
};

// Komponen Button dan Card dari '@/components/ui/*' di React
// perlu diimplementasikan sebagai komponen Vue kustom jika ingin digunakan
// dengan cara yang sama (misalnya, <Button> atau <Card>).
// Dalam contoh ini, struktur Card direplikasi dengan <div> dan styling Tailwind,
// dan Button menggunakan elemen <button> HTML standar dengan styling Tailwind.
</script>

<template>
  <Head>
    <title>Tentang PT. Intergeo Mitigasi | Spesialis Survey Seismik & GPR Profesional</title>
    <meta name="description" content="Kenali PT. Intergeo Mitigasi, spesialis survey seismik, GPR, dan georadar dengan pengalaman lebih dari 7 tahun di Indonesia. Tim ahli survey geofisika, layanan terpercaya, hasil akurat." />
    <link rel="canonical" href="https://surveyseismikgpr.com/about" />
    <meta name="keywords" content="tentang intergeo, survey seismik, GPR, georadar, tim ahli survey, pengalaman survey geofisika, PT. Intergeo Mitigasi" />
    <meta property="og:title" content="Tentang PT. Intergeo Mitigasi | Spesialis Survey Seismik & GPR Profesional" />
    <meta property="og:description" content="Kenali PT. Intergeo Mitigasi, spesialis survey seismik, GPR, dan georadar dengan pengalaman lebih dari 7 tahun di Indonesia." />
    <meta property="og:image" content="https://surveyseismikgpr.com/images/og-image.jpg" />
    <meta property="og:url" content="https://surveyseismikgpr.com/about" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="PT. Intergeo Mitigasi" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Tentang PT. Intergeo Mitigasi | Spesialis Survey Seismik & GPR Profesional" />
    <meta name="twitter:description" content="Kenali PT. Intergeo Mitigasi, spesialis survey seismik, GPR, dan georadar dengan pengalaman lebih dari 7 tahun di Indonesia." />
    <meta name="twitter:image" content="https://surveyseismikgpr.com/images/og-image.jpg" />
  </Head>
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
          Dipercaya sejak 2018 sebagai spesialis survey seismik, GPR, dan georadar terbaik di Indonesia dengan pengalaman lebih dari 7 tahun.
        </p>
      </div>
    </section>

    <section class="py-20 bg-white">
      <div class="max-w-7xl mx-auto px-6">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
          <div class="space-y-8">
            <h2 class="text-4xl lg:text-5xl font-bold text-gray-900">
              Perjalanan <span class="text-blue-600">Survey</span> Kami
            </h2>
            <div class="space-y-6 text-lg text-gray-700 leading-relaxed">
              <p>
                Didirikan pada tahun 2018, Intergeo Mitigasi lahir dari visi untuk
                memberikan solusi terbaik dalam bidang survey geofisika. Kami memulai perjalanan 
                dengan tim kecil yang berpengalaman dan komitmen tinggi untuk memberikan 
                layanan survey seismik, GPR, dan georadar berkualitas.
              </p>
              <p>
                Seiring berjalannya waktu, kami terus berkembang dan memperluas
                jangkauan layanan survey. Dari proyek survey kecil hingga mega proyek,
                kami telah membuktikan kemampuan dalam menangani berbagai tantangan survey
                dengan teknologi canggih dan hasil yang akurat.
              </p>
              <p>
                Hari ini, Intergeo Mitigasi telah menjadi nama terpercaya dalam survey geofisika
                dengan lebih dari 30 klien survey puas dan 50+ proyek survey yang berhasil diselesaikan
                di seluruh Indonesia.
              </p>
            </div>
          </div>

          <div class="relative">
            <img
                src="https://images.unsplash.com/photo-1486312338219-ce68d2c6f44d?w=600&h=500&fit=crop"
                alt="Tim Survey Geofisika Intergeo Mitigasi"
                class="w-full h-96 object-cover rounded-2xl shadow-2xl"
            />
            <div class="absolute -bottom-8 -left-8 bg-yellow-400 text-blue-900 p-6 rounded-xl shadow-xl">
              <div class="text-3xl font-bold">7+</div>
              <div class="text-sm font-medium">Tahun Pengalaman Survey</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="py-20 bg-gray-50">
      <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16">
          <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
            Nilai-Nilai <span class="text-blue-600">Survey</span> Kami
          </h2>
          <p class="text-xl text-gray-600 max-w-3xl mx-auto">
            Prinsip-prinsip yang menjadi fondasi dalam setiap layanan survey geofisika yang kami berikan
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


    <!-- <section class="py-20 bg-blue-900 text-white">
      <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16">
          <h2 class="text-4xl lg:text-5xl font-bold mb-6">
            Pencapaian <span class="text-yellow-400">Survey</span> Kami
          </h2>
          <p class="text-xl text-blue-100 max-w-3xl mx-auto">
            Angka-angka yang menunjukkan dedikasi dan kualitas layanan survey geofisika kami
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
    </section> -->

    <section class="py-20 bg-blue-900 text-white">
      <div class="max-w-4xl mx-auto px-6 text-center">
        <h2 class="text-4xl font-bold text-white mb-6">
          Siap Memulai Proyek Survey Anda?
        </h2>
        <p class="text-xl text-blue-100 mb-8 leading-relaxed">
          Mari diskusikan proyek survey seismik, GPR, atau georadar Anda dan temukan solusi terbaik 
          bersama tim ahli survey geofisika kami. Konsultasi survey gratis untuk evaluasi awal.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <button
              @click="scrollToContact"
              class="bg-yellow-400 hover:bg-yellow-500 text-blue-900 px-8 py-3 rounded-md text-lg font-semibold transition-colors cursor-pointer"
          >
            Konsultasi Survey Gratis
          </button>
          <button
              @click="scrollToPortfolio"
              class="border-yellow-400 text-yellow-400 hover:bg-yellow-400 hover:text-blue-900 px-8 py-3 rounded-md text-lg font-semibold transition-colors border cursor-pointer"
          >
            Lihat Portfolio Survey
          </button>
        </div>
      </div>
    </section>
  </div>
  <Footer />
</template>




<style scoped>

</style>