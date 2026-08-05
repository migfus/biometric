<template>
    <div>
        <ImageModal v-if="photos.length > 0" :photos />
        <div v-else class="flex flex-col gap-2 xl:grid xl:grid-cols-3">
            <div class="order-1 flex flex-col xl:order-2 xl:col-span-1">
                <BasicCard
                    title="Employee Information"
                    icon="ic:outline-people"
                >
                    <div class="flex flex-col gap-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="flex flex-col gap-1">
                                <p
                                    class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-300"
                                >
                                    Employee No.
                                </p>
                                <p
                                    class="text-sm font-semibold text-neutral-800 dark:text-neutral-200"
                                >
                                    {{ employee.id }}
                                </p>
                            </div>

                            <div class="flex flex-col gap-1">
                                <p
                                    class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-300"
                                >
                                    Full Name
                                </p>
                                <p
                                    class="text-sm font-semibold text-neutral-800 dark:text-neutral-200"
                                >
                                    {{ employee.full_name }}
                                </p>
                            </div>

                            <div class="flex flex-col gap-1">
                                <p
                                    class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-300"
                                >
                                    College or Department
                                </p>
                                <p
                                    class="text-sm font-semibold text-neutral-800 dark:text-neutral-200"
                                >
                                    {{
                                        employee.college?.name ??
                                        'No college or department'
                                    }}
                                </p>
                            </div>

                            <div class="flex flex-col gap-1">
                                <p
                                    class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-300"
                                >
                                    Office
                                </p>
                                <p
                                    class="text-sm font-semibold text-neutral-800 dark:text-neutral-200"
                                >
                                    {{ employee.office?.name ?? 'No office' }}
                                </p>
                            </div>

                            <div class="flex flex-col gap-1 sm:col-span-2">
                                <p
                                    class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-300"
                                >
                                    Email
                                </p>
                                <p
                                    class="text-sm font-semibold text-neutral-800 dark:text-neutral-200"
                                >
                                    {{ employee.email ?? 'No email' }}
                                </p>
                            </div>
                        </div>

                        <div
                            class="flex flex-col gap-2 sm:flex-row items-center justify-end"
                        >
                            <AppButton
                                :href="route('dashboard.employees.index')"
                                type="button"
                                icon="material-symbols:arrow-back"
                                class="w-full sm:w-auto"
                            >
                                Back
                            </AppButton>
                        </div>
                    </div>
                </BasicCard>
            </div>

            <div class="order-2 flex flex-col gap-2 xl:order-1 xl:col-span-2">
                <div class="flex flex-col gap-2">
                    <SearchCard
                        :index_data_id="[]"
                        v-model:search="query.search"
                        @print="print()"
                        @search="getChecks"
                    />
                </div>

                <DataTransition
                    v-if="checks.data.length > 0"
                    class="flex flex-col gap-2 lg:grid lg:grid-cols-2"
                >
                    <CheckCard
                        v-for="check in checks.data"
                        :key="check.id"
                        :check="check"
                        :dropdown_menu
                    />

                    <PaginationCard
                        :data="checks"
                        @paginationChangePage="getChecks"
                    />
                </DataTransition>

                <div
                    v-else
                    class="rounded-3xl border border-dashed border-neutral-300 p-6 text-center text-sm text-neutral-500"
                >
                    No checks found for this employee.
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'
import SearchCard from '@/components/cards/SearchCard.vue'
import CheckCard from '@/components/data/CheckCard.vue'
import AppButton from '@/components/form/AppButton.vue'
import ImageModal from '@/components/modals/ImageModal.vue'
import PaginationCard from '@/components/cards/PaginationCard.vue'
import DataTransition from '@/components/transitions/DataTransition.vue'

import { Check, DropdownMenuItem, Employee, Paginate } from '@/globalInterfaces'
import { usePreviewPhotoStore } from '@/stores/previewPhoto.store'
import { usePromptModalStore } from '@/stores/promptModal.store'
import { router } from '@inertiajs/vue3'
import { storeToRefs } from 'pinia'
import { reactive } from 'vue'

const { employee } = defineProps<{
    employee: Employee
    checks: Paginate<Check>
}>()

const query = reactive({
    search: '',
})

const $previewPhotoStore = usePreviewPhotoStore()
const { photos } = storeToRefs($previewPhotoStore)
const $promptModalStore = usePromptModalStore()

const dropdown_menu: DropdownMenuItem[] = [
    {
        name: 'Verify',
        icon: 'material-symbols:check-circle',
        color: '',
        callback: (check_id) => updateCheck(check_id),
    },
    {
        name: 'Unverify',
        icon: 'mdi:close-circle',
        color: 'danger',
        callback: (check_id) => updateCheck(check_id),
    },
    {
        name: 'Details',
        icon: 'mingcute:time-line',
        color: '',
        callback: function (check_id) {
            router.get(route('dashboard.checks.show', check_id))
        },
    },
    {
        name: 'Remove',
        icon: 'mdi:trash-outline',
        color: 'danger',
        callback: (check_id) => removeCheck(check_id),
    },
]

function removeCheck(check_id: number | string): void {
    $promptModalStore.menu_items = [
        {
            name: 'Yes, Permanently remove',
            icon: 'mdi:trash-outline',
            color: 'danger',
            callback: function () {
                deleteCheck(check_id)
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

function deleteCheck(check_id: number | string): void {
    router.delete(route('dashboard.checks.destroy', check_id), {
        preserveState: true,
    })
}

function updateCheck(check_id: number | string): void {
    router.put(
        route('dashboard.checks.update', check_id),
        {
            type: 'verify',
        },
        {
            preserveState: true,
            preserveScroll: true,
            only: ['checks'],
        },
    )
}

function getChecks(page = 1): void {
    router.get(
        route('dashboard.employees.show', employee.id),
        { page, search: query.search },
        { preserveState: true, only: ['checks'] },
    )
}

function print(): void {
    const params = new URLSearchParams({
        search: query.search,
    })

    window.location.href = `${route('dashboard.employees.showPrint', employee.id)}?${params.toString()}`
}
</script>
