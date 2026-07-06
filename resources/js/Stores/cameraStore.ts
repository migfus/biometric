import { CapturedPhoto } from "@/globalInterfaces"
import { router } from "@inertiajs/vue3"
import { defineStore } from "pinia"
import { ref } from "vue"

export const useCameraStore = defineStore('Camera', () => {
    const taken_photos = ref<CapturedPhoto[]>([])

    return {
        taken_photos,
    }
})
