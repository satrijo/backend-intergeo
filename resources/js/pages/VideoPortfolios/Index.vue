<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Plus, Eye, Edit, Trash2, Video } from 'lucide-vue-next';

interface VideoPortfolio {
    id: string;
    title: string;
    description: string;
    youtube_url: string;
    youtube_id: string;
    thumbnail_url: string;
    duration: string | null;
    published_at: string | null;
    status: 'draft' | 'published';
    sort_order: number;
    created_at: string;
    updated_at: string;
    user: {
        name: string;
    };
}

interface Props {
    videos: {
        data: VideoPortfolio[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Work Showcase', href: '/dashboard/video-portfolios' },
];

const confirmDelete = (videoId: string, videoTitle: string) => {
    if (window.confirm(`Are you sure you want to delete "${videoTitle}"?`)) {
        router.delete(route('dashboard.video-portfolios.destroy', videoId), {
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
    <Head title="Work Showcase" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Work Showcase</h1>
                    <p class="text-muted-foreground">Manage your video portfolio</p>
                </div>
                <Button as-child>
                    <Link :href="route('dashboard.video-portfolios.create')">
                        <Plus class="mr-2 h-4 w-4" />
                        Add Video
                    </Link>
                </Button>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Videos</CardTitle>
                    <CardDescription>
                        {{ videos.total }} video{{ videos.total !== 1 ? 's' : '' }} in your showcase
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="rounded-md border">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Thumbnail</TableHead>
                                    <TableHead>Title</TableHead>
                                    <TableHead>Author</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead>Published Date</TableHead>
                                    <TableHead class="text-right">Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="video in videos.data" :key="video.id">
                                    <TableCell>
                                        <div class="h-20 w-32 rounded-md overflow-hidden bg-gray-100 relative">
                                            <img 
                                                v-if="video.thumbnail_url" 
                                                :src="video.thumbnail_url" 
                                                :alt="video.title"
                                                class="h-full w-full object-cover"
                                            />
                                            <div v-else class="h-full w-full flex items-center justify-center text-gray-400">
                                                <Video class="h-6 w-6" />
                                            </div>
                                            <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30">
                                                <Video class="h-6 w-6 text-white" />
                                            </div>
                                        </div>
                                    </TableCell>
                                    <TableCell>
                                        <div>
                                            <div class="font-medium">{{ video.title }}</div>
                                            <div class="text-sm text-muted-foreground line-clamp-2">
                                                {{ getExcerpt(video.description) }}
                                            </div>
                                        </div>
                                    </TableCell>
                                    <TableCell>
                                        <span v-if="video.user.name" class="text-sm">
                                            {{ video.user.name }}
                                        </span>
                                        <span v-else class="text-sm text-muted-foreground">-</span>
                                    </TableCell>
                                    <TableCell>
                                        <Badge :variant="getStatusBadgeVariant(video.status)">
                                            {{ video.status === 'published' ? 'Published' : 'Draft' }}
                                        </Badge>
                                    </TableCell>
                                    <TableCell>
                                        <span v-if="video.published_at" class="text-sm">
                                            {{ new Date(video.published_at).toLocaleDateString() }}
                                        </span>
                                        <span v-else class="text-sm text-muted-foreground">-</span>
                                    </TableCell>
                                    <TableCell class="text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <Button variant="ghost" size="sm" as-child>
                                                <Link :href="route('dashboard.video-portfolios.show', video.id)">
                                                    <Eye class="h-4 w-4" />
                                                </Link>
                                            </Button>
                                            <Button variant="ghost" size="sm" as-child>
                                                <Link :href="route('dashboard.video-portfolios.edit', video.id)">
                                                    <Edit class="h-4 w-4" />
                                                </Link>
                                            </Button>
                                            <Button variant="ghost" size="sm" @click="confirmDelete(video.id, video.title)">
                                                <Trash2 class="h-4 w-4" />
                                            </Button>
                                        </div>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="videos.last_page > 1" class="mt-4 flex items-center justify-between">
                        <div class="text-sm text-muted-foreground">
                            Showing {{ (videos.current_page - 1) * videos.per_page + 1 }} to 
                            {{ Math.min(videos.current_page * videos.per_page, videos.total) }} of 
                            {{ videos.total }} results
                        </div>
                        <div class="flex gap-2">
                            <Button 
                                v-if="videos.current_page > 1" 
                                variant="outline" 
                                size="sm"
                                as-child
                            >
                                <Link :href="route('dashboard.video-portfolios.index', { page: videos.current_page - 1 })">
                                    Previous
                                </Link>
                            </Button>
                            <Button 
                                v-if="videos.current_page < videos.last_page" 
                                variant="outline" 
                                size="sm"
                                as-child
                            >
                                <Link :href="route('dashboard.video-portfolios.index', { page: videos.current_page + 1 })">
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

