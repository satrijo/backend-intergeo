<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import ImageUpload from '@/components/ImageUpload.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import { ref, watch } from 'vue';

const props = defineProps<{ client?: any }>();
const isEdit = !!props.client;
const logoImages = ref<string[]>(props.client?.logo ? [props.client.logo] : []);

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Clients', href: '/dashboard/clients' },
  { title: isEdit ? 'Edit' : 'Create', href: '#' },
];

const form = useForm({
  name: props.client?.name ?? '',
  logo: props.client?.logo ?? '',
  website_url: props.client?.website_url ?? '',
  is_active: props.client?.is_active ?? true,
  sort_order: props.client?.sort_order ?? 0,
});

watch(logoImages, (value) => {
  form.logo = value[0] || '';
});

const submit = () => isEdit
  ? form.put(route('dashboard.clients.update', props.client.id))
  : form.post(route('dashboard.clients.store'));
</script>

<template>
  <Head :title="isEdit ? 'Edit Client' : 'Create Client'" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <Button variant="ghost" size="sm" as-child class="w-fit">
        <Link :href="route('dashboard.clients.index')">
          <ArrowLeft class="mr-2 h-4 w-4" />
          Back to Clients
        </Link>
      </Button>

      <Card>
        <CardHeader>
          <CardTitle>{{ isEdit ? 'Edit' : 'Create' }} Client</CardTitle>
          <CardDescription>Logo client yang aktif akan tampil di section Our Clients halaman depan.</CardDescription>
        </CardHeader>
        <CardContent>
          <form @submit.prevent="submit" class="space-y-6">
            <div class="grid gap-4 md:grid-cols-2">
              <div class="space-y-2">
                <Label for="name">Nama Client</Label>
                <Input id="name" v-model="form.name" placeholder="Contoh: Pertamina" />
                <p v-if="form.errors.name" class="text-sm text-red-500">{{ form.errors.name }}</p>
              </div>
              <div class="space-y-2">
                <Label for="website_url">Website URL (Opsional)</Label>
                <Input id="website_url" v-model="form.website_url" placeholder="https://example.com" />
                <p v-if="form.errors.website_url" class="text-sm text-red-500">{{ form.errors.website_url }}</p>
              </div>
            </div>

            <ImageUpload v-model="logoImages" label="Logo Client" :max-images="1" :error="form.errors.logo" />

            <div class="grid gap-4 md:grid-cols-2">
              <div class="space-y-2">
                <Label for="sort_order">Sort Order</Label>
                <Input id="sort_order" v-model="form.sort_order" type="number" min="0" />
              </div>
              <label class="flex items-center gap-2 pt-8 text-sm">
                <input v-model="form.is_active" type="checkbox" />
                Tampilkan di halaman depan
              </label>
            </div>

            <Button type="submit" :disabled="form.processing">
              {{ form.processing ? 'Saving...' : isEdit ? 'Update Client' : 'Create Client' }}
            </Button>
          </form>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>
