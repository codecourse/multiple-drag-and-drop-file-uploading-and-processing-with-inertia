<script setup>
defineProps({
    upload: Object
})
</script>

<template>
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        {{ upload }}
    </div>
</div>
</template>
