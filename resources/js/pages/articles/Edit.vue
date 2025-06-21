<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import CategorySelect from '@/components/CategorySelect.vue';
import RichTextEditor from '@/components/RichTextEditor.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';

interface Category {
    id: string;
    name: string;
    color: string;
}

interface Article {
    id: string;
    title: string;
    body: string;
    image: string;
    published: boolean;
    categories: Category[];
}

interface Props {
    article: Article;
    categories: Category[];
}

const props = defineProps<Props>();

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
        title: 'Edit Article',
        href: '#',
    },
];

const form = useForm({
    title: props.article.title,
    body: props.article.body,
    image: props.article.image,
    published: props.article.published,
    category_ids: props.article.categories.map((cat: Category) => cat.id),
});

const submit = () => {
    form.put(route('articles.update', props.article.id));
};
</script>

<template>
    <Head title="Edit Article" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center gap-4">
                <Button variant="ghost" size="sm" as-child>
                    <a :href="route('articles.index')">
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        Back to Articles
                    </a>
                </Button>
                <div>
                    <h1 class="text-2xl font-bold">Edit Article</h1>
                    <p class="text-muted-foreground">Update your article</p>
                </div>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Article Details</CardTitle>
                    <CardDescription>Update the details for your article</CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="space-y-2">
                            <Label for="title">Title</Label>
                            <Input
                                id="title"
                                v-model="form.title"
                                type="text"
                                placeholder="Enter article title"
                                :class="{ 'border-red-500': form.errors.title }"
                            />
                            <p v-if="form.errors.title" class="text-sm text-red-500">
                                {{ form.errors.title }}
                            </p>
                        </div>

                        <RichTextEditor
                            v-model="form.body"
                            label="Content"
                            placeholder="Write your article content here..."
                            :error="form.errors.body"
                            :height="500"
                        />

                        <div class="space-y-2">
                            <Label for="image">Featured Image URL (Optional)</Label>
                            <Input
                                id="image"
                                v-model="form.image"
                                type="url"
                                placeholder="https://example.com/image.jpg"
                                :class="{ 'border-red-500': form.errors.image }"
                            />
                            <p v-if="form.errors.image" class="text-sm text-red-500">
                                {{ form.errors.image }}
                            </p>
                        </div>

                        <CategorySelect
                            v-model="form.category_ids"
                            :categories="categories"
                            :error="form.errors.category_ids"
                        />

                        <div class="flex items-center space-x-2">
                            <input
                                type="checkbox"
                                id="published"
                                v-model="form.published"
                                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                            />
                            <Label for="published">Published</Label>
                        </div>

                        <div class="flex gap-4">
                            <Button type="submit" :disabled="form.processing">
                                {{ form.processing ? 'Updating...' : 'Update Article' }}
                            </Button>
                            <Button type="button" variant="outline" as-child>
                                <a :href="route('articles.index')">Cancel</a>
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template> 