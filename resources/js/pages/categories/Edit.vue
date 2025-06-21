<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';

interface Category {
    id: string;
    name: string;
    slug: string;
    description: string | null;
    color: string;
    is_active: boolean;
}

interface Props {
    category: Category;
}

const props = defineProps<Props>();

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
        title: 'Edit Category',
        href: '#',
    },
];

const form = useForm({
    name: props.category.name,
    description: props.category.description || '',
    color: props.category.color,
    is_active: props.category.is_active,
});

const submit = () => {
    form.put(route('categories.update', props.category.id));
};
</script>

<template>
    <Head title="Edit Category" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center gap-4">
                <Button variant="ghost" size="sm" as-child>
                    <a :href="route('categories.index')">
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        Back to Categories
                    </a>
                </Button>
                <div>
                    <h1 class="text-2xl font-bold">Edit Category</h1>
                    <p class="text-muted-foreground">Update category details</p>
                </div>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Category Details</CardTitle>
                    <CardDescription>Update the details for this category</CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="space-y-2">
                            <Label for="name">Name</Label>
                            <Input
                                id="name"
                                v-model="form.name"
                                type="text"
                                placeholder="Enter category name"
                                :class="{ 'border-red-500': form.errors.name }"
                            />
                            <p v-if="form.errors.name" class="text-sm text-red-500">
                                {{ form.errors.name }}
                            </p>
                        </div>

                        <div class="space-y-2">
                            <Label for="description">Description (Optional)</Label>
                            <textarea
                                id="description"
                                v-model="form.description"
                                rows="3"
                                class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                placeholder="Enter category description..."
                                :class="{ 'border-red-500': form.errors.description }"
                            ></textarea>
                            <p v-if="form.errors.description" class="text-sm text-red-500">
                                {{ form.errors.description }}
                            </p>
                        </div>

                        <div class="space-y-2">
                            <Label for="color">Color</Label>
                            <div class="flex items-center gap-3">
                                <Input
                                    id="color"
                                    v-model="form.color"
                                    type="color"
                                    class="w-20 h-10 p-1"
                                    :class="{ 'border-red-500': form.errors.color }"
                                />
                                <Input
                                    v-model="form.color"
                                    type="text"
                                    placeholder="#3B82F6"
                                    class="flex-1"
                                    :class="{ 'border-red-500': form.errors.color }"
                                />
                            </div>
                            <p v-if="form.errors.color" class="text-sm text-red-500">
                                {{ form.errors.color }}
                            </p>
                        </div>

                        <div class="flex items-center space-x-2">
                            <input
                                type="checkbox"
                                id="is_active"
                                v-model="form.is_active"
                                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                            />
                            <Label for="is_active">Active category</Label>
                        </div>

                        <div class="flex gap-4">
                            <Button type="submit" :disabled="form.processing">
                                {{ form.processing ? 'Updating...' : 'Update Category' }}
                            </Button>
                            <Button type="button" variant="outline" as-child>
                                <a :href="route('categories.index')">Cancel</a>
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template> 