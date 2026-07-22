<template>
    <div class="flex justify-between text-sm text-neutral-500 px-4 sm:px-0">
        <div class="flex gap-2 items-center">
            <p>Searched</p>
            <p class="font-semibold">{{ searched }}</p>
        </div>

        <Icon
            v-if="loading"
            icon="line-md:loading-twotone-loop"
            class="size-4"
        />

        <p>
            {{
                total > 1
                    ? `${formatNumber(total)} results`
                    : `${formatNumber(total)} result`
            }}
        </p>
    </div>
</template>

<script setup lang="ts">
import { Icon } from '@iconify/vue'

import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { formatNumber } from '@/utils'

defineProps<{
    total: number
    searched: string
}>()

const loading = ref(false)

router.on('start', () => {
    loading.value = true
})
router.on('finish', () => {
    loading.value = false
})
</script>
