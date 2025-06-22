<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import AuthCardLayout from '@/layouts/auth/AuthCardLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { LoaderCircle, Mail, Lock, Eye, EyeOff } from 'lucide-vue-next';
import { ref } from 'vue';

defineProps<{
    canResetPassword?: boolean;
    status?: string;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const showPassword = ref(false);

const submit = () => {
    form.post(route('login'), {
        onFinish: () => {
            form.reset('password');
        },
    });
};
</script>

<template>
    <AuthCardLayout>
        <Head title="Log in" />

        <!-- Welcome Section -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold tracking-tight mb-2">Welcome back</h1>
            <p class="text-muted-foreground">Enter your credentials to access your account</p>
        </div>

        <!-- Status Message -->
        <div v-if="status" class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-700 dark:bg-green-900/20 dark:border-green-800 dark:text-green-400">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <!-- Email Field -->
            <div class="space-y-2">
                <Label for="email" class="text-sm font-medium">Email address</Label>
                <div class="relative">
                    <Mail class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                    <Input
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="pl-10 h-11"
                        placeholder="Enter your email"
                        required
                        autofocus
                        autocomplete="username"
                    />
                </div>
                <InputError class="mt-1" :message="form.errors.email" />
            </div>

            <!-- Password Field -->
            <div class="space-y-2">
                <Label for="password" class="text-sm font-medium">Password</Label>
                <div class="relative">
                    <Lock class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                    <Input
                        id="password"
                        v-model="form.password"
                        :type="showPassword ? 'text' : 'password'"
                        class="pl-10 pr-10 h-11"
                        placeholder="Enter your password"
                        required
                        autocomplete="current-password"
                    />
                    <button
                        type="button"
                        @click="showPassword = !showPassword"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-muted-foreground hover:text-foreground transition-colors"
                    >
                        <Eye v-if="!showPassword" class="h-4 w-4" />
                        <EyeOff v-else class="h-4 w-4" />
                    </button>
                </div>
                <InputError class="mt-1" :message="form.errors.password" />
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer">
                    <Checkbox v-model:checked="form.remember" name="remember" />
                    <span class="text-sm text-muted-foreground">Remember me</span>
                </label>
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-sm text-primary hover:text-primary/80 underline-offset-4 hover:underline transition-colors"
                >
                    Forgot password?
                </Link>
            </div>
            
            <!-- Submit Button -->
            <Button type="submit" class="w-full h-11 text-base font-medium" :disabled="form.processing">
                <LoaderCircle v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
                {{ form.processing ? 'Signing in...' : 'Sign in' }}
            </Button>

            <!-- Divider -->
            <!-- <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <span class="w-full border-t" />
                </div>
                <div class="relative flex justify-center text-xs uppercase">
                    <span class="bg-background px-2 text-muted-foreground">Or</span>
                </div>
            </div> -->

            <!-- Register Link -->
            <!-- <div class="text-center">
                <p class="text-sm text-muted-foreground">
                    Don't have an account? 
                    <Link 
                        :href="route('register')" 
                        class="text-primary hover:text-primary/80 underline-offset-4 hover:underline font-medium transition-colors"
                    >
                        Create one
                    </Link>
                </p>
            </div> -->
        </form>
    </AuthCardLayout>
</template>
