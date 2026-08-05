<template>
    <div class="flex flex-col gap-4">
        <SearchCard
            :index_data_id="[]"
            v-model:search="search_params.search"
            no_print
            @search="getBiometricDeviceStatuses()"
        />

        <SearchResultSection
            :total="biometric_device_statuses.total"
            :searched="search_params.search"
        />

        <div
            class="flex flex-col gap-0 lg:grid lg:grid-cols-2 lg:gap-1 xl:grid-cols-3"
        >
            <BiometricDeviceStatusCard
                v-for="item in biometric_device_statuses.data"
                :key="item.id"
                :biometric_device_status="item"
                :dropdown_menu="dropdown_menu"
            />

            <div
                v-if="biometric_device_statuses.total === 0"
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
                :data="biometric_device_statuses"
                @paginationChangePage="getBiometricDeviceStatuses()"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import PaginationCard from '@/components/cards/PaginationCard.vue'
import SearchCard from '@/components/cards/SearchCard.vue'
import AppButton from '@/components/form/AppButton.vue'
import SearchResultSection from '@/components/data/SearchResultSection.vue'
import BiometricDeviceStatusCard from '@/components/data/BiometricDeviceStatusCard.vue'

import {
    BiometricDeviceStatus,
    DropdownMenuItem,
    Paginate,
} from '@/globalInterfaces'
import { usePromptModalStore } from '@/stores/promptModal.store'
import { router } from '@inertiajs/vue3'
import { reactive } from 'vue'

defineProps<{
    biometric_device_statuses: Paginate<BiometricDeviceStatus>
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
        callback: (biometric_device_status_id) =>
            removeBiometricDeviceStatus(biometric_device_status_id),
    },
]

function getBiometricDeviceStatuses(page = 1): void {
    router.get(
        route('dashboard.biometric-device-statuses.index'),
        { page, search: search_params.search },
        { preserveState: true, only: ['biometric_device_statuses'] },
    )
}

function resetSearch(): void {
    search_params.search = ''
    getBiometricDeviceStatuses(1)
}

function removeBiometricDeviceStatus(
    biometric_device_status_id: number | string,
): void {
    $promptModalStore.menu_items = [
        {
            name: 'Yes, Permanently remove',
            icon: 'mdi:trash-outline',
            color: 'danger',
            callback: function () {
                deleteBiometricDeviceStatus(biometric_device_status_id)
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

function deleteBiometricDeviceStatus(
    biometric_device_status_id: number | string,
): void {
    router.delete(
        route(
            'dashboard.biometric-device-statuses.destroy',
            biometric_device_status_id,
        ),
        {
            preserveState: true,
        },
    )
}
</script>
