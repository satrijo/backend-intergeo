<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Edit, Calendar, User, Tag } from 'lucide-vue-next';

interface Category {
    id: string;
    name: string;
    color: string;
}

interface Article {
    id: string;
    title: string;
    slug: string;
    body: string;
    image: string;
    published: boolean;
    created_at: string;
    updated_at: string;
    user: {
        name: string;
    };
    categories: Category[];
}

interface Props {
    article: Article;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Articles',
        href: '/articles',
    },
    {
        title: 'View Article',
        href: '#',
    },
];
</script>

<template>
    <Head :title="article.title" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center gap-4">
                <Button variant="ghost" size="sm" as-child>
                    <a :href="route('articles.index')">
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        Back to Articles
                    </a>
                </Button>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold">{{ article.title }}</h1>
                    <div class="flex items-center gap-4 text-sm text-muted-foreground">
                        <div class="flex items-center gap-1">
                            <User class="h-4 w-4" />
                            {{ article.user.name }}
                        </div>
                        <div class="flex items-center gap-1">
                            <Calendar class="h-4 w-4" />
                            {{ new Date(article.created_at).toLocaleDateString() }}
                        </div>
                        <span
                            :class="[
                                'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                                article.published
                                    ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
                                    : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300'
                            ]"
                        >
                            {{ article.published ? 'Published' : 'Draft' }}
                        </span>
                    </div>
                </div>
                <Button as-child>
                    <Link :href="route('articles.edit', article.id)">
                        <Edit class="mr-2 h-4 w-4" />
                        Edit Article
                    </Link>
                </Button>
            </div>

            <div v-if="article.categories.length > 0" class="flex items-center gap-2">
                <Tag class="h-4 w-4 text-muted-foreground" />
                <span class="text-sm text-muted-foreground">Categories:</span>
                <div class="flex flex-wrap gap-1">
                    <span
                        v-for="category in article.categories"
                        :key="category.id"
                        class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium"
                        :style="{
                            backgroundColor: category.color + '20',
                            color: category.color,
                            border: '1px solid ' + category.color + '40'
                        }"
                    >
                        {{ category.name }}
                    </span>
                </div>
            </div>

            <Card>
                <CardContent class="p-6">
                    <div v-if="article.image" class="mb-6">
                        <img :src="article.image" :alt="article.title" class="w-full rounded-lg" />
                    </div>
                    <div class="prose prose-sm max-w-none dark:prose-invert">
                        <div class="whitespace-pre-wrap">{{ article.body }}</div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template> 