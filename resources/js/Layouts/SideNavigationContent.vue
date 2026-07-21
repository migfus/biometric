<template>
    <!-- NOTE: TITLE -->
    <label class="ml-2 font-semibold text-neutral-600 mb-8">{{ title }}</label>
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
                    ? 'bg-neutral-100 text-neutral-900 ring-neutral-200 ring'
                    : 'text-neutral-700 hover:bg-neutral-200 hover:text-gray-900 hover:ring hover:ring-neutral-200',
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
                        class="text-neutral-700 mr-3 size-5"
                        aria-hidden="true"
                    />
                    <!-- NOTE IF DEFAULT -->
                    <Icon
                        v-else
                        :icon="item.icon"
                        :class="[
                            'text-neutral-700 group-hover:text-neutral-700',
                            'mr-3 size-5',
                        ]"
                        aria-hidden="true"
                    />
                    <div class="truncate">{{ item.name }}</div>
                </div>
                <div
                    v-if="item.count"
                    class="bg-rose-50 rounded-full text-rose-800 px-2 text-xs ring ring-rose-100"
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
