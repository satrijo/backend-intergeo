<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <div class="bg-white rounded-md p-1">
        <img src="/images/intergeo-logo-sm.png" alt="Intergeo Mitigasi Logo" :class="className" v-bind="$attrs" />
    </div>
</template>
