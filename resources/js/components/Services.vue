<script setup>
import { shallowRef } from 'vue'; //
import { Building, MapPin, Home, FileText, Calculator, Users } from 'lucide-vue-next';

const services = shallowRef([
  {
    icon: Building,
    title: "Konsultasi Pembangunan",
    description: "Perencanaan dan pengawasan pembangunan gedung, rumah, dan infrastruktur dengan standar internasional.",
    features: ["Desain Arsitektur", "Analisis Struktur", "Pengawasan Konstruksi"]
  },
  {
    icon: MapPin,
    title: "Survei & Pemetaan Tanah",
    description: "Layanan survei topografi, pemetaan digital, dan analisis kelayakan tanah untuk berbagai keperluan.",
    features: ["Survei Topografi", "Pemetaan Digital", "Analisis Tanah"]
  },
  {
    icon: Home,
    title: "Perencanaan Properti",
    description: "Konsultasi pengembangan properti residensial dan komersial dari konsep hingga implementasi.",
    features: ["Master Plan", "Feasibility Study", "Market Analysis"]
  },
  {
    icon: FileText,
    title: "Perizinan & Legal",
    description: "Pengurusan dokumen legal, IMB, sertifikat tanah, dan berbagai perizinan konstruksi.",
    features: ["Pengurusan IMB", "Sertifikat Tanah", "Dokumen Legal"]
  },
  {
    icon: Calculator,
    title: "Estimasi Biaya",
    description: "Perhitungan detail biaya konstruksi, RAB, dan analisis investasi properti yang akurat.",
    features: ["RAB Detail", "Cost Planning", "Investment Analysis"]
  },
  {
    icon: Users,
    title: "Manajemen Proyek",
    description: "Pengelolaan proyek konstruksi end-to-end dengan timeline yang terstruktur dan efisien.",
    features: ["Project Planning", "Quality Control", "Timeline Management"]
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
          Layanan <span class="text-blue-600">Profesional</span> Kami
        </h2>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
          Solusi lengkap untuk semua kebutuhan konsultasi tanah dan bangunan Anda
          dengan tim ahli berpengalaman lebih dari 15 tahun.
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
          Tidak menemukan layanan yang Anda cari?
        </p>
        <button class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors cursor-pointer" @click="goToContact">
          Konsultasi Custom
        </button>
      </div>
    </div>
  </section>
</template>


