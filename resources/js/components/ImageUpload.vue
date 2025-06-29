<script setup lang="ts">
import { ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { X, Upload, Image as ImageIcon } from 'lucide-vue-next';

interface Props {
    modelValue: string[];
    label?: string;
    maxImages?: number;
    error?: string;
}

const props = withDefaults(defineProps<Props>(), {
    label: 'Images',
    maxImages: 5,
});

const emit = defineEmits<{
    'update:modelValue': [value: string[]];
}>();

const isUploading = ref(false);
const newImageUrl = ref('');

// Function to ensure URL is absolute
const ensureAbsoluteUrl = (url: string) => {
    if (url.startsWith('http://') || url.startsWith('https://')) {
        return url;
    }
    if (url.startsWith('/')) {
        return window.location.origin + url;
    }
    return window.location.origin + '/' + url;
};

const addImage = () => {
    if (newImageUrl.value.trim() && props.modelValue.length < props.maxImages) {
        const absoluteUrl = ensureAbsoluteUrl(newImageUrl.value.trim());
        if (!props.modelValue.includes(absoluteUrl)) {
            emit('update:modelValue', [...props.modelValue, absoluteUrl]);
            newImageUrl.value = '';
        }
    }
};

const removeImage = (index: number) => {
    const newImages = [...props.modelValue];
    newImages.splice(index, 1);
    emit('update:modelValue', newImages);
};

const uploadImage = async (file: File) => {
    if (props.modelValue.length >= props.maxImages) {
        alert(`Maximum ${props.maxImages} images allowed`);
        return;
    }

    isUploading.value = true;
    
    try {
        const formData = new FormData();
        formData.append('file', file);
        
        const response = await fetch(route('dashboard.media.upload'), {
            method: 'POST',
            body: formData,
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json',
            },
        });
        
        if (!response.ok) {
            throw new Error(`Upload failed: ${response.status} ${response.statusText}`);
        }
        
        const result = await response.json();
        
        if (result.success) {
            const absoluteUrl = ensureAbsoluteUrl(result.file.url);
            if (!props.modelValue.includes(absoluteUrl)) {
                emit('update:modelValue', [...props.modelValue, absoluteUrl]);
            }
        } else {
            throw new Error(result.error || 'Upload failed');
        }
    } catch (error) {
        console.error('Upload error:', error);
        alert('Upload failed: ' + (error instanceof Error ? error.message : 'Unknown error'));
    } finally {
        isUploading.value = false;
    }
};

const handleFileSelect = (event: Event) => {
    const input = event.target as HTMLInputElement;
    const file = input.files?.[0];
    if (file) {
        uploadImage(file);
    }
    // Reset input
    input.value = '';
};

const handleDrop = (event: DragEvent) => {
    event.preventDefault();
    const files = event.dataTransfer?.files;
    if (files && files.length > 0) {
        uploadImage(files[0]);
    }
};

const handleDragOver = (event: DragEvent) => {
    event.preventDefault();
};
</script>

<template>
    <div class="space-y-4">
        <Label v-if="label">{{ label }}</Label>
        
        <!-- Image Upload Area -->
        <div class="space-y-4">
            <!-- URL Input -->
            <div class="flex gap-2">
                <Input
                    v-model="newImageUrl"
                    type="url"
                    placeholder="Add image URL (e.g., https://example.com/image.jpg)"
                    @keyup.enter="addImage"
                    :disabled="modelValue.length >= maxImages"
                />
                <Button 
                    type="button" 
                    @click="addImage" 
                    variant="outline"
                    :disabled="!newImageUrl.trim() || modelValue.length >= maxImages"
                >
                    Add URL
                </Button>
            </div>

            <!-- File Upload Area -->
            <div
                class="border-2 border-dashed border-border rounded-lg p-4 text-center hover:border-border/80 transition-colors"
                @drop="handleDrop"
                @dragover="handleDragOver"
                :class="{ 'border-primary bg-primary/5': isUploading }"
            >
                <input
                    type="file"
                    accept="image/*"
                    @change="handleFileSelect"
                    class="hidden"
                    id="image-upload"
                    :disabled="modelValue.length >= maxImages"
                />
                <label for="image-upload" class="cursor-pointer">
                    <Upload class="mx-auto h-8 w-8 text-muted-foreground mb-2" />
                    <p class="text-sm text-muted-foreground">
                        <span class="font-medium text-primary hover:text-primary/80">
                            Click to upload
                        </span>
                        or drag and drop
                    </p>
                    <p class="text-xs text-muted-foreground mt-1">
                        PNG, JPG, GIF up to 10MB
                    </p>
                    <p class="text-xs text-muted-foreground">
                        {{ modelValue.length }}/{{ maxImages }} images
                    </p>
                </label>
            </div>

            <!-- Upload Progress -->
            <div v-if="isUploading" class="text-center py-2">
                <div class="inline-flex items-center gap-2 text-sm text-primary">
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-primary"></div>
                    Uploading image...
                </div>
            </div>
        </div>

        <!-- Image Preview Grid -->
        <div v-if="modelValue.length > 0" class="space-y-4">
            <Label>Project Images ({{ modelValue.length }}/{{ maxImages }})</Label>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <div
                    v-for="(image, index) in modelValue"
                    :key="index"
                    class="relative group aspect-square rounded-lg overflow-hidden border bg-muted"
                >
                    <img
                        :src="image"
                        :alt="`Project image ${index + 1}`"
                        class="w-full h-full object-cover"
                        @error="(event) => { const target = event.target as HTMLImageElement; if (target) target.style.display = 'none'; }"
                    />
                    <div class="absolute inset-0 bg-background/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                        <Button
                            type="button"
                            variant="destructive"
                            size="sm"
                            class="scale-90 opacity-0 group-hover:opacity-100 group-hover:scale-100 transition-all duration-300"
                            @click="removeImage(index)"
                        >
                            <X class="h-4 w-4" />
                        </Button>
                    </div>
                    <div class="absolute top-2 left-2 bg-black/50 text-white text-xs px-2 py-1 rounded">
                        {{ index + 1 }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Error Message -->
        <p v-if="error" class="text-sm text-red-500">
            {{ error }}
        </p>

        <!-- Help Text -->
        <div class="text-xs text-muted-foreground">
            <p>💡 Tips:</p>
            <ul class="list-disc list-inside space-y-1 mt-1">
                <li>You can upload up to {{ maxImages }} images per project</li>
                <li>Drag and drop images directly into the upload area</li>
                <li>Or paste image URLs to add external images</li>
                <li>Supported formats: JPG, PNG, GIF (max 10MB each)</li>
            </ul>
        </div>
    </div>
</template> 