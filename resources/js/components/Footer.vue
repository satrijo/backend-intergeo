<template>
    <footer class="bg-gray-900 text-white">
      <div class="max-w-7xl mx-auto px-6 py-16">
        <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-8">
          <div class="space-y-6">
            <div>
              <h3 class="text-2xl font-bold mb-4">
                <span class="text-yellow-400">Intergeo</span> Mitigasi
              </h3>
              <p class="text-gray-300 leading-relaxed">
                Konsultan tanah dan bangunan terpercaya dengan pengalaman lebih dari 15 tahun.
                Membantu mewujudkan proyek impian Anda dengan standar profesional terbaik.
              </p>
            </div>
            <div class="flex space-x-4">
              <a href="#" aria-label="Facebook" class="text-gray-400 hover:text-white transition-colors">
                <Facebook class="w-6 h-6" />
              </a>
              <a href="#" aria-label="Instagram" class="text-gray-400 hover:text-white transition-colors">
                <Instagram class="w-6 h-6" />
              </a>
              <a href="#" aria-label="LinkedIn" class="text-gray-400 hover:text-white transition-colors">
                <Linkedin class="w-6 h-6" />
              </a>
              <a href="#" aria-label="Twitter" class="text-gray-400 hover:text-white transition-colors">
                <Twitter class="w-6 h-6" />
              </a>
            </div>
          </div>
  
          <div>
            <h4 class="text-xl font-semibold mb-6">Layanan Kami</h4>
            <ul class="space-y-3 text-gray-300">
              <li v-for="service in services" :key="service.name">
                <a :href="service.link" class="hover:text-white transition-colors">{{ service.name }}</a>
              </li>
            </ul>
          </div>
  
          <div>
            <h4 class="text-xl font-semibold mb-6">Tautan Cepat</h4>
            <ul class="space-y-3 text-gray-300">
              <li v-for="link in quickLinks" :key="link.name">
                <a :href="link.link" class="hover:text-white transition-colors">{{ link.name }}</a>
              </li>
            </ul>
          </div>
  
          <div>
            <h4 class="text-xl font-semibold mb-6">Kontak</h4>
            <div class="space-y-4 text-gray-300">
              <div class="flex items-start space-x-3">
                <MapPin class="w-5 h-5 text-yellow-400 flex-shrink-0 mt-1" />
                <div>
                  <p>Jl. Sudirman No. 123</p>
                  <p>Jakarta Pusat 10220</p>
                  <p>Indonesia</p>
                </div>
              </div>
              <div class="flex items-center space-x-3">
                <Phone class="w-5 h-5 text-yellow-400" />
                <a href="tel:+622155550123" class="hover:text-white transition-colors">+62 21 5555-0123</a>
              </div>
              <div class="flex items-center space-x-3">
                <Mail class="w-5 h-5 text-yellow-400" />
                <a href="mailto:intergeo.mitigasi@gmail.com" class="hover:text-white transition-colors">intergeo.mitigasi@gmail.com</a>
              </div>
            </div>
          </div>
        </div>
  
        <div class="border-t border-gray-800 mt-12 pt-8">
          <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
            <p class="text-gray-400 text-sm">
              © {{ currentYear }} Intergeo Mitigasi. Semua hak cipta dilindungi.
            </p>
            <div class="flex space-x-6 text-sm text-gray-400">
              <a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a>
              <a href="#" class="hover:text-white transition-colors">Syarat & Ketentuan</a>
              <a href="#" class="hover:text-white transition-colors">Sitemap</a>
            </div>
          </div>
        </div>
      </div>
    </footer>
  </template>
  
  <script setup>
  import { shallowRef, computed } from 'vue';
  import { MapPin, Phone, Mail, Facebook, Instagram, Linkedin, Twitter } from 'lucide-vue-next';
  
  const services = shallowRef([
    { name: "Konsultasi Pembangunan", link: "#services" },
    { name: "Survei & Pemetaan", link: "#services" },
    { name: "Perencanaan Properti", link: "#services" },
    { name: "Perizinan & Legal", link: "#services" },
    { name: "Estimasi Biaya", link: "#services" },
    { name: "Manajemen Proyek", link: "#services" },
  ]);
  
  const quickLinks = shallowRef([
    { name: "Tentang Kami", link: "#about" },
    { name: "Portfolio", link: "#portfolio" }, // Ganti dengan ID atau URL yang sesuai
    { name: "Testimoni", link: "#testimonials" },
    { name: "Blog", link: "#blog" }, // Ganti dengan ID atau URL yang sesuai
    { name: "Karir", link: "#career" }, // Ganti dengan ID atau URL yang sesuai
    { name: "FAQ", link: "#faq" }, // Ganti dengan ID atau URL yang sesuai
  ]);
  
  const currentYear = computed(() => new Date().getFullYear());
  </script>
  
  <style scoped>
  /* Gaya spesifik komponen bisa ditambahkan di sini jika perlu */
  </style>