<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';

const props = defineProps<{ category?: any }>();
const isEdit = !!props.category;
const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Product Categories', href: '/dashboard/product-categories' },
  { title: isEdit ? 'Edit' : 'Create', href: '#' },
];
const form = useForm({
  name: props.category?.name ?? '',
  description: props.category?.description ?? '',
  color: props.category?.color ?? '#3b82f6',
  is_active: props.category?.is_active ?? true,
  sort_order: props.category?.sort_order ?? 0,
});
const submit = () => isEdit
  ? form.put(route('dashboard.product-categories.update', props.category.id))
  : form.post(route('dashboard.product-categories.store'));
</script>
<template>
  <Head :title="isEdit ? 'Edit Product Category' : 'Create Product Category'" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <Button variant="ghost" size="sm" as-child class="w-fit"><Link :href="route('dashboard.product-categories.index')"><ArrowLeft class="mr-2 h-4 w-4"/>Back</Link></Button>
      <Card><CardHeader><CardTitle>{{ isEdit ? 'Edit' : 'Create' }} Product Category</CardTitle><CardDescription>Manage catalog grouping.</CardDescription></CardHeader>
        <CardContent><form @submit.prevent="submit" class="space-y-4">
          <div class="space-y-2"><Label>Name</Label><Input v-model="form.name"/><p v-if="form.errors.name" class="text-sm text-red-500">{{ form.errors.name }}</p></div>
          <div class="space-y-2"><Label>Description</Label><textarea v-model="form.description" class="min-h-24 w-full rounded-md border bg-background px-3 py-2 text-sm"/></div>
          <div class="grid gap-4 md:grid-cols-2"><div class="space-y-2"><Label>Color</Label><Input v-model="form.color" type="color"/></div><div class="space-y-2"><Label>Sort Order</Label><Input v-model="form.sort_order" type="number" min="0"/></div></div>
          <label class="flex items-center gap-2 text-sm"><input v-model="form.is_active" type="checkbox"/> Active</label>
          <Button type="submit" :disabled="form.processing">{{ isEdit ? 'Update' : 'Create' }}</Button>
        </form></CardContent></Card>
    </div>
  </AppLayout>
</template>
