import { BottomSheetData } from '@/globalInterfaces'
import { defineStore } from 'pinia'
import { ref } from 'vue'

export const usePromptModalStore = defineStore('PromptModal', () => {
    const menu_items = ref<BottomSheetData[]>([])
    const open_modal = ref<boolean>(false)

    function initMenuItems() {
        menu_items.value = []
    }

    return {
        menu_items,
        open_modal,

        initMenuItems,
    }
})
