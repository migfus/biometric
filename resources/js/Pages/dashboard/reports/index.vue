<template>
    <div class="flex flex-col gap-4">
        <SearchCard
            :index_data_id="[]"
            v-model:search="search_params.search"
            no_print
            @search="getChecks"
        />

        <SearchResultSection
            :total="reports.total"
            :searched="search_params.search"
        />

        <div
            class="flex flex-col gap-0 lg:grid lg:grid-cols-2 lg:gap-1 xl:grid-cols-3"
        >
            <ReportCard
                v-for="report in reports.data"
                :key="report.id"
                :report="report"
                :dropdown_menu="dropdown_menu"
            />

            <div
                v-if="reports.total === 0"
                class="border border-dashed border-neutral-300 text-center p-8 rounded-3xl m-4 text-neutral-500 flex flex-col gap-4 items-center"
            >
                <p class="text-sm">No reports found</p>
                <AppButton
                    v-if="search_params.search !== ''"
                    @click="resetSearch"
                    icon="ic:baseline-autorenew"
                >
                    Reset Search
                </AppButton>
            </div>

            <PaginationCard :data="reports" @paginationChangePage="getChecks" />
        </div>
    </div>
</template>

<script setup lang="ts">
import PaginationCard from '@/components/cards/PaginationCard.vue'
import SearchCard from '@/components/cards/SearchCard.vue'
import ReportCard from '@/components/data/ReportCard.vue'
import AppButton from '@/components/form/AppButton.vue'

import SearchResultSection from '@/components/data/SearchResultSection.vue'
import { DropdownMenuItem, Paginate, Report } from '@/globalInterfaces'
import { usePromptModalStore } from '@/stores/promptModal.store'
import { router } from '@inertiajs/vue3'
import { reactive } from 'vue'

defineProps<{
    reports: Paginate<Report>
}>()

const search_params = reactive({
    search: '',
})

const $promptModalStore = usePromptModalStore()

const dropdown_menu: DropdownMenuItem[] = [
    // {
    //     name: 'Details',
    //     icon: 'mingcute:time-line',
    //     color: '',
    //     callback: function (check_id) {
    //         router.get(route('dashboard.checks.show', check_id))
    //     },
    // },
    {
        name: 'Remove',
        icon: 'mdi:trash-outline',
        color: 'danger',
        callback: (report_type_id) => removeReportType(report_type_id),
    },
]

function getChecks(page = 1): void {
    router.get(
        route('dashboard.reports.index'),
        { page, search: search_params.search },
        { preserveState: true, only: ['reports'] },
    )
}

function resetSearch(): void {
    search_params.search = ''
    getChecks(1)
}

// function updateCheck(check_id: number | string): void {
//     router.put(
//         route('dashboard.check-status.update', check_id),
//         {
//             type: 'verify',
//             redirect: 'dashboard.check-status.index',
//         },
//         {
//             preserveState: true,
//             preserveScroll: true,
//             only: ['checks'],
//         },
//     )
// }

function removeReportType(report_type_id: number | string): void {
    $promptModalStore.menu_items = [
        {
            name: 'Yes, Permanently remove',
            icon: 'mdi:trash-outline',
            color: 'danger',
            callback: function () {
                deleteReportType(report_type_id)
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

function deleteReportType(report_type_id: number | string): void {
    router.delete(route('dashboard.report-types.destroy', report_type_id), {
        preserveState: true,
    })
}
</script>
