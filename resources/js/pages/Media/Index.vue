<template>
  <AppLayout title="Manajemen Media">
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Manajemen Media
        </h2>
        <div class="flex items-center gap-4">
          <Button @click="showUploadDialog = true" variant="default">
            <Icon name="upload" class="w-4 h-4 mr-2" />
            Upload Media
          </Button>
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <!-- Search and Filter -->
            <div class="mb-6 flex items-center gap-4">
              <div class="flex-1">
                <Input
                  v-model="search"
                  placeholder="Cari file..."
                  class="max-w-sm"
                />
              </div>
              <div class="flex items-center gap-2">
                <Label for="type-filter">Tipe:</Label>
                <Select v-model="typeFilter">
                  <option value="">Semua</option>
                  <option value="image">Gambar</option>
                  <option value="document">Dokumen</option>
                </Select>
              </div>
            </div>

            <!-- Media Grid -->
            <div v-if="filteredFiles.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
              <div
                v-for="file in filteredFiles"
                :key="file.id"
                class="group relative bg-gray-50 rounded-lg overflow-hidden border hover:shadow-md transition-shadow"
              >
                <!-- Image Preview -->
                <div class="aspect-square bg-gray-100 flex items-center justify-center">
                  <img
                    v-if="file.is_image"
                    :src="file.full_url"
                    :alt="file.original_name"
                    class="w-full h-full object-cover"
                  />
                  <div v-else class="flex flex-col items-center justify-center p-4 text-gray-500">
                    <Icon name="file" class="w-12 h-12 mb-2" />
                    <span class="text-xs">{{ file.extension.toUpperCase() }}</span>
                  </div>
                </div>

                <!-- File Info -->
                <div class="p-3">
                  <h3 class="font-medium text-sm text-gray-900 truncate" :title="file.original_name">
                    {{ file.original_name }}
                  </h3>
                  <p class="text-xs text-gray-500 mt-1">
                    {{ file.human_size }} • {{ formatDate(file.created_at) }}
                  </p>
                  <p class="text-xs text-gray-400 mt-1">
                    Uploaded by {{ file.user?.name }}
                  </p>
                </div>

                <!-- Actions -->
                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                  <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                      <Button variant="secondary" size="sm" class="h-8 w-8 p-0">
                        <Icon name="more-horizontal" class="w-4 h-4" />
                      </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                      <DropdownMenuItem @click="copyUrl(file.full_url)">
                        <Icon name="copy" class="w-4 h-4 mr-2" />
                        Copy URL
                      </DropdownMenuItem>
                      <DropdownMenuItem @click="downloadFile(file)">
                        <Icon name="download" class="w-4 h-4 mr-2" />
                        Download
                      </DropdownMenuItem>
                      <DropdownMenuSeparator />
                      <DropdownMenuItem @click="confirmDelete(file)" class="text-red-600">
                        <Icon name="trash-2" class="w-4 h-4 mr-2" />
                        Delete
                      </DropdownMenuItem>
                    </DropdownMenuContent>
                  </DropdownMenu>
                </div>
              </div>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-12">
              <Icon name="image" class="w-16 h-16 mx-auto text-gray-300 mb-4" />
              <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada media</h3>
              <p class="text-gray-500 mb-4">Belum ada file yang diupload.</p>
              <Button @click="showUploadDialog = true" variant="default">
                Upload File Pertama
              </Button>
            </div>

            <!-- Pagination -->
            <div v-if="files.data && files.data.length > 0" class="mt-6">
              <Pagination 
                :links="files.links" 
                :total="files.total"
                :per-page="files.per_page"
                :current-page="files.current_page"
              />
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Upload Dialog -->
    <Dialog v-model:open="showUploadDialog">
      <DialogContent class="sm:max-w-md">
        <DialogHeader>
          <DialogTitle>Upload Media</DialogTitle>
          <DialogDescription>
            Pilih file yang ingin diupload. Maksimal 10MB per file.
          </DialogDescription>
        </DialogHeader>
        <div class="space-y-4">
          <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
            <input
              ref="fileInput"
              type="file"
              @change="handleFileUpload"
              accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.txt,.csv"
              class="hidden"
            />
            <div v-if="!uploading" @click="$refs.fileInput.click()" class="cursor-pointer">
              <Icon name="upload" class="w-12 h-12 mx-auto text-gray-400 mb-4" />
              <p class="text-gray-600">Klik untuk memilih file atau drag & drop</p>
              <p class="text-sm text-gray-500 mt-2">PNG, JPG, PDF, DOC, XLS hingga 10MB</p>
            </div>
            <div v-else class="flex items-center justify-center">
              <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
              <span class="ml-2 text-gray-600">Uploading...</span>
            </div>
          </div>
        </div>
        <DialogFooter>
          <Button variant="outline" @click="showUploadDialog = false">Cancel</Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>

    <!-- Delete Confirmation Dialog -->
    <Dialog v-model:open="showDeleteDialog">
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Konfirmasi Hapus</DialogTitle>
          <DialogDescription>
            Apakah Anda yakin ingin menghapus file "{{ fileToDelete?.original_name }}"? 
            Tindakan ini tidak dapat dibatalkan.
          </DialogDescription>
        </DialogHeader>
        <DialogFooter>
          <Button variant="outline" @click="showDeleteDialog = false">Cancel</Button>
          <Button variant="destructive" @click="deleteFile" :disabled="deleting">
            <div v-if="deleting" class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
            {{ deleting ? 'Deleting...' : 'Delete' }}
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Select } from '@/components/ui/select'
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog'
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuSeparator, DropdownMenuTrigger } from '@/components/ui/dropdown-menu'
import { Icon } from '@/components/Icon.vue'
import Pagination from '@/components/Pagination.vue'

interface MediaFile {
  id: string
  original_name: string
  filename: string
  mime_type: string
  extension: string
  size: number
  human_size: string
  full_url: string
  is_image: boolean
  created_at: string
  user: {
    name: string
  }
}

interface PaginatedData {
  data: MediaFile[]
  links: any[]
  current_page: number
  last_page: number
  per_page: number
  total: number
}

const props = defineProps<{
  files: PaginatedData
}>()

const search = ref('')
const typeFilter = ref('')
const showUploadDialog = ref(false)
const showDeleteDialog = ref(false)
const fileToDelete = ref<MediaFile | null>(null)
const uploading = ref(false)
const deleting = ref(false)

const filteredFiles = computed(() => {
  let files = props.files.data

  if (search.value) {
    files = files.filter(file => 
      file.original_name.toLowerCase().includes(search.value.toLowerCase())
    )
  }

  if (typeFilter.value) {
    if (typeFilter.value === 'image') {
      files = files.filter(file => file.is_image)
    } else if (typeFilter.value === 'document') {
      files = files.filter(file => !file.is_image)
    }
  }

  return files
})

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const handleFileUpload = async (event: Event) => {
  const target = event.target as HTMLInputElement
  const file = target.files?.[0]
  
  if (!file) return

  uploading.value = true
  
  const formData = new FormData()
  formData.append('file', file)

  try {
    const response = await fetch('/dashboard/media/upload', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
      },
      body: formData
    })

    if (response.ok) {
      showUploadDialog.value = false
      router.reload()
    } else {
      throw new Error('Upload failed')
    }
  } catch (error) {
    console.error('Upload error:', error)
    alert('Gagal mengupload file')
  } finally {
    uploading.value = false
    if (target) target.value = ''
  }
}

const confirmDelete = (file: MediaFile) => {
  fileToDelete.value = file
  showDeleteDialog.value = true
}

const deleteFile = async () => {
  if (!fileToDelete.value) return

  deleting.value = true

  try {
    const response = await fetch(`/dashboard/media/${fileToDelete.value.id}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
      }
    })

    if (response.ok) {
      showDeleteDialog.value = false
      fileToDelete.value = null
      router.reload()
    } else {
      throw new Error('Delete failed')
    }
  } catch (error) {
    console.error('Delete error:', error)
    alert('Gagal menghapus file')
  } finally {
    deleting.value = false
  }
}

const copyUrl = async (url: string) => {
  try {
    await navigator.clipboard.writeText(url)
    alert('URL berhasil disalin')
  } catch (error) {
    console.error('Copy failed:', error)
    alert('Gagal menyalin URL')
  }
}

const downloadFile = (file: MediaFile) => {
  const link = document.createElement('a')
  link.href = file.full_url
  link.download = file.original_name
  document.body.appendChild(link)
  link.click()
  document.body.removeChild(link)
}
</script> 