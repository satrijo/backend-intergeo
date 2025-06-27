<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Plus, Edit, Eye, Trash2 } from 'lucide-vue-next';

interface Category {
    id: string;
    name: string;
    color: string;
}

interface Article {
    id: string;
    title: string;
    slug: string;
    published: boolean;
    created_at: string;
    updated_at: string;
    categories: Category[];
    user: {
        name: string;
    };
}

interface Props {
    articles: Article[];
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Articles',
        href: '/dashboard/articles',
    },
];

const deleteArticle = (articleId: string) => {
    if (confirm('Are you sure you want to delete this article? This action cannot be undone.')) {
        router.delete(route('dashboard.articles.destroy', articleId));
    }
};
</script>

<template>
    <Head title="Articles" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Articles</h1>
                    <p class="text-muted-foreground">Manage your articles</p>
                </div>
                <div class="flex gap-2">
                    <Button variant="outline" as-child>
                        <Link :href="route('blog')">
                            View Blog
                        </Link>
                    </Button>
                    <Button as-child>
                        <Link :href="route('dashboard.articles.create')">
                            <Plus class="mr-2 h-4 w-4" />
                            Create Article
                        </Link>
                    </Button>
                </div>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>All Articles</CardTitle>
                    <CardDescription>A list of all your articles</CardDescription>
                </CardHeader>
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Title</TableHead>
                                <TableHead>Author</TableHead>
                                <TableHead>Categories</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead>Created</TableHead>
                                <TableHead>Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="article in articles" :key="article.id">
                                <TableCell class="font-medium">{{ article.title }}</TableCell>
                                <TableCell>{{ article.user.name }}</TableCell>
                                <TableCell>
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
                                        <span v-if="article.categories.length === 0" class="text-muted-foreground text-xs">
                                            No categories
                                        </span>
                                    </div>
                                </TableCell>
                                <TableCell>
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
                                </TableCell>
                                <TableCell>{{ new Date(article.created_at).toLocaleDateString() }}</TableCell>
                                <TableCell>
                                    <div class="flex items-center gap-2">
                                        <Button variant="ghost" size="sm" as-child>
                                            <Link :href="route('dashboard.articles.show', article.id)">
                                                <Eye class="h-4 w-4" />
                                            </Link>
                                        </Button>
                                        <Button variant="ghost" size="sm" as-child>
                                            <Link :href="route('dashboard.articles.edit', article.id)">
                                                <Edit class="h-4 w-4" />
                                            </Link>
                                        </Button>
                                        <Button 
                                            variant="ghost" 
                                            size="sm" 
                                            @click="deleteArticle(article.id)"
                                            class="text-red-600 hover:text-red-700 hover:bg-red-50"
                                        >
                                            <Trash2 class="h-4 w-4" />
                                        </Button>
                                    </div>
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="articles.length === 0">
                                <TableCell colspan="5" class="text-center text-muted-foreground">
                                    No articles found. Create your first article!
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template> 