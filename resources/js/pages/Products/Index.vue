<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Edit, Eye, Plus, Trash2 } from 'lucide-vue-next';
defineProps<{ products: any }>();
const breadcrumbs: BreadcrumbItem[] = [{ title: 'Dashboard', href: '/dashboard' }, { title: 'Products', href: '/dashboard/products' }];
const destroy = (id: string, name: string) => { if (confirm(`Delete product "${name}"?`)) router.delete(route('dashboard.products.destroy', id)); };
</script>
<template><Head title="Products"/><AppLayout :breadcrumbs="breadcrumbs"><div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
 <div class="flex items-center justify-between"><div><h1 class="text-2xl font-bold">Products</h1><p class="text-muted-foreground">Manage product catalog</p></div><Button as-child><Link :href="route('dashboard.products.create')"><Plus class="mr-2 h-4 w-4"/>Create Product</Link></Button></div>
 <Card><CardHeader><CardTitle>Product Catalog</CardTitle><CardDescription>{{ products.total }} products</CardDescription></CardHeader><CardContent><Table><TableHeader><TableRow><TableHead>Product</TableHead><TableHead>Category</TableHead><TableHead>Price</TableHead><TableHead>Status</TableHead><TableHead>Featured</TableHead><TableHead class="text-right">Actions</TableHead></TableRow></TableHeader><TableBody><TableRow v-for="product in products.data" :key="product.id"><TableCell><div class="flex items-center gap-3"><img v-if="product.image_url" :src="product.image_url" class="h-12 w-12 rounded object-cover"/><div><div class="font-medium">{{ product.name }}</div><div class="text-sm text-muted-foreground">{{ product.summary }}</div></div></div></TableCell><TableCell>{{ product.category?.name ?? '-' }}</TableCell><TableCell>{{ product.price_label ?? '-' }}</TableCell><TableCell><Badge :variant="product.status === 'published' ? 'default' : 'secondary'">{{ product.status }}</Badge></TableCell><TableCell>{{ product.featured ? 'Yes' : 'No' }}</TableCell><TableCell class="text-right"><div class="flex justify-end gap-2"><Button variant="outline" size="sm" as-child><Link :href="route('dashboard.products.show', product.id)"><Eye class="h-4 w-4"/></Link></Button><Button variant="outline" size="sm" as-child><Link :href="route('dashboard.products.edit', product.id)"><Edit class="h-4 w-4"/></Link></Button><Button variant="outline" size="sm" @click="destroy(product.id, product.name)"><Trash2 class="h-4 w-4"/></Button></div></TableCell></TableRow></TableBody></Table></CardContent></Card>
</div></AppLayout></template>
