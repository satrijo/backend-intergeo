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
                <div class="h-12 w-12 rounded-md overflow-hidden bg-gray-100">
                  <img
                    v-if="file.is_image"
                    :src="file.full_url"
                    :alt="file.original_name"
                    class="h-full w-full object-cover"
                  />
                  <div v-else class="h-full w-full flex items-center justify-center text-gray-400">
                    <File class="w-6 h-6" />
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
                  {{ file.is_image ? 'Gambar' : 'Dokumen' }}
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
        <Image class="w-16 h-16 mx-auto text-gray-300 mb-4" />
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
              <Upload class="w-12 h-12 mx-auto text-gray-400 mb-4" />
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
import { Badge } from '@/components/ui/badge'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog'
import { Upload, File, Copy, Download, Trash2, Image } from 'lucide-vue-next'
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