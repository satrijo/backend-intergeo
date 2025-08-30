<script setup>
import Navbar from '@/components/NavMenu.vue'; // Sesuaikan path jika perlu
import { MapPin, Phone, Mail } from 'lucide-vue-next';

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
</script>

<template>
  <section class="relative min-h-screen bg-gradient-to-br from-blue-900 via-blue-800 to-blue-700 text-white overflow-hidden">
    <div class="absolute inset-0 opacity-10">
      <div
          class="absolute inset-0 bg-blue-800 bg-[length:50px_50px]"
          :style="{ backgroundImage: 'radial-gradient(circle at 25px 25px, rgba(255,255,255,0.1) 2px, transparent 0)' }"
      ></div>
    </div>

    <Navbar :transparentBg="true" />

    <div class="relative z-10 max-w-7xl mx-auto px-6 py-10 md:py-20">
      <div class="grid lg:grid-cols-2 gap-12 items-center">
        <!-- Mobile: Video first, Desktop: Text first -->
        <div class="order-2 lg:order-1 space-y-8 animate-fade-in">
          <h1 class="text-3xl sm:text-4xl lg:text-6xl font-bold leading-tight">
            Jasa <span class="text-yellow-400">Survey Seismik & GPR</span> Profesional
          </h1>
          <p class="text-xl text-blue-100 leading-relaxed">
            Spesialis survey seismik, Ground Penetrating Radar (GPR), georadar, survey dan pemetaan, studi kelayakan tambang, serta survey infrastruktur dan jembatan. 
            Tim ahli dengan teknologi canggih untuk hasil akurat dalam deteksi utilitas, analisa struktur tanah, dan studi geoteknik.
          </p>
          <div class="flex flex-col sm:flex-row gap-4">
            <button
                @click="scrollToContact"
                class="bg-yellow-400 text-blue-900 hover:bg-yellow-300 text-lg px-8 py-3 rounded-md font-semibold transition-colors cursor-pointer"
            >
              Konsultasi Survey Gratis
            </button>
            <button
                @click="scrollToPortfolio"
                class="border-white text-white hover:bg-white hover:text-blue-900 text-lg px-8 py-3 rounded-md font-semibold transition-colors border cursor-pointer"
            >
              Lihat Proyek Survey
            </button>
          </div>
        </div>

        <!-- Mobile: Video first, Desktop: Video second -->
        <div class="order-1 lg:order-2 relative animate-scale-in mt-10 lg:mt-0">
          <div class="relative z-10 bg-white/10 backdrop-blur-sm rounded-2xl p-6 md:p-8 border border-white/20">
            <!-- YouTube Video Embed -->
            <div class="relative w-full h-72 md:h-80 rounded-xl overflow-hidden">
              <iframe
                src="https://www.youtube.com/embed/3ofooDdGXKI?rel=0&modestbranding=1&showinfo=0"
                title="Survey Seismik dan GPR Equipment"
                class="w-full h-full"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen
              ></iframe>
            </div>
            <div class="mt-6 space-y-4">
              <div class="flex items-center space-x-3 text-sm">
                <MapPin class="w-5 h-5 text-yellow-400 flex-shrink-0" />
                <span>Survey di seluruh Indonesia</span>
              </div>
              <div class="flex items-center space-x-3 text-sm">
                <Phone class="w-5 h-5 text-yellow-400 flex-shrink-0" />
                <a href="tel:+6285886283668" class="hover:text-yellow-300 transition-colors">+62 858-8628-3668</a>
              </div>
              <div class="flex items-center space-x-3 text-sm">
                <Mail class="w-5 h-5 text-yellow-400 flex-shrink-0" />
                <a href="mailto:intergeo.mitigasi@gmail.com" class="hover:text-yellow-300 transition-colors">intergeo.mitigasi@gmail.com</a>
              </div>
            </div>
          </div>

          <div class="absolute -top-6 -right-6 w-24 h-24 bg-yellow-400/20 rounded-full animate-pulse"></div>
          <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-blue-400/20 rounded-full animate-pulse delay-1000"></div>
        </div>
      </div>
    </div>

    <!-- Our Partners -->
    <div class="flex justify-center items-center flex-col mx-auto">
      <h2 class="font-bold">Our Clients</h2>
      <div class="flex flex-wrap justify-center items-center gap-4 bg-white w-max mx-auto p-4 rounded-lg mt-4">
        <img src="https://surveyseismikgpr.com/storage/media/2025/08/6ca595e8-a7b0-4d0d-aff1-72ceafbbbc87.png" alt="Pertamina" class="h-10 w-auto">
        <img src="https://surveyseismikgpr.com/storage/media/2025/08/6a791828-6971-418b-843e-4e57c96eb984.png" alt="WIKA" class="h-10 w-auto"> 
        <img src="https://surveyseismikgpr.com/storage/media/2025/08/87ba5de9-903c-4055-94b7-4470e4f51cf1.png" alt="MM2100" class="h-10 w-auto">
      </div>
    </div>

    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce hidden md:block">
      <div class="w-6 h-10 border-2 border-white rounded-full flex justify-center items-start pt-1">
        <div class="w-1 h-2 bg-white rounded-full animate-scroll-dot"></div>
      </div>
    </div>
  </section>
</template>

<style scoped>
/* Animasi kustom jika diperlukan */
.animate-fade-in {
  animation: fadeIn 1s ease-out;
}
.animate-scale-in {
  animation: scaleIn 0.8s ease-out;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes scaleIn {
  from { opacity: 0; transform: scale(0.9); }
  to { opacity: 1; transform: scale(1); }
}

.animate-scroll-dot {
  animation: scrollDotAnimation 1.5s infinite ease-in-out;
}

@keyframes scrollDotAnimation {
  0%, 100% { transform: translateY(0); opacity: 1; }
  50% { transform: translateY(10px); opacity: 0; }
}
</style>