<script setup lang="ts">
import Footer from '@/components/Footer.vue';
import NavMenu from '@/components/NavMenu.vue';
import { Head, router } from '@inertiajs/vue3';
import {
    ArrowRight,
    Award,
    Building2,
    CheckCircle,
    ClipboardCheck,
    Compass,
    FileText,
    HardHat,
    Mail,
    MapPin,
    Phone,
    PlayCircle,
    Radar,
    Search,
    Send,
    Settings,
    Calculator,
    ShieldCheck,
    Users,
} from 'lucide-vue-next';
import { onMounted, onUnmounted, ref } from 'vue';

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
    {
        icon: Radar,
        title: 'Survey Seismik',
        description: 'Layanan survey seismik untuk analisis struktur bawah permukaan, deteksi reservoir, investigasi lapisan tanah, dan kebutuhan studi geoteknik dengan teknologi akuisisi data yang presisi.',
        points: ['Seismic Reflection', 'Seismic Refraction', 'Cross-hole Seismic'],
    },
    {
        icon: Search,
        title: 'Ground Penetrating Radar (GPR)',
        description: 'Deteksi utilitas bawah tanah, analisis struktur beton, identifikasi rongga, dan pemetaan objek bawah permukaan secara non-destruktif untuk area konstruksi maupun industri.',
        points: ['Deteksi Utilitas', 'Analisis Beton', 'Pemetaan Bawah Tanah'],
    },
    {
        icon: MapPin,
        title: 'Survey Georadar',
        description: 'Pemetaan geologi bawah permukaan, identifikasi anomali, deteksi air tanah, dan analisis struktur tanah untuk mendukung pekerjaan konstruksi, eksplorasi, dan mitigasi risiko.',
        points: ['Pemetaan Geologi', 'Deteksi Air Tanah', 'Analisis Struktur Tanah'],
    },
    {
        icon: Compass,
        title: 'Survey dan Pemetaan',
        description: 'Layanan survey topografi, batimetri, pemetaan geologi permukaan, dan pengukuran area untuk kebutuhan perencanaan proyek, desain teknis, dan dokumentasi lapangan.',
        points: ['Survey Topografi', 'Survey Batimetri', 'Pemetaan Geologi'],
    },
    {
        icon: HardHat,
        title: 'Studi Kelayakan Tambang',
        description: 'Analisis kelayakan tambang, studi geologi ekonomi, pemetaan potensi mineral, dan evaluasi teknis untuk membantu pengambilan keputusan pada industri pertambangan.',
        points: ['Analisis Kelayakan', 'Studi Geologi Ekonomi', 'Evaluasi Potensi Mineral'],
    },
    {
        icon: Settings,
        title: 'Survey Infrastruktur & Jembatan',
        description: 'Inspeksi kondisi fondasi jembatan, utilitas, struktur infrastruktur, dan pemantauan keamanan bangunan dengan pendekatan survey geofisika yang minim gangguan.',
        points: ['Inspeksi Fondasi', 'Analisis Struktur', 'Pemantauan Keamanan'],
    },
    {
        icon: FileText,
        title: 'Analisis Geoteknik',
        description: 'Studi kelayakan tanah, analisis stabilitas lereng, interpretasi kondisi bawah permukaan, serta rekomendasi teknis untuk pondasi dan perencanaan konstruksi.',
        points: ['Studi Kelayakan', 'Analisis Stabilitas', 'Rekomendasi Fondasi'],
    },
    {
        icon: Calculator,
        title: 'Interpretasi Data Survey',
        description: 'Pengolahan dan interpretasi data survey seismik, GPR, georadar, dan geolistrik menjadi laporan teknis yang ringkas, akurat, dan mudah digunakan oleh tim proyek.',
        points: ['Pengolahan Data', 'Interpretasi Hasil', 'Laporan Teknis'],
    },
    {
        icon: Users,
        title: 'Konsultasi Survey',
        description: 'Konsultasi lengkap untuk menentukan metode survey, kebutuhan data, cakupan area, estimasi pekerjaan, dan strategi implementasi survey geofisika di lapangan.',
        points: ['Perencanaan Survey', 'Pemilihan Metode', 'Implementasi Proyek'],
    },
];

const stats = [
    { icon: Users, number: '30+', label: 'Klien Survey' },
    { icon: ClipboardCheck, number: '50+', label: 'Proyek Selesai' },
    { icon: Award, number: '7+', label: 'Tahun Pengalaman' },
    { icon: ShieldCheck, number: '24/7', label: 'Support' },
];

const workflow = [
    'Konsultasi kebutuhan dan tujuan survey',
    'Pemilihan metode serta perencanaan area kerja',
    'Akuisisi data lapangan oleh tim teknis',
    'Interpretasi data dan penyusunan laporan teknis',
];

const servicesScroller = ref<HTMLElement | null>(null);
const latestVideos = ref<any[]>([]);
const latestPortfolios = ref<any[]>([]);
const contactSuccess = ref('');
const contactError = ref('');
const contactSubmitting = ref(false);
const contactForm = ref({
    full_name: '',
    email: '',
    phone_number: '',
    service_type: '',
    project_description: '',
});
let servicesInterval: number | undefined;

const stopServicesAutoScroll = () => {
    if (servicesInterval) {
        window.clearInterval(servicesInterval);
        servicesInterval = undefined;
    }
};

const startServicesAutoScroll = () => {
    stopServicesAutoScroll();

    servicesInterval = window.setInterval(() => {
        const el = servicesScroller.value;
        if (!el) return;

        const nextLeft = el.scrollLeft + 380;
        const nearEnd = nextLeft + el.clientWidth >= el.scrollWidth - 20;

        el.scrollTo({
            left: nearEnd ? 0 : nextLeft,
            behavior: 'smooth',
        });
    }, 3500);
};

const fetchHomepageContent = async () => {
    try {
        const [videosResponse, portfoliosResponse] = await Promise.all([
            fetch('/api/v1/video-portfolios?per_page=3'),
            fetch('/api/v1/portfolios?per_page=3'),
        ]);

        if (videosResponse.ok) {
            const videos = await videosResponse.json();
            latestVideos.value = videos.data || [];
        }

        if (portfoliosResponse.ok) {
            const portfolios = await portfoliosResponse.json();
            latestPortfolios.value = portfolios.data || [];
        }
    } catch (error) {
        console.error('Failed to fetch homepage content:', error);
    }
};

const submitContact = async () => {
    contactSubmitting.value = true;
    contactSuccess.value = '';
    contactError.value = '';

    try {
        const response = await fetch('/contact', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            },
            body: JSON.stringify(contactForm.value),
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Gagal mengirim permintaan konsultasi.');
        }

        contactSuccess.value = result.message || 'Permintaan konsultasi berhasil dikirim. Tim kami akan segera menghubungi Anda.';
        contactForm.value = { full_name: '', email: '', phone_number: '', service_type: '', project_description: '' };
    } catch (error) {
        contactError.value = error instanceof Error ? error.message : 'Gagal mengirim permintaan konsultasi.';
    } finally {
        contactSubmitting.value = false;
    }
};

onMounted(() => {
    startServicesAutoScroll();
    fetchHomepageContent();
});

onUnmounted(stopServicesAutoScroll);
</script>

<template>
    <Head>
        <title>PT. Intergeo Mitigasi - Jasa Survey Seismik, GPR & Georadar</title>
        <meta
            name="description"
            content="Jasa survey seismik, GPR, georadar, pemetaan, geoteknik, dan studi kelayakan tambang oleh PT. Intergeo Mitigasi."
        />
    </Head>

    <div class="min-h-screen bg-white text-gray-900 light-mode">
        <!-- Hero: tetap dekat dengan existing, tapi lebih rapi dan corporate -->
        <section class="relative overflow-hidden bg-gradient-to-br from-blue-950 via-blue-900 to-blue-700 text-white">
            <div
                class="absolute inset-0 opacity-10"
                :style="{ backgroundImage: 'radial-gradient(circle at 25px 25px, rgba(255,255,255,0.25) 2px, transparent 0)', backgroundSize: '50px 50px' }"
            ></div>

            <NavMenu :transparentBg="true" />

            <div class="relative z-10 mx-auto max-w-7xl px-6 py-14 lg:py-20">
                <div class="grid items-center gap-12 lg:grid-cols-2">
                    <div class="space-y-8">
                        <div class="inline-flex items-center rounded-full border border-white/20 bg-white/10 px-4 py-2 text-sm font-medium text-blue-100 backdrop-blur-sm">
                            <Building2 class="mr-2 h-4 w-4 text-yellow-400" />
                            PT. Intergeo Mitigasi • Survey Geofisika Profesional
                        </div>

                        <div class="space-y-5">
                            <h1 class="text-4xl font-bold leading-tight sm:text-5xl lg:text-6xl">
                                Jasa <span class="text-yellow-400">Survey Seismik, GPR & Georadar</span> Profesional
                            </h1>
                            <p class="max-w-2xl text-lg leading-relaxed text-blue-100 lg:text-xl">
                                Solusi survey bawah permukaan untuk deteksi utilitas, analisis struktur tanah, studi geoteknik,
                                pemetaan, dan kelayakan tambang dengan tim berpengalaman serta teknologi survey modern.
                            </p>
                        </div>

                        <div class="flex flex-col gap-4 sm:flex-row">
                            <button
                                @click="router.visit('/contact')"
                                class="inline-flex items-center justify-center rounded-md bg-yellow-400 px-7 py-3 text-lg font-semibold text-blue-950 transition hover:bg-yellow-300"
                            >
                                Konsultasi Survey Gratis
                                <ArrowRight class="ml-2 h-5 w-5" />
                            </button>
                            <button
                                @click="router.visit('/portfolio')"
                                class="inline-flex items-center justify-center rounded-md border border-white px-7 py-3 text-lg font-semibold text-white transition hover:bg-white hover:text-blue-900"
                            >
                                Lihat Proyek Survey
                            </button>
                        </div>

                        <div class="grid gap-3 pt-2 text-sm text-blue-100 sm:grid-cols-3">
                            <div class="flex items-center gap-2">
                                <MapPin class="h-5 w-5 text-yellow-400" />
                                Seluruh Indonesia
                            </div>
                            <a href="tel:+6285886283668" class="flex items-center gap-2 transition hover:text-yellow-300">
                                <Phone class="h-5 w-5 text-yellow-400" />
                                +62 858-8628-3668
                            </a>
                            <a href="mailto:intergeo.mitigasi@gmail.com" class="flex items-center gap-2 transition hover:text-yellow-300">
                                <Mail class="h-5 w-5 text-yellow-400" />
                                Email Kami
                            </a>
                        </div>
                    </div>

                    <div class="relative">
                        <div class="rounded-2xl border border-white/20 bg-white/10 p-5 shadow-2xl backdrop-blur-sm">
                            <div class="overflow-hidden rounded-xl bg-blue-950">
                                <iframe
                                    src="https://www.youtube.com/embed/3ofooDdGXKI?rel=0&modestbranding=1&showinfo=0"
                                    title="Survey Seismik dan GPR Equipment"
                                    class="h-72 w-full md:h-80"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen
                                ></iframe>
                            </div>
                            <div class="mt-5 grid grid-cols-2 gap-3">
                                <div v-for="stat in stats.slice(0, 2)" :key="stat.label" class="rounded-lg bg-white/10 p-4 text-center">
                                    <component :is="stat.icon" class="mx-auto mb-2 h-6 w-6 text-yellow-400" />
                                    <div class="text-2xl font-bold">{{ stat.number }}</div>
                                    <div class="text-xs text-blue-100">{{ stat.label }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Clients -->
                <div class="mt-12 flex flex-col items-center">
                    <h2 class="text-sm font-semibold uppercase tracking-widest text-blue-100">Our Clients</h2>
                    <div class="mt-4 flex max-w-4xl flex-wrap items-center justify-center gap-4 rounded-xl bg-white p-4 shadow-lg">
                        <component
                            :is="client.website_url ? 'a' : 'div'"
                            v-for="client in (clients.length ? clients : fallbackClients)"
                            :key="client.id || client.name"
                            :href="client.website_url || undefined"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="flex h-12 min-w-28 items-center justify-center rounded-md px-3 transition hover:scale-105"
                        >
                            <img :src="client.logo_url" :alt="client.name" class="max-h-10 max-w-32 object-contain" />
                        </component>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services -->
        <section class="bg-gray-50 py-20">
            <div class="mx-auto max-w-7xl px-6">
                <div class="mx-auto mb-10 max-w-3xl text-center">
                    <h2 class="text-4xl font-bold text-gray-900 lg:text-5xl">
                        Layanan <span class="text-blue-600">Survey Geofisika</span> Kami
                    </h2>
                    <p class="mt-5 text-xl leading-relaxed text-gray-600">
                        Solusi lengkap survey seismik, GPR, georadar, survey dan pemetaan, studi kelayakan tambang,
                        infrastruktur, analisis geoteknik, hingga interpretasi data teknis.
                    </p>
                    <p class="mt-3 text-sm font-medium text-blue-600">
                        Geser kartu layanan untuk melihat seluruh layanan kami →
                    </p>
                </div>

                <div
                    ref="servicesScroller"
                    class="-mx-6 overflow-x-auto px-6 pb-4 [scrollbar-width:thin]"
                    @mouseenter="stopServicesAutoScroll"
                    @mouseleave="startServicesAutoScroll"
                >
                    <div class="flex snap-x snap-mandatory gap-6">
                        <div
                            v-for="service in services"
                            :key="service.title"
                            class="group flex min-h-[360px] w-[310px] flex-none snap-start flex-col rounded-xl bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl md:w-[360px]"
                        >
                            <div class="mb-5 flex h-14 w-14 items-center justify-center rounded-full bg-blue-100 transition group-hover:bg-blue-600">
                                <component :is="service.icon" class="h-7 w-7 text-blue-600 transition group-hover:text-white" />
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-600">{{ service.title }}</h3>
                            <p class="mt-3 flex-1 text-sm leading-relaxed text-gray-600">{{ service.description }}</p>
                            <div class="mt-5 space-y-2 border-t border-gray-100 pt-4">
                                <div v-for="point in service.points" :key="point" class="flex items-center text-sm text-gray-500">
                                    <CheckCircle class="mr-2 h-4 w-4 flex-shrink-0 text-green-500" />
                                    {{ point }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Why choose us -->
        <section class="bg-white py-20">
            <div class="mx-auto grid max-w-7xl items-center gap-14 px-6 lg:grid-cols-2">
                <div class="space-y-6">
                    <h2 class="text-4xl font-bold text-gray-900 lg:text-5xl">
                        Mengapa Memilih <span class="text-blue-600">Intergeo Mitigasi?</span>
                    </h2>
                    <p class="text-lg leading-relaxed text-gray-600">
                        Kami memahami bahwa setiap proyek membutuhkan metode survey yang tepat. Karena itu, tim kami
                        membantu sejak tahap konsultasi awal, perencanaan metode, pengambilan data, hingga interpretasi teknis.
                    </p>

                    <div class="space-y-4">
                        <div v-for="item in workflow" :key="item" class="flex items-start gap-3">
                            <CheckCircle class="mt-1 h-6 w-6 flex-shrink-0 text-green-500" />
                            <span class="text-lg text-gray-700">{{ item }}</span>
                        </div>
                    </div>

                    <button
                        @click="router.visit('/contact')"
                        class="rounded-lg bg-blue-600 px-8 py-3 text-lg font-semibold text-white transition hover:bg-blue-700"
                    >
                        Diskusikan Kebutuhan Survey
                    </button>
                </div>

                <div class="relative">
                    <img src="/images/georadar.jpg" alt="Tim Survey Geofisika Intergeo Mitigasi" class="h-96 w-full rounded-2xl object-cover shadow-2xl" />
                    <div class="absolute -bottom-8 left-6 right-6 rounded-xl bg-white p-6 shadow-xl">
                        <div class="grid grid-cols-2 gap-5 sm:grid-cols-4">
                            <div v-for="stat in stats" :key="stat.label" class="text-center">
                                <component :is="stat.icon" class="mx-auto mb-2 h-7 w-7 text-blue-600" />
                                <div class="text-2xl font-bold text-gray-900">{{ stat.number }}</div>
                                <div class="text-xs text-gray-600">{{ stat.label }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Latest Videos -->
        <section class="bg-gray-50 py-20">
            <div class="mx-auto max-w-7xl px-6">
                <div class="mb-12 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                    <div>
                        <p class="font-semibold uppercase tracking-widest text-blue-600">Video Terbaru</p>
                        <h2 class="mt-3 text-4xl font-bold text-gray-900">Dokumentasi pekerjaan survey geofisika</h2>
                        <p class="mt-4 max-w-2xl text-lg text-gray-600">Lihat dokumentasi video dari proyek dan pekerjaan survey terbaru yang telah kami selesaikan.</p>
                    </div>
                    <button @click="router.visit('/work-showcase')" class="inline-flex items-center font-semibold text-blue-600 hover:text-blue-800">
                        Lihat Semua Video <ArrowRight class="ml-2 h-4 w-4" />
                    </button>
                </div>

                <div class="grid gap-6 md:grid-cols-3">
                    <div v-for="video in latestVideos" :key="video.id" class="overflow-hidden rounded-xl bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                        <div class="relative aspect-video bg-blue-950">
                            <img v-if="video.thumbnail_url" :src="video.thumbnail_url" :alt="video.title" class="h-full w-full object-cover" />
                            <div class="absolute inset-0 flex items-center justify-center bg-black/25">
                                <PlayCircle class="h-14 w-14 text-white" />
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="line-clamp-2 text-xl font-bold text-gray-900">{{ video.title }}</h3>
                            <p class="mt-3 line-clamp-3 text-sm leading-relaxed text-gray-600">{{ video.description }}</p>
                            <button @click="router.visit(`/work-showcase/${video.id}`)" class="mt-5 font-semibold text-blue-600 hover:text-blue-800">Lihat Video →</button>
                        </div>
                    </div>
                    <div v-if="latestVideos.length === 0" class="rounded-xl bg-white p-8 text-center text-gray-500 shadow-sm md:col-span-3">Belum ada video terbaru.</div>
                </div>
            </div>
        </section>

        <!-- Latest Portfolio -->
        <section class="bg-white py-20">
            <div class="mx-auto max-w-7xl px-6">
                <div class="mb-12 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                    <div>
                        <p class="font-semibold uppercase tracking-widest text-blue-600">Portfolio Terbaru</p>
                        <h2 class="mt-3 text-4xl font-bold text-gray-900">Bukti pengalaman kami di lapangan</h2>
                        <p class="mt-4 max-w-2xl text-lg text-gray-600">Beberapa proyek survey seismik, GPR, georadar, dan pemetaan yang telah kami kerjakan untuk berbagai kebutuhan industri.</p>
                    </div>
                    <button @click="router.visit('/portfolio')" class="inline-flex items-center font-semibold text-blue-600 hover:text-blue-800">
                        Lihat Semua Portfolio <ArrowRight class="ml-2 h-4 w-4" />
                    </button>
                </div>

                <div class="grid gap-6 md:grid-cols-3">
                    <div v-for="project in latestPortfolios" :key="project.id" class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                        <div class="aspect-video bg-gray-100">
                            <img v-if="project.image_url" :src="project.image_url" :alt="project.title" class="h-full w-full object-cover" />
                            <div v-else class="flex h-full items-center justify-center text-gray-400">No Image</div>
                        </div>
                        <div class="p-6">
                            <h3 class="line-clamp-2 text-xl font-bold text-gray-900">{{ project.title }}</h3>
                            <p class="mt-3 line-clamp-3 text-sm leading-relaxed text-gray-600" v-html="project.description"></p>
                            <div class="mt-4 flex flex-wrap gap-2">
                                <span v-for="tech in (project.technologies || []).slice(0, 3)" :key="tech" class="rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700">{{ tech }}</span>
                            </div>
                            <button @click="router.visit(`/portfolio/${project.id}`)" class="mt-5 font-semibold text-blue-600 hover:text-blue-800">Lihat Detail →</button>
                        </div>
                    </div>
                    <div v-if="latestPortfolios.length === 0" class="rounded-xl bg-gray-50 p-8 text-center text-gray-500 md:col-span-3">Belum ada portfolio terbaru.</div>
                </div>
            </div>
        </section>

        <!-- Contact Form -->
        <section class="bg-gray-50 py-20">
            <div class="mx-auto grid max-w-7xl gap-10 px-6 lg:grid-cols-[.9fr_1.1fr]">
                <div class="space-y-6">
                    <p class="font-semibold uppercase tracking-widest text-blue-600">Konsultasi Survey</p>
                    <h2 class="text-4xl font-bold text-gray-900">Siap Memulai Survey Geofisika Anda?</h2>
                    <p class="text-lg leading-relaxed text-gray-600">Isi form konsultasi dan tim kami akan menghubungi Anda untuk membahas kebutuhan survey, metode yang sesuai, serta estimasi pekerjaan.</p>
                    <div class="space-y-4 rounded-xl bg-blue-900 p-6 text-white">
                        <div class="flex items-center gap-3"><MapPin class="h-5 w-5 text-yellow-400" /> Jakarta Selatan, DKI Jakarta, Indonesia</div>
                        <div class="flex items-center gap-3"><Phone class="h-5 w-5 text-yellow-400" /> +62 858-8628-3668</div>
                        <div class="flex items-center gap-3"><Mail class="h-5 w-5 text-yellow-400" /> intergeo.mitigasi@gmail.com</div>
                    </div>
                </div>

                <form @submit.prevent="submitContact" class="rounded-2xl bg-white p-6 shadow-xl">
                    <div class="grid gap-4 md:grid-cols-2">
                        <input v-model="contactForm.full_name" required class="rounded-md border px-4 py-3" placeholder="Nama Lengkap *" />
                        <input v-model="contactForm.email" required type="email" class="rounded-md border px-4 py-3" placeholder="Email *" />
                        <input v-model="contactForm.phone_number" required class="rounded-md border px-4 py-3" placeholder="Nomor Telepon *" />
                        <select v-model="contactForm.service_type" class="rounded-md border px-4 py-3">
                            <option value="">Jenis Layanan Survey</option>
                            <option v-for="service in services" :key="service.title" :value="service.title">{{ service.title }}</option>
                        </select>
                    </div>
                    <textarea v-model="contactForm.project_description" required rows="5" class="mt-4 w-full rounded-md border px-4 py-3" placeholder="Deskripsi Proyek Survey Geofisika *"></textarea>
                    <button :disabled="contactSubmitting" class="mt-4 inline-flex w-full items-center justify-center rounded-md bg-blue-600 px-6 py-3 font-semibold text-white transition hover:bg-blue-700 disabled:opacity-60">
                        <Send class="mr-2 h-5 w-5" />
                        {{ contactSubmitting ? 'Mengirim...' : 'Kirim Permintaan Konsultasi' }}
                    </button>
                    <p v-if="contactSuccess" class="mt-4 rounded-md bg-green-50 p-3 text-sm text-green-700">{{ contactSuccess }}</p>
                    <p v-if="contactError" class="mt-4 rounded-md bg-red-50 p-3 text-sm text-red-700">{{ contactError }}</p>
                </form>
            </div>
        </section>

        <!-- CTA -->
        <section class="bg-blue-900 py-20 text-white">
            <div class="mx-auto max-w-7xl px-6 text-center">
                <FileText class="mx-auto mb-5 h-12 w-12 text-yellow-400" />
                <h2 class="text-4xl font-bold">Siap Memulai Survey Geofisika Anda?</h2>
                <p class="mx-auto mt-5 max-w-3xl text-xl leading-relaxed text-blue-100">
                    Hubungi kami untuk konsultasi awal. Tim Intergeo akan membantu memilih metode survey yang sesuai
                    dengan kebutuhan teknis dan kondisi proyek Anda.
                </p>
                <div class="mt-8 flex flex-col justify-center gap-4 sm:flex-row">
                    <button
                        @click="router.visit('/contact')"
                        class="rounded-lg bg-yellow-400 px-8 py-3 text-lg font-semibold text-blue-950 transition hover:bg-yellow-300"
                    >
                        Konsultasi Sekarang
                    </button>
                    <a
                        href="https://wa.me/6285886283668"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="rounded-lg border border-white px-8 py-3 text-lg font-semibold text-white transition hover:bg-white hover:text-blue-900"
                    >
                        WhatsApp
                    </a>
                </div>
            </div>
        </section>

        <Footer />
    </div>
</template>
