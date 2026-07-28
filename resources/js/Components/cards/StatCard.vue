<template>
    <div
        class="bg-white dark:bg-neutral-800 rounded-3xl p-4 border border-neutral-200 dark:border-neutral-700 flex flex-col gap-2"
    >
        <div class="flex items-center justify-between">
            <p
                class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-300"
            >
                {{ title }}
            </p>
            <Icon :icon="icon" class="text-neutral-500 dark:text-neutral-300" />
        </div>
        <p class="text-3xl font-bold text-neutral-800 dark:text-neutral-200">
            {{ formatNumber(this_month) }}
        </p>

        <p
            v-if="previous_month != undefined"
            class="text-xs"
            :class="deltaClass(this_month, previous_month)"
        >
            {{ deltaLabel(this_month, previous_month) }}
            vs previous month
        </p>
        <p v-else class="text-xs" :class="deltaClass(this_month)">total</p>
    </div>
</template>

<script setup lang="ts">
import { Icon } from '@iconify/vue'
import { formatNumber, deltaValue, deltaLabel } from '@/utils'

defineProps<{
    title: string
    icon: string
    this_month: number
    previous_month?: number
}>()

function deltaClass(current: number, previous?: number): string {
    const delta = previous !== undefined ? deltaValue(current, previous) : 0

    if (delta > 0) {
        return 'text-brand-700'
    }

    if (delta < 0) {
        return 'text-red-600'
    }

    return 'text-neutral-500'
}
</script>
