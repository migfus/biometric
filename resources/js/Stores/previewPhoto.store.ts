import { router } from "@inertiajs/vue3"
import { defineStore } from "pinia"
import { ref } from "vue"

export const usePreviewPhotoStore = defineStore('Preview Photo', () => {
    const photos = ref<{file_location: string, id: number | string}[]>([])

    function initPhoto() {
        photos.value = []
    }

    function removePhoto(photo_id: number | string) {
        if(typeof photo_id === 'number') {
            router.delete(`/attachments/${photo_id}`, { preserveState:true, preserveScroll: true})

            router.on('success', () => {
                photos.value = photos.value.filter(item => item.id !== photo_id)
            })
        }
        else {
            alert('photo_id is string ' + photo_id)
            return photo_id
        }
    }

    return {
        photos,

        initPhoto,
        removePhoto
    }
})
