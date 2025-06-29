<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
// Jika menggunakan Vue Router, pastikan sudah terkonfigurasi
// import { useRouter } from 'vue-router';
import { ArrowLeft, HelpCircle, Phone, Mail, ChevronDown } from 'lucide-vue-next';
import NavMenuSimple from "@/components/NavMenuSimple.vue";
import Footer from "@/components/Footer.vue";

// const router = useRouter(); // Aktifkan jika menggunakan navigasi router

const faqData = ref([
  {
    question: "Apa saja layanan survey yang ditawarkan Intergeo Mitigasi?",
    answer: "Kami menyediakan layanan survey geofisika lengkap meliputi survey seismik, Ground Penetrating Radar (GPR), survey georadar, survey dan pemetaan, studi kelayakan tambang, analisis geoteknik, interpretasi data survey, dan konsultasi survey untuk berbagai kebutuhan proyek."
  },
  {
    question: "Berapa lama proses survey seismik dan GPR?",
    answer: "Proses survey seismik dan GPR biasanya memakan waktu 1-3 hari tergantung pada luas area dan kompleksitas proyek. Untuk analisis data dan laporan lengkap membutuhkan waktu tambahan 3-7 hari kerja."
  },
  {
    question: "Apakah Intergeo Mitigasi melayani survey di seluruh Indonesia?",
    answer: "Ya, kami melayani survey geofisika di seluruh Indonesia. Untuk proyek survey di luar Jabodetabek, kami akan mengirimkan tim ahli survey atau bekerja sama dengan mitra lokal yang terpercaya."
  },
  {
    question: "Bagaimana cara menghitung biaya survey seismik dan GPR?",
    answer: "Biaya survey dihitung berdasarkan jenis survey (seismik, GPR, georadar), luas area, kompleksitas lokasi, dan kedalaman yang dibutuhkan. Kami menyediakan konsultasi survey gratis untuk evaluasi awal dan memberikan penawaran yang transparan."
  },
  {
    question: "Apakah ada garansi untuk hasil survey yang diberikan?",
    answer: "Ya, kami memberikan garansi untuk semua layanan survey sesuai dengan perjanjian kontrak. Jika terjadi kesalahan dalam interpretasi data survey, kami akan melakukan survey ulang tanpa biaya tambahan."
  },
  {
    question: "Dokumen apa saja yang perlu disiapkan untuk survey?",
    answer: "Dokumen yang diperlukan meliputi peta lokasi survey, foto area survey, informasi kedalaman yang dibutuhkan, dan dokumen pendukung lainnya. Tim survey kami akan memberikan daftar lengkap dokumen yang dibutuhkan."
  },
  {
    question: "Berapa lama waktu yang dibutuhkan untuk analisis geoteknik?",
    answer: "Analisis geoteknik berdasarkan data survey biasanya membutuhkan waktu 1-2 minggu setelah semua data survey lengkap. Laporan akan mencakup analisis struktur tanah, rekomendasi fondasi, dan studi kelayakan."
  },
  {
    question: "Apakah Intergeo Mitigasi memiliki izin resmi untuk survey geofisika?",
    answer: "Ya, Intergeo Mitigasi memiliki izin resmi dan terdaftar di asosiasi profesi terkait. Tim kami terdiri dari ahli survey geofisika bersertifikat dengan pengalaman bertahun-tahun di bidang survey seismik, GPR, georadar, dan studi kelayakan tambang."
  },
  {
    question: "Apa yang termasuk dalam layanan Survey dan Pemetaan?",
    answer: "Layanan Survey dan Pemetaan meliputi survey topografi, survey batimetri, pemetaan geologi permukaan, dan pembuatan peta digital. Kami menggunakan teknologi GPS dan drone untuk hasil yang akurat dan efisien."
  },
  {
    question: "Bagaimana proses Studi Kelayakan Tambang dilakukan?",
    answer: "Studi Kelayakan Tambang meliputi analisis geologi ekonomi, evaluasi potensi mineral, studi teknis dan ekonomis, serta analisis dampak lingkungan. Proses ini membutuhkan waktu 2-4 minggu tergantung kompleksitas lokasi dan jenis mineral."
  }
]);

const openAccordionIndex = ref(null); // Menyimpan indeks item akordion yang terbuka

const toggleAccordion = (index) => {
  if (openAccordionIndex.value === index) {
    openAccordionIndex.value = null; // Tutup jika sudah terbuka
  } else {
    openAccordionIndex.value = index; // Buka item yang diklik
  }
};

const scrollToContact = () => {
  // Jika halaman kontak terpisah, gunakan router.push
  // router.push({ name: 'contact' });
  // Jika bagian kontak ada di halaman yang sama:
  const contactSection = document.getElementById('contact'); // Pastikan elemen ini ada
  if (contactSection) {
    contactSection.scrollIntoView({ behavior: 'smooth' });
  } else {
    window.location.href = '/contact';
  }
};

// Komponen UI kustom (Button, Card, Accordion, dll.) dari React
// telah diganti dengan elemen HTML standar + Tailwind CSS.
// Jika Anda memiliki versi Vue dari komponen UI tersebut, Anda bisa mengimpor dan menggunakannya.
</script>

<template>
  <Head>
    <title>FAQ Survey Seismik & GPR | PT. Intergeo Mitigasi</title>
    <meta name="description" content="Temukan jawaban atas pertanyaan umum tentang layanan survey seismik, GPR, georadar, dan survey geofisika dari PT. Intergeo Mitigasi. Konsultasi survey gratis tersedia." />
    <link rel="canonical" href="https://surveyseismikgpr.com/faq" />
    <meta name="keywords" content="faq survey seismik, pertanyaan GPR, pertanyaan georadar, survey geofisika, konsultasi survey, PT. Intergeo Mitigasi" />
    <meta property="og:title" content="FAQ Survey Seismik & GPR | PT. Intergeo Mitigasi" />
    <meta property="og:description" content="Temukan jawaban atas pertanyaan umum tentang layanan survey seismik, GPR, georadar, dan survey geofisika dari PT. Intergeo Mitigasi." />
    <meta property="og:image" content="https://surveyseismikgpr.com/images/og-image.jpg" />
    <meta property="og:url" content="https://surveyseismikgpr.com/faq" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="PT. Intergeo Mitigasi" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="FAQ Survey Seismik & GPR | PT. Intergeo Mitigasi" />
    <meta name="twitter:description" content="Temukan jawaban atas pertanyaan umum tentang layanan survey seismik, GPR, georadar, dan survey geofisika dari PT. Intergeo Mitigasi." />
    <meta name="twitter:image" content="https://surveyseismikgpr.com/images/og-image.jpg" />
  </Head>
  <div class="min-h-screen flex flex-col light-mode">
    <NavMenuSimple />

    <section class="py-20 bg-gray-50 flex-grow">
      <div class="max-w-4xl mx-auto px-6">
        <div class="text-center mb-16">
          <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-6">
            <HelpCircle class="w-8 h-8 text-blue-600" />
          </div>
          <h1 class="text-4xl font-bold text-gray-900 mb-4">
            FAQ Survey Geofisika
          </h1>
          <p class="text-xl text-gray-600 max-w-2xl mx-auto">
            Temukan jawaban untuk pertanyaan yang sering diajukan tentang layanan survey seismik, GPR, dan georadar kami
          </p>
        </div>

        <div class="mb-12 bg-white shadow-md rounded-lg border">
          <div class="p-6 md:p-8">
            <div class="space-y-1">
              <div
                  v-for="(item, index) in faqData"
                  :key="index"
                  class="border-b border-gray-200 last:border-b-0"
              >
                <button
                    @click="toggleAccordion(index)"
                    class="flex justify-between items-center w-full text-left text-lg font-semibold text-gray-900 hover:text-blue-600 py-5 md:py-6 focus:outline-none transition-colors"
                >
                  <span>{{ item.question }}</span>
                  <ChevronDown
                      class="w-5 h-5 transform transition-transform duration-300 flex-shrink-0"
                      :class="{ 'rotate-180': openAccordionIndex === index }"
                  />
                </button>
                <div
                    v-show="openAccordionIndex === index"
                    class="text-gray-600 pb-5 md:pb-6 leading-relaxed animate-fade-in-down"
                >
                  {{ item.answer }}
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-blue-900 text-white rounded-lg shadow-xl">
          <div class="p-6 md:p-8 text-center">
            <h2 class="text-2xl font-semibold mb-2">Masih Ada Pertanyaan Survey?</h2>
            <p class="text-blue-100 mb-6">
              Tim ahli survey geofisika kami siap membantu Anda dengan pertanyaan spesifik tentang proyek survey Anda
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-6">
              <div class="flex items-center space-x-2">
                <Phone class="w-5 h-5 text-yellow-400 flex-shrink-0" />
                <a href="tel:+6285886283658" class="hover:text-yellow-300 transition-colors">+62 858-8628-3668</a>
              </div>
              <div class="flex items-center space-x-2">
                <Mail class="w-5 h-5 text-yellow-400 flex-shrink-0" />
                <a href="mailto:intergeo.mitigasi@gmail.com" class="hover:text-yellow-300 transition-colors">intergeo.mitigasi@gmail.com</a>
              </div>
            </div>
            <button
                @click="scrollToContact"
                class="bg-yellow-400 text-blue-900 hover:bg-yellow-300 px-8 py-3 rounded-md text-lg font-semibold transition-colors cursor-pointer"
            >
              Konsultasi Survey Gratis
            </button>
          </div>
        </div>
      </div>
    </section>
    <Footer />
  </div>
</template>

<style scoped>
/* Untuk transisi expand/collapse akordion yang lebih halus (opsional) */
/* Anda mungkin memerlukan pendekatan yang lebih canggih dengan <transition> Vue jika
   ingin animasi tinggi yang dinamis */
.animate-fade-in-down {
  animation: fadeInDown 0.3s ease-out;
}

@keyframes fadeInDown {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Force light mode for homepage */
.light-mode {
  color-scheme: light;
}
</style>