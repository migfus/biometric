<template>
    <div class="flex flex-col gap-4">
        <SearchCard
            :index_data_id="[]"
            v-model:search="search_params.search"
            no_print
            @search="getChecks"
        />

        <div
            class="flex flex-col gap-0 lg:grid lg:grid-cols-2 lg:gap-1 xl:grid-cols-3"
        >
            <!-- SECTION: UNVERIFIED -->
            <div class="flex flex-col gap-2">
                <h2 class="font-semibold text-neutral-500 px-4">Unverified</h2>

                <CheckCard
                    v-for="check in checks.unverified.data"
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

                <div
                    v-if="checks.unverified.total === 0"
                    class="border border-dashed border-neutral-300 text-center p-8 rounded-3xl m-4 text-neutral-500 flex flex-col gap-4 items-center"
                >
                    <p class="text-sm">No unverified checks found</p>
                    <AppButton
                        v-if="search_params.search !== ''"
                        @click="resetSearch"
                        icon="ic:baseline-autorenew"
                    >
                        Reset Search
                    </AppButton>
                </div>

                <PaginationCard
                    :data="checks.unverified"
                    @paginationChangePage="getChecks"
                />
            </div>

            <!-- SECTION: VERIFIED -->
            <div class="flex flex-col gap-2">
                <h2 class="font-semibold text-neutral-500 px-4">Verified</h2>
                <CheckCard
                    v-for="check in checks.verified.data"
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

                <div
                    v-if="checks.verified.total === 0"
                    class="border border-dashed border-neutral-300 text-center p-8 rounded-3xl m-4 text-neutral-500 flex flex-col gap-4 items-center"
                >
                    <p class="text-sm">No verified checks found</p>
                    <AppButton
                        v-if="search_params.search !== ''"
                        @click="resetSearch"
                        icon="ic:baseline-autorenew"
                    >
                        Reset Search
                    </AppButton>
                </div>

                <PaginationCard
                    :data="checks.verified"
                    @paginationChangePage="getChecks"
                />
            </div>

            <!-- SECTION: REMOVED -->
            <div class="flex flex-col gap-2 lg:col-span-2 xl:col-span-1">
                <h2 class="font-semibold text-neutral-500 px-4">Removed</h2>

                <CheckCard
                    v-for="check in checks.removed.data"
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

                <div
                    v-if="checks.removed.total === 0"
                    class="border border-dashed border-neutral-300 text-center p-8 rounded-3xl m-4 text-neutral-500 flex flex-col gap-4 items-center"
                >
                    <p class="text-sm">No removed checks found</p>
                    <AppButton
                        v-if="search_params.search !== ''"
                        @click="resetSearch"
                        icon="ic:baseline-autorenew"
                    >
                        Reset Search
                    </AppButton>
                </div>

                <PaginationCard
                    :data="checks.removed"
                    @paginationChangePage="getChecks"
                />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import PaginationCard from '@/Components/cards/PaginationCard.vue'
import SearchCard from '@/Components/cards/SearchCard.vue'
import CheckCard from '@/Components/data/CheckCard.vue'
import AppButton from '@/Components/form/AppButton.vue'
import { MenuItem } from '@headlessui/vue'
import { Link } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'

import { Check, Paginate } from '@/globalInterfaces'
import { router } from '@inertiajs/vue3'
import { reactive } from 'vue'
import { usePromptModalStore } from '@/Stores/promptModal.store'

defineProps<{
    checks: {
        unverified: Paginate<Check>
        verified: Paginate<Check>
        removed: Paginate<Check>
    }
}>()

const search_params = reactive({
    search: '',
})

const $promptModalStore = usePromptModalStore()

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

function updateCheck(check_id: number): void {
    router.put(
        route('dashboard.checks.update', check_id),
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
</script>
