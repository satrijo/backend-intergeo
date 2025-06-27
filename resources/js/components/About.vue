<script setup>
import { shallowRef } from 'vue';
import { CheckCircle, Award, Users, Calendar } from 'lucide-vue-next'; // Pastikan menggunakan lucide-vue-next

const stats = shallowRef([
  { icon: Users, number: "30+", label: "Klien Survey" },
  { icon: CheckCircle, number: "50+", label: "Proyek Survey Selesai" },
  { icon: Award, number: "7+", label: "Tahun Pengalaman" },
  { icon: Calendar, number: "24/7", label: "Layanan Support" }
]);

const featuresList = shallowRef([
  "Tim ahli survey geofisika bersertifikat",
  "Teknologi survey seismik & GPR terdepan",
  "Konsultasi survey gratis untuk evaluasi awal",
  "Hasil survey akurat dengan garansi 100%",
  "Dukungan teknis after-survey terbaik"
]);

const goToContact = () => {
  window.location.href = '/contact';
};
</script>

<template>
  <section id="about" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6">
      <div class="grid lg:grid-cols-2 gap-16 items-center">
        <div class="space-y-8">
          <div>
            <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
              Mengapa Memilih <span class="text-blue-600">Intergeo Mitigasi?</span>
            </h2>
            <p class="text-xl text-gray-600 leading-relaxed mb-8">
              Kami adalah spesialis survey seismik, GPR, georadar, survey dan pemetaan, studi kelayakan tambang, serta survey infrastruktur dan jembatan yang telah membantu
              ratusan klien dengan proyek survey geofisika mereka. Dengan keahlian mendalam
              di bidang survey bawah permukaan dan pemetaan, kami memberikan solusi terbaik untuk deteksi utilitas,
              analisis struktur tanah, studi geoteknik, evaluasi kelayakan tambang, dan inspeksi infrastruktur.
            </p>
          </div>

          <div class="space-y-4">
            <div
                v-for="(feature, index) in featuresList"
                :key="index"
                class="flex items-center space-x-3"
            >
              <CheckCircle class="w-6 h-6 text-green-500 flex-shrink-0" />
              <span class="text-gray-700 text-lg">{{ feature }}</span>
            </div>
          </div>

          <button
              class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg text-lg font-semibold cursor-pointer"
              @click="goToContact"
          >
            Konsultasi Survey Gratis
          </button>
        </div>

        <div class="relative">
          <div class="relative z-10">
            <img
                src="/images/georadar.jpg"
                alt="Tim Survey Geofisika Intergeo Mitigasi"
                class="w-full h-96 object-cover rounded-2xl shadow-2xl"
            />

            <div class="absolute -bottom-8 -left-8 bg-white rounded-xl shadow-xl p-6 border">
              <div class="grid grid-cols-2 gap-4">
                <div
                    v-for="(stat, index) in stats.slice(0, 2)"
                    :key="'floating-' + index"
                    class="text-center"
                >
                  <component :is="stat.icon" class="w-8 h-8 text-blue-600 mx-auto mb-2" />
                  <div class="text-2xl font-bold text-gray-900">{{ stat.number }}</div>
                  <div class="text-sm text-gray-600">{{ stat.label }}</div>
                </div>
              </div>
            </div>
          </div>

          <div class="absolute top-8 right-8 w-32 h-32 bg-blue-100 rounded-full opacity-50"></div>
          <div class="absolute -top-4 -right-4 w-24 h-24 bg-yellow-200 rounded-full opacity-30"></div>
        </div>
      </div>

      <div class="mt-20 grid grid-cols-2 lg:grid-cols-4 gap-8">
        <div
            v-for="(stat, index) in stats"
            :key="'main-' + index"
            class="text-center group"
        >
          <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-600 transition-colors duration-300">
            <component :is="stat.icon" class="w-8 h-8 text-blue-600 group-hover:text-white transition-colors duration-300" />
          </div>
          <div class="text-3xl font-bold text-gray-900 mb-2">{{ stat.number }}</div>
          <div class="text-gray-600">{{ stat.label }}</div>
        </div>
      </div>
    </div>
  </section>
</template>
