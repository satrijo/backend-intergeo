<script setup>
import Footer from '@/components/Footer.vue';
import NavMenu from '@/components/NavMenu.vue';
import { router, Head } from '@inertiajs/vue3';
import { ArrowLeft, Calendar, ChevronLeft, Clock, User } from 'lucide-vue-next';
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

const props = defineProps({
    article: {
        type: Object,
        required: true,
    },
});

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};

const readTime = computed(() => {
    const wordCount = props.article.body.split(' ').length;
    return Math.ceil(wordCount / 200) + ' min';
});

const goBack = () => {
    router.visit('/blog');
};

// Fungsi untuk mengambil excerpt dari body artikel
const getExcerpt = (html, maxLength = 150) => {
  const tmp = document.createElement('div');
  tmp.innerHTML = html;
  const text = tmp.textContent || tmp.innerText || '';
  return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
};

const canonicalUrl = `https://surveyseismikgpr.com/blog/${props.article.slug}`;
const ogImage = props.article.image || 'https://surveyseismikgpr.com/images/og-image.jpg';
const description = getExcerpt(props.article.body);
</script>

<template>
    <Head>
        <title>{{ props.article.title }} - Blog Survey Geofisika | PT. Intergeo Mitigasi</title>
        <meta name="description" :content="description" />
        <link rel="canonical" :href="canonicalUrl" />
        <meta name="keywords" content="survey seismik, GPR, georadar, survey geofisika, ground penetrating radar, deteksi utilitas, PT. Intergeo Mitigasi, artikel survey, blog geofisika" />
        <meta property="og:title" :content="props.article.title + ' - Blog Survey Geofisika | PT. Intergeo Mitigasi'" />
        <meta property="og:description" :content="description" />
        <meta property="og:image" :content="ogImage" />
        <meta property="og:url" :content="canonicalUrl" />
        <meta property="og:type" content="article" />
        <meta property="og:site_name" content="PT. Intergeo Mitigasi" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" :content="props.article.title + ' - Blog Survey Geofisika | PT. Intergeo Mitigasi'" />
        <meta name="twitter:description" :content="description" />
        <meta name="twitter:image" :content="ogImage" />
    </Head>
    <NavMenu />
    <div class="min-h-screen bg-gray-50">
        <!-- Breadcrumb -->
        <nav class="bg-white py-4 shadow-sm">
            <div class="mx-auto max-w-4xl px-6">
                <div class="flex items-center space-x-2 text-sm text-gray-600">
                    <button @click="() => router.visit('/')" class="hover:text-blue-600 cursor-pointer">Beranda</button>
                    <ChevronLeft class="h-4 w-4 flex-shrink-0 rotate-180" />
                    <button @click="goBack" class="hover:text-blue-600 cursor-pointer">Blog Survey Geofisika</button>
                    <ChevronLeft class="h-4 w-4 flex-shrink-0 rotate-180" />
                    <span class="font-medium text-blue-600">Artikel</span>
                </div>
            </div>
        </nav>

        <!-- Article Header -->
        <div class="bg-white">
            <div class="mx-auto max-w-4xl px-6 py-8">
                <button @click="goBack" class="mb-6 flex items-center text-blue-600 hover:text-blue-700">
                    <ArrowLeft class="mr-2 h-4 w-4" />
                    Kembali ke Blog Survey Geofisika
                </button>

                <div class="mb-6">
                    <div class="mb-4 flex flex-wrap gap-2">
                        <span
                            v-for="category in article.categories"
                            :key="category.id"
                            class="rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-800"
                        >
                            {{ category.name }}
                        </span>
                    </div>

                    <h1 class="mb-4 text-4xl font-bold text-gray-900 md:text-5xl">
                        {{ article.title }}
                    </h1>

                    <div class="flex flex-wrap items-center gap-6 text-sm text-gray-600">
                        <div class="flex items-center">
                            <User class="mr-2 h-4 w-4" />
                            {{ article.user?.name || 'Anonymous' }}
                        </div>
                        <div class="flex items-center">
                            <Calendar class="mr-2 h-4 w-4" />
                            {{ formatDate(article.created_at) }}
                        </div>
                        <div class="flex items-center">
                            <Clock class="mr-2 h-4 w-4" />
                            {{ readTime }} membaca
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Article Image -->
        <div v-if="article.image" class="bg-white">
            <div class="mx-auto max-w-4xl px-6 py-8">
                <img :src="article.image" :alt="article.title" class="w-full rounded-lg object-cover" style="max-height: 400px" />
            </div>
        </div>

        <!-- Article Content -->
        <div class="bg-white">
            <div class="mx-auto max-w-4xl px-6 py-2">
                <div class="prose prose-lg max-w-none text-gray-800" v-html="article.body"></div>

                <!-- Article Footer -->
                <div class="mt-12 border-t border-gray-200 pt-8 mb-12">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center text-sm text-gray-600">
                            <span>Ditulis oleh {{ article.user?.name || 'Anonymous' }}</span>
                        </div>
                        <button @click="goBack" class="flex items-center text-blue-600 hover:text-blue-700">
                            <ChevronLeft class="mr-1 h-4 w-4" />
                            Kembali ke Blog
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <section class="py-16 bg-blue-900 text-white">
        <div class="max-w-4xl mx-auto px-6 text-center space-y-6">
            <h2 class="text-3xl font-bold">
                Butuh Survey <span class="text-yellow-400">Seismik atau GPR?</span>
            </h2>
            <p class="text-xl text-blue-100">
                Konsultasikan kebutuhan survey geofisika Anda dengan tim ahli kami. Dapatkan solusi terbaik untuk proyek Anda.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button
                    @click="() => router.visit('/contact')"
                    class="bg-yellow-400 text-blue-900 hover:bg-yellow-300 px-8 py-3 rounded-md text-lg font-semibold transition-colors cursor-pointer"
                >
                    Konsultasi Survey Gratis
                </button>
                <button
                    @click="() => router.visit('/portfolio')"
                    class="border-white text-white hover:bg-white hover:text-blue-900 px-8 py-3 rounded-md text-lg font-semibold transition-colors border cursor-pointer"
                >
                    Lihat Portfolio Kami
                </button>
            </div>
        </div>
    </section>
    <Footer />
</template>
