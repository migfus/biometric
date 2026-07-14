import { CapturedPhoto } from '@/globalInterfaces'
import { defineStore } from 'pinia'
import { useStorage } from '@vueuse/core'

export const useCameraStore = defineStore('Camera', () => {
    const taken_photos = useStorage<CapturedPhoto[]>('taken_photos', [])

    return {
        taken_photos,
    }
})
