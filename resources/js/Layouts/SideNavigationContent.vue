<template>
    <!-- NOTE: TITLE -->
    <label
        class="ml-2 font-semibold text-neutral-600 dark:text-neutral-400 mb-8"
        >{{ title }}</label
    >
    <!-- NOTE: CONTENT -->
    <DataTransition>
        <Link
            v-for="(item, index) in data"
            :key="item.name"
            :href="item.href"
            v-if="page"
            @click="loadingAnimation(index)"
            :class="[
                item.components.some((row: string) => row === $page.component)
                    ? 'bg-neutral-100 dark:bg-brand-800 text-neutral-900 dark:text-brand-300 ring-neutral-200 dark:ring-brand-700 ring'
                    : 'text-neutral-700 dark:text-neutral-300 hover:bg-neutral-200 hover:dark:bg-neutral-900 hover:text-gray-900 hover:dark:text-neutral-300 hover:ring hover:ring-neutral-200 hover:dark:ring-neutral-700',
                'group flex flex-col px-2 py-2 text-sm font-medium rounded-2xl mb-1 pl-3',
            ]"
        >
            <!-- {{ item.name }} -->
            <div class="flex justify-between items-center">
                <div class="flex justify-start truncate">
                    <!-- NOTE IF LOADING -->
                    <Icon
                        v-if="index == index_loading"
                        icon="line-md:loading-twotone-loop"
                        :class="['mr-3 size-5']"
                        aria-hidden="true"
                    />
                    <!-- NOTE IF ACTIVE -->
                    <Icon
                        v-else-if="
                            item.components.some(
                                (row: string) => row === $page.component,
                            )
                        "
                        :icon="item.icon"
                        class="text-neutral-700 dark:text-brand-300 mr-3 size-5"
                        aria-hidden="true"
                    />
                    <!-- NOTE IF DEFAULT -->
                    <Icon
                        v-else
                        :icon="item.icon"
                        :class="[
                            'text-neutral-700 dark:text-neutral-300 group-hover:text-neutral-700 group-hover:dark:text-neutral-300',
                            'mr-3 size-5',
                        ]"
                        aria-hidden="true"
                    />
                    <div class="truncate">{{ item.name }}</div>
                </div>
                <div
                    v-if="item.count"
                    class="bg-rose-50 dark:bg-rose-950 rounded-full text-rose-800 dark:text-rose-300 px-2 text-xs ring ring-rose-100 dark:ring-rose-900"
                >
                    {{ item.count }}
                </div>
            </div>
        </Link>
    </DataTransition>
</template>

<script setup lang="ts">
import DataTransition from '@/components/transitions/DataTransition.vue'
import { Link } from '@inertiajs/vue3'

import { TopNavigation } from '@/globalInterfaces'
import { Icon } from '@iconify/vue'
import { router, usePage } from '@inertiajs/vue3'
import { ref } from 'vue'

defineProps<{
    title: string
    data: TopNavigation[]
}>()
const $model = defineModel<boolean>()
const page = usePage()

const index_loading = ref<number | null>(null)

router.on('finish', () => {
    index_loading.value = null
    $model.value = false
})

function loadingAnimation(index: number) {
    index_loading.value = index
}
</script>
