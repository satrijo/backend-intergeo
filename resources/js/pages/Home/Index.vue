<script setup>
import { Head } from '@inertiajs/vue3';

import Hero from "@/components/Hero.vue";
import Services from "@/components/Services.vue";
import LatestVideos from "@/components/LatestVideos.vue";
import About from "@/components/About.vue";
import Portfolio from "@/components/Portfolio.vue";
import Contact from "@/components/Contact.vue";
import Footer from "@/components/Footer.vue";

import { onMounted, onUnmounted, ref, computed } from 'vue';


const wasDarkMode = ref(false);

onMounted(() => {
    const htmlElement = document.documentElement;
    // Cek apakah dark mode aktif saat komponen dimuat
    if (htmlElement.classList.contains('dark')) {
        wasDarkMode.value = true;
        // Hapus class 'dark' untuk memaksa light mode
        htmlElement.classList.remove('dark');
    }
});

onUnmounted(() => {
    // Saat meninggalkan halaman, kembalikan class 'dark' jika sebelumnya aktif
    if (wasDarkMode.value) {
        document.documentElement.classList.add('dark');
    }
});

</script>

<template>
  <Head>
    <title>PT. Intergeo Mitigasi - Jasa Survey Seismik, GPR, Georadar & Studi Kelayakan Tambang</title>
    <meta name="description" content="Spesialis survey seismik, GPR, georadar, survey dan pemetaan, serta studi kelayakan tambang. Tim ahli dengan teknologi canggih untuk hasil akurat dalam deteksi utilitas, analisa struktur tanah, dan studi geoteknik." />
    <link rel="canonical" href="https://surveyseismikgpr.com" />
    <meta name="keywords" content="survey seismik, GPR, georadar, survey pemetaan, studi kelayakan tambang, PT. Intergeo Mitigasi, jasa survey geofisika" />
    <meta property="og:title" content="PT. Intergeo Mitigasi - Jasa Survey Seismik, GPR, Georadar & Studi Kelayakan Tambang" />
    <meta property="og:description" content="Spesialis survey seismik, GPR, georadar, survey dan pemetaan, serta studi kelayakan tambang dengan teknologi canggih." />
    <meta property="og:image" content="https://surveyseismikgpr.com/images/og-image.jpg" />
    <meta property="og:url" content="https://surveyseismikgpr.com" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="PT. Intergeo Mitigasi" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="PT. Intergeo Mitigasi - Jasa Survey Seismik, GPR, Georadar & Studi Kelayakan Tambang" />
    <meta name="twitter:description" content="Spesialis survey seismik, GPR, georadar, survey dan pemetaan, serta studi kelayakan tambang dengan teknologi canggih." />
    <meta name="twitter:image" content="https://surveyseismikgpr.com/images/og-image.jpg" />
  </Head>
  <Hero />
  <Services />
  <LatestVideos />
  <About />
  <Portfolio />
  <Contact />
  <Footer />
</template>

<style scoped>

</style>