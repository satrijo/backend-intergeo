<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import RichTextEditor from '@/components/RichTextEditor.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';

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
}

interface Props {
    video: VideoPortfolio;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Work Showcase', href: '/dashboard/video-portfolios' },
    { title: 'Edit', href: `/dashboard/video-portfolios/${props.video.id}/edit` },
];

const form = useForm({
    title: props.video.title,
    description: props.video.description || '',
    youtube_url: props.video.youtube_url,
    published_at: props.video.published_at || '',
    status: props.video.status,
    sort_order: props.video.sort_order,
});

const submit = () => {
    form.put(route('dashboard.video-portfolios.update', props.video.id));
};
</script>

<template>
    <Head title="Edit Video" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center gap-4">
                <Button variant="ghost" size="sm" as-child>
                    <Link :href="route('dashboard.video-portfolios.index')">
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        Back to Work Showcase
                    </Link>
                </Button>
                <div>
                    <h1 class="text-2xl font-bold">Edit Video</h1>
                    <p class="text-muted-foreground">Update your video details</p>
                </div>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Video Details</CardTitle>
                    <CardDescription>Update the details for your video</CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="space-y-2">
                            <Label for="youtube_url">YouTube URL *</Label>
                            <Input
                                id="youtube_url"
                                v-model="form.youtube_url"
                                type="url"
                                placeholder="https://www.youtube.com/watch?v=..."
                                :class="{ 'border-red-500': form.errors.youtube_url }"
                            />
                            <p v-if="form.errors.youtube_url" class="text-sm text-red-500">
                                {{ form.errors.youtube_url }}
                            </p>
                            <p class="text-sm text-muted-foreground">
                                Supported formats: youtube.com/watch?v=..., youtu.be/..., youtube.com/embed/...
                            </p>
                        </div>

                        <div class="space-y-2">
                            <Label for="title">Video Title *</Label>
                            <Input
                                id="title"
                                v-model="form.title"
                                type="text"
                                placeholder="Enter video title"
                                :class="{ 'border-red-500': form.errors.title }"
                            />
                            <p v-if="form.errors.title" class="text-sm text-red-500">
                                {{ form.errors.title }}
                            </p>
                        </div>

                        <RichTextEditor
                            v-model="form.description"
                            label="Description (Optional)"
                            placeholder="Describe the video or project..."
                            :error="form.errors.description"
                            :height="300"
                        />

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <Label for="published_at">Published Date (Optional)</Label>
                                <Input
                                    id="published_at"
                                    v-model="form.published_at"
                                    type="date"
                                    :class="{ 'border-red-500': form.errors.published_at }"
                                />
                                <p v-if="form.errors.published_at" class="text-sm text-red-500">
                                    {{ form.errors.published_at }}
                                </p>
                            </div>

                            <div class="space-y-2">
                                <Label for="sort_order">Sort Order</Label>
                                <Input
                                    id="sort_order"
                                    v-model="form.sort_order"
                                    type="number"
                                    min="0"
                                    placeholder="0"
                                    :class="{ 'border-red-500': form.errors.sort_order }"
                                />
                                <p v-if="form.errors.sort_order" class="text-sm text-red-500">
                                    {{ form.errors.sort_order }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-2">
                            <input
                                type="checkbox"
                                id="status"
                                v-model="form.status"
                                true-value="published"
                                false-value="draft"
                                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                            />
                            <Label for="status">Publish immediately</Label>
                        </div>

                        <div class="flex gap-4">
                            <Button type="submit" :disabled="form.processing">
                                {{ form.processing ? 'Updating...' : 'Update Video' }}
                            </Button>
                            <Button type="button" variant="outline" as-child>
                                <Link :href="route('dashboard.video-portfolios.index')">Cancel</Link>
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>

