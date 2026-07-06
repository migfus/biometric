import { useStorage } from '@vueuse/core'
import { defineStore } from 'pinia'

export const useHistoryNavigation = defineStore('History Navigation', () => {
    const histories = useStorage<string[]>('histories', ['form'], localStorage)

    function clearHistories() {
        histories.value = []
    }

    function newHistory(new_history: string) {
        histories.value.push(new_history)
        if (histories.value.length > 10) {
            histories.value.shift()
        }
    }

    function goBack() {
        if (histories.value.length > 1) {
            histories.value.pop()
        }
    }

    return {
        histories,

        clearHistories,
        newHistory,
        goBack,
    }
})
