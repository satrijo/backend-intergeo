<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

interface Category {
    id: string;
    name: string;
    color: string;
}

interface Props {
    modelValue: string[];
    categories: Category[];
    label?: string;
    placeholder?: string;
    error?: string;
}

const props = withDefaults(defineProps<Props>(), {
    label: 'Categories',
    placeholder: 'Select categories...',
});

const emit = defineEmits<{
    'update:modelValue': [value: string[]];
}>();

const isOpen = ref(false);
const searchTerm = ref('');

const filteredCategories = computed(() => {
    if (!searchTerm.value) return props.categories;
    return props.categories.filter(category =>
        category.name.toLowerCase().includes(searchTerm.value.toLowerCase())
    );
});

const selectedCategories = computed(() => {
    return props.categories.filter(category => props.modelValue.includes(category.id));
});

const toggleCategory = (categoryId: string) => {
    const newValue = [...props.modelValue];
    const index = newValue.indexOf(categoryId);
    
    if (index > -1) {
        newValue.splice(index, 1);
    } else {
        newValue.push(categoryId);
    }
    
    emit('update:modelValue', newValue);
};

const removeCategory = (categoryId: string) => {
    const newValue = props.modelValue.filter(id => id !== categoryId);
    emit('update:modelValue', newValue);
};

const clearAll = () => {
    emit('update:modelValue', []);
};

const selectAll = () => {
    const allIds = props.categories.map(cat => cat.id);
    emit('update:modelValue', allIds);
};
</script>

<template>
    <div class="space-y-2">
        <Label v-if="label">{{ label }}</Label>
        
        <!-- Selected Categories Display -->
        <div v-if="selectedCategories.length > 0" class="flex flex-wrap gap-2 mb-2">
            <div
                v-for="category in selectedCategories"
                :key="category.id"
                class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-sm font-medium"
                :style="{
                    backgroundColor: category.color + '20',
                    color: category.color,
                    border: '1px solid ' + category.color + '40'
                }"
            >
                <div
                    class="w-2 h-2 rounded-full"
                    :style="{ backgroundColor: category.color }"
                ></div>
                {{ category.name }}
                <button
                    type="button"
                    @click="removeCategory(category.id)"
                    class="ml-1 hover:bg-black/10 dark:hover:bg-white/10 rounded-full p-0.5"
                >
                    ×
                </button>
            </div>
        </div>

        <!-- Dropdown Trigger -->
        <div class="relative">
            <Button
                type="button"
                variant="outline"
                @click="isOpen = !isOpen"
                class="w-full justify-between"
                :class="{ 'border-red-500': error }"
            >
                <span v-if="selectedCategories.length === 0" class="text-muted-foreground">
                    {{ placeholder }}
                </span>
                <span v-else>
                    {{ selectedCategories.length }} category{{ selectedCategories.length !== 1 ? 'ies' : 'y' }} selected
                </span>
                <svg
                    class="h-4 w-4 transition-transform"
                    :class="{ 'rotate-180': isOpen }"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </Button>

            <!-- Dropdown Menu -->
            <div
                v-if="isOpen"
                class="absolute z-50 w-full mt-1 bg-background border border-border rounded-md shadow-lg max-h-60 overflow-auto"
            >
                <!-- Search Input -->
                <div class="p-2 border-b border-border">
                    <Input
                        v-model="searchTerm"
                        placeholder="Search categories..."
                        class="h-8"
                    />
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-1 p-2 border-b border-border">
                    <Button
                        type="button"
                        variant="outline"
                        size="sm"
                        @click="selectAll"
                        class="text-xs"
                    >
                        Select All
                    </Button>
                    <Button
                        type="button"
                        variant="outline"
                        size="sm"
                        @click="clearAll"
                        class="text-xs"
                    >
                        Clear All
                    </Button>
                </div>

                <!-- Category Options -->
                <div class="py-1">
                    <div
                        v-for="category in filteredCategories"
                        :key="category.id"
                        @click="toggleCategory(category.id)"
                        class="flex items-center gap-3 px-3 py-2 hover:bg-accent hover:text-accent-foreground cursor-pointer"
                        :class="{ 'bg-accent/50': modelValue.includes(category.id) }"
                    >
                        <input
                            type="checkbox"
                            :checked="modelValue.includes(category.id)"
                            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600"
                            readonly
                        />
                        <div
                            class="w-3 h-3 rounded-full"
                            :style="{ backgroundColor: category.color }"
                        ></div>
                        <span class="flex-1">{{ category.name }}</span>
                    </div>
                    
                    <div v-if="filteredCategories.length === 0" class="px-3 py-2 text-sm text-muted-foreground">
                        No categories found
                    </div>
                </div>
            </div>
        </div>

        <p v-if="error" class="text-sm text-red-500">
            {{ error }}
        </p>
    </div>
</template> 