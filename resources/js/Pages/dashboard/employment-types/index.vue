<template>
    <div class="flex flex-col gap-4">
        <SearchCard
            :index_data_id="[]"
            v-model:search="search_params.search"
            no_print
            @search="getEmploymentTypes()"
        />

        <SearchResultSection
            :total="employment_types.total"
            :searched="search_params.search"
        />

        <div
            class="flex flex-col gap-0 lg:grid lg:grid-cols-2 lg:gap-1 xl:grid-cols-3"
        >
            <EmploymentCard
                v-for="item in employment_types.data"
                :key="item.id"
                :employment_type="item"
                :dropdown_menu="dropdown_menu"
            />

            <div
                v-if="employment_types.total === 0"
                class="border border-dashed border-neutral-300 text-center p-8 rounded-3xl m-4 text-neutral-500 flex flex-col gap-4 items-center"
            >
                <p class="text-sm">No biometric device status found</p>
                <AppButton
                    v-if="search_params.search !== ''"
                    @click="resetSearch"
                    icon="ic:baseline-autorenew"
                >
                    Reset Search
                </AppButton>
            </div>

            <PaginationCard
                :data="employment_types"
                @paginationChangePage="getEmploymentTypes()"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import PaginationCard from '@/components/cards/PaginationCard.vue'
import SearchCard from '@/components/cards/SearchCard.vue'
import EmploymentCard from '@/components/data/EmploymentTypeCard.vue'
import SearchResultSection from '@/components/data/SearchResultSection.vue'
import AppButton from '@/components/form/AppButton.vue'

import { DropdownMenuItem, EmploymentType, Paginate } from '@/globalInterfaces'
import { usePromptModalStore } from '@/stores/promptModal.store'
import { router } from '@inertiajs/vue3'
import { reactive } from 'vue'

defineProps<{
    employment_types: Paginate<EmploymentType>
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
    //     callback: function (biometric_device_status_id) {
    //         router.get(
    //             route(
    //                 'dashboard.biometric-device-statuses.show',
    //                 biometric_device_status_id,
    //             ),
    //         )
    //     },
    // },
    {
        name: 'Remove',
        icon: 'mdi:trash-outline',
        color: 'danger',
        callback: (employment_type_id) =>
            removeEmploymentType(employment_type_id),
    },
]

function getEmploymentTypes(page = 1): void {
    router.get(
        route('dashboard.biometric-device-statuses.index'),
        { page, search: search_params.search },
        { preserveState: true, only: ['biometric_device_statuses'] },
    )
}

function resetSearch(): void {
    search_params.search = ''
    getEmploymentTypes(1)
}

function removeEmploymentType(employment_type_id: number | string): void {
    $promptModalStore.menu_items = [
        {
            name: 'Yes, Permanently remove',
            icon: 'mdi:trash-outline',
            color: 'danger',
            callback: function () {
                deleteEmploymentType(employment_type_id)
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

function deleteEmploymentType(employment_type_id: number | string): void {
    router.delete(
        route('dashboard.employment-types.destroy', employment_type_id),
        {
            preserveState: true,
        },
    )
}
</script>
