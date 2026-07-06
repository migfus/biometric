<template>
    <div class="flex flex-col gap-2 mb-10">
        <div v-for="item in photos" :key="item.id" class="relative">
            <img :src="item.file_location" class="rounded-xl"/>
            <button v-if="photos.length > 1" @click="$previewPhotoStore.removePhoto(item.id);  " class="bg-red-50 text-red-700 absolute top-2 right-2 rounded-full p-1">
                <Icon icon="mdi:trash-outline" class="size-4" />
            </button>
        </div>

        <div class="flex flex-col gap-2">
            <AppButton icon="material-symbols:close" @click="$previewPhotoStore.initPhoto()" type="button">Close</AppButton>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppButton from '@/Components/form/AppButton.vue';
import { Icon } from '@iconify/vue';

import { usePreviewPhotoStore } from '@/Stores/previewPhotoStore'

defineProps<{
    photos: {
        file_location: string
        id: number | string
    }[]
}>()

const $previewPhotoStore = usePreviewPhotoStore()
</script>
