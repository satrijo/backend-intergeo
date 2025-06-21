<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Edit, Calendar, FileText } from 'lucide-vue-next';

interface Article {
    id: string;
    title: string;
    published: boolean;
    created_at: string;
}

interface Category {
    id: string;
    name: string;
    slug: string;
    description: string | null;
    color: string;
    is_active: boolean;
    created_at: string;
    updated_at: string;
    articles: Article[];
}

interface Props {
    category: Category;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Categories',
        href: '/categories',
    },
    {
        title: 'View Category',
        href: '#',
    },
];
</script>

<template>
    <Head :title="category.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center gap-4">
                <Button variant="ghost" size="sm" as-child>
                    <a :href="route('categories.index')">
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        Back to Categories
                    </a>
                </Button>
                <div class="flex-1">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-6 h-6 rounded-full"
                            :style="{ backgroundColor: category.color }"
                        ></div>
                        <h1 class="text-2xl font-bold">{{ category.name }}</h1>
                        <span
                            :class="[
                                'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                                category.is_active
                                    ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
                                    : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'
                            ]"
                        >
                            {{ category.is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <p v-if="category.description" class="text-muted-foreground mt-1">
                        {{ category.description }}
                    </p>
                </div>
                <Button as-child>
                    <Link :href="route('categories.edit', category.id)">
                        <Edit class="mr-2 h-4 w-4" />
                        Edit Category
                    </Link>
                </Button>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <FileText class="h-5 w-5" />
                            Articles in this Category
                        </CardTitle>
                        <CardDescription>{{ category.articles.length }} articles found</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div v-if="category.articles.length > 0" class="space-y-3">
                            <div v-for="article in category.articles" :key="article.id" class="flex items-center justify-between p-3 border rounded-lg">
                                <div class="flex-1">
                                    <p class="font-medium">{{ article.title }}</p>
                                    <p class="text-sm text-muted-foreground">
                                        {{ new Date(article.created_at).toLocaleDateString() }}
                                    </p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span
                                        :class="[
                                            'inline-flex items-center rounded-full px-2 py-1 text-xs font-medium',
                                            article.published
                                                ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
                                                : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300'
                                        ]"
                                    >
                                        {{ article.published ? 'Published' : 'Draft' }}
                                    </span>
                                    <Button variant="ghost" size="sm" as-child>
                                        <Link :href="route('articles.show', article.id)">
                                            View
                                        </Link>
                                    </Button>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-center py-8">
                            <FileText class="mx-auto h-8 w-8 text-muted-foreground mb-2" />
                            <p class="text-sm text-muted-foreground">No articles in this category yet</p>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Category Information</CardTitle>
                        <CardDescription>Details about this category</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Name</Label>
                            <p class="text-lg font-medium">{{ category.name }}</p>
                        </div>
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Slug</Label>
                            <p class="text-sm font-mono bg-muted px-2 py-1 rounded">{{ category.slug }}</p>
                        </div>
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Description</Label>
                            <p class="text-sm">{{ category.description || 'No description provided' }}</p>
                        </div>
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Color</Label>
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-6 h-6 rounded-full border"
                                    :style="{ backgroundColor: category.color }"
                                ></div>
                                <span class="text-sm font-mono">{{ category.color }}</span>
                            </div>
                        </div>
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Created</Label>
                            <p class="text-sm">{{ new Date(category.created_at).toLocaleDateString() }}</p>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template> 