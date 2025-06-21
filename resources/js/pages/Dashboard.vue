<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Plus, FileText, Eye } from 'lucide-vue-next';

interface Article {
    id: string;
    title: string;
    slug: string;
    published: boolean;
    created_at: string;
    updated_at: string;
}

interface Props {
    recentArticles?: Article[];
}

withDefaults(defineProps<Props>(), {
    recentArticles: () => [],
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Dashboard</h1>
                    <p class="text-muted-foreground">Welcome back! Here's what's happening with your articles.</p>
                </div>
                <Button as-child>
                    <Link :href="route('articles.create')">
                        <Plus class="mr-2 h-4 w-4" />
                        Create Article
                    </Link>
                </Button>
            </div>

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <FileText class="h-5 w-5" />
                            Recent Articles
                        </CardTitle>
                        <CardDescription>Your latest articles</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div v-if="recentArticles.length > 0" class="space-y-3">
                            <div v-for="article in recentArticles.slice(0, 3)" :key="article.id" class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="font-medium">{{ article.title }}</p>
                                    <p class="text-sm text-muted-foreground">
                                        {{ new Date(article.created_at).toLocaleDateString() }}
                                    </p>
                                </div>
                                <Button variant="ghost" size="sm" as-child>
                                    <Link :href="route('articles.show', article.id)">
                                        <Eye class="h-4 w-4" />
                                    </Link>
                                </Button>
                            </div>
                        </div>
                        <div v-else class="text-center py-4">
                            <FileText class="mx-auto h-8 w-8 text-muted-foreground mb-2" />
                            <p class="text-sm text-muted-foreground">No articles yet</p>
                            <Button variant="outline" size="sm" class="mt-2" as-child>
                                <Link :href="route('articles.create')">Create your first article</Link>
                            </Button>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Quick Actions</CardTitle>
                        <CardDescription>Common tasks</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-2">
                        <Button variant="outline" class="w-full justify-start" as-child>
                            <Link :href="route('articles.index')">
                                <FileText class="mr-2 h-4 w-4" />
                                View All Articles
                            </Link>
                        </Button>
                        <Button variant="outline" class="w-full justify-start" as-child>
                            <Link :href="route('articles.create')">
                                <Plus class="mr-2 h-4 w-4" />
                                Create New Article
                            </Link>
                        </Button>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Statistics</CardTitle>
                        <CardDescription>Article overview</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span>Total Articles:</span>
                                <span class="font-medium">{{ recentArticles.length }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Published:</span>
                                <span class="font-medium">{{ recentArticles.filter(a => a.published).length }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Drafts:</span>
                                <span class="font-medium">{{ recentArticles.filter(a => !a.published).length }}</span>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
