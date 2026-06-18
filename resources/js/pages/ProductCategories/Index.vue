<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Edit, Plus, Trash2 } from 'lucide-vue-next';
defineProps<{ categories: any[] }>();
const breadcrumbs: BreadcrumbItem[] = [{ title: 'Dashboard', href: '/dashboard' }, { title: 'Product Categories', href: '/dashboard/product-categories' }];
const destroy = (id: string, name: string) => { if (confirm(`Delete category "${name}"?`)) router.delete(route('dashboard.product-categories.destroy', id)); };
</script>
<template><Head title="Product Categories"/><AppLayout :breadcrumbs="breadcrumbs"><div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
  <div class="flex items-center justify-between"><div><h1 class="text-2xl font-bold">Product Categories</h1><p class="text-muted-foreground">Manage product catalog categories</p></div><Button as-child><Link :href="route('dashboard.product-categories.create')"><Plus class="mr-2 h-4 w-4"/>Create Category</Link></Button></div>
  <Card><CardHeader><CardTitle>Categories</CardTitle><CardDescription>{{ categories.length }} categories</CardDescription></CardHeader><CardContent><Table><TableHeader><TableRow><TableHead>Name</TableHead><TableHead>Products</TableHead><TableHead>Status</TableHead><TableHead>Sort</TableHead><TableHead class="text-right">Actions</TableHead></TableRow></TableHeader><TableBody><TableRow v-for="category in categories" :key="category.id"><TableCell><div class="flex items-center gap-2"><span class="h-3 w-3 rounded-full" :style="{backgroundColor: category.color}"/>{{ category.name }}</div></TableCell><TableCell>{{ category.products_count }}</TableCell><TableCell><Badge :variant="category.is_active ? 'default' : 'secondary'">{{ category.is_active ? 'Active' : 'Inactive' }}</Badge></TableCell><TableCell>{{ category.sort_order }}</TableCell><TableCell class="text-right"><div class="flex justify-end gap-2"><Button variant="outline" size="sm" as-child><Link :href="route('dashboard.product-categories.edit', category.id)"><Edit class="h-4 w-4"/></Link></Button><Button variant="outline" size="sm" @click="destroy(category.id, category.name)"><Trash2 class="h-4 w-4"/></Button></div></TableCell></TableRow></TableBody></Table></CardContent></Card>
</div></AppLayout></template>
