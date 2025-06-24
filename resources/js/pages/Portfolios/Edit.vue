<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import RichTextEditor from '@/components/RichTextEditor.vue';
import ImageUpload from '@/components/ImageUpload.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Plus, X } from 'lucide-vue-next';
import { ref } from 'vue';

interface Portfolio {
    id: string;
    title: string;
    description: string;
    images: string[];
    client: string;
    project_url: string;
    project_date: string;
    technologies: string[];
    status: 'draft' | 'published';
    sort_order: number;
}

interface Props {
    portfolio: Portfolio;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Portfolio',
        href: '/portfolios',
    },
    {
        title: 'Edit Project',
        href: '#',
    },
];

const form = useForm({
    title: props.portfolio.title,
    description: props.portfolio.description,
    images: props.portfolio.images || [],
    client: props.portfolio.client,
    project_url: props.portfolio.project_url,
    project_date: props.portfolio.project_date,
    technologies: props.portfolio.technologies || [],
    status: props.portfolio.status,
    sort_order: props.portfolio.sort_order,
});

const newTechnology = ref('');

const addTechnology = () => {
    if (newTechnology.value.trim() && !form.technologies.includes(newTechnology.value.trim())) {
        form.technologies.push(newTechnology.value.trim());
        newTechnology.value = '';
    }
};

const removeTechnology = (index: number) => {
    form.technologies.splice(index, 1);
};

const submit = () => {
    form.put(route('portfolios.update', props.portfolio.id));
};
</script>

<template>
    <Head title="Edit Portfolio Project" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center gap-4">
                <Button variant="ghost" size="sm" as-child>
                    <a :href="route('portfolios.index')">
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        Back to Portfolio
                    </a>
                </Button>
                <div>
                    <h1 class="text-2xl font-bold">Edit Portfolio Project</h1>
                    <p class="text-muted-foreground">Update your portfolio project</p>
                </div>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Project Details</CardTitle>
                    <CardDescription>Update the details for your portfolio project</CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="space-y-2">
                            <Label for="title">Project Title *</Label>
                            <Input
                                id="title"
                                v-model="form.title"
                                type="text"
                                placeholder="Enter project title"
                                :class="{ 'border-red-500': form.errors.title }"
                            />
                            <p v-if="form.errors.title" class="text-sm text-red-500">
                                {{ form.errors.title }}
                            </p>
                        </div>

                        <RichTextEditor
                            v-model="form.description"
                            label="Description *"
                            placeholder="Describe your project..."
                            :error="form.errors.description"
                            :height="300"
                        />

                        <div class="space-y-2">
                            <!-- <Label for="images">Project Images (Optional)</Label> -->
                            <ImageUpload
                                v-model="form.images"
                                label="Project Images"
                                :max-images="4"
                                :error="form.errors.images"
                            />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <Label for="client">Client (Optional)</Label>
                                <Input
                                    id="client"
                                    v-model="form.client"
                                    type="text"
                                    placeholder="Client name"
                                    :class="{ 'border-red-500': form.errors.client }"
                                />
                                <p v-if="form.errors.client" class="text-sm text-red-500">
                                    {{ form.errors.client }}
                                </p>
                            </div>

                            <div class="space-y-2">
                                <Label for="project_url">Project URL (Optional)</Label>
                                <Input
                                    id="project_url"
                                    v-model="form.project_url"
                                    type="url"
                                    placeholder="https://example.com"
                                    :class="{ 'border-red-500': form.errors.project_url }"
                                />
                                <p v-if="form.errors.project_url" class="text-sm text-red-500">
                                    {{ form.errors.project_url }}
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <Label for="project_date">Project Date (Optional)</Label>
                                <Input
                                    id="project_date"
                                    v-model="form.project_date"
                                    type="date"
                                    :class="{ 'border-red-500': form.errors.project_date }"
                                />
                                <p v-if="form.errors.project_date" class="text-sm text-red-500">
                                    {{ form.errors.project_date }}
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

                        <div class="space-y-2">
                            <Label>Technologies Used (Optional)</Label>
                            <div class="flex gap-2">
                                <Input
                                    v-model="newTechnology"
                                    type="text"
                                    placeholder="Add technology (e.g., Vue.js, Laravel)"
                                    @keyup.enter="addTechnology"
                                />
                                <Button type="button" @click="addTechnology" variant="outline">
                                    <Plus class="h-4 w-4" />
                                </Button>
                            </div>
                            <div v-if="form.technologies.length > 0" class="flex flex-wrap gap-2 mt-2">
                                <div
                                    v-for="(tech, index) in form.technologies"
                                    :key="index"
                                    class="flex items-center gap-1 bg-secondary text-secondary-foreground px-2 py-1 rounded-md text-sm"
                                >
                                    <span>{{ tech }}</span>
                                    <button
                                        type="button"
                                        @click="removeTechnology(index)"
                                        class="text-muted-foreground hover:text-foreground"
                                    >
                                        <X class="h-3 w-3" />
                                    </button>
                                </div>
                            </div>
                            <p v-if="form.errors.technologies" class="text-sm text-red-500">
                                {{ form.errors.technologies }}
                            </p>
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
                            <Label for="status">Published</Label>
                        </div>

                        <div class="flex gap-4">
                            <Button type="submit" :disabled="form.processing">
                                {{ form.processing ? 'Updating...' : 'Update Project' }}
                            </Button>
                            <Button type="button" variant="outline" as-child>
                                <a :href="route('portfolios.index')">Cancel</a>
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template> 