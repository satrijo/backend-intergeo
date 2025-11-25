<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Edit, ExternalLink, Calendar, User, Video } from 'lucide-vue-next';

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
    video: VideoPortfolio;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Work Showcase', href: '/dashboard/video-portfolios' },
    { title: 'Detail', href: `/dashboard/video-portfolios/${props.video.id}` },
];

const embedUrl = `https://www.youtube.com/embed/${props.video.youtube_id}`;
</script>

<template>
    <Head :title="video.title" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="sm" as-child>
                        <Link :href="route('dashboard.video-portfolios.index')">
                            <ArrowLeft class="mr-2 h-4 w-4" />
                            Back to Work Showcase
                        </Link>
                    </Button>
                    <div>
                        <h1 class="text-2xl font-bold">{{ video.title }}</h1>
                        <p class="text-muted-foreground">Video Details</p>
                    </div>
                </div>
                <Button as-child>
                    <Link :href="route('dashboard.video-portfolios.edit', video.id)">
                        <Edit class="mr-2 h-4 w-4" />
                        Edit Video
                    </Link>
                </Button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Video Embed -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Video</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="aspect-video rounded-lg overflow-hidden border border-gray-200 bg-gray-100">
                                <iframe
                                    :src="embedUrl"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen
                                    class="w-full h-full"
                                ></iframe>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Video Description -->
                    <Card v-if="video.description">
                        <CardHeader>
                            <CardTitle>Description</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="prose prose-sm max-w-none" v-html="video.description"></div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Video Status -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Status</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <Badge :variant="video.status === 'published' ? 'default' : 'secondary'">
                                {{ video.status === 'published' ? 'Published' : 'Draft' }}
                            </Badge>
                        </CardContent>
                    </Card>

                    <!-- Video Details -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Video Details</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="flex items-center gap-2">
                                <User class="h-4 w-4 text-muted-foreground" />
                                <div>
                                    <p class="text-sm font-medium">Created By</p>
                                    <p class="text-sm text-muted-foreground">{{ video.user.name }}</p>
                                </div>
                            </div>

                            <div v-if="video.published_at" class="flex items-center gap-2">
                                <Calendar class="h-4 w-4 text-muted-foreground" />
                                <div>
                                    <p class="text-sm font-medium">Published Date</p>
                                    <p class="text-sm text-muted-foreground">
                                        {{ new Date(video.published_at).toLocaleDateString() }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <Video class="h-4 w-4 text-muted-foreground" />
                                <div>
                                    <p class="text-sm font-medium">YouTube URL</p>
                                    <a 
                                        :href="video.youtube_url" 
                                        target="_blank" 
                                        rel="noopener noreferrer"
                                        class="text-sm text-blue-600 hover:underline flex items-center gap-1"
                                    >
                                        View on YouTube
                                        <ExternalLink class="h-3 w-3" />
                                    </a>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <div>
                                    <p class="text-sm font-medium">Sort Order</p>
                                    <p class="text-sm text-muted-foreground">{{ video.sort_order }}</p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Video Info -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Info</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-muted-foreground">Created:</span>
                                <span>{{ new Date(video.created_at).toLocaleDateString() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-muted-foreground">Last Updated:</span>
                                <span>{{ new Date(video.updated_at).toLocaleDateString() }}</span>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Prose styles are now defined globally in app.css */
</style>

