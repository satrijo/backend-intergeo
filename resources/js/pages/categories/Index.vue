<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Plus, Edit, Eye, Trash2, Tag } from 'lucide-vue-next';

interface Category {
    id: string;
    name: string;
    slug: string;
    description: string | null;
    color: string;
    is_active: boolean;
    articles_count: number;
    created_at: string;
    updated_at: string;
}

interface Props {
    categories: Category[];
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
];

const deleteCategory = (categoryId: string) => {
    if (confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
        router.delete(route('categories.destroy', categoryId));
    }
};
</script>

<template>
    <Head title="Categories" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Categories</h1>
                    <p class="text-muted-foreground">Manage your article categories</p>
                </div>
                <Button as-child>
                    <Link :href="route('categories.create')">
                        <Plus class="mr-2 h-4 w-4" />
                        Create Category
                    </Link>
                </Button>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>All Categories</CardTitle>
                    <CardDescription>A list of all your categories</CardDescription>
                </CardHeader>
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Name</TableHead>
                                <TableHead>Description</TableHead>
                                <TableHead>Articles</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead>Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="category in categories" :key="category.id">
                                <TableCell>
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-3 h-3 rounded-full"
                                            :style="{ backgroundColor: category.color }"
                                        ></div>
                                        <span class="font-medium">{{ category.name }}</span>
                                    </div>
                                </TableCell>
                                <TableCell class="text-muted-foreground">
                                    {{ category.description || 'No description' }}
                                </TableCell>
                                <TableCell>
                                    <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                        {{ category.articles_count }} articles
                                    </span>
                                </TableCell>
                                <TableCell>
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
                                </TableCell>
                                <TableCell>
                                    <div class="flex items-center gap-2">
                                        <Button variant="ghost" size="sm" as-child>
                                            <Link :href="route('categories.show', category.id)">
                                                <Eye class="h-4 w-4" />
                                            </Link>
                                        </Button>
                                        <Button variant="ghost" size="sm" as-child>
                                            <Link :href="route('categories.edit', category.id)">
                                                <Edit class="h-4 w-4" />
                                            </Link>
                                        </Button>
                                        <Button 
                                            variant="ghost" 
                                            size="sm" 
                                            @click="deleteCategory(category.id)"
                                            class="text-red-600 hover:text-red-700 hover:bg-red-50"
                                        >
                                            <Trash2 class="h-4 w-4" />
                                        </Button>
                                    </div>
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="categories.length === 0">
                                <TableCell colspan="5" class="text-center text-muted-foreground">
                                    No categories found. Create your first category!
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template> 