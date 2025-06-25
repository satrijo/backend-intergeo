<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Plus, Eye, Edit, Trash2, ExternalLink } from 'lucide-vue-next';

interface Portfolio {
    id: string;
    title: string;
    description: string;
    images: string[];
    client: string;
    project_url: string;
    project_date: string;
    technologies: string[];
    status: 'draft' | 'published';
    sort_order: number;
    created_at: string;
    updated_at: string;
}

interface Props {
    portfolios: {
        data: Portfolio[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Portfolios', href: '/dashboard/portfolios' },
];

const confirmDelete = (portfolioId: string, portfolioTitle: string) => {
    if (window.confirm(`Are you sure you want to delete the project "${portfolioTitle}"?`)) {
        router.delete(route('dashboard.portfolios.destroy', portfolioId), {
            preserveScroll: true,
        });
    }
};

const getStatusBadgeVariant = (status: string) => {
    return status === 'published' ? 'default' : 'secondary';
};

const stripHtml = (html: string) => {
    const tmp = document.createElement('div');
    tmp.innerHTML = html;
    return tmp.textContent || tmp.innerText || '';
};

const getExcerpt = (html: string, maxLength = 120) => {
    const clean = stripHtml(html || '');
    return clean.length > maxLength ? clean.substring(0, maxLength) + '...' : clean;
};
</script>

<template>
    <Head title="Portfolio" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Portfolio</h1>
                    <p class="text-muted-foreground">Manage your portfolio projects</p>
                </div>
                <Button as-child>
                    <Link :href="route('dashboard.portfolios.create')">
                        <Plus class="mr-2 h-4 w-4" />
                        Create Portfolio
                    </Link>
                </Button>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Projects</CardTitle>
                    <CardDescription>
                        {{ portfolios.total }} project{{ portfolios.total !== 1 ? 's' : '' }} in your portfolio
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="rounded-md border">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Image</TableHead>
                                    <TableHead>Title</TableHead>
                                    <TableHead>Client</TableHead>
                                    <TableHead>Technologies</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead>Date</TableHead>
                                    <TableHead class="text-right">Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="portfolio in portfolios.data" :key="portfolio.id">
                                    <TableCell>
                                        <div class="h-12 w-12 rounded-md overflow-hidden bg-gray-100">
                                            <img 
                                                v-if="portfolio.images.length > 0 && portfolio.images[0]" 
                                                :src="portfolio.images[0]" 
                                                :alt="portfolio.title"
                                                class="h-full w-full object-cover"
                                            />
                                            <div v-else class="h-full w-full flex items-center justify-center text-gray-400">
                                                <span class="text-xs">No image</span>
                                            </div>
                                        </div>
                                    </TableCell>
                                    <TableCell>
                                        <div>
                                            <div class="font-medium">{{ portfolio.title }}</div>
                                            <div class="text-sm text-muted-foreground line-clamp-2">
                                                {{ getExcerpt(portfolio.description) }}
                                            </div>
                                        </div>
                                    </TableCell>
                                    <TableCell>
                                        <span v-if="portfolio.client" class="text-sm">
                                            {{ portfolio.client }}
                                        </span>
                                        <span v-else class="text-sm text-muted-foreground">-</span>
                                    </TableCell>
                                    <TableCell>
                                        <div class="flex flex-wrap gap-1">
                                            <Badge 
                                                v-for="tech in portfolio.technologies?.slice(0, 3)" 
                                                :key="tech" 
                                                variant="outline" 
                                                class="text-xs"
                                            >
                                                {{ tech }}
                                            </Badge>
                                            <span v-if="portfolio.technologies?.length > 3" class="text-xs text-muted-foreground">
                                                +{{ portfolio.technologies.length - 3 }} more
                                            </span>
                                        </div>
                                    </TableCell>
                                    <TableCell>
                                        <Badge :variant="getStatusBadgeVariant(portfolio.status)">
                                            {{ portfolio.status === 'published' ? 'Published' : 'Draft' }}
                                        </Badge>
                                    </TableCell>
                                    <TableCell>
                                        <span v-if="portfolio.project_date" class="text-sm">
                                            {{ new Date(portfolio.project_date).toLocaleDateString() }}
                                        </span>
                                        <span v-else class="text-sm text-muted-foreground">-</span>
                                    </TableCell>
                                    <TableCell class="text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <Button variant="ghost" size="sm" as-child>
                                                <Link :href="route('dashboard.portfolios.show', portfolio.id)">
                                                    <Eye class="h-4 w-4" />
                                                </Link>
                                            </Button>
                                            <Button variant="ghost" size="sm" as-child>
                                                <Link :href="route('dashboard.portfolios.edit', portfolio.id)">
                                                    <Edit class="h-4 w-4" />
                                                </Link>
                                            </Button>
                                            <Button variant="ghost" size="sm" @click="confirmDelete(portfolio.id, portfolio.title)">
                                                <Trash2 class="h-4 w-4" />
                                            </Button>
                                        </div>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="portfolios.last_page > 1" class="mt-4 flex items-center justify-between">
                        <div class="text-sm text-muted-foreground">
                            Showing {{ (portfolios.current_page - 1) * portfolios.per_page + 1 }} to 
                            {{ Math.min(portfolios.current_page * portfolios.per_page, portfolios.total) }} of 
                            {{ portfolios.total }} results
                        </div>
                        <div class="flex gap-2">
                            <Button 
                                v-if="portfolios.current_page > 1" 
                                variant="outline" 
                                size="sm"
                                as-child
                            >
                                <Link :href="route('dashboard.portfolios.index', { page: portfolios.current_page - 1 })">
                                    Previous
                                </Link>
                            </Button>
                            <Button 
                                v-if="portfolios.current_page < portfolios.last_page" 
                                variant="outline" 
                                size="sm"
                                as-child
                            >
                                <Link :href="route('dashboard.portfolios.index', { page: portfolios.current_page + 1 })">
                                    Next
                                </Link>
                            </Button>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template> 