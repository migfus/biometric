import { defineStore } from "pinia"
import { ref } from "vue"

export const useHistoryNavigation = defineStore('History Navigation', () => {
    const histories = ref<string[]>(['form'])

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
        goBack
    }
})
