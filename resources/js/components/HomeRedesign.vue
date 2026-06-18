<script setup lang="ts">
import Footer from '@/components/Footer.vue';
import NavMenu from '@/components/NavMenu.vue';
import { Head, router } from '@inertiajs/vue3';
import { Activity, ArrowRight, CheckCircle, ClipboardCheck, Compass, Drill, MapPin, Phone, Radar, ShieldCheck, Users } from 'lucide-vue-next';

interface Client {
    id?: string;
    name: string;
    logo_url: string;
    website_url?: string | null;
}

defineProps<{
    clients: Client[];
}>();

const fallbackClients: Client[] = [
    { name: 'Pertamina', logo_url: 'https://surveyseismikgpr.com/storage/media/2025/08/6ca595e8-a7b0-4d0d-aff1-72ceafbbbc87.png' },
    { name: 'WIKA', logo_url: 'https://surveyseismikgpr.com/storage/media/2025/08/6a791828-6971-418b-843e-4e57c96eb984.png' },
    { name: 'MM2100', logo_url: 'https://surveyseismikgpr.com/storage/media/2025/08/87ba5de9-903c-4055-94b7-4470e4f51cf1.png' },
];

const services = [
    { icon: Radar, title: 'Survey Seismik & GPR', text: 'Deteksi utilitas, struktur tanah, rongga, dan kondisi bawah permukaan secara non-destruktif.' },
    { icon: Compass, title: 'Survey & Pemetaan', text: 'Pengukuran topografi, pemetaan area, dan dukungan data spasial untuk perencanaan proyek.' },
    { icon: Drill, title: 'Geoteknik & Tambang', text: 'Studi kelayakan, investigasi geologi, dan interpretasi data untuk keputusan teknis.' },
];

const process = [
    'Konsultasi kebutuhan survey',
    'Perencanaan metode & area kerja',
    'Akuisisi data lapangan',
    'Analisis, interpretasi, dan laporan',
];
</script>

<template>
    <Head>
        <title>PT. Intergeo Mitigasi - Survey Seismik, GPR & Geofisika</title>
        <meta name="description" content="Layanan survey seismik, GPR, georadar, pemetaan, dan studi geoteknik dengan tim profesional dan teknologi survey modern." />
    </Head>

    <div class="min-h-screen bg-slate-950 text-white">
        <section class="relative overflow-hidden bg-[radial-gradient(circle_at_top_left,#1d4ed8,transparent_38%),linear-gradient(135deg,#0f172a,#1e3a8a_55%,#0f172a)]">
            <div class="absolute inset-0 opacity-20" :style="{ backgroundImage: 'linear-gradient(rgba(255,255,255,.08) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.08) 1px, transparent 1px)', backgroundSize: '48px 48px' }"></div>
            <NavMenu :transparentBg="true" />

            <div class="relative mx-auto grid max-w-7xl gap-12 px-6 py-20 lg:grid-cols-[1.1fr_.9fr] lg:py-28">
                <div class="space-y-8">
                    <div class="inline-flex items-center rounded-full border border-white/20 bg-white/10 px-4 py-2 text-sm text-blue-100 backdrop-blur">
                        <Activity class="mr-2 h-4 w-4 text-yellow-400" />
                        Survey geofisika profesional di seluruh Indonesia
                    </div>

                    <div class="space-y-6">
                        <h1 class="text-4xl font-bold leading-tight md:text-6xl">
                            Data bawah permukaan yang <span class="text-yellow-400">akurat</span> untuk keputusan proyek yang tepat.
                        </h1>
                        <p class="max-w-2xl text-lg leading-relaxed text-blue-100 md:text-xl">
                            Intergeo Mitigasi membantu proyek konstruksi, infrastruktur, energi, dan tambang melalui survey Seismik, GPR, Georadar, pemetaan, serta analisis geoteknik berbasis data lapangan.
                        </p>
                    </div>

                    <div class="flex flex-col gap-4 sm:flex-row">
                        <button @click="router.visit('/contact')" class="inline-flex items-center justify-center rounded-lg bg-yellow-400 px-6 py-3 font-semibold text-blue-950 transition hover:bg-yellow-300">
                            Konsultasi Survey Gratis
                            <ArrowRight class="ml-2 h-5 w-5" />
                        </button>
                        <button @click="router.visit('/portfolio')" class="inline-flex items-center justify-center rounded-lg border border-white/40 px-6 py-3 font-semibold text-white transition hover:bg-white hover:text-blue-950">
                            Lihat Portfolio
                        </button>
                    </div>

                    <div class="grid max-w-2xl grid-cols-3 gap-4 pt-4">
                        <div class="rounded-xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                            <div class="text-2xl font-bold text-yellow-400">50+</div>
                            <div class="text-sm text-blue-100">Proyek Survey</div>
                        </div>
                        <div class="rounded-xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                            <div class="text-2xl font-bold text-yellow-400">7+</div>
                            <div class="text-sm text-blue-100">Tahun Pengalaman</div>
                        </div>
                        <div class="rounded-xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                            <div class="text-2xl font-bold text-yellow-400">24/7</div>
                            <div class="text-sm text-blue-100">Support Survey</div>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div class="rounded-3xl border border-white/15 bg-white/10 p-5 shadow-2xl backdrop-blur">
                        <div class="overflow-hidden rounded-2xl bg-slate-900">
                            <iframe
                                src="https://www.youtube.com/embed/3ofooDdGXKI?rel=0&modestbranding=1&showinfo=0"
                                title="Survey Seismik dan GPR Equipment"
                                class="h-72 w-full md:h-96"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen
                            ></iframe>
                        </div>
                        <div class="mt-5 grid gap-3 text-sm text-blue-100">
                            <div class="flex items-center gap-3"><MapPin class="h-5 w-5 text-yellow-400" />Survey di seluruh Indonesia</div>
                            <div class="flex items-center gap-3"><Phone class="h-5 w-5 text-yellow-400" />+62 858-8628-3668</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-white py-16 text-slate-900">
            <div class="mx-auto max-w-7xl px-6">
                <div class="mb-10 text-center">
                    <p class="font-semibold uppercase tracking-widest text-blue-600">Layanan Utama</p>
                    <h2 class="mt-3 text-3xl font-bold md:text-4xl">Solusi survey dari perencanaan hingga laporan teknis</h2>
                </div>
                <div class="grid gap-6 md:grid-cols-3">
                    <div v-for="service in services" :key="service.title" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                        <div class="mb-5 flex h-14 w-14 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                            <component :is="service.icon" class="h-7 w-7" />
                        </div>
                        <h3 class="text-xl font-bold">{{ service.title }}</h3>
                        <p class="mt-3 text-slate-600">{{ service.text }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-slate-50 py-16 text-slate-900">
            <div class="mx-auto grid max-w-7xl gap-10 px-6 lg:grid-cols-2 lg:items-center">
                <div>
                    <p class="font-semibold uppercase tracking-widest text-blue-600">Workflow</p>
                    <h2 class="mt-3 text-3xl font-bold md:text-4xl">Proses kerja yang jelas dan terukur</h2>
                    <p class="mt-5 text-lg text-slate-600">Setiap pekerjaan disusun mulai dari pemahaman kebutuhan, pemilihan metode survey, pengambilan data, hingga laporan yang mudah dipakai untuk pengambilan keputusan.</p>
                </div>
                <div class="space-y-4">
                    <div v-for="(item, index) in process" :key="item" class="flex items-center gap-4 rounded-xl bg-white p-4 shadow-sm">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-600 font-bold text-white">{{ index + 1 }}</div>
                        <div class="font-semibold">{{ item }}</div>
                        <CheckCircle class="ml-auto h-5 w-5 text-green-500" />
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-white py-14 text-slate-900">
            <div class="mx-auto max-w-7xl px-6 text-center">
                <p class="font-semibold uppercase tracking-widest text-blue-600">Our Clients</p>
                <h2 class="mt-3 text-3xl font-bold">Dipercaya oleh berbagai klien dan industri</h2>
                <div class="mt-8 flex flex-wrap items-center justify-center gap-5 rounded-2xl border bg-slate-50 p-6">
                    <component
                        :is="client.website_url ? 'a' : 'div'"
                        v-for="client in (clients.length ? clients : fallbackClients)"
                        :key="client.id || client.name"
                        :href="client.website_url || undefined"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="flex h-16 min-w-36 items-center justify-center rounded-xl bg-white px-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md"
                    >
                        <img :src="client.logo_url" :alt="client.name" class="max-h-10 max-w-32 object-contain" />
                    </component>
                </div>
            </div>
        </section>

        <section class="bg-blue-900 py-16 text-white">
            <div class="mx-auto flex max-w-7xl flex-col gap-6 px-6 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="flex items-center gap-2 text-yellow-400"><ShieldCheck class="h-5 w-5" /> Siap membantu kebutuhan survey Anda</p>
                    <h2 class="mt-3 text-3xl font-bold">Diskusikan kebutuhan survey geofisika proyek Anda.</h2>
                </div>
                <button @click="router.visit('/contact')" class="rounded-lg bg-yellow-400 px-6 py-3 font-semibold text-blue-950 transition hover:bg-yellow-300">
                    Hubungi Tim Intergeo
                </button>
            </div>
        </section>

        <Footer />
    </div>
</template>
