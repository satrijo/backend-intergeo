<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import ImageUpload from '@/components/ImageUpload.vue';
import RichTextEditor from '@/components/RichTextEditor.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Plus, X } from 'lucide-vue-next';

const props = defineProps<{ product?: any; categories: any[] }>();
const isEdit = !!props.product;
const breadcrumbs: BreadcrumbItem[] = [{ title: 'Dashboard', href: '/dashboard' }, { title: 'Products', href: '/dashboard/products' }, { title: isEdit ? 'Edit' : 'Create', href: '#' }];
const form = useForm({
  product_category_id: props.product?.product_category_id ?? '',
  name: props.product?.name ?? '',
  summary: props.product?.summary ?? '',
  description: props.product?.description ?? '',
  images: props.product?.images ?? [],
  price: props.product?.price ?? '',
  specifications: props.product?.specifications ?? [] as {key: string; value: string}[],
  status: props.product?.status ?? 'draft',
  featured: props.product?.featured ?? false,
  sort_order: props.product?.sort_order ?? 0,
});
const addSpec = () => form.specifications.push({ key: '', value: '' });
const removeSpec = (index: number) => form.specifications.splice(index, 1);
const submit = () => isEdit ? form.put(route('dashboard.products.update', props.product.id)) : form.post(route('dashboard.products.store'));
</script>
<template><Head :title="isEdit ? 'Edit Product' : 'Create Product'"/><AppLayout :breadcrumbs="breadcrumbs"><div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
  <Button variant="ghost" size="sm" as-child class="w-fit"><Link :href="route('dashboard.products.index')"><ArrowLeft class="mr-2 h-4 w-4"/>Back</Link></Button>
  <Card><CardHeader><CardTitle>{{ isEdit ? 'Edit' : 'Create' }} Product</CardTitle><CardDescription>Product catalog data without cart/checkout.</CardDescription></CardHeader><CardContent><form @submit.prevent="submit" class="space-y-6">
    <div class="grid gap-4 md:grid-cols-2"><div class="space-y-2"><Label>Name</Label><Input v-model="form.name"/><p v-if="form.errors.name" class="text-sm text-red-500">{{ form.errors.name }}</p></div><div class="space-y-2"><Label>Category</Label><select v-model="form.product_category_id" class="h-10 w-full rounded-md border bg-background px-3 text-sm"><option value="">No category</option><option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option></select></div></div>
    <div class="space-y-2"><Label>Summary</Label><Input v-model="form.summary" maxlength="500"/></div>
    <div class="space-y-2"><Label>Description</Label><RichTextEditor v-model="form.description"/><p v-if="form.errors.description" class="text-sm text-red-500">{{ form.errors.description }}</p></div>
    <ImageUpload v-model="form.images" label="Product Images" :max-images="8" :error="form.errors.images" />
    <div class="grid gap-4 md:grid-cols-3"><div class="space-y-2"><Label>Price (optional)</Label><Input v-model="form.price" type="number" min="0" step="1000"/></div><div class="space-y-2"><Label>Status</Label><select v-model="form.status" class="h-10 w-full rounded-md border bg-background px-3 text-sm"><option value="draft">Draft</option><option value="published">Published</option></select></div><div class="space-y-2"><Label>Sort Order</Label><Input v-model="form.sort_order" type="number" min="0"/></div></div>
    <label class="flex items-center gap-2 text-sm"><input v-model="form.featured" type="checkbox"/> Featured product</label>
    <div class="space-y-2"><div class="flex items-center justify-between"><Label>Specifications</Label><Button type="button" variant="outline" size="sm" @click="addSpec"><Plus class="mr-2 h-4 w-4"/>Add</Button></div><div v-for="(spec, index) in form.specifications" :key="index" class="grid gap-2 md:grid-cols-[1fr_1fr_auto]"><Input v-model="spec.key" placeholder="Spec name"/><Input v-model="spec.value" placeholder="Spec value"/><Button type="button" variant="outline" size="sm" @click="removeSpec(index)"><X class="h-4 w-4"/></Button></div></div>
    <Button type="submit" :disabled="form.processing">{{ isEdit ? 'Update Product' : 'Create Product' }}</Button>
  </form></CardContent></Card>
</div></AppLayout></template>
