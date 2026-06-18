<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Edit, Plus, Trash2 } from 'lucide-vue-next';

defineProps<{ clients: any[] }>();

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Clients', href: '/dashboard/clients' },
];

const confirmDelete = (client: any) => {
  if (window.confirm(`Delete client "${client.name}"?`)) {
    router.delete(route('dashboard.clients.destroy', client.id));
  }
};
</script>

<template>
  <Head title="Clients" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold">Clients</h1>
          <p class="text-muted-foreground">Kelola logo client yang tampil di halaman depan.</p>
        </div>
        <Button as-child>
          <Link :href="route('dashboard.clients.create')">
            <Plus class="mr-2 h-4 w-4" />
            Add Client
          </Link>
        </Button>
      </div>

      <Card>
        <CardHeader>
          <CardTitle>Our Clients</CardTitle>
          <CardDescription>{{ clients.length }} client terdaftar</CardDescription>
        </CardHeader>
        <CardContent>
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Logo</TableHead>
                <TableHead>Nama</TableHead>
                <TableHead>Website</TableHead>
                <TableHead>Status</TableHead>
                <TableHead>Sort</TableHead>
                <TableHead class="text-right">Aksi</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow v-for="client in clients" :key="client.id">
                <TableCell>
                  <div class="flex h-14 w-28 items-center justify-center rounded border bg-white p-2">
                    <img :src="client.logo_url" :alt="client.name" class="max-h-10 max-w-full object-contain" />
                  </div>
                </TableCell>
                <TableCell class="font-medium">{{ client.name }}</TableCell>
                <TableCell>
                  <a v-if="client.website_url" :href="client.website_url" target="_blank" class="text-blue-600 hover:underline">
                    {{ client.website_url }}
                  </a>
                  <span v-else class="text-muted-foreground">-</span>
                </TableCell>
                <TableCell>
                  <Badge :variant="client.is_active ? 'default' : 'secondary'">
                    {{ client.is_active ? 'Active' : 'Hidden' }}
                  </Badge>
                </TableCell>
                <TableCell>{{ client.sort_order }}</TableCell>
                <TableCell class="text-right">
                  <div class="flex justify-end gap-2">
                    <Button variant="outline" size="sm" as-child>
                      <Link :href="route('dashboard.clients.edit', client.id)">
                        <Edit class="h-4 w-4" />
                      </Link>
                    </Button>
                    <Button variant="outline" size="sm" @click="confirmDelete(client)">
                      <Trash2 class="h-4 w-4" />
                    </Button>
                  </div>
                </TableCell>
              </TableRow>
              <TableRow v-if="clients.length === 0">
                <TableCell colspan="6" class="h-24 text-center text-muted-foreground">
                  Belum ada client.
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>
