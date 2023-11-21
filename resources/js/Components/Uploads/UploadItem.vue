<script setup>
import InputLabel from '../InputLabel.vue';
import TextInput from '../TextInput.vue';
import { useForm } from '@inertiajs/vue3'
import Textarea from '../Textarea.vue';
import PrimaryButton from '../PrimaryButton.vue';
import InputError from '../InputError.vue';

const props = defineProps({
    upload: Object
})

const form = useForm({
    title: props.upload.title,
    description: ''
})
</script>

<template>
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <div class="flex space-x-6">
            <div class="max-w-[240px] w-full space-y-3">

                <div class="space-y-1" v-if="upload.uploading">
                    <div class="bg-gray-100 shadow-inner h-3 rounded overflow-hidden">
                        <div class="bg-blue-500 h-full" v-bind:style="{ width: `${upload.uploadProgress}%` }"></div>
                    </div>
                    <div class="text-sm">
                        Uploading
                    </div>
                </div>

            </div>

            <form class="flex-grow space-y-6" v-on:submit.prevent="form.patch(route('videos.update', upload.id), { preserveScroll: true, preserveState: true })">
                <div>
                    <InputLabel for="title" value="Title" />
                    <TextInput id="title" type="text" class="mt-1 block w-full" v-model="form.title" />
                    <InputError class="mt-2" :message="form.errors.title" />
                </div>

                <div>
                    <InputLabel for="description" value="Description" />
                    <Textarea id="description" class="mt-1 block w-full" v-model="form.description" />
                    <InputError class="mt-2" :message="form.errors.description" />
                </div>

                <div class="flex items-center space-x-3">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Save
                    </PrimaryButton>
                    <div class="text-sm" v-if="form.recentlySuccessful">
                        Video updated
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</template>
