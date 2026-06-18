<script setup lang="ts">
import Footer from '@/components/Footer.vue';
import NavMenu from '@/components/NavMenu.vue';
import { Head, Link } from '@inertiajs/vue3';

interface ProductSpec {
    key: string;
    value: string;
}

interface Product {
    name: string;
    summary?: string;
    description: string;
    image_url?: string;
    image_urls?: string[];
    price_label?: string;
    specifications?: ProductSpec[];
    category?: {
        name: string;
    };
}

defineProps<{ product: Product }>();
</script>

<template>
    <Head>
        <title>{{ product.name }} | Katalog Produk PT. Intergeo Mitigasi</title>
        <meta name="description" :content="product.summary || product.name" />
    </Head>

    <NavMenu />

    <div class="min-h-screen bg-gray-50 light-mode">
        <section class="bg-gradient-to-br from-blue-900 via-blue-800 to-blue-700 py-20 text-white">
            <div class="mx-auto max-w-7xl px-6">
                <div class="max-w-3xl">
                    <Link href="/products" class="mb-6 inline-flex text-sm font-medium text-blue-100 transition-colors hover:text-yellow-400">
                        ← Kembali ke Katalog Produk
                    </Link>
                    <p class="mb-3 text-sm font-semibold uppercase tracking-widest text-yellow-400">
                        {{ product.category?.name ?? 'Produk' }}
                    </p>
                    <h1 class="text-4xl font-bold md:text-5xl">{{ product.name }}</h1>
                    <p v-if="product.summary" class="mt-5 text-xl text-blue-100">
                        {{ product.summary }}
                    </p>
                </div>
            </div>
        </section>

        <section class="py-16">
            <div class="mx-auto grid max-w-7xl gap-10 px-6 lg:grid-cols-2">
                <div>
                    <div class="overflow-hidden rounded-lg bg-white shadow-lg">
                        <img
                            v-if="product.image_url"
                            :src="product.image_url"
                            :alt="product.name"
                            class="h-full w-full object-cover"
                        />
                        <div v-else class="flex h-96 items-center justify-center text-gray-400">
                            Belum ada gambar
                        </div>
                    </div>

                    <div v-if="product.image_urls?.length && product.image_urls.length > 1" class="mt-4 grid grid-cols-4 gap-3">
                        <img
                            v-for="image in product.image_urls"
                            :key="image"
                            :src="image"
                            :alt="product.name"
                            class="aspect-square rounded-lg object-cover shadow-sm"
                        />
                    </div>
                </div>

                <div class="space-y-8">
                    <div class="rounded-lg bg-white p-8 shadow-lg">
                        <p v-if="product.price_label" class="mb-6 text-2xl font-bold text-blue-900">
                            {{ product.price_label }}
                        </p>

                        <div class="prose max-w-none text-gray-700" v-html="product.description"></div>

                        <Link
                            href="/contact"
                            class="mt-8 inline-flex rounded-md bg-blue-900 px-6 py-3 font-semibold text-white transition-colors hover:bg-blue-800"
                        >
                            Hubungi Kami
                        </Link>
                    </div>

                    <div v-if="product.specifications?.length" class="rounded-lg bg-white p-8 shadow-lg">
                        <h2 class="mb-6 text-2xl font-bold text-gray-900">Spesifikasi Produk</h2>
                        <dl class="grid gap-4 sm:grid-cols-2">
                            <div v-for="spec in product.specifications" :key="spec.key" class="rounded-lg border border-gray-200 p-4">
                                <dt class="text-sm font-medium text-gray-500">{{ spec.key }}</dt>
                                <dd class="mt-1 font-semibold text-gray-900">{{ spec.value }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <Footer />
</template>
