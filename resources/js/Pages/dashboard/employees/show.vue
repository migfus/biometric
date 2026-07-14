<template>
    <div>
        <ImageModal v-if="photos.length > 0" :photos />
        <div v-else class="flex flex-col gap-4">
            <BasicCard title="Employee Information" icon="ic:outline-people">
                <div class="flex flex-col gap-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1">
                            <p
                                class="text-xs uppercase tracking-wide text-neutral-500"
                            >
                                Employee No.
                            </p>
                            <p class="text-sm font-semibold text-neutral-800">
                                {{ employee.id }}
                            </p>
                        </div>

                        <div class="flex flex-col gap-1">
                            <p
                                class="text-xs uppercase tracking-wide text-neutral-500"
                            >
                                Full Name
                            </p>
                            <p class="text-sm font-semibold text-neutral-800">
                                {{ employee.full_name }}
                            </p>
                        </div>

                        <div class="flex flex-col gap-1">
                            <p
                                class="text-xs uppercase tracking-wide text-neutral-500"
                            >
                                College or Department
                            </p>
                            <p class="text-sm font-semibold text-neutral-800">
                                {{
                                    employee.college?.name ??
                                    'No college or department'
                                }}
                            </p>
                        </div>

                        <div class="flex flex-col gap-1">
                            <p
                                class="text-xs uppercase tracking-wide text-neutral-500"
                            >
                                Office
                            </p>
                            <p class="text-sm font-semibold text-neutral-800">
                                {{ employee.office?.name ?? 'No office' }}
                            </p>
                        </div>

                        <div class="flex flex-col gap-1 sm:col-span-2">
                            <p
                                class="text-xs uppercase tracking-wide text-neutral-500"
                            >
                                Email
                            </p>
                            <p class="text-sm font-semibold text-neutral-800">
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

            <div class="flex flex-col gap-2">
                <SearchCard
                    :index_data_id="[]"
                    v-model:search="query.search"
                    no_print
                    @search="getChecks"
                />
            </div>

            <DataTransition
                v-if="checks.data.length > 0"
                class="flex flex-col gap-2 lg:grid lg:grid-cols-2 xl:grid-cols-3"
            >
                <CheckCard
                    v-for="check in checks.data"
                    :key="check.id"
                    :check="check"
                >
                    <MenuItem
                        v-slot="{ active }"
                        class="flex items-center rounded-xl cursor-pointer"
                    >
                        <button
                            v-if="check.verified_user"
                            @click="updateCheck(check.id)"
                            :class="[
                                active ? 'bg-red-50 text-red-700' : '',
                                'px-4 py-2 text-sm text-brand-200 flex hover:bg-red-100 dark:hover:bg-dark-003 gap-2 items-center w-full',
                            ]"
                        >
                            <Icon icon="mdi:close-circle" />
                            <p>Unverify</p>
                        </button>
                        <button
                            v-else
                            @click="updateCheck(check.id)"
                            :class="[
                                active ? 'bg-green-50 text-green-800' : '',
                                'px-4 py-2 text-sm text-brand-200 flex hover:bg-green-100 dark:hover:bg-dark-003 gap-2 items-center w-full',
                            ]"
                        >
                            <Icon icon="material-symbols:check-circle" />
                            <p>Verify</p>
                        </button>
                    </MenuItem>
                    <MenuItem
                        v-slot="{ active }"
                        class="flex items-center rounded-xl cursor-pointer"
                    >
                        <Link
                            :href="route('dashboard.checks.show', check.id)"
                            :class="[
                                active ? 'bg-neutral-50' : '',
                                'px-4 py-2 text-sm text-brand-200 flex hover:bg-neutral-200 dark:hover:bg-dark-003 gap-2 items-center',
                            ]"
                        >
                            <Icon icon="mingcute:time-line" />
                            <p>Details</p>
                        </Link>
                    </MenuItem>

                    <MenuItem
                        v-if="check.employee"
                        v-slot="{ active }"
                        class="flex items-center rounded-xl cursor-pointer"
                    >
                        <Link
                            :href="
                                route(
                                    'dashboard.employees.show',
                                    check.employee.id,
                                )
                            "
                            :class="[
                                active ? 'bg-neutral-50' : '',
                                'px-4 py-2 text-sm text-brand-200 flex hover:bg-neutral-200 dark:hover:bg-dark-003 gap-2 items-center',
                            ]"
                        >
                            <Icon icon="mingcute:user-4-line" />
                            <p>Employee</p>
                        </Link>
                    </MenuItem>

                    <MenuItem
                        class="flex items-center rounded-xl cursor-pointer"
                    >
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
</template>

<script setup lang="ts">
import BasicCard from '@/Components/cards/BasicCard.vue'
import SearchCard from '@/Components/cards/SearchCard.vue'
import CheckCard from '@/Components/data/CheckCard.vue'
import AppButton from '@/Components/form/AppButton.vue'
import ImageModal from '@/Components/modals/ImageModal.vue'
import { MenuItem } from '@headlessui/vue'
import { Icon } from '@iconify/vue'
import PaginationCard from '@/Components/cards/PaginationCard.vue'
import DataTransition from '@/Components/transitions/DataTransition.vue'

import { Check, Employee, Paginate } from '@/globalInterfaces'
import { usePreviewPhotoStore } from '@/Stores/previewPhoto.store'
import { usePromptModalStore } from '@/Stores/promptModal.store'
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

function removeCheck(check_id: number): void {
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

function deleteCheck(check_id: number): void {
    router.delete(route('dashboard.checks.destroy', check_id), {
        preserveState: true,
    })
}

function updateCheck(check_id: number): void {
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
</script>
