<template>
    <div class="flex flex-col gap-2 mb-10 items-center">
        <div class="mb-5 flex flex-col gap-2">
            <div v-for="item in photos" :key="item.id" class="relative w-full">
                <img :src="item.file_location" class="w-full" />
                <div
                    class="pointer-events-none absolute inset-x-0 top-0 h-20 bg-linear-to-b from-black/50 to-transparent"
                />
                <div class="absolute top-2 left-2 text-white">
                    {{ moment(item.created_at).format('MMM DD, YYYY hh:mm A') }}
                </div>
                <button
                    v-if="photos.length > 1 || checkIfBlob(item.file_location)"
                    @click="$previewPhotoStore.removePhoto(item.id)"
                    class="bg-red-50 text-red-700 absolute top-2 right-2 rounded-full p-1"
                >
                    <Icon icon="mdi:trash-outline" class="size-6" />
                </button>
            </div>
        </div>

        <div
            class="fixed bottom-0 flex w-full flex-col gap-2 bg-linear-to-b from-neutral-100/0 to-black/70 p-4 sm:flex-row sm:justify-end"
        >
            <AppButton
                icon="material-symbols:close"
                @click="$previewPhotoStore.initPhoto()"
                type="button"
            >
                Close
            </AppButton>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppButton from '@/Components/form/AppButton.vue'
import { Icon } from '@iconify/vue'
import moment from 'moment'

import { usePreviewPhotoStore } from '@/Stores/previewPhoto.store'

defineProps<{
    photos: {
        file_location: string
        id: number | string
        created_at: string
    }[]
}>()

const $previewPhotoStore = usePreviewPhotoStore()

function checkIfBlob(file_location: string): boolean {
    return file_location.toLowerCase().includes('blob:')
}
</script>
