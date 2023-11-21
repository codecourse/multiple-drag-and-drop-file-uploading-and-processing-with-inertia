<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue'
import UploadItem from '@/Components/Uploads/UploadItem.vue'

const uploads = ref([])

const handleDroppedFiles = (files) => {
    Array.from(files).forEach((file) => {
        uploads.value.unshift({
            id: 1,
            title: file.name,
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
                        <label for="file" class="flex items-center justify-center w-full h-44 bg-gray-100 border-2 border-dashed border-gray-200 text-gray-500 font-medium">
                            Drop or click to upload files
                        </label>
                        <input type="file" id="file" class="sr-only" multiple v-on:change="handleDroppedFiles($event.target.files)">
                    </form>
                </div>

                <div class="space-y-3">
                    <UploadItem v-for="upload in uploads" :key="upload.id" :upload="upload" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
