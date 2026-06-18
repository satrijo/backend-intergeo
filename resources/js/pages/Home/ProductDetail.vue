<script setup lang="ts">
import NavMenu from '@/components/NavMenu.vue';
import Footer from '@/components/Footer.vue';
import { Head, Link } from '@inertiajs/vue3';
defineProps<{ product: any }>();
</script>
<template><Head :title="product.name"/><div class="min-h-screen bg-white text-gray-900"><NavMenu/>
<section class="px-4 py-24"><div class="mx-auto grid max-w-6xl gap-10 lg:grid-cols-2"><div><img v-if="product.image_url" :src="product.image_url" :alt="product.name" class="w-full rounded-2xl object-cover shadow-lg"/><div v-if="product.image_urls?.length > 1" class="mt-4 grid grid-cols-4 gap-3"><img v-for="image in product.image_urls" :key="image" :src="image" class="aspect-square rounded-lg object-cover"/></div></div><div><Link href="/products" class="text-sm text-blue-600 hover:underline">← Kembali ke katalog</Link><p class="mt-6 text-sm uppercase tracking-widest text-blue-600">{{ product.category?.name ?? 'Produk' }}</p><h1 class="mt-2 text-4xl font-bold">{{ product.name }}</h1><p v-if="product.price_label" class="mt-4 text-2xl font-semibold">{{ product.price_label }}</p><p class="mt-4 text-slate-600">{{ product.summary }}</p><div class="mt-8 prose max-w-none" v-html="product.description"></div><div v-if="product.specifications?.length" class="mt-8"><h2 class="mb-4 text-xl font-semibold">Spesifikasi</h2><dl class="grid gap-3 sm:grid-cols-2"><div v-for="spec in product.specifications" :key="spec.key" class="rounded-xl border p-4"><dt class="text-sm text-slate-500">{{ spec.key }}</dt><dd class="font-medium">{{ spec.value }}</dd></div></dl></div><Link href="/contact" class="mt-8 inline-flex rounded-lg bg-blue-600 px-5 py-3 font-medium text-white hover:bg-blue-700">Hubungi Kami</Link></div></div></section>
<Footer/></div></template>
