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
    question: "Apa saja layanan yang ditawarkan Intergeo Mitigasi?",
    answer: "Kami menyediakan layanan konsultasi lengkap meliputi perencanaan properti, analisis kelayakan investasi, pengurusan sertifikat tanah, desain bangunan, pengawasan konstruksi, dan manajemen proyek properti."
  },
  {
    question: "Berapa lama proses pengurusan sertifikat tanah?",
    answer: "Proses pengurusan sertifikat tanah biasanya memakan waktu 3-6 bulan tergantung pada kompleksitas kasus dan kelengkapan dokumen. Kami akan membantu mempercepat proses dengan memastikan semua persyaratan terpenuhi."
  },
  {
    question: "Apakah Intergeo Mitigasi melayani di seluruh Indonesia?",
    answer: "Ya, kami melayani konsultasi di seluruh Indonesia. Untuk proyek di luar Jabodetabek, kami akan mengirimkan tim ahli atau bekerja sama dengan mitra lokal yang terpercaya."
  },
  {
    question: "Bagaimana cara menghitung biaya konsultasi?",
    answer: "Biaya konsultasi dihitung berdasarkan jenis layanan, kompleksitas proyek, dan durasi pengerjaan. Kami menyediakan konsultasi gratis untuk evaluasi awal dan memberikan penawaran yang transparan."
  },
  {
    question: "Apakah ada garansi untuk layanan yang diberikan?",
    answer: "Ya, kami memberikan garansi untuk semua layanan sesuai dengan perjanjian kontrak. Jika terjadi masalah akibat kesalahan dari pihak kami, kami akan bertanggung jawab penuh untuk memperbaikinya."
  },
  {
    question: "Dokumen apa saja yang perlu disiapkan untuk konsultasi?",
    answer: "Dokumen yang diperlukan meliputi surat kepemilikan tanah, IMB (jika ada), peta lokasi, foto properti, dan dokumen pendukung lainnya. Tim kami akan memberikan daftar lengkap dokumen yang dibutuhkan."
  },
  {
    question: "Berapa lama waktu yang dibutuhkan untuk analisis kelayakan investasi?",
    answer: "Analisis kelayakan investasi properti biasanya membutuhkan waktu 1-2 minggu setelah semua data dan dokumen lengkap. Laporan akan mencakup analisis pasar, proyeksi finansial, dan rekomendasi investasi."
  },
  {
    question: "Apakah Intergeo Mitigasi memiliki izin resmi?",
    answer: "Ya, Intergeo Mitigasi memiliki izin resmi dan terdaftar di asosiasi profesi terkait. Tim kami terdiri dari ahli bersertifikat dengan pengalaman bertahun-tahun di bidang properti dan konstruksi."
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
    <title>FAQ Intergeo | Pertanyaan Umum Layanan Konsultan & Survey</title>
    <meta name="description" content="Temukan jawaban atas pertanyaan umum tentang layanan PT. Intergeo Mitigasi: konsultasi, survey, geoteknik, properti, dan lainnya. Hubungi kami untuk info lebih lanjut." />
    <link rel="canonical" href="https://surveyseismikgpr.com/faq" />
    <meta name="keywords" content="faq intergeo, pertanyaan umum, layanan konsultasi, survey, PT. Intergeo Mitigasi" />
    <meta property="og:title" content="FAQ Intergeo | Pertanyaan Umum Layanan Konsultan & Survey" />
    <meta property="og:description" content="Temukan jawaban atas pertanyaan umum tentang layanan PT. Intergeo Mitigasi: konsultasi, survey, geoteknik, properti, dan lainnya." />
    <meta property="og:image" content="https://surveyseismikgpr.com/images/og-image.jpg" />
    <meta property="og:url" content="https://surveyseismikgpr.com/faq" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="PT. Intergeo Mitigasi" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="FAQ Intergeo | Pertanyaan Umum Layanan Konsultan & Survey" />
    <meta name="twitter:description" content="Temukan jawaban atas pertanyaan umum tentang layanan PT. Intergeo Mitigasi: konsultasi, survey, geoteknik, properti, dan lainnya." />
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
            Frequently Asked Questions
          </h1>
          <p class="text-xl text-gray-600 max-w-2xl mx-auto">
            Temukan jawaban untuk pertanyaan yang sering diajukan tentang layanan konsultasi kami
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
            <h2 class="text-2xl font-semibold mb-2">Masih Ada Pertanyaan?</h2>
            <p class="text-blue-100 mb-6">
              Tim ahli kami siap membantu Anda dengan pertanyaan spesifik tentang proyek Anda
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-6">
              <div class="flex items-center space-x-2">
                <Phone class="w-5 h-5 text-yellow-400 flex-shrink-0" />
                <a href="tel:+6281234567890" class="hover:text-yellow-300 transition-colors">+62 812-3456-7890</a>
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
              Hubungi Kami Sekarang
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