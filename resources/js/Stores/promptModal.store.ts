import { BottomSheetData } from '@/globalInterfaces'
import { defineStore } from 'pinia'
import { ref } from 'vue'

export const usePromptModalStore = defineStore('PromptModal', () => {
    const menu_items = ref<BottomSheetData[]>([])

    function initMenuItems() {
        menu_items.value = []
    }

    return {
        menu_items,

        initMenuItems,
    }
})
