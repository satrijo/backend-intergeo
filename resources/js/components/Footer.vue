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
                Spesialis survey seismik, GPR, dan georadar terpercaya dengan pengalaman lebih dari 15 tahun.
                Membantu proyek survey geofisika Anda dengan teknologi canggih dan hasil akurat.
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
            <h4 class="text-xl font-semibold mb-6">Layanan Survey</h4>
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
            <h4 class="text-xl font-semibold mb-6">Kontak Survey</h4>
            <div class="space-y-4 text-gray-300">
              <div class="flex items-start space-x-3">
                <MapPin class="w-5 h-5 text-yellow-400 flex-shrink-0 mt-1" />
                <div>
                  <p>Jl. DR. Ide Anak Agung Gde Agung</p>
                  <p>Kota Jakarta Selatan, DKI Jakarta</p>
                  <p>Indonesia</p>
                </div>
              </div>
              <div class="flex items-center space-x-3">
                <Phone class="w-5 h-5 text-yellow-400" />
                <a href="tel:+6285886283658" class="hover:text-white transition-colors">+62 858-8628-3658</a>
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
              © {{ currentYear }} PT. Intergeo Mitigasi - Spesialis Survey Seismik & GPR. Semua hak cipta dilindungi.
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
    { name: "Survey Seismik", link: "#services" },
    { name: "Ground Penetrating Radar (GPR)", link: "#services" },
    { name: "Survey Georadar", link: "#services" },
    { name: "Survey dan Pemetaan", link: "#services" },
    { name: "Studi Kelayakan Tambang", link: "#services" },
    { name: "Survey Infrastruktur & Jembatan", link: "#services" },
    { name: "Analisis Geoteknik", link: "#services" },
    { name: "Interpretasi Data Survey", link: "#services" },
    { name: "Konsultasi Survey", link: "#services" },
  ]);
  
  const quickLinks = shallowRef([
    { name: "Tentang Kami", link: "#about" },
    { name: "Portfolio Survey", link: "#portfolio" }, // Ganti dengan ID atau URL yang sesuai
    { name: "Testimoni Klien", link: "#testimonials" },
    { name: "Blog Survey", link: "#blog" }, // Ganti dengan ID atau URL yang sesuai
    { name: "Karir Survey", link: "#career" }, // Ganti dengan ID atau URL yang sesuai
    { name: "FAQ Survey", link: "#faq" }, // Ganti dengan ID atau URL yang sesuai
  ]);
  
  const currentYear = computed(() => new Date().getFullYear());
  </script>
  
  <style scoped>
  /* Gaya spesifik komponen bisa ditambahkan di sini jika perlu */
  </style>