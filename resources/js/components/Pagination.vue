<template>
  <div v-if="links.length > 3" class="flex items-center justify-between">
    <div class="flex items-center gap-2">
      <p class="text-sm text-gray-700">
        Showing {{ from }} to {{ to }} of {{ total }} results
      </p>
    </div>
    
    <div class="flex items-center gap-1">
      <Button
        v-for="(link, index) in links"
        :key="index"
        :variant="link.active ? 'default' : 'outline'"
        :disabled="!link.url || link.url === '#'"
        @click="goToPage(link.url)"
        class="px-3 py-2 text-sm"
        :class="{
          'bg-blue-600 text-white': link.active,
          'opacity-50 cursor-not-allowed': !link.url || link.url === '#'
        }"
      >
        <span v-if="link.label === '&laquo; Previous'">Previous</span>
        <span v-else-if="link.label === 'Next &raquo;'">Next</span>
        <span v-else v-html="link.label"></span>
      </Button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'

interface Link {
  url: string | null
  label: string
  active: boolean
}

const props = defineProps<{
  links: Link[]
  total?: number
  perPage?: number
  currentPage?: number
}>()

const from = computed(() => {
  if (!props.currentPage || !props.perPage) return 0
  return (props.currentPage - 1) * props.perPage + 1
})

const to = computed(() => {
  if (!props.currentPage || !props.perPage || !props.total) return 0
  return Math.min(props.currentPage * props.perPage, props.total)
})

const total = computed(() => {
  return props.total || 0
})

const goToPage = (url: string | null) => {
  if (!url || url === '#') return
  
  // Extract page number from URL
  const urlParams = new URLSearchParams(url.split('?')[1])
  const page = urlParams.get('page')
  
  if (page) {
    router.get(window.location.pathname, { page }, {
      preserveState: true,
      preserveScroll: true,
    })
  }
}
</script> 