<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import Editor from '@tinymce/tinymce-vue';
import { Label } from '@/components/ui/label';
import { router } from '@inertiajs/vue3';

interface Props {
    modelValue: string;
    label?: string;
    placeholder?: string;
    error?: string;
    height?: number;
}

const props = withDefaults(defineProps<Props>(), {
    label: 'Content',
    placeholder: 'Write your content here...',
    height: 400,
});

const emit = defineEmits<{
    'update:modelValue': [value: string];
}>();

const editorRef = ref();
const isUploading = ref(false);

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

const init = {
    height: props.height,
    menubar: false,
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | blocks | ' +
        'bold italic forecolor | alignleft aligncenter ' +
        'alignright alignjustify | bullist numlist outdent indent | ' +
        'removeformat | image media | help',
    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
    placeholder: props.placeholder,
    images_upload_url: route('media.upload'),
    images_upload_handler: async (blobInfo: any, progress: any) => {
        isUploading.value = true;
        
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            const formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
            
            xhr.open('POST', route('media.upload'));
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '');
            xhr.setRequestHeader('Accept', 'application/json');
            
            xhr.onload = () => {
                isUploading.value = false;
                
                if (xhr.status === 200) {
                    try {
                        const result = JSON.parse(xhr.responseText);
                        if (result.success) {
                            resolve(ensureAbsoluteUrl(result.file.url));
                        } else {
                            reject(new Error(result.error || 'Upload failed'));
                        }
                    } catch (error) {
                        reject(new Error('Invalid JSON response'));
                    }
                } else {
                    reject(new Error(`Upload failed: ${xhr.status} ${xhr.statusText}`));
                }
            };
            
            xhr.onerror = () => {
                isUploading.value = false;
                reject(new Error('Network error'));
            };
            
            xhr.upload.onprogress = (e) => {
                if (e.lengthComputable) {
                    const percentComplete = (e.loaded / e.total) * 100;
                    progress(percentComplete);
                }
            };
            
            xhr.send(formData);
        });
    },
    file_picker_types: 'image media',
    file_picker_callback: (callback: any, value: any, meta: any) => {
        // Create file input
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', meta.filetype === 'image' ? 'image/*' : '*/*');
        
        input.onchange = () => {
            const file = input.files?.[0];
            if (!file) return;
            
            isUploading.value = true;
            
            const xhr = new XMLHttpRequest();
            const formData = new FormData();
            formData.append('file', file);
            
            xhr.open('POST', route('media.upload'));
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '');
            xhr.setRequestHeader('Accept', 'application/json');
            
            xhr.onload = () => {
                isUploading.value = false;
                
                if (xhr.status === 200) {
                    try {
                        const result = JSON.parse(xhr.responseText);
                        if (result.success) {
                            if (meta.filetype === 'image') {
                                callback(ensureAbsoluteUrl(result.file.url), { title: result.file.name });
                            } else {
                                callback(ensureAbsoluteUrl(result.file.url), { 
                                    title: result.file.name,
                                    text: result.file.name 
                                });
                            }
                        } else {
                            alert('Upload failed: ' + (result.error || 'Unknown error'));
                        }
                    } catch (error) {
                        alert('Upload failed: Invalid response');
                    }
                } else {
                    alert('Upload failed: ' + xhr.status + ' ' + xhr.statusText);
                }
            };
            
            xhr.onerror = () => {
                isUploading.value = false;
                alert('Upload failed: Network error');
            };
            
            xhr.send(formData);
        };
        
        input.click();
    },
    setup: (editor: any) => {
        editor.on('change', () => {
            emit('update:modelValue', editor.getContent());
        });
        
        editor.on('init', () => {
            editor.setContent(props.modelValue || '');
        });
    },
};

const handleChange = (content: string) => {
    emit('update:modelValue', content);
};
</script>

<template>
    <div class="space-y-2">
        <Label v-if="label">{{ label }}</Label>
        
        <div class="relative">
            <Editor
                api-key="0prkuct3mfhjno0p2upyihyysz3snx94w5gecw66smpq2epl"
                ref="editorRef"
                :init="init"
                @onChange="handleChange"
                :class="{ 'border-red-500': error }"
            />
            
            <!-- Upload Progress Indicator -->
            <div v-if="isUploading" class="absolute top-2 right-2 bg-blue-500 text-white px-2 py-1 rounded text-xs">
                Uploading...
            </div>
        </div>
        
        <p v-if="error" class="text-sm text-red-500">
            {{ error }}
        </p>
        
        <div class="text-xs text-muted-foreground">
            <p>💡 Tips:</p>
            <ul class="list-disc list-inside space-y-1 mt-1">
                <li>Drag and drop images directly into the editor</li>
                <li>Use the image/media button to upload files</li>
                <li>Supported formats: Images (JPG, PNG, GIF), Documents (PDF, DOC, DOCX)</li>
                <li>Maximum file size: 10MB</li>
            </ul>
        </div>
    </div>
</template>

<style scoped>
/* Custom styles for TinyMCE */
:deep(.tox-tinymce) {
    border-radius: 0.375rem;
    border-color: hsl(var(--input));
}

:deep(.tox-tinymce:focus-within) {
    border-color: hsl(var(--ring));
    outline: 2px solid hsl(var(--ring));
    outline-offset: 2px;
}

:deep(.tox-tinymce.border-red-500) {
    border-color: rgb(239 68 68);
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    :deep(.tox-tinymce) {
        background-color: hsl(var(--background));
        color: hsl(var(--foreground));
    }
}
</style> 