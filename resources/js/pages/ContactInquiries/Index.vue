<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Eye, Phone, Mail, Calendar, MessageSquare } from 'lucide-vue-next';

interface ContactInquiry {
  id: string;
  full_name: string;
  email: string;
  phone_number: string;
  service_type: string;
  status: string;
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
    from: number;
    to: number;
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
  {
    title: 'Dashboard',
    href: '/dashboard',
  },
  {
    title: 'Contact Inquiries',
    href: '/dashboard/contact-inquiries',
  },
];

const statusColors = {
  new: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
  contacted: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
  in_progress: 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
  completed: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
  cancelled: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'
};

const statusLabels = {
  new: 'Baru',
  contacted: 'Sudah Dihubungi',
  in_progress: 'Dalam Proses',
  completed: 'Selesai',
  cancelled: 'Dibatalkan'
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
  <Head title="Contact Inquiries" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold">Contact Inquiries</h1>
          <p class="text-muted-foreground">Kelola permintaan konsultasi survey dari pengunjung website</p>
        </div>
      </div>

      <Card>
        <CardHeader>
          <div class="flex items-center justify-between">
            <div>
              <CardTitle class="flex items-center gap-2">
                <MessageSquare class="h-5 w-5" />
                Daftar Permintaan Konsultasi
              </CardTitle>
              <CardDescription>Total: {{ inquiries.total }} inquiries</CardDescription>
            </div>
          </div>
        </CardHeader>
        <CardContent>
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Pengirim</TableHead>
                <TableHead>Layanan</TableHead>
                <TableHead>Status</TableHead>
                <TableHead>Tanggal</TableHead>
                <TableHead>Aksi</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow v-for="inquiry in inquiries.data" :key="inquiry.id">
                <TableCell>
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                      <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center dark:bg-blue-900">
                        <span class="text-sm font-medium text-blue-600 dark:text-blue-300">
                          {{ inquiry.full_name.charAt(0).toUpperCase() }}
                        </span>
                      </div>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-foreground">
                        {{ inquiry.full_name }}
                      </div>
                      <div class="text-sm text-muted-foreground flex items-center">
                        <Mail class="w-4 h-4 mr-1" />
                        {{ inquiry.email }}
                      </div>
                      <div class="text-sm text-muted-foreground flex items-center">
                        <Phone class="w-4 h-4 mr-1" />
                        {{ inquiry.phone_number }}
                      </div>
                    </div>
                  </div>
                </TableCell>
                <TableCell>
                  <div class="text-sm text-foreground">
                    {{ inquiry.service_type || 'Tidak ditentukan' }}
                  </div>
                </TableCell>
                <TableCell>
                  <span :class="[
                    'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                    statusColors[inquiry.status as keyof typeof statusColors]
                  ]">
                    {{ statusLabels[inquiry.status as keyof typeof statusLabels] }}
                  </span>
                </TableCell>
                <TableCell>
                  <div class="flex items-center text-sm text-muted-foreground">
                    <Calendar class="w-4 h-4 mr-1" />
                    {{ formatDate(inquiry.created_at) }}
                  </div>
                </TableCell>
                <TableCell>
                  <Button variant="ghost" size="sm" as-child>
                    <Link :href="`/dashboard/contact-inquiries/${inquiry.id}`">
                      <Eye class="h-4 w-4" />
                    </Link>
                  </Button>
                </TableCell>
              </TableRow>
              <TableRow v-if="inquiries.data.length === 0">
                <TableCell colspan="5" class="text-center text-muted-foreground">
                  Tidak ada inquiry ditemukan.
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>

          <!-- Pagination -->
          <div v-if="inquiries.last_page > 1" class="mt-6 flex items-center justify-between">
            <div class="flex-1 flex justify-between sm:hidden">
              <Button v-if="inquiries.prev_page_url" variant="outline" size="sm" as-child>
                <Link :href="inquiries.prev_page_url">
                  Sebelumnya
                </Link>
              </Button>
              <Button v-if="inquiries.next_page_url" variant="outline" size="sm" as-child>
                <Link :href="inquiries.next_page_url">
                  Selanjutnya
                </Link>
              </Button>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
              <div>
                <p class="text-sm text-muted-foreground">
                  Menampilkan
                  <span class="font-medium">{{ inquiries.from }}</span>
                  sampai
                  <span class="font-medium">{{ inquiries.to }}</span>
                  dari
                  <span class="font-medium">{{ inquiries.total }}</span>
                  hasil
                </p>
              </div>
              <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                  <Button
                    v-for="(link, index) in inquiries.links"
                    :key="index"
                    variant="outline"
                    size="sm"
                    :class="[
                      'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                      link.url === null
                        ? 'bg-muted border-border text-muted-foreground cursor-not-allowed'
                        : link.active
                        ? 'z-10 bg-blue-50 dark:bg-blue-900 border-blue-500 text-blue-600 dark:text-blue-300'
                        : 'bg-background border-border text-muted-foreground hover:bg-muted'
                    ]"
                    as-child
                  >
                    <Link :href="link.url || '#'" v-html="link.label" />
                  </Button>
                </nav>
              </div>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template> 