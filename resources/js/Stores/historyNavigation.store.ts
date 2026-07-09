import { useStorage } from '@vueuse/core'
import { defineStore } from 'pinia'

export const useHistoryNavigation = defineStore('History Navigation', () => {
    const histories = useStorage<string[]>('histories', ['form'], localStorage)

    function clearHistories(): void {
        histories.value = []
    }

    function newHistory(new_history: string): void {
        histories.value.push(new_history)
        if (histories.value.length > 10) {
            histories.value.shift()
        }
    }

    function goBack(): void {
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
