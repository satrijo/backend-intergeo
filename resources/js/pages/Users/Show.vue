<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Edit, Calendar, Mail, User } from 'lucide-vue-next';

interface User {
    id: string;
    name: string;
    email: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

interface Props {
    user: User;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Users', href: '/dashboard/users' },
    { title: 'Detail', href: `/dashboard/users/${props.user.id}` },
];
</script>

<template>
    <Head :title="props.user.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center gap-4">
                <Button variant="ghost" size="sm" as-child>
                    <Link :href="route('dashboard.users.index')">
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        Back to Users
                    </Link>
                </Button>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold">{{ props.user.name }}</h1>
                    <p class="text-muted-foreground">User Details</p>
                </div>
                <Button as-child>
                    <Link :href="route('dashboard.users.edit', props.user.id)">
                        <Edit class="mr-2 h-4 w-4" />
                        Edit User
                    </Link>
                </Button>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <User class="h-5 w-5" />
                            User Information
                        </CardTitle>
                        <CardDescription>Basic user details</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Name</Label>
                            <p class="text-lg font-medium">{{ props.user.name }}</p>
                        </div>
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Email</Label>
                            <div class="flex items-center gap-2">
                                <Mail class="h-4 w-4 text-muted-foreground" />
                                <p class="text-sm">{{ props.user.email }}</p>
                            </div>
                        </div>
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Email Verification</Label>
                            <span
                                :class="[
                                    'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                                    props.user.email_verified_at
                                        ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
                                        : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'
                                ]"
                            >
                                {{ props.user.email_verified_at ? 'Verified' : 'Not Verified' }}
                            </span>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <Calendar class="h-5 w-5" />
                            Account Information
                        </CardTitle>
                        <CardDescription>Account creation and update details</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Created</Label>
                            <p class="text-sm">{{ new Date(props.user.created_at).toLocaleDateString() }}</p>
                        </div>
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Last Updated</Label>
                            <p class="text-sm">{{ new Date(props.user.updated_at).toLocaleDateString() }}</p>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template> 