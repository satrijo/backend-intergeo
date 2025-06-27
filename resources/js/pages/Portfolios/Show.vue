<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Edit, ExternalLink, Calendar, User, Code } from 'lucide-vue-next';

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
    created_at: string;
    updated_at: string;
    user: {
        name: string;
    };
}

interface Props {
    portfolio: Portfolio;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Portfolios', href: '/dashboard/portfolios' },
    { title: 'Detail', href: `/dashboard/portfolios/${props.portfolio.id}` },
];
</script>

<template>
    <Head :title="portfolio.title" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="sm" as-child>
                        <a :href="route('dashboard.portfolios.index')">
                            <ArrowLeft class="mr-2 h-4 w-4" />
                            Back to Portfolios
                        </a>
                    </Button>
                    <div>
                        <h1 class="text-2xl font-bold">{{ portfolio.title }}</h1>
                        <p class="text-muted-foreground">Portfolio Project Details</p>
                    </div>
                </div>
                <Button as-child>
                    <Link :href="route('dashboard.portfolios.edit', portfolio.id)">
                        <Edit class="mr-2 h-4 w-4" />
                        Edit Project
                    </Link>
                </Button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Project Images Gallery -->
                    <Card v-if="portfolio.images && portfolio.images.length > 0">
                        <CardHeader>
                            <CardTitle>Project Images</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div
                                    v-for="(image, index) in portfolio.images"
                                    :key="index"
                                    class="aspect-video rounded-lg overflow-hidden border border-gray-200"
                                >
                                    <img
                                        :src="image"
                                        :alt="`${portfolio.title} - Image ${index + 1}`"
                                        class="w-full h-full object-cover hover:scale-105 transition-transform duration-200"
                                    />
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Project Description -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Project Description</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="prose prose-sm max-w-none" v-html="portfolio.description"></div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Project Status -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Project Status</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <Badge :variant="portfolio.status === 'published' ? 'default' : 'secondary'">
                                {{ portfolio.status === 'published' ? 'Published' : 'Draft' }}
                            </Badge>
                        </CardContent>
                    </Card>

                    <!-- Project Details -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Project Details</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="flex items-center gap-2">
                                <User class="h-4 w-4 text-muted-foreground" />
                                <div>
                                    <p class="text-sm font-medium">Created By</p>
                                    <p class="text-sm text-muted-foreground">{{ portfolio.user.name }}</p>
                                </div>
                            </div>

                            <div v-if="portfolio.client" class="flex items-center gap-2">
                                <User class="h-4 w-4 text-muted-foreground" />
                                <div>
                                    <p class="text-sm font-medium">Client</p>
                                    <p class="text-sm text-muted-foreground">{{ portfolio.client }}</p>
                                </div>
                            </div>

                            <div v-if="portfolio.project_date" class="flex items-center gap-2">
                                <Calendar class="h-4 w-4 text-muted-foreground" />
                                <div>
                                    <p class="text-sm font-medium">Project Date</p>
                                    <p class="text-sm text-muted-foreground">
                                        {{ new Date(portfolio.project_date).toLocaleDateString() }}
                                    </p>
                                </div>
                            </div>

                            <div v-if="portfolio.project_url" class="flex items-center gap-2">
                                <ExternalLink class="h-4 w-4 text-muted-foreground" />
                                <div>
                                    <p class="text-sm font-medium">Project URL</p>
                                    <a 
                                        :href="portfolio.project_url" 
                                        target="_blank" 
                                        rel="noopener noreferrer"
                                        class="text-sm text-blue-600 hover:underline"
                                    >
                                        Visit Project
                                    </a>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <div>
                                    <p class="text-sm font-medium">Sort Order</p>
                                    <p class="text-sm text-muted-foreground">{{ portfolio.sort_order }}</p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Technologies -->
                    <Card v-if="portfolio.technologies && portfolio.technologies.length > 0">
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <Code class="h-4 w-4" />
                                Technologies Used
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="flex flex-wrap gap-2">
                                <Badge 
                                    v-for="tech in portfolio.technologies" 
                                    :key="tech" 
                                    variant="outline"
                                >
                                    {{ tech }}
                                </Badge>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Project Info -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Project Info</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-muted-foreground">Created:</span>
                                <span>{{ new Date(portfolio.created_at).toLocaleDateString() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-muted-foreground">Last Updated:</span>
                                <span>{{ new Date(portfolio.updated_at).toLocaleDateString() }}</span>
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