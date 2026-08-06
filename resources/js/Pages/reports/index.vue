<template>
    <div class="flex flex-col md:mx-auto md:w-150 gap-2 mt-2">
        <h3
            class="text-neutral-600 dark:text-neutral-400 font-semibold px-4 md:px-0"
        >
            Records
        </h3>

        <DataTransition
            v-if="reports.data.length > 0"
            class="flex flex-col gap-2"
        >
            <ReportCard
                v-for="report in reports.data"
                :key="report.id"
                :report="report"
                no_address
                :dropdown_menu="dropdown_menu"
            >
            </ReportCard>
        </DataTransition>
        <div
            v-if="reports.data.length === 0"
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
import AppButton from '@/components/form/AppButton.vue'
import DataTransition from '@/components/transitions/DataTransition.vue'
import ReportCard from '@/components/data/ReportCard.vue'

import { Report, Pagination, DropdownMenuItem } from '@/globalInterfaces'
import { router } from '@inertiajs/vue3'
import { usePromptModalStore } from '@/stores/promptModal.store'

const { reports } = defineProps<{
    reports: Pagination<Report>
}>()

const $promptModalStore = usePromptModalStore()

const dropdown_menu: DropdownMenuItem[] = [
    {
        name: 'Remove',
        icon: 'mdi:trash-outline',
        color: 'danger',
        callback: function (check_id: number | string) {
            removeCheck(check_id)
        },
    },
]

function removeCheck(check_id: number | string): void {
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

function removeCheckData(id: number | string): void {
    router.delete(`/records/${id}`, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            reports.data = reports.data.filter((item) => item.id !== id)
        },
    })
}
</script>
