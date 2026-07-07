import { router } from '@inertiajs/vue3'
import { defineStore } from 'pinia'
import { ref } from 'vue'
import { useCameraStore } from './camera.store'

export const usePreviewPhotoStore = defineStore('Preview Photo', () => {
    const $cameraStore = useCameraStore()
    const photos = ref<{ file_location: string; id: number | string }[]>([])

    function initPhoto() {
        photos.value = []
    }

    function removePhoto(photo_id: number | string) {
        if (typeof photo_id === 'number') {
            router.delete(`/attachments/${photo_id}`, {
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    photos.value = photos.value.filter(
                        (item) => item.id !== photo_id,
                    )
                },
            })
        } else {
            photos.value = photos.value.filter((item) => item.id != photo_id)
            $cameraStore.taken_photos = $cameraStore.taken_photos.filter(
                (item) => item.id != photo_id,
            )
        }
    }

    return {
        photos,

        initPhoto,
        removePhoto,
    }
})
