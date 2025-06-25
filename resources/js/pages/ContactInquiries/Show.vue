<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { ArrowLeft, Phone, Mail, Calendar, MessageSquare, User, MapPin } from 'lucide-vue-next';
import { ref } from 'vue';

interface ContactInquiry {
  id: string;
  full_name: string;
  email: string;
  phone_number: string;
  service_type: string;
  project_description: string;
  status: string;
  notes: string;
  created_at: string;
  updated_at: string;
}

interface Props {
  inquiry: ContactInquiry;
  statusLogs: any[];
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dashboard',
    href: '/dashboard',
  },
  {
    title: 'Contact Inquiries',
    href: '/dashboard/contact-inquiries',
  },
  {
    title: 'Detail',
    href: `/dashboard/contact-inquiries/${props.inquiry.id}`,
  },
];

const statusOptions = [
  { value: 'new', label: 'Baru' },
  { value: 'contacted', label: 'Sudah Dihubungi' },
  { value: 'in_progress', label: 'Dalam Proses' },
  { value: 'completed', label: 'Selesai' },
  { value: 'cancelled', label: 'Dibatalkan' }
];

const statusColors = {
  new: 'bg-blue-100 text-blue-800',
  contacted: 'bg-yellow-100 text-yellow-800',
  in_progress: 'bg-orange-100 text-orange-800',
  completed: 'bg-green-100 text-green-800',
  cancelled: 'bg-red-100 text-red-800'
};

const form = ref({
  status: props.inquiry.status,
  notes: props.inquiry.notes || ''
});

const isLoading = ref(false);

const updateStatus = async () => {
  isLoading.value = true;
  
  try {
    await router.put(route('dashboard.contact-inquiries.update', props.inquiry.id), form.value);
  } catch (error) {
    console.error('Error updating status:', error);
  } finally {
    isLoading.value = false;
  }
};

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};
</script>

<template>
  <Head title="Detail Contact Inquiry" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold">Detail Contact Inquiry</h1>
          <p class="text-muted-foreground">Informasi lengkap permintaan konsultasi survey</p>
        </div>
        <Button variant="outline" as-child>
          <Link :href="route('dashboard.contact-inquiries.index')">
            <ArrowLeft class="mr-2 h-4 w-4" />
            Kembali ke Daftar
          </Link>
        </Button>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Contact Information -->
          <Card>
            <CardHeader>
              <CardTitle class="flex items-center gap-2">
                <User class="h-5 w-5" />
                Informasi Kontak
              </CardTitle>
            </CardHeader>
            <CardContent>
              <div class="space-y-4">
                <div class="flex items-center">
                  <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mr-4 dark:bg-blue-900">
                    <span class="text-lg font-medium text-blue-600 dark:text-blue-300">
                      {{ props.inquiry.full_name.charAt(0).toUpperCase() }}
                    </span>
                  </div>
                  <div>
                    <h3 class="text-xl font-semibold text-foreground">{{ props.inquiry.full_name }}</h3>
                    <div class="flex items-center text-muted-foreground mt-1">
                      <Mail class="w-4 h-4 mr-2" />
                      {{ props.inquiry.email }}
                    </div>
                    <div class="flex items-center text-muted-foreground mt-1">
                      <Phone class="w-4 h-4 mr-2" />
                      {{ props.inquiry.phone_number }}
                    </div>
                  </div>
                </div>
              </div>
            </CardContent>
          </Card>

          <!-- Project Details -->
          <Card>
            <CardHeader>
              <CardTitle class="flex items-center gap-2">
                <MessageSquare class="h-5 w-5" />
                Detail Proyek Survey
              </CardTitle>
            </CardHeader>
            <CardContent>
              <div class="space-y-4">
                <div>
                  <Label class="text-sm font-medium text-muted-foreground">Jenis Layanan</Label>
                  <p class="text-foreground bg-muted px-3 py-2 rounded-md mt-1">
                    {{ props.inquiry.service_type || 'Tidak ditentukan' }}
                  </p>
                </div>
                <div>
                  <Label class="text-sm font-medium text-muted-foreground">Deskripsi Proyek</Label>
                  <div class="bg-muted px-3 py-2 rounded-md mt-1">
                    <p class="text-foreground whitespace-pre-wrap">{{ props.inquiry.project_description }}</p>
                  </div>
                </div>
              </div>
            </CardContent>
          </Card>

          <!-- Timeline -->
          <Card>
            <CardHeader>
              <CardTitle>Timeline</CardTitle>
            </CardHeader>
            <CardContent>
              <div class="space-y-4">
                <div v-for="log in props.statusLogs" :key="log.id" class="flex items-start gap-3">
                  <div class="w-3 h-3 rounded-full mt-1"
                    :class="{
                      'bg-blue-500': log.status === 'new',
                      'bg-yellow-500': log.status === 'contacted',
                      'bg-orange-500': log.status === 'in_progress',
                      'bg-green-500': log.status === 'completed',
                      'bg-red-500': log.status === 'cancelled',
                    }"
                  ></div>
                  <div class="flex-1">
                    <div class="flex items-center gap-2">
                      <span class="font-semibold capitalize">{{ statusOptions.find(opt => opt.value === log.status)?.label || log.status }}</span>
                      <span class="text-xs text-muted-foreground">{{ formatDate(log.created_at) }}</span>
                    </div>
                    <div class="text-xs text-muted-foreground">
                      Oleh: <span class="font-medium">{{ log.user?.name || 'System' }}</span>
                    </div>
                    <div v-if="log.notes" class="text-sm mt-1 text-muted-foreground">Catatan: {{ log.notes }}</div>
                  </div>
                </div>
              </div>
            </CardContent>
          </Card>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
          <!-- Status Update -->
          <Card>
            <CardHeader>
              <CardTitle>Update Status</CardTitle>
            </CardHeader>
            <CardContent>
              <form @submit.prevent="updateStatus" class="space-y-4">
                <div>
                  <Label for="status">Status</Label>
                  <select
                    id="status"
                    v-model="form.status"
                    class="w-full px-3 py-2 border border-border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 mt-1 bg-background text-foreground dark:bg-background dark:text-foreground"
                  >
                    <option
                      v-for="option in statusOptions"
                      :key="option.value"
                      :value="option.value"
                    >
                      {{ option.label }}
                    </option>
                  </select>
                </div>
                <div>
                  <Label for="notes">Catatan</Label>
                  <textarea
                    id="notes"
                    v-model="form.notes"
                    rows="4"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 mt-1"
                    placeholder="Tambahkan catatan internal..."
                  ></textarea>
                </div>
                <Button
                  type="submit"
                  :disabled="isLoading"
                  class="w-full"
                >
                  {{ isLoading ? 'Menyimpan...' : 'Update Status' }}
                </Button>
              </form>
            </CardContent>
          </Card>

          <!-- Current Status -->
          <Card>
            <CardHeader>
              <CardTitle>Status Saat Ini</CardTitle>
            </CardHeader>
            <CardContent>
              <div class="space-y-4">
                <div>
                  <span :class="[
                    'inline-flex px-3 py-1 text-sm font-semibold rounded-full',
                    statusColors[props.inquiry.status as keyof typeof statusColors]
                  ]">
                    {{ statusOptions.find(opt => opt.value === props.inquiry.status)?.label }}
                  </span>
                </div>
                <div v-if="props.inquiry.notes" class="text-sm text-muted-foreground">
                  <p class="font-medium mb-1">Catatan:</p>
                  <p class="whitespace-pre-wrap">{{ props.inquiry.notes }}</p>
                </div>
              </div>
            </CardContent>
          </Card>

          <!-- Quick Actions -->
          <Card>
            <CardHeader>
              <CardTitle>Aksi Cepat</CardTitle>
            </CardHeader>
            <CardContent>
              <div class="space-y-3">
                <Button variant="outline" class="w-full justify-start" as-child>
                  <a :href="`mailto:${props.inquiry.email}`">
                    <Mail class="mr-2 h-4 w-4" />
                    Kirim Email
                  </a>
                </Button>
                <Button variant="outline" class="w-full justify-start" as-child>
                  <a :href="`tel:${props.inquiry.phone_number}`">
                    <Phone class="mr-2 h-4 w-4" />
                    Telepon
                  </a>
                </Button>
                <Button variant="outline" class="w-full justify-start" as-child>
                  <a :href="`https://wa.me/${props.inquiry.phone_number.replace(/[^0-9]/g, '')}`" target="_blank">
                    <MessageSquare class="mr-2 h-4 w-4" />
                    WhatsApp
                  </a>
                </Button>
              </div>
            </CardContent>
          </Card>
        </div>
      </div>
    </div>
  </AppLayout>
</template> 