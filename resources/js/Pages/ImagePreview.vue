<template>
    <div class="flex flex-col gap-4 relative">
        <div v-for="photo in image_preview" :key="photo.id" class="relative">
            <img
                :src="photo.preview"
                alt="Captured Photo"
                class="w-full h-full object-cover rounded-xl"
            />
            <button
                class="absolute right-2 top-2 bg-red-50 text-red-700/75 p-2 rounded-3xl backdrop-blur-xl"
                @click="$emit('removePhoto', photo.id)"
            >
                <Icon icon="mdi:trash-outline" class="size-6"></Icon>
            </button>
        </div>

        <div
            v-if="image_preview.length <= 0"
            class="border-2 border-dashed p-4 rounded-3xl text-center py-24 flex flex-col items-center gap-4"
        >
            No Image to display
            <AppButton
                @click="$emit('navigateTo', 'camera')"
                color="brand"
                icon="material-symbols:camera"
                >Get a new photo</AppButton
            >
        </div>

        <div class="flex gap-4 justify-end">
            <AppButton
                icon="material-symbols:close"
                type="button"
                @click="$emit('close')"
                >Close</AppButton
            >
        </div>
    </div>
</template>

<script setup lang="ts">
import AppButton from '@/Components/form/AppButton.vue'

import { CapturedPhoto } from '@/globalInterfaces'

defineProps<{
    image_preview: CapturedPhoto[]
}>()

const $emit = defineEmits(['removePhoto', 'close', 'navigateTo'])
</script>
