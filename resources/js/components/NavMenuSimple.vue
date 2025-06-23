<script setup>
import {ArrowLeft} from "lucide-vue-next";
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <header class="bg-blue-900 text-white py-6">
    <div class="max-w-7xl mx-auto px-6">
      <div class="flex items-center justify-between">
        <Link :href="route('home')" class="text-2xl font-bold">
          <span class="text-yellow-400">Intergeo</span> Mitigasi
        </Link>
        <Link :href="route('home')">
          <button
              class="text-white hover:text-yellow-400 px-3 py-2 rounded-md text-sm font-medium flex items-center transition-colors"
          >
            <ArrowLeft class="w-4 h-4 mr-2 flex-shrink-0" />
            Kembali ke Beranda
          </button>
        </Link>
      </div>
    </div>
  </header>
</template>

<style scoped>

</style>