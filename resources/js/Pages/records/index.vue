<template>
    <div class="flex flex-col mb-16 md:mx-auto md:w-150 gap-2 mt-2">
        <h3 class="text-neutral-600 font-semibold mx-4 sm:mx-0">Records</h3>

        <DataTransition
            v-if="checks.data.length > 0"
            class="flex flex-col gap-2"
        >
            <CheckCard
                v-for="check in checks.data"
                :key="check.id"
                :check="check"
                no_address
            >
                <MenuItem class="flex items-center rounded-xl cursor-pointer">
                    <button
                        type="button"
                        @click="removeCheck(check.id)"
                        class="w-full text-left hover:bg-red-50 hover:text-red-700"
                    >
                        <div
                            :class="[
                                'px-4 py-2 text-sm text-brand-200 flex gap-2 items-center',
                            ]"
                        >
                            <Icon icon="mdi:trash-outline" />
                            <p>Remove</p>
                        </div>
                    </button>
                </MenuItem>
            </CheckCard>
        </DataTransition>
        <div
            v-if="checks.data.length === 0"
            class="text-sm text-neutral-500 text-center border border-dashed rounded-3xl p-8 flex justify-center items-center flex-col gap-4"
        >
            No records yet
            <AppButton :href="route('index')" color="brand">
                Start Now
            </AppButton>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppButton from '@/Components/form/AppButton.vue'
import DataTransition from '@/Components/transitions/DataTransition.vue'
import { MenuItem } from '@headlessui/vue'
import { Icon } from '@iconify/vue'

import CheckCard from '@/Components/data/CheckCard.vue'
import { Check, Pagination } from '@/globalInterfaces'
import { router } from '@inertiajs/vue3'
import { usePromptModalStore } from '@/Stores/promptModal.store'

const { checks } = defineProps<{
    checks: Pagination<Check>
}>()

const $promptModalStore = usePromptModalStore()

function removeCheck(check_id: number): void {
    $promptModalStore.menu_items = [
        {
            name: 'Yes, Remove',
            icon: 'mdi:trash-outline',
            color: 'danger',
            callback: function () {
                removeCheckData(check_id)
            },
        },
        {
            name: 'Cancel',
            icon: 'material-symbols:close',
            color: '',
            callback: function () {
                $promptModalStore.menu_items = []
            },
        },
    ]
}

function removeCheckData(id: number): void {
    router.delete(`/records/${id}`, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            checks.data = checks.data.filter((item) => item.id !== id)
        },
    })
}
</script>
