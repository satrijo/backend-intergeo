<script setup>
import { computed, ref, onMounted } from 'vue';
import { router, Head } from '@inertiajs/vue3';
// Jika menggunakan Vue Router, pastikan sudah terkonfigurasi
// import { useRouter } from 'vue-router';
import Footer from '@/components/Footer.vue';
import NavMenu from '@/components/NavMenu.vue';
import { Calendar, ChevronRight, Clock, User, Settings } from 'lucide-vue-next';

// Komponen UI kustom (Button, Card, Badge) dari React perlu diimplementasikan
// sebagai komponen Vue atau diganti dengan elemen HTML standar + Tailwind.
// Contoh ini menggunakan elemen HTML standar.

// const router = useRouter(); // Aktifkan jika menggunakan navigasi router

const blogPosts = ref([]);
const categories = ref(['Semua']);
const selectedCategory = ref('Semua');
const currentPage = ref(1);
const postsPerPage = 6;
const loading = ref(true);
const error = ref(null);

// Function to strip HTML tags from text
const stripHtml = (html) => {
    const tmp = document.createElement('div');
    tmp.innerHTML = html;
    return tmp.textContent || tmp.innerText || '';
};

// Fetch articles from API
const fetchArticles = async () => {
    try {
        loading.value = true;
        error.value = null;
        
        const response = await fetch('/api/v1/articles');
        if (!response.ok) {
            throw new Error('Failed to fetch articles');
        }
        
        const data = await response.json();
        blogPosts.value = data.data.map(article => {
            const cleanBody = stripHtml(article.body);
            const excerpt = cleanBody.length > 150 ? cleanBody.substring(0, 150) + '...' : cleanBody;
            
            return {
                id: article.id,
                title: article.title,
                excerpt: excerpt,
                category: article.categories.length > 0 ? article.categories[0].name : 'Uncategorized',
                author: article.user?.name || 'Anonymous',
                date: article.created_at,
                readTime: Math.ceil(cleanBody.split(' ').length / 200) + ' min',
                image: article.image || 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=600&h=400&fit=crop',
                slug: article.slug
            };
        });

        // Extract unique categories
        const uniqueCategories = [...new Set(blogPosts.value.map(post => post.category))];
        categories.value = ['Semua', ...uniqueCategories];
        
    } catch (err) {
        error.value = err.message;
        console.error('Error fetching articles:', err);
    } finally {
        loading.value = false;
    }
};

const filteredPosts = computed(() => {
    if (selectedCategory.value === 'Semua') {
        return blogPosts.value;
    }
    return blogPosts.value.filter((post) => post.category === selectedCategory.value);
});

const totalPages = computed(() => Math.ceil(filteredPosts.value.length / postsPerPage));

const currentPosts = computed(() => {
    const startIndex = (currentPage.value - 1) * postsPerPage;
    return filteredPosts.value.slice(startIndex, startIndex + postsPerPage);
});

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};

const selectCategory = (category) => {
    selectedCategory.value = category;
    currentPage.value = 1; // Reset ke halaman pertama saat filter berubah
};

const changePage = (pageNumber) => {
    if (pageNumber >= 1 && pageNumber <= totalPages.value) {
        currentPage.value = pageNumber;
    }
};

const viewArticle = (article) => {
    // Navigate to article detail page using slug
    router.visit(`/blog/${article.slug}`);
};

const goToDashboard = () => {
    router.visit('/dashboard');
};

// Fetch articles on component mount
onMounted(() => {
    fetchArticles();
});
</script>

<template>
    <Head>
        <title>Blog Survey Seismik & GPR | PT. Intergeo Mitigasi</title>
        <meta name="description" content="Baca artikel terbaru seputar survey seismik, GPR, georadar, dan survey geofisika dari PT. Intergeo Mitigasi. Tips, wawasan, dan berita industri survey terkini." />
        <link rel="canonical" href="https://surveyseismikgpr.com/blog" />
        <meta name="keywords" content="blog survey seismik, artikel GPR, artikel georadar, survey geofisika, PT. Intergeo Mitigasi, blog intergeo" />
        <meta property="og:title" content="Blog Survey Seismik & GPR | PT. Intergeo Mitigasi" />
        <meta property="og:description" content="Baca artikel terbaru seputar survey seismik, GPR, georadar, dan survey geofisika dari PT. Intergeo Mitigasi." />
        <meta property="og:image" content="https://surveyseismikgpr.com/images/og-image.jpg" />
        <meta property="og:url" content="https://surveyseismikgpr.com/blog" />
        <meta property="og:type" content="website" />
        <meta property="og:site_name" content="PT. Intergeo Mitigasi" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="Blog Survey Seismik & GPR | PT. Intergeo Mitigasi" />
        <meta name="twitter:description" content="Baca artikel terbaru seputar survey seismik, GPR, georadar, dan survey geofisika dari PT. Intergeo Mitigasi." />
        <meta name="twitter:image" content="https://surveyseismikgpr.com/images/og-image.jpg" />
    </Head>
    <NavMenu />
    <div class="min-h-screen bg-gray-50 light-mode">
        <section class="bg-gradient-to-br from-blue-900 via-blue-800 to-blue-700 py-20 text-white">
            <div class="mx-auto max-w-7xl px-6">
                <div class="text-center">
                    <h1 class="mb-6 text-5xl font-bold">Blog <span class="text-yellow-400">Survey Geofisika</span></h1>
                    <p class="mx-auto max-w-3xl text-xl text-blue-100">
                        Dapatkan wawasan terbaru seputar survey seismik, GPR, georadar, dan teknologi survey geofisika dari para ahli di bidangnya
                    </p>
                    <!-- Dashboard Link for Authenticated Users -->
                    <div v-if="$page.props.auth?.user" class="mt-8">
                        <button @click="goToDashboard" class="inline-flex items-center rounded-md bg-white/10 px-4 py-2 text-sm font-medium text-white backdrop-blur-sm transition-colors hover:bg-white/20">
                            <Settings class="mr-2 h-4 w-4" />
                            Kelola Artikel
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <nav class="bg-white py-4 shadow-sm">
            <div class="mx-auto max-w-7xl px-6">
                <div class="flex items-center space-x-2 text-sm text-gray-600">
                    <button @click="() => router.visit('/')" class="hover:text-blue-600 cursor-pointer">Beranda</button>
                    <ChevronRight class="h-4 w-4 flex-shrink-0" />
                    <span class="font-medium text-blue-600">Blog Survey Geofisika</span>
                </div>
            </div>
        </nav>

        <div class="mx-auto max-w-7xl px-6 py-12">
            <!-- Loading State -->
            <div v-if="loading" class="text-center py-12">
                <div class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-blue-600 border-r-transparent"></div>
                <p class="mt-4 text-gray-600">Memuat artikel...</p>
            </div>

            <!-- Error State -->
            <div v-else-if="error" class="text-center py-12">
                <p class="text-red-600 mb-4">{{ error }}</p>
                <button @click="fetchArticles" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Coba Lagi
                </button>
            </div>

            <!-- Content -->
            <div v-else>
                <div class="mb-8">
                    <h2 class="mb-4 text-2xl font-bold text-gray-900">Kategori</h2>
                    <div class="flex flex-wrap gap-2">
                        <button
                            v-for="category in categories"
                            :key="category"
                            @click="selectCategory(category)"
                            :class="[
                                'rounded-md px-4 py-2 text-sm font-medium transition-colors',
                                selectedCategory === category
                                    ? 'bg-blue-600 text-white hover:bg-blue-700'
                                    : 'border border-gray-300 bg-white text-gray-700 hover:bg-gray-100',
                            ]"
                        >
                            {{ category }}
                        </button>
                    </div>
                </div>

                <!-- No Articles Message -->
                <div v-if="currentPosts.length === 0" class="text-center py-12">
                    <p class="text-gray-600 text-lg">Belum ada artikel yang dipublikasikan.</p>
                    <p class="text-gray-500 mt-2">Silakan kembali lagi nanti.</p>
                </div>

                <!-- Articles Grid -->
                <div v-else class="mb-12 grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="post in currentPosts"
                        :key="post.id"
                        class="flex flex-col overflow-hidden rounded-lg border bg-white transition-shadow hover:shadow-lg"
                    >
                        <div class="aspect-video overflow-hidden">
                            <img
                                :src="post.image"
                                :alt="post.title"
                                class="h-full w-full object-cover transition-transform duration-300 hover:scale-105"
                            />
                        </div>
                        <div class="flex flex-grow flex-col p-6">
                            <div class="mb-2 flex items-center justify-between">
                                <span class="rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800">{{ post.category }}</span>
                                <div class="flex items-center text-sm text-gray-500">
                                    <Clock class="mr-1 h-4 w-4 flex-shrink-0" />
                                    {{ post.readTime }}
                                </div>
                            </div>
                            <h3 class="mb-2 text-xl font-bold text-gray-900 transition-colors hover:text-blue-600 cursor-pointer" @click="viewArticle(post)">
                                {{ post.title }}
                            </h3>
                            <p class="mb-4 line-clamp-3 flex-grow text-sm leading-relaxed text-gray-600">
                                {{ post.excerpt }}
                            </p>
                            <div class="mt-auto flex items-center justify-between border-t border-gray-100 pt-4 text-sm text-gray-500">
                                <div class="flex items-center">
                                    <User class="mr-1 h-4 w-4 flex-shrink-0" />
                                    {{ post.author }}
                                </div>
                                <div class="flex items-center">
                                    <Calendar class="mr-1 h-4 w-4 flex-shrink-0" />
                                    {{ formatDate(post.date) }}
                                </div>
                            </div>
                            <button @click="viewArticle(post)" class="mt-4 w-full rounded-md bg-blue-600 px-4 py-2 font-semibold text-white transition-colors hover:bg-blue-700">
                                Baca Selengkapnya
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="totalPages > 1" class="flex justify-center space-x-2">
                    <button
                        @click="changePage(currentPage - 1)"
                        :disabled="currentPage === 1"
                        class="rounded-md border border-gray-300 bg-white px-4 py-2 font-medium text-gray-700 transition-colors hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        Sebelumnya
                    </button>

                    <button
                        v-for="pageNumber in totalPages"
                        :key="pageNumber"
                        @click="changePage(pageNumber)"
                        :class="[
                            'rounded-md px-4 py-2 font-medium transition-colors',
                            currentPage === pageNumber
                                ? 'bg-blue-600 text-white hover:bg-blue-700'
                                : 'border border-gray-300 bg-white text-gray-700 hover:bg-gray-100',
                        ]"
                    >
                        {{ pageNumber }}
                    </button>

                    <button
                        @click="changePage(currentPage + 1)"
                        :disabled="currentPage === totalPages"
                        class="rounded-md border border-gray-300 bg-white px-4 py-2 font-medium text-gray-700 transition-colors hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        Selanjutnya
                    </button>
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

<style scoped>
/* Gaya spesifik komponen bisa ditambahkan di sini jika perlu */
.aspect-video {
    aspect-ratio: 16 / 9;
}

.line-clamp-3 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 3;
}

/* Force light mode for homepage */
.light-mode {
    color-scheme: light;
}
</style>
