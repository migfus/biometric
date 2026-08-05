<template>
    <div class="flex flex-col gap-4">
        <SearchCard
            :index_data_id="[]"
            v-model:search="search_params.search"
            no_print
            @search="getChecks"
        />

        <SearchResultSection
            :total="check_statuses.total"
            :searched="search_params.search"
        />

        <div
            class="flex flex-col gap-0 lg:grid lg:grid-cols-2 lg:gap-1 xl:grid-cols-3"
        >
            <CheckStatusCard
                v-for="check in check_statuses.data"
                :key="check.id"
                :check_status="check"
                :dropdown_menu="dropdown_menu"
            />

            <div
                v-if="check_statuses.total === 0"
                class="border border-dashed border-neutral-300 text-center p-8 rounded-3xl m-4 text-neutral-500 flex flex-col gap-4 items-center"
            >
                <p class="text-sm">No checks statuses found</p>
                <AppButton
                    v-if="search_params.search !== ''"
                    @click="resetSearch"
                    icon="ic:baseline-autorenew"
                >
                    Reset Search
                </AppButton>
            </div>

            <PaginationCard
                :data="check_statuses"
                @paginationChangePage="getChecks"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import PaginationCard from '@/components/cards/PaginationCard.vue'
import SearchCard from '@/components/cards/SearchCard.vue'
import CheckStatusCard from '@/components/data/CheckStatusCard.vue'
import AppButton from '@/components/form/AppButton.vue'

import SearchResultSection from '@/components/data/SearchResultSection.vue'
import { CheckStatus, DropdownMenuItem, Paginate } from '@/globalInterfaces'
import { usePromptModalStore } from '@/stores/promptModal.store'
import { router } from '@inertiajs/vue3'
import { reactive } from 'vue'

defineProps<{
    check_statuses: Paginate<CheckStatus>
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
        callback: (check_id) => removeCheckStatus(check_id),
    },
]

function getChecks(page = 1): void {
    router.get(
        route('dashboard.check-status.index'),
        { page, search: search_params.search },
        { preserveState: true, only: ['checks'] },
    )
}

function resetSearch(): void {
    search_params.search = ''
    getChecks(1)
}

function updateCheck(check_id: number | string): void {
    router.put(
        route('dashboard.check-status.update', check_id),
        {
            type: 'verify',
            redirect: 'dashboard.check-status.index',
        },
        {
            preserveState: true,
            preserveScroll: true,
            only: ['checks'],
        },
    )
}

function removeCheckStatus(check_id: number | string): void {
    $promptModalStore.menu_items = [
        {
            name: 'Yes, Permanently remove',
            icon: 'mdi:trash-outline',
            color: 'danger',
            callback: function () {
                deleteCheckStatus(check_id)
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

function deleteCheckStatus(check_id: number | string): void {
    router.delete(route('dashboard.check-status.destroy', check_id), {
        preserveState: true,
    })
}
</script>
