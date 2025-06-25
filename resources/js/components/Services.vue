<script setup>
import { shallowRef } from 'vue'; //
import { Radio, MapPin, Search, FileText, Calculator, Users, Compass, HardHat, Settings } from 'lucide-vue-next';

const services = shallowRef([
  {
    icon: Radio,
    title: "Survey Seismik",
    description: "Layanan survey seismik untuk analisis struktur bawah permukaan, deteksi reservoir, dan studi geoteknik dengan teknologi canggih.",
    features: ["Seismic Reflection", "Seismic Refraction", "Cross-hole Seismic"]
  },
  {
    icon: Search,
    title: "Ground Penetrating Radar (GPR)",
    description: "Deteksi utilitas bawah tanah, analisis struktur beton, dan pemetaan bawah permukaan menggunakan teknologi radar.",
    features: ["Deteksi Utilitas", "Analisis Beton", "Pemetaan Bawah Tanah"]
  },
  {
    icon: MapPin,
    title: "Survey Georadar",
    description: "Pemetaan geologi bawah permukaan, deteksi air tanah, dan analisis struktur tanah untuk konstruksi dan eksplorasi.",
    features: ["Pemetaan Geologi", "Deteksi Air Tanah", "Analisis Struktur Tanah"]
  },
  {
    icon: Compass,
    title: "Survey dan Pemetaan",
    description: "Layanan survey dan pemetaan topografi, batimetri, dan geologi permukaan untuk perencanaan proyek dan eksplorasi.",
    features: ["Survey Topografi", "Survey Batimetri", "Pemetaan Geologi"]
  },
  {
    icon: HardHat,
    title: "Studi Kelayakan Tambang",
    description: "Analisis kelayakan tambang, studi geologi ekonomi, dan evaluasi potensi mineral untuk industri pertambangan.",
    features: ["Analisis Kelayakan", "Studi Geologi Ekonomi", "Evaluasi Potensi Mineral"]
  },
  {
    icon: Settings,
    title: "Survey Infrastruktur & Jembatan",
    description: "Inspeksi kondisi fondasi jembatan, analisis struktur infrastruktur, dan pemantauan keamanan bangunan dengan teknologi geofisika.",
    features: ["Inspeksi Fondasi", "Analisis Struktur", "Pemantauan Keamanan"]
  },
  {
    icon: FileText,
    title: "Analisis Geoteknik",
    description: "Studi kelayakan tanah, analisis stabilitas lereng, dan rekomendasi fondasi berdasarkan data survey geofisika.",
    features: ["Studi Kelayakan", "Analisis Stabilitas", "Rekomendasi Fondasi"]
  },
  {
    icon: Calculator,
    title: "Interpretasi Data Survey",
    description: "Pengolahan dan interpretasi data survey seismik, GPR, dan georadar untuk laporan teknis yang akurat.",
    features: ["Pengolahan Data", "Interpretasi Hasil", "Laporan Teknis"]
  },
  {
    icon: Users,
    title: "Konsultasi Survey",
    description: "Konsultasi lengkap untuk perencanaan survey, pemilihan metode, dan implementasi proyek survey geofisika.",
    features: ["Perencanaan Survey", "Pemilihan Metode", "Implementasi Proyek"]
  }
]);

const goToContact = () => {
  window.location.href = '/contact';
};
</script>

<template>
  <section id="services" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center mb-16">
        <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
          Layanan <span class="text-blue-600">Survey Geofisika</span> Kami
        </h2>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
          Solusi lengkap survey seismik, GPR, dan georadar untuk deteksi utilitas, 
          analisis struktur tanah, dan studi geoteknik dengan teknologi canggih.
        </p>
      </div>

      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div
            v-for="(service, index) in services"
            :key="index"
            class="group hover:shadow-xl transition-all duration-300 hover:-translate-y-2 border-0 bg-white rounded-lg overflow-hidden"
        >
          <div class="p-6 text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-600 transition-colors duration-300">
              <component :is="service.icon" class="w-8 h-8 text-blue-600 group-hover:text-white transition-colors duration-300" />
            </div>
            <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors mb-2">
              {{ service.title }}
            </h3>
            <p class="text-gray-600 mb-6 leading-relaxed text-sm">
              {{ service.description }}
            </p>
            <div class="space-y-2">
              <div
                  v-for="(feature, idx) in service.features"
                  :key="idx"
                  class="flex items-center justify-center text-sm text-gray-500"
              >
                <div class="w-2 h-2 bg-blue-400 rounded-full mr-2"></div>
                {{ feature }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="text-center mt-12">
        <p class="text-lg text-gray-600 mb-6">
          Butuh layanan survey geofisika khusus?
        </p>
        <button class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors cursor-pointer" @click="goToContact">
          Konsultasi Survey Custom
        </button>
      </div>
    </div>
  </section>
</template>


