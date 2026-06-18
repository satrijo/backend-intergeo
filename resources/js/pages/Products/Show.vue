<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Edit } from 'lucide-vue-next';
defineProps<{ product: any }>();
const breadcrumbs: BreadcrumbItem[] = [{ title: 'Dashboard', href: '/dashboard' }, { title: 'Products', href: '/dashboard/products' }, { title: 'Detail', href: '#' }];
</script>
<template><Head :title="product.name"/><AppLayout :breadcrumbs="breadcrumbs"><div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4"><div class="flex justify-between"><Button variant="ghost" size="sm" as-child><Link :href="route('dashboard.products.index')"><ArrowLeft class="mr-2 h-4 w-4"/>Back</Link></Button><Button as-child><Link :href="route('dashboard.products.edit', product.id)"><Edit class="mr-2 h-4 w-4"/>Edit</Link></Button></div><Card><CardHeader><CardTitle>{{ product.name }}</CardTitle></CardHeader><CardContent class="space-y-4"><div class="flex gap-2"><Badge>{{ product.status }}</Badge><Badge v-if="product.featured" variant="secondary">Featured</Badge></div><p class="text-muted-foreground">{{ product.summary }}</p><div v-if="product.image_urls?.length" class="grid gap-3 md:grid-cols-3"><img v-for="image in product.image_urls" :key="image" :src="image" class="rounded-lg object-cover"/></div><div v-html="product.description" class="prose max-w-none dark:prose-invert"></div><div v-if="product.specifications?.length"><h3 class="font-semibold">Specifications</h3><dl class="mt-2 grid gap-2 md:grid-cols-2"><div v-for="spec in product.specifications" :key="spec.key" class="rounded border p-3"><dt class="text-sm text-muted-foreground">{{ spec.key }}</dt><dd>{{ spec.value }}</dd></div></dl></div></CardContent></Card></div></AppLayout></template>
