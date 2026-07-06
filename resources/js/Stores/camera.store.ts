import { CapturedPhoto } from '@/globalInterfaces'
import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useCameraStore = defineStore('Camera', () => {
    const taken_photos = ref<CapturedPhoto[]>([])

    return {
        taken_photos,
    }
})
