<template>
    <!-- NOTE: TITLE -->
    <label class="ml-2 font-semibold text-brand-200">{{ title }}</label>
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
                    ? 'bg-brand-200 text-brand-900 shadow'
                    : 'text-brand-100 hover:bg-brand-100 hover:text-gray-900',
                'group flex flex-col px-2 py-2 text-sm font-medium rounded-2xl mb-1 pl-3',
            ]"
        >
            <!-- {{ item.name }} -->
            <div class="flex justify-between">
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
                        class="text-brand-700 mr-3 size-5"
                        aria-hidden="true"
                    />
                    <!-- NOTE IF DEFAULT -->
                    <Icon
                        v-else
                        :icon="item.icon"
                        :class="[
                            'text-brand-100 group-hover:text-brand-700',
                            'mr-3 size-5',
                        ]"
                        aria-hidden="true"
                    />
                    <div class="truncate">{{ item.name }}</div>
                </div>
                <div
                    v-if="item.name == 'Dashboard' && 2 > 0"
                    class="bg-brand-50 rounded-full text-brand-800 px-2 group-hover:bg-brand-700 group-hover:text-brand-50 transition-all"
                >
                    2
                </div>
            </div>
        </Link>
    </DataTransition>
</template>

<script setup lang="ts">
import DataTransition from '@/Components/transitions/DataTransition.vue'
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
