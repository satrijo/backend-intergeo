<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import { type BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    homepageVariant: 'classic' | 'redesign';
}>();

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Homepage settings',
        href: '/settings/homepage',
    },
];

const form = useForm({
    homepage_variant: props.homepageVariant,
});

const submit = () => {
    form.put(route('homepage.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Homepage settings" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall title="Homepage display" description="Choose which homepage design visitors will see." />

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid gap-4">
                        <label
                            class="cursor-pointer rounded-lg border p-4 transition hover:bg-muted"
                            :class="form.homepage_variant === 'classic' ? 'border-blue-500 bg-blue-50 dark:bg-blue-950/30' : 'border-border'"
                        >
                            <div class="flex items-start gap-3">
                                <input v-model="form.homepage_variant" type="radio" value="classic" class="mt-1" />
                                <div>
                                    <div class="font-semibold">Classic / Existing</div>
                                    <p class="text-sm text-muted-foreground">Keep the current homepage design that is already live.</p>
                                </div>
                            </div>
                        </label>

                        <label
                            class="cursor-pointer rounded-lg border p-4 transition hover:bg-muted"
                            :class="form.homepage_variant === 'redesign' ? 'border-blue-500 bg-blue-50 dark:bg-blue-950/30' : 'border-border'"
                        >
                            <div class="flex items-start gap-3">
                                <input v-model="form.homepage_variant" type="radio" value="redesign" class="mt-1" />
                                <div>
                                    <div class="font-semibold">Redesign</div>
                                    <p class="text-sm text-muted-foreground">Use the new homepage layout with stronger hero, service highlights, process section, clients, and CTA.</p>
                                </div>
                            </div>
                        </label>
                    </div>

                    <div class="flex items-center gap-4">
                        <Button :disabled="form.processing">Save homepage setting</Button>
                        <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0" leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                            <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">Saved.</p>
                        </Transition>
                    </div>
                </form>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
