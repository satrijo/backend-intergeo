<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold">Media</h1>
          <p class="text-muted-foreground">Kelola file media Anda</p>
        </div>
        <Button @click="showUploadDialog = true" variant="default">
          <Upload class="w-4 h-4 mr-2" />
          Upload Media
        </Button>
      </div>

      <!-- Search and Filter -->
      <div class="flex items-center gap-4">
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

      <!-- Media Table -->
      <div v-if="filteredFiles.length > 0" class="rounded-md border">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>Preview</TableHead>
              <TableHead>Nama File</TableHead>
              <TableHead>Tipe</TableHead>
              <TableHead>Ukuran</TableHead>
              <TableHead>Uploaded By</TableHead>
              <TableHead>Tanggal Upload</TableHead>
              <TableHead class="text-right">Actions</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="file in filteredFiles" :key="file.id">
              <TableCell>
                <div class="h-16 w-16 rounded-md overflow-hidden bg-muted border cursor-pointer hover:shadow-md transition-shadow" @click="openPreview(file)">
                  <img
                    v-if="file.is_image"
                    :src="file.full_url"
                    :alt="file.original_name"
                    class="h-full w-full object-cover"
                  />
                  <div v-else class="h-full w-full flex flex-col items-center justify-center p-2" :class="getFileColor(file)">
                    <component :is="getFileIcon(file)" class="w-6 h-6 mb-1" />
                    <span class="text-xs text-center font-medium">{{ file.extension.toUpperCase() }}</span>
                  </div>
                </div>
              </TableCell>
              <TableCell>
                <div>
                  <div class="font-medium">{{ file.original_name }}</div>
                  <div class="text-sm text-muted-foreground">
                    {{ file.extension.toUpperCase() }} • {{ file.mime_type }}
                  </div>
                </div>
              </TableCell>
              <TableCell>
                <Badge :variant="file.is_image ? 'default' : 'secondary'">
                  {{ getFileTypeLabel(file) }}
                </Badge>
              </TableCell>
              <TableCell>
                <span class="text-sm">{{ file.human_size }}</span>
              </TableCell>
              <TableCell>
                <span v-if="file.user?.name" class="text-sm">
                  {{ file.user.name }}
                </span>
                <span v-else class="text-sm text-muted-foreground">-</span>
              </TableCell>
              <TableCell>
                <span class="text-sm">{{ formatDate(file.created_at) }}</span>
              </TableCell>
              <TableCell class="text-right">
                <div class="flex items-center justify-end gap-2">
                  <Button variant="ghost" size="sm" @click="openPreview(file)">
                    <Eye class="w-4 h-4" />
                  </Button>
                  <Button variant="ghost" size="sm" @click="copyUrl(file.full_url)">
                    <Copy class="w-4 h-4" />
                  </Button>
                  <Button variant="ghost" size="sm" @click="downloadFile(file)">
                    <Download class="w-4 h-4" />
                  </Button>
                  <Button 
                    variant="ghost" 
                    size="sm" 
                    @click="confirmDelete(file)"
                    class="text-red-600 hover:text-red-700 hover:bg-red-50"
                  >
                    <Trash2 class="w-4 h-4" />
                  </Button>
                </div>
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-12">
        <Image class="w-16 h-16 mx-auto text-muted-foreground mb-4" />
        <h3 class="text-lg font-medium mb-2">Tidak ada media</h3>
        <p class="text-muted-foreground mb-4">Belum ada file yang diupload.</p>
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
          <div 
            class="border-2 border-dashed rounded-lg p-6 text-center transition-colors"
            :class="[
              isDragOver 
                ? 'border-primary bg-primary/5' 
                : 'border-border hover:border-border/80'
            ]"
            @dragover="handleDragOver"
            @dragleave="handleDragLeave"
            @drop="handleDrop"
          >
            <input
              ref="fileInput"
              type="file"
              @change="handleFileUpload"
              accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.txt,.csv,.zip,.rar,.mp4,.mp3"
              class="hidden"
            />
            <div v-if="!uploading" @click="$refs.fileInput.click()" class="cursor-pointer">
              <Upload class="w-12 h-12 mx-auto text-muted-foreground mb-4" />
              <p class="text-foreground mb-2">Klik untuk memilih file atau drag & drop</p>
              <p class="text-sm text-muted-foreground">PNG, JPG, PDF, DOC, XLS, ZIP, MP4, MP3 hingga 10MB</p>
              <div v-if="isDragOver" class="mt-4 p-2 bg-primary/10 rounded text-primary text-sm">
                Lepas file di sini untuk upload
              </div>
            </div>
            <div v-else class="flex flex-col items-center justify-center">
              <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary mb-2"></div>
              <span class="text-foreground mb-2">Uploading... {{ uploadProgress }}%</span>
              <div class="w-full bg-muted rounded-full h-2">
                <div 
                  class="bg-primary h-2 rounded-full transition-all duration-300" 
                  :style="{ width: uploadProgress + '%' }"
                ></div>
              </div>
            </div>
          </div>
        </div>
        <DialogFooter>
          <Button variant="outline" @click="showUploadDialog = false">Cancel</Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>

    <!-- Preview Dialog -->
    <Dialog v-model:open="showPreviewDialog">
      <DialogContent class="sm:max-w-4xl max-h-[90vh] overflow-y-auto">
        <DialogHeader>
          <DialogTitle>{{ fileToPreview?.original_name }}</DialogTitle>
          <DialogDescription>
            {{ getFileTypeLabel(fileToPreview!) }} • {{ fileToPreview?.human_size }} • {{ fileToPreview?.mime_type }}
          </DialogDescription>
        </DialogHeader>
        <div class="space-y-4">
          <!-- Image Preview -->
          <div v-if="fileToPreview?.is_image" class="flex justify-center">
            <img
              :src="fileToPreview.full_url"
              :alt="fileToPreview.original_name"
              class="max-w-full max-h-96 object-contain rounded-lg border"
            />
          </div>
          
          <!-- Document Preview -->
          <div v-else class="flex flex-col items-center justify-center py-8">
            <div class="w-32 h-32 rounded-lg bg-muted border-2 border-dashed border-border flex flex-col items-center justify-center mb-4">
              <component :is="getFileIcon(fileToPreview!)" class="w-12 h-12 text-muted-foreground mb-2" />
              <span class="text-sm text-muted-foreground">{{ fileToPreview?.extension.toUpperCase() }}</span>
            </div>
            <div class="text-center">
              <h3 class="font-medium text-lg">{{ fileToPreview?.original_name }}</h3>
              <p class="text-sm text-muted-foreground mt-1">{{ getFileTypeLabel(fileToPreview!) }}</p>
              <p class="text-xs text-muted-foreground/70 mt-1">{{ fileToPreview?.human_size }} • {{ fileToPreview?.mime_type }}</p>
            </div>
          </div>

          <!-- File Details -->
          <div class="bg-muted/50 rounded-lg p-4 space-y-3">
            <h4 class="font-medium text-sm">Detail File</h4>
            <div class="grid grid-cols-2 gap-4 text-sm">
              <div>
                <span class="text-muted-foreground">Nama File:</span>
                <p class="font-medium">{{ fileToPreview?.original_name }}</p>
              </div>
              <div>
                <span class="text-muted-foreground">Tipe File:</span>
                <p class="font-medium">{{ getFileTypeLabel(fileToPreview!) }}</p>
              </div>
              <div>
                <span class="text-muted-foreground">Ukuran:</span>
                <p class="font-medium">{{ fileToPreview?.human_size }}</p>
              </div>
              <div>
                <span class="text-muted-foreground">Uploaded By:</span>
                <p class="font-medium">{{ fileToPreview?.user?.name || '-' }}</p>
              </div>
              <div>
                <span class="text-muted-foreground">Tanggal Upload:</span>
                <p class="font-medium">{{ fileToPreview ? formatDate(fileToPreview.created_at) : '-' }}</p>
              </div>
              <div>
                <span class="text-muted-foreground">MIME Type:</span>
                <p class="font-medium">{{ fileToPreview?.mime_type }}</p>
              </div>
            </div>
          </div>
        </div>
        <DialogFooter>
          <Button variant="outline" @click="showPreviewDialog = false">Close</Button>
          <Button @click="fileToPreview && copyUrl(fileToPreview.full_url)">
            <Copy class="w-4 h-4 mr-2" />
            Copy URL
          </Button>
          <Button @click="fileToPreview && downloadFile(fileToPreview)">
            <Download class="w-4 h-4 mr-2" />
            Download
          </Button>
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
import { Badge } from '@/components/ui/badge'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog'
import { Upload, File, Copy, Download, Trash2, Image, Eye, FileText, FileImage, FileVideo, FileAudio, FileArchive, FileCode } from 'lucide-vue-next'
import Pagination from '@/components/Pagination.vue'
import { type BreadcrumbItem } from '@/types'

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

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dashboard',
    href: '/dashboard',
  },
  {
    title: 'Media',
    href: '/dashboard/media',
  },
]

const search = ref('')
const typeFilter = ref('')
const showUploadDialog = ref(false)
const showDeleteDialog = ref(false)
const showPreviewDialog = ref(false)
const fileToDelete = ref<MediaFile | null>(null)
const fileToPreview = ref<MediaFile | null>(null)
const uploading = ref(false)
const deleting = ref(false)
const isDragOver = ref(false)
const uploadProgress = ref(0)

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

const getFileIcon = (file: MediaFile) => {
  if (file.is_image) return FileImage
  if (file.mime_type.includes('video')) return FileVideo
  if (file.mime_type.includes('audio')) return FileAudio
  if (file.mime_type.includes('zip') || file.mime_type.includes('rar') || file.mime_type.includes('tar')) return FileArchive
  if (file.mime_type.includes('text') || file.mime_type.includes('code')) return FileCode
  return FileText
}

const getFileTypeLabel = (file: MediaFile) => {
  if (file.is_image) return 'Gambar'
  if (file.mime_type.includes('video')) return 'Video'
  if (file.mime_type.includes('audio')) return 'Audio'
  if (file.mime_type.includes('pdf')) return 'PDF'
  if (file.mime_type.includes('word')) return 'Dokumen Word'
  if (file.mime_type.includes('excel') || file.mime_type.includes('spreadsheet')) return 'Spreadsheet'
  if (file.mime_type.includes('zip') || file.mime_type.includes('rar') || file.mime_type.includes('tar')) return 'Arsip'
  if (file.mime_type.includes('text') || file.mime_type.includes('code')) return 'Teks'
  return 'Dokumen'
}

const getFileColor = (file: MediaFile) => {
  if (file.is_image) return 'bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-400 border-green-200 dark:border-green-800'
  if (file.mime_type.includes('video')) return 'bg-purple-100 dark:bg-purple-900/20 text-purple-700 dark:text-purple-400 border-purple-200 dark:border-purple-800'
  if (file.mime_type.includes('audio')) return 'bg-yellow-100 dark:bg-yellow-900/20 text-yellow-700 dark:text-yellow-400 border-yellow-200 dark:border-yellow-800'
  if (file.mime_type.includes('pdf')) return 'bg-red-100 dark:bg-red-900/20 text-red-700 dark:text-red-400 border-red-200 dark:border-red-800'
  if (file.mime_type.includes('word')) return 'bg-blue-100 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 border-blue-200 dark:border-blue-800'
  if (file.mime_type.includes('excel') || file.mime_type.includes('spreadsheet')) return 'bg-emerald-100 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800'
  if (file.mime_type.includes('zip') || file.mime_type.includes('rar') || file.mime_type.includes('tar')) return 'bg-orange-100 dark:bg-orange-900/20 text-orange-700 dark:text-orange-400 border-orange-200 dark:border-orange-800'
  if (file.mime_type.includes('text') || file.mime_type.includes('code')) return 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700'
  return 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700'
}

const openPreview = (file: MediaFile) => {
  fileToPreview.value = file
  showPreviewDialog.value = true
}

const handleDragOver = (event: DragEvent) => {
  event.preventDefault()
  isDragOver.value = true
}

const handleDragLeave = (event: DragEvent) => {
  event.preventDefault()
  isDragOver.value = false
}

const handleDrop = (event: DragEvent) => {
  event.preventDefault()
  isDragOver.value = false
  
  const files = event.dataTransfer?.files
  if (files && files.length > 0) {
    const file = files[0]
    uploadFile(file)
  }
}

const uploadFile = async (file: File) => {
  // Validate file size (10MB = 10 * 1024 * 1024 bytes)
  const maxSize = 250 * 1024 * 1024
  if (file.size > maxSize) {
    alert('File terlalu besar. Maksimal ukuran file adalah 250MB.')
    return
  }

  uploading.value = true
  uploadProgress.value = 0
  
  const formData = new FormData()
  formData.append('file', file)

  try {
    const xhr = new XMLHttpRequest()
    
    xhr.upload.addEventListener('progress', (event) => {
      if (event.lengthComputable) {
        uploadProgress.value = Math.round((event.loaded / event.total) * 100)
      }
    })

    xhr.addEventListener('load', () => {
      if (xhr.status === 200) {
        showUploadDialog.value = false
        router.reload()
      } else {
        throw new Error('Upload failed')
      }
    })

    xhr.addEventListener('error', () => {
      throw new Error('Upload failed')
    })

    xhr.open('POST', '/dashboard/media/upload')
    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '')
    xhr.send(formData)

  } catch (error) {
    console.error('Upload error:', error)
    alert('Gagal mengupload file')
  } finally {
    uploading.value = false
    uploadProgress.value = 0
  }
}

const handleFileUpload = async (event: Event) => {
  const target = event.target as HTMLInputElement
  const file = target.files?.[0]
  
  if (!file) return

  await uploadFile(file)
  
  if (target) target.value = ''
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