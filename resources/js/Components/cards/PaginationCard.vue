<template>
    <div class="flex gap-2 items-center justify-end mx-4">
        <Icon
            v-if="clicked"
            icon="line-md:loading-twotone-loop"
            class="size-8 text-neutral-100"
        />
        <TailwindPagination
            :data
            @paginationChangePage="(page: number) => changePagination(page)"
            :itemClasses="[
                'bg-white dark:bg-neutral-800 last:rounded-r-3xl first:rounded-l-3xl text-neutral-500 border border-neutral-300 dark:border-neutral-700 cursor-pointer',
            ]"
            :activeClasses="[
                'bg-neutral-100 dark:bg-neutral-700 text-neutral-400 hover:bg-brand-700 cursor-pointer shadow-none border border-neutral-300 dark:border-neutral-700',
            ]"
            :limit="1"
            keepLength
            class="remove_shadow"
            style="box-shadow: none"
        />
    </div>
</template>

<script setup lang="ts">
import { TailwindPagination } from 'laravel-vue-pagination'
import { Icon } from '@iconify/vue'

import { Paginate } from '@/globalInterfaces'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

defineProps<{
    data: Paginate<any>
}>()

const $emit = defineEmits(['paginationChangePage'])

const clicked = ref<boolean>(false)

function changePagination(page: number): void {
    clicked.value = true
    $emit('paginationChangePage', page)
}

router.on('finish', () => {
    clicked.value = false
})
</script>
