<script setup lang="ts">
import { ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { X, Upload, Image as ImageIcon } from 'lucide-vue-next';

interface Props {
    modelValue: string;
    label?: string;
    error?: string;
}

const props = withDefaults(defineProps<Props>(), {
    label: 'Featured Image',
});

const emit = defineEmits<{
    'update:modelValue': [value: string];
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

const setImage = (url: string) => {
    const absoluteUrl = ensureAbsoluteUrl(url.trim());
    emit('update:modelValue', absoluteUrl);
};

const addImageUrl = () => {
    if (newImageUrl.value.trim()) {
        setImage(newImageUrl.value);
        newImageUrl.value = '';
    }
};

const removeImage = () => {
    emit('update:modelValue', '');
};

const uploadImage = async (file: File) => {
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
            setImage(result.file.url);
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
        
        <!-- Current Image Preview -->
        <div v-if="modelValue" class="space-y-4">
            <div class="relative group max-w-md aspect-[4/3] rounded-lg overflow-hidden border bg-muted">
                <img
                    :src="modelValue"
                    :alt="'Featured image'"
                    class="w-full h-full object-cover"
                    @error="(event) => { const target = event.target as HTMLImageElement; if (target) target.style.display = 'none'; }"
                />
                <div class="absolute inset-0 bg-background/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                    <Button
                        type="button"
                        variant="destructive"
                        size="sm"
                        class="scale-90 opacity-0 group-hover:opacity-100 group-hover:scale-100 transition-all duration-300"
                        @click="removeImage"
                    >
                        <X class="h-4 w-4 mr-2" />
                        Remove Image
                    </Button>
                </div>
            </div>
        </div>

        <!-- Image Upload Area -->
        <div v-if="!modelValue" class="space-y-4">
            <!-- URL Input -->
            <div class="flex gap-2">
                <Input
                    v-model="newImageUrl"
                    type="url"
                    placeholder="Add image URL (e.g., https://example.com/image.jpg)"
                    @keyup.enter="addImageUrl"
                />
                <Button 
                    type="button" 
                    @click="addImageUrl" 
                    variant="outline"
                    :disabled="!newImageUrl.trim()"
                >
                    Add URL
                </Button>
            </div>

            <!-- File Upload Area -->
            <div
                class="border-2 border-dashed border-border rounded-lg p-6 text-center hover:border-border/80 transition-colors"
                @drop="handleDrop"
                @dragover="handleDragOver"
                :class="{ 'border-primary bg-primary/5': isUploading }"
            >
                <input
                    type="file"
                    accept="image/*"
                    @change="handleFileSelect"
                    class="hidden"
                    id="featured-image-upload"
                />
                <label for="featured-image-upload" class="cursor-pointer">
                    <Upload class="mx-auto h-10 w-10 text-muted-foreground mb-3" />
                    <p class="text-base font-medium text-foreground mb-2">
                        Upload Featured Image
                    </p>
                    <p class="text-sm text-muted-foreground mb-3">
                        <span class="font-medium text-primary hover:text-primary/80">
                            Click to upload
                        </span>
                        or drag and drop
                    </p>
                    <p class="text-xs text-muted-foreground">
                        PNG, JPG, GIF up to 10MB
                    </p>
                </label>
            </div>

            <!-- Upload Progress -->
            <div v-if="isUploading" class="text-center py-3">
                <div class="inline-flex items-center gap-2 text-sm text-primary">
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-primary"></div>
                    Uploading image...
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
                <li>Upload a high-quality image that represents your article</li>
                <li>Recommended size: 1200x630 pixels (16:9 aspect ratio)</li>
                <li>Drag and drop images directly into the upload area</li>
                <li>Or paste image URLs to add external images</li>
                <li>Supported formats: JPG, PNG, GIF (max 10MB)</li>
            </ul>
        </div>
    </div>
</template> 