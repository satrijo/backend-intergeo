<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Calendar, Eye, Mail, MessageSquare, Phone } from 'lucide-vue-next';
import { computed } from 'vue';

type InquiryStatus = 'new' | 'contacted' | 'in_progress' | 'completed' | 'cancelled';

interface ContactInquiry {
  id: string;
  full_name: string;
  email: string;
  phone_number: string;
  service_type: string | null;
  status: InquiryStatus;
  created_at: string;
  updated_at: string;
}

interface Props {
  inquiries: {
    data: ContactInquiry[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number | null;
    to: number | null;
    links: Array<{
      url: string | null;
      label: string;
      active: boolean;
    }>;
    prev_page_url: string | null;
    next_page_url: string | null;
  };
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Contact Inquiries', href: '/dashboard/contact-inquiries' },
];

const statusColors: Record<InquiryStatus, string> = {
  new: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
  contacted: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
  in_progress: 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
  completed: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
  cancelled: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
};

const statusLabels: Record<InquiryStatus, string> = {
  new: 'Baru',
  contacted: 'Sudah Dihubungi',
  in_progress: 'Dalam Proses',
  completed: 'Selesai',
  cancelled: 'Dibatalkan',
};

const currentPageStats = computed(() => ({
  new: props.inquiries.data.filter((inquiry) => inquiry.status === 'new').length,
  active: props.inquiries.data.filter((inquiry) => ['contacted', 'in_progress'].includes(inquiry.status)).length,
  closed: props.inquiries.data.filter((inquiry) => ['completed', 'cancelled'].includes(inquiry.status)).length,
}));

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
};

const getInitial = (name: string) => name?.charAt(0)?.toUpperCase() || '?';

const whatsappUrl = (phoneNumber: string) => {
  const digits = phoneNumber.replace(/[^0-9]/g, '');
  const normalized = digits.startsWith('0') ? `62${digits.slice(1)}` : digits;
  return `https://wa.me/${normalized}`;
};
</script>

<template>
  <Head title="Contact Inquiries" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
      <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h1 class="text-2xl font-bold">Contact Inquiries</h1>
          <p class="text-muted-foreground">Kelola permintaan konsultasi survey dari pengunjung website.</p>
        </div>
      </div>

      <div class="grid gap-4 md:grid-cols-3">
        <Card>
          <CardHeader class="pb-2">
            <CardDescription>Total Inquiry</CardDescription>
            <CardTitle class="text-3xl">{{ inquiries.total }}</CardTitle>
          </CardHeader>
        </Card>
        <Card>
          <CardHeader class="pb-2">
            <CardDescription>Baru di Halaman Ini</CardDescription>
            <CardTitle class="text-3xl text-blue-600">{{ currentPageStats.new }}</CardTitle>
          </CardHeader>
        </Card>
        <Card>
          <CardHeader class="pb-2">
            <CardDescription>Sedang Ditindaklanjuti</CardDescription>
            <CardTitle class="text-3xl text-orange-600">{{ currentPageStats.active }}</CardTitle>
          </CardHeader>
        </Card>
      </div>

      <Card>
        <CardHeader>
          <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
              <CardTitle class="flex items-center gap-2">
                <MessageSquare class="h-5 w-5" />
                Daftar Permintaan Konsultasi
              </CardTitle>
              <CardDescription>
                Menampilkan {{ inquiries.from || 0 }}–{{ inquiries.to || 0 }} dari {{ inquiries.total }} inquiry.
              </CardDescription>
            </div>
          </div>
        </CardHeader>
        <CardContent>
          <div class="overflow-x-auto rounded-md border">
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead class="min-w-[280px]">Pengirim</TableHead>
                  <TableHead class="min-w-[180px]">Layanan</TableHead>
                  <TableHead>Status</TableHead>
                  <TableHead class="min-w-[160px]">Tanggal Masuk</TableHead>
                  <TableHead class="text-right">Aksi</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow v-for="inquiry in inquiries.data" :key="inquiry.id">
                  <TableCell>
                    <div class="flex items-start gap-3">
                      <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900">
                        <span class="text-sm font-semibold text-blue-600 dark:text-blue-300">
                          {{ getInitial(inquiry.full_name) }}
                        </span>
                      </div>
                      <div class="min-w-0 space-y-1">
                        <div class="font-medium text-foreground">{{ inquiry.full_name }}</div>
                        <a :href="`mailto:${inquiry.email}`" class="flex items-center text-sm text-muted-foreground hover:text-foreground">
                          <Mail class="mr-1 h-4 w-4" />
                          <span class="truncate">{{ inquiry.email }}</span>
                        </a>
                        <a :href="`tel:${inquiry.phone_number}`" class="flex items-center text-sm text-muted-foreground hover:text-foreground">
                          <Phone class="mr-1 h-4 w-4" />
                          {{ inquiry.phone_number }}
                        </a>
                      </div>
                    </div>
                  </TableCell>
                  <TableCell>
                    <div class="max-w-[220px] text-sm text-foreground">
                      {{ inquiry.service_type || 'Tidak ditentukan' }}
                    </div>
                  </TableCell>
                  <TableCell>
                    <Badge :class="statusColors[inquiry.status]">
                      {{ statusLabels[inquiry.status] }}
                    </Badge>
                  </TableCell>
                  <TableCell>
                    <div class="flex items-center text-sm text-muted-foreground">
                      <Calendar class="mr-1 h-4 w-4" />
                      {{ formatDate(inquiry.created_at) }}
                    </div>
                  </TableCell>
                  <TableCell>
                    <div class="flex justify-end gap-2">
                      <Button variant="outline" size="sm" as-child>
                        <a :href="whatsappUrl(inquiry.phone_number)" target="_blank" rel="noopener noreferrer">
                          WhatsApp
                        </a>
                      </Button>
                      <Button variant="default" size="sm" as-child>
                        <Link :href="route('dashboard.contact-inquiries.show', inquiry.id)">
                          <Eye class="mr-2 h-4 w-4" />
                          Detail
                        </Link>
                      </Button>
                    </div>
                  </TableCell>
                </TableRow>
                <TableRow v-if="inquiries.data.length === 0">
                  <TableCell colspan="5" class="h-32 text-center text-muted-foreground">
                    Belum ada permintaan konsultasi yang masuk.
                  </TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </div>

          <div v-if="inquiries.last_page > 1" class="mt-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-muted-foreground">
              Halaman {{ inquiries.current_page }} dari {{ inquiries.last_page }}
            </p>
            <div class="flex flex-wrap gap-2">
              <Button v-if="inquiries.prev_page_url" variant="outline" size="sm" as-child>
                <Link :href="inquiries.prev_page_url">Sebelumnya</Link>
              </Button>
              <Button
                v-for="(link, index) in inquiries.links.filter((item) => !item.label.includes('Previous') && !item.label.includes('Next'))"
                :key="index"
                :variant="link.active ? 'default' : 'outline'"
                size="sm"
                :disabled="!link.url"
                as-child
              >
                <Link :href="link.url || '#'" v-html="link.label" />
              </Button>
              <Button v-if="inquiries.next_page_url" variant="outline" size="sm" as-child>
                <Link :href="inquiries.next_page_url">Selanjutnya</Link>
              </Button>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>
