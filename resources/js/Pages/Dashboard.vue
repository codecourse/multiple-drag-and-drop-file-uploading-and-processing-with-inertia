<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue'
import UploadItem from '@/Components/Uploads/UploadItem.vue'
import axios from 'axios'
import { createUpload } from '@mux/upchunk'

const uploads = ref([])
const filesBeingDragged = ref(false)

const getUploadById = (id) => {
    return uploads.value.find((upload) => upload.id === id)
}

const pauseUpload = (id) => {
    getUploadById(id).file.pause()
}

const resumeUpload = (id) => {
    getUploadById(id).file.resume()
}

const cancelUpload = (id) => {
    getUploadById(id).file.abort()

    router.delete(route('videos.destroy', id), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            uploads.value = uploads.value.filter(upload => upload.id !== id)
        }
    })
}

const startChunkedUpload = (file, id) => {
    const upload = createUpload({
        endpoint: route('videos.file.store', id),
        headers: {
            'X-CSRF-TOKEN': usePage().props.csrf_token
        },
        method: 'post',
        file: file,
        chunkSize: 30 * 1024 // 30mb
    })

    upload.on('progress', (p) => {
        getUploadById(id).uploadProgress = p.detail
    })

    upload.on('success', () => {
        getUploadById(id).uploading = false
    })

    return upload
}

const handleDroppedFiles = (files) => {
    Array.from(files).forEach((file) => {
        axios.post(route('videos.store'), {
            title: file.name
        }).then((response) => {
            uploads.value.unshift({
                id: response.data.id,
                title: file.name,
                file: startChunkedUpload(file, response.data.id),
                uploading: true,
                uploadProgress: 0
            })
        })
    })
}
</script>

<template>
    <Head title="Upload" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Upload</h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form class="p-6 text-gray-900">
                        <label for="file" class="flex items-center justify-center w-full h-44 bg-gray-100 border-2 border-dashed border-gray-200 text-gray-500 font-medium" v-on:dragover.prevent="filesBeingDragged = true" v-on:dragleave.prevent="filesBeingDragged = false" v-on:drop.prevent="handleDroppedFiles($event.dataTransfer.files); filesBeingDragged = false;" :class="{ 'bg-gray-200 border-gray-300': filesBeingDragged }">
                            Drop or click to upload files
                        </label>
                        <input type="file" id="file" class="sr-only" multiple v-on:change="handleDroppedFiles($event.target.files)">
                    </form>
                </div>

                <div class="space-y-3">
                    <UploadItem
                        v-for="upload in uploads"
                        :key="upload.id"
                        :upload="upload"
                        v-on:cancel="cancelUpload"
                        v-on:pause="pauseUpload"
                        v-on:resume="resumeUpload"
                    />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
