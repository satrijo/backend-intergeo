<script setup>
import { ref, defineProps } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  transparentBg: {
    type: Boolean,
    default: false
  }
});

const mobileMenuOpen = ref(false);

const navItems = [
  { name: "Home", href: route('home') },
  { name: "About", href: route('about') },
  { name: "Portfolio", href: route('portfolios') },
  { name: "Work Showcase", href: route('work-showcase') },
  { name: "Blog", href: route('blog') },
  { name: "FAQ", href: route('faq') },
  { name: "Contact", href: route('contact') },
];

const toggleMobileMenu = () => {
  mobileMenuOpen.value = !mobileMenuOpen.value;
};
</script>

<template>
  <nav class="relative z-20 px-6 py-4" :class="[transparentBg ? 'bg-transparent' : 'bg-blue-900 text-white shadow-md']">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
      <Link :href="route('home')" class="flex items-center space-x-2" :class="[transparentBg ? 'text-white' : '']">
        <div class="bg-white rounded-md p-1">
            <img src="/images/intergeo-logo-sm.png" alt="Intergeo Mitigasi Logo" class="h-6 w-6 object-contain" />
        </div>
        <span class="text-2xl font-bold">
          <span class="text-yellow-400">Intergeo</span> Mitigasi
        </span>
      </Link>
      <div class="hidden md:flex space-x-8">
        <Link
          v-for="item in navItems"
          :key="item.name"
          :href="item.href"
          class="hover:text-yellow-400 transition-colors"
          :class="[transparentBg ? 'text-white' : '']"
        >
          {{ item.name }}
        </Link>
      </div>
      <button @click="toggleMobileMenu" class="md:hidden text-white focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path v-if="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
          <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
    </div>
    <div v-if="mobileMenuOpen" class="md:hidden mt-3 space-y-2 px-2 pb-3 pt-2 bg-blue-800 rounded-md">
      <Link
        v-for="item in navItems"
        :key="item.name + '-mobile'"
        :href="item.href"
        class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-blue-700 hover:text-yellow-400 transition-colors"
        @click="() => { mobileMenuOpen.value = false; }"
      >
        {{ item.name }}
      </Link>
    </div>
  </nav>
</template>