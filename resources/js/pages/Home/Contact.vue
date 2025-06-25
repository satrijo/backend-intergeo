<script setup>
// Jika menggunakan Vue Router, pastikan sudah terkonfigurasi
// import { useRouter } from 'vue-router';
import { Head, router } from '@inertiajs/vue3';
import Contact from '@/components/Contact.vue';
import NavSimple from '@/components/NavMenuSimple.vue';
</script>

<template>
    <Head>
        <title>Kontak Survey Seismik & GPR | PT. Intergeo Mitigasi</title>
        <meta name="description" content="Hubungi PT. Intergeo Mitigasi untuk konsultasi survey seismik, GPR, dan georadar gratis. Tim ahli survey geofisika siap membantu proyek Anda." />
        <link rel="canonical" href="https://surveyseismikgpr.com/contact" />
        <meta name="keywords" content="kontak survey seismik, konsultasi GPR, survey georadar, PT. Intergeo Mitigasi, layanan survey geofisika" />
        <meta property="og:title" content="Kontak Survey Seismik & GPR | PT. Intergeo Mitigasi" />
        <meta property="og:description" content="Hubungi PT. Intergeo Mitigasi untuk konsultasi survey seismik, GPR, dan georadar gratis." />
        <meta property="og:image" content="https://surveyseismikgpr.com/images/og-image.jpg" />
        <meta property="og:url" content="https://surveyseismikgpr.com/contact" />
        <meta property="og:type" content="website" />
        <meta property="og:site_name" content="PT. Intergeo Mitigasi" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="Kontak Survey Seismik & GPR | PT. Intergeo Mitigasi" />
        <meta name="twitter:description" content="Hubungi PT. Intergeo Mitigasi untuk konsultasi survey seismik, GPR, dan georadar gratis." />
        <meta name="twitter:image" content="https://surveyseismikgpr.com/images/og-image.jpg" />
    </Head>
    <div class="flex min-h-screen flex-col light-mode">
        <NavSimple />

        <!-- Breadcrumb -->
        <nav class="bg-white py-3 shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex items-center space-x-2 text-sm text-gray-600">
                    <button @click="() => router.visit('/')" class="hover:text-blue-600 cursor-pointer">Beranda</button>
                    <span class="text-gray-400">/</span>
                    <span class="font-medium text-blue-600">Kontak Survey Geofisika</span>
                </div>
            </div>
        </nav>

        <Contact />
    </div>
</template>

<style scoped>
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
