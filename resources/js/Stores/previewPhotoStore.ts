import { defineStore } from "pinia"
import { ref } from "vue"

export const usePreviewPhotoStore = defineStore('Preview Photo', () => {
    const photos = ref<string[]>([])

    function initPhoto() {
        photos.value = []
    }

    return {
        photos,

        initPhoto
    }
})
