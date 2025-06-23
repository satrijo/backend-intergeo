<script setup>
import { computed, ref } from 'vue';
// Jika menggunakan Vue Router, pastikan sudah terkonfigurasi
// import { useRouter } from 'vue-router';
import Footer from '@/components/Footer.vue';
import NavMenu from '@/components/NavMenu.vue';
import { Calendar, ChevronRight, Clock, User } from 'lucide-vue-next';

// Komponen UI kustom (Button, Card, Badge) dari React perlu diimplementasikan
// sebagai komponen Vue atau diganti dengan elemen HTML standar + Tailwind.
// Contoh ini menggunakan elemen HTML standar.

// const router = useRouter(); // Aktifkan jika menggunakan navigasi router

const blogPosts = ref([
    {
        id: 1,
        title: 'Tips Memilih Lokasi Properti yang Strategis',
        excerpt:
            'Panduan lengkap untuk memilih lokasi properti yang tepat dengan pertimbangan akses, fasilitas, dan potensi investasi jangka panjang.',
        category: 'Tips',
        author: 'Ahmad Rizki',
        date: '2024-01-15',
        readTime: '5 min',
        image: 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=600&h=400&fit=crop',
    },
    {
        id: 2,
        title: 'Panduan Lengkap Sertifikat Tanah di Indonesia',
        excerpt: 'Memahami berbagai jenis sertifikat tanah, proses pengurusan, dan hal-hal penting yang perlu diperhatikan dalam kepemilikan tanah.',
        category: 'Edukasi',
        author: 'Sari Dewi',
        date: '2024-01-12',
        readTime: '8 min',
        image: 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=600&h=400&fit=crop',
    },
    {
        id: 3,
        title: 'Tren Properti 2024: Peluang dan Tantangan',
        excerpt: 'Analisis mendalam tentang tren pasar properti tahun 2024, termasuk prediksi harga dan area yang berpotensi berkembang.',
        category: 'Analisis',
        author: 'Budi Santoso',
        date: '2024-01-10',
        readTime: '6 min',
        image: 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=600&h=400&fit=crop',
    },
    {
        id: 4,
        title: 'Strategi Investasi Properti untuk Pemula',
        excerpt: 'Langkah-langkah praktis memulai investasi properti, dari persiapan dana hingga pemilihan jenis properti yang tepat.',
        category: 'Investasi',
        author: 'Maya Putri',
        date: '2024-01-08',
        readTime: '7 min',
        image: 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=600&h=400&fit=crop',
    },
    {
        id: 5,
        title: 'Mengurus IMB: Proses dan Persyaratan Terbaru',
        excerpt: 'Panduan lengkap mengurus Izin Mendirikan Bangunan (IMB) sesuai regulasi terbaru, termasuk dokumen yang diperlukan.',
        category: 'Regulasi',
        author: 'Doni Pratama',
        date: '2024-01-05',
        readTime: '10 min',
        image: 'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?w=600&h=400&fit=crop',
    },
    {
        id: 6,
        title: 'Desain Rumah Minimalis Modern yang Fungsional',
        excerpt: 'Inspirasi desain rumah minimalis modern yang menggabungkan estetika dan fungsionalitas untuk kehidupan sehari-hari.',
        category: 'Desain',
        author: 'Lisa Maharani',
        date: '2024-01-03',
        readTime: '4 min',
        image: 'https://images.unsplash.com/photo-1523217582562-09d0def993a6?w=600&h=400&fit=crop',
    },
]);

const categories = ref(['Semua', 'Tips', 'Edukasi', 'Analisis', 'Investasi', 'Regulasi', 'Desain']);

const selectedCategory = ref('Semua');
const currentPage = ref(1);
const postsPerPage = 6;

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
</script>

<template>
    <NavMenu />
    <div class="min-h-screen bg-gray-50">
        <section class="bg-gradient-to-br from-blue-900 via-blue-800 to-blue-700 py-20 text-white">
            <div class="mx-auto max-w-7xl px-6">
                <div class="text-center">
                    <h1 class="mb-6 text-5xl font-bold">Blog <span class="text-yellow-400">Intergeo</span></h1>
                    <p class="mx-auto max-w-3xl text-xl text-blue-100">
                        Dapatkan wawasan terbaru seputar properti, investasi, dan konsultasi dari para ahli di bidangnya
                    </p>
                </div>
            </div>
        </section>

        <nav class="bg-white py-4 shadow-sm">
            <div class="mx-auto max-w-7xl px-6">
                <div class="flex items-center space-x-2 text-sm text-gray-600">
                    <router-link to="/" class="hover:text-blue-600">Beranda</router-link>
                    <ChevronRight class="h-4 w-4 flex-shrink-0" />
                    <span class="font-medium text-blue-600">Blog</span>
                </div>
            </div>
        </nav>

        <div class="mx-auto max-w-7xl px-6 py-12">
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

            <div class="mb-12 grid gap-8 md:grid-cols-2 lg:grid-cols-3">
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
                        <h3 class="mb-2 text-xl font-bold text-gray-900 transition-colors hover:text-blue-600">
                            <router-link :to="{ name: 'blog', params: { id: post.id } }">{{ post.title }}</router-link>
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
                        <router-link :to="{ name: 'blog', params: { id: post.id } }" class="mt-4">
                            <button class="w-full rounded-md bg-blue-600 px-4 py-2 font-semibold text-white transition-colors hover:bg-blue-700">
                                Baca Selengkapnya
                            </button>
                        </router-link>
                    </div>
                </div>
            </div>

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
</style>
