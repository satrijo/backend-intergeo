<script setup>
// Jika menggunakan Vue Router, pastikan sudah terkonfigurasi
// import { useRouter } from 'vue-router';
import Contact from '@/components/Contact.vue';
import NavSimple from '@/components/NavMenuSimple.vue';
</script>

<template>
    <div class="flex min-h-screen flex-col light-mode">
        <NavSimple />

        <Contact />
    </div>
</template>

<style scoped>
.animate-fade-in-down {
    animation: fadeInDown 0.3s ease-out;
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Force light mode for homepage */
.light-mode {
    color-scheme: light;
}
</style>
