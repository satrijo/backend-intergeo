<script setup lang="ts">
import Footer from '@/components/Footer.vue';
import NavMenu from '@/components/NavMenu.vue';
import { Head, Link } from '@inertiajs/vue3';

interface ProductCategory {
    id: string;
    name: string;
    color: string;
}

interface Product {
    id: string;
    slug: string;
    name: string;
    summary?: string;
    image_url?: string;
    price_label?: string;
    featured: boolean;
    category?: ProductCategory;
}

defineProps<{
    products: {
        data: Product[];
    };
    categories: ProductCategory[];
}>();
</script>

<template>
    <Head>
        <title>Katalog Produk Survey & Geofisika | PT. Intergeo Mitigasi</title>
        <meta
            name="description"
            content="Temukan katalog produk PT. Intergeo Mitigasi untuk mendukung kebutuhan survey, investigasi bawah permukaan, geofisika, dan pekerjaan lapangan."
        />
        <link rel="canonical" href="https://surveyseismikgpr.com/products" />
        <meta name="keywords" content="katalog produk survey, produk geofisika, alat survey, PT. Intergeo Mitigasi" />
    </Head>

    <NavMenu />

    <div class="min-h-screen bg-gray-50 light-mode">
        <section class="bg-gradient-to-br from-blue-900 via-blue-800 to-blue-700 py-20 text-white">
            <div class="mx-auto max-w-7xl px-6">
                <div class="text-center">
                    <h1 class="mb-6 text-5xl font-bold">
                        Katalog <span class="text-yellow-400">Produk</span>
                    </h1>
                    <p class="mx-auto max-w-3xl text-xl text-blue-100">
                        Temukan berbagai produk pilihan yang kami sediakan untuk mendukung kebutuhan survei,
                        investigasi, dan pekerjaan lapangan dengan standar profesional.
                    </p>
                </div>
            </div>
        </section>

        <nav v-if="categories.length" class="bg-white py-4 shadow-sm">
            <div class="mx-auto max-w-7xl px-6">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-sm font-medium text-gray-600">Kategori:</span>
                    <span
                        v-for="category in categories"
                        :key="category.id"
                        class="rounded-full border px-4 py-2 text-sm font-medium text-gray-700"
                        :style="{ borderColor: category.color }"
                    >
                        {{ category.name }}
                    </span>
                </div>
            </div>
        </nav>

        <section class="py-16">
            <div class="mx-auto max-w-7xl px-6">
                <div class="mb-12 text-center">
                    <h2 class="mb-4 text-3xl font-bold text-gray-900">Pilihan Produk Kami</h2>
                    <p class="mx-auto max-w-2xl text-gray-600">
                        Pilih produk yang sesuai dengan kebutuhan proyek Anda. Untuk detail teknis, konsultasi,
                        atau pemesanan, tim kami siap membantu.
                    </p>
                </div>

                <div v-if="products.data.length" class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                    <Link
                        v-for="product in products.data"
                        :key="product.id"
                        :href="route('products.show', product.slug)"
                        class="group overflow-hidden rounded-lg bg-white shadow-lg transition-all duration-300 hover:-translate-y-2 hover:shadow-xl"
                    >
                        <div class="relative h-56 overflow-hidden bg-gray-100">
                            <img
                                v-if="product.image_url"
                                :src="product.image_url"
                                :alt="product.name"
                                class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-110"
                            />
                            <div v-else class="flex h-full items-center justify-center text-gray-400">
                                Belum ada gambar
                            </div>
                            <div v-if="product.featured" class="absolute right-4 top-4 rounded-full bg-yellow-400 px-3 py-1 text-xs font-semibold text-blue-900">
                                Unggulan
                            </div>
                        </div>

                        <div class="p-6">
                            <p class="mb-2 text-sm font-medium text-blue-600">
                                {{ product.category?.name ?? 'Produk' }}
                            </p>
                            <h3 class="mb-3 text-xl font-bold text-gray-900 transition-colors group-hover:text-blue-600">
                                {{ product.name }}
                            </h3>
                            <p class="mb-4 line-clamp-3 text-gray-600">
                                {{ product.summary || 'Informasi produk tersedia melalui tim kami.' }}
                            </p>
                            <div class="flex items-center justify-between">
                                <span class="font-semibold text-blue-900">{{ product.price_label ?? 'Hubungi kami' }}</span>
                                <span class="text-sm font-medium text-blue-600">Lihat detail →</span>
                            </div>
                        </div>
                    </Link>
                </div>

                <div v-else class="rounded-lg bg-white p-12 text-center shadow-sm">
                    <h3 class="mb-2 text-xl font-semibold text-gray-900">Belum ada produk yang dipublikasikan</h3>
                    <p class="text-gray-600">Silakan kembali lagi nanti atau hubungi tim kami untuk informasi produk.</p>
                </div>
            </div>
        </section>
    </div>

    <Footer />
</template>
