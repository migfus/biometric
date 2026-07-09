<template>
    <div
        class="flex flex-col gap-2 p-6 bg-white sm:rounded-3xl text-brand-200 border border-neutral-200"
    >
        <div class="flex flex-col gap-4">
            <div class="flex justify-center">
                <AppInput
                    v-model="$m_search"
                    name="Search"
                    color="white"
                    class="w-xl"
                    :loading
                    placeholder="Search Something"
                />
            </div>

            <div class="flex flex-col justify-between gap-2 sm:items-center">
                <!-- SECTION DESKTOP ACTIONS -->
                <div
                    class="gap-2 flex flex-col sm:flex-row md:justify-end md:w-xl"
                >
                    <AppButton
                        v-if="!no_print"
                        @click="$emit('print')"
                        icon="ic:baseline-local-printshop"
                    >
                        Print</AppButton
                    >
                    <AppButton
                        v-if="create"
                        :href="create"
                        icon="ic:baseline-plus"
                        color="brand"
                    >
                        {{ create_name }}</AppButton
                    >
                    <AppButton
                        @click="$emit('reset')"
                        icon="ic:baseline-autorenew"
                    >
                        Reset</AppButton
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppButton from '@/Components/form/AppButton.vue'
import AppInput from '@/Components/form/AppInput.vue'

import { useDebounceFn } from '@vueuse/core'
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'

import { SearchFilter } from '@/globalInterfaces'

const { create, create_name = 'Create' } = defineProps<{
    create?: string
    no_print?: boolean
    index_data_id: string[]
    create_name?: string
}>()

const $m_search = defineModel<string>('search')
const $m_start = defineModel<string>('start')
const $m_end = defineModel<string>('end')
const $m_search_filter = defineModel<SearchFilter>('search_filter')

const $emit = defineEmits([
    'search',
    'reset',
    'changeFilter',
    'create',
    'print',
    'selectAll',
    'deleteSelected',
])
const loading = ref(false)

const debounceFn = useDebounceFn((): void => {
    $emit('search', 1)
    loading.value = true
}, 500)

// SEARCH TRIGGER (debounce mode)
watch($m_search, () => {
    debounceFn()
})
// OTHER PARAMETER TRIGGERS
watch(
    () => [$m_start, $m_end, $m_search_filter],
    () => {
        $emit('search', 1)
    },
)

router.on('finish', () => {
    loading.value = false
})
</script>
