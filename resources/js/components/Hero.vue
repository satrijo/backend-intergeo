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
        <div class="space-y-8 animate-fade-in">
          <h1 class="text-5xl lg:text-7xl font-bold leading-tight">
            Konsultan <span class="text-yellow-400">Tanah & Bangunan</span> Terpercaya
          </h1>
          <p class="text-xl text-blue-100 leading-relaxed">
            Wujudkan impian properti Anda dengan layanan konsultasi profesional.
            Kami membantu dalam perencanaan, pengembangan, dan pengelolaan properti
            dengan standar terbaik.
          </p>
          <div class="flex flex-col sm:flex-row gap-4">
            <button
                @click="scrollToContact"
                class="bg-yellow-400 text-blue-900 hover:bg-yellow-300 text-lg px-8 py-3 rounded-md font-semibold transition-colors cursor-pointer"
            >
              Konsultasi Gratis
            </button>
            <button
                @click="scrollToPortfolio"
                class="border-white text-white hover:bg-white hover:text-blue-900 text-lg px-8 py-3 rounded-md font-semibold transition-colors border cursor-pointer"
            >
              Lihat Portfolio
            </button>
          </div>
        </div>

        <div class="relative animate-scale-in mt-10 lg:mt-0">
          <div class="relative z-10 bg-white/10 backdrop-blur-sm rounded-2xl p-6 md:p-8 border border-white/20">
            <img
                src="https://images.unsplash.com/photo-1487958449943-2429e8be8625?w=600&h=400&fit=crop"
                alt="Modern Building"
                class="w-full h-72 md:h-80 object-cover rounded-xl"
            />
            <div class="mt-6 space-y-4">
              <div class="flex items-center space-x-3 text-sm">
                <MapPin class="w-5 h-5 text-yellow-400 flex-shrink-0" />
                <span>Melayani seluruh Indonesia</span>
              </div>
              <div class="flex items-center space-x-3 text-sm">
                <Phone class="w-5 h-5 text-yellow-400 flex-shrink-0" />
                <a href="tel:+6281234567890" class="hover:text-yellow-300 transition-colors">+62 812-3456-7890</a>
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