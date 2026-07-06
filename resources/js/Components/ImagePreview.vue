<template>
    <button class="relative group h-32 w-auto rounded-xl" type="button">
        <img
            :src="photo.preview"
            class="rounded-xl w-full h-full object-cover"
        />
    </button>
</template>

<script setup lang="ts">
import { CapturedPhoto } from '@/globalInterfaces'

defineProps<{
    photo: CapturedPhoto
}>()
</script>
