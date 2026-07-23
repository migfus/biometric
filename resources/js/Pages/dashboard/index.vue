<template>
    <div class="flex flex-col gap-4">
        <!-- SECTION: STATS -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 px-2 sm:px-0">
            <StatCard
                title="PENDING VERIFICATION"
                :this_month="stats.pending_verifications"
                :previous_month="stats.pending_verifications"
                icon="mingcute:time-line"
            />

            <StatCard
                title="Checks this Month"
                :this_month="stats.active_checks.this_month"
                :previous_month="stats.active_checks.previous_month"
                icon="ic:baseline-check-circle-outline"
            />

            <StatCard
                title="Empoyees"
                :this_month="stats.employees_count"
                icon="ic:outline-people"
            />

            <StatCard
                title="Checks"
                :this_month="stats.checks_count"
                icon="ic:baseline-check-circle-outline"
            />
        </div>

        <SearchCard v-model:search="params.search" no_print />

        <SearchResultSection
            :searched="params.search"
            :total="unverified_checks.total"
        />

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
            <!-- SECTION: Unverified Checks -->
            <div class="flex flex-col gap-2">
                <button
                    @click="
                        config.unverified_checks_show =
                            !config.unverified_checks_show
                    "
                    class="flex justify-between"
                >
                    <h2 class="font-semibold text-neutral-500">
                        Unverified Checks
                    </h2>
                    <p
                        v-if="unverified_checks.total > 0"
                        class="font-semibold text-neutral-500 bg-neutral-200 rounded-full px-2"
                    >
                        {{ unverified_checks.total }}
                    </p>
                </button>

                <CheckCard
                    v-if="config.unverified_checks_show"
                    v-for="item in unverified_checks.data"
                    :check="item"
                    minified
                >
                    <MenuItem
                        v-slot="{ active }"
                        class="flex items-center rounded-xl cursor-pointer"
                    >
                        <button
                            @click="updateCheck(item.id)"
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
                            :href="route('dashboard.checks.show', item.id)"
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
                        v-if="item.employee"
                        v-slot="{ active }"
                        class="flex items-center rounded-xl cursor-pointer"
                    >
                        <Link
                            :href="
                                route(
                                    'dashboard.employees.show',
                                    item.employee.id,
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
                            @click="removeCheck(item.id)"
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
                <button
                    @click="config.unverified_checks_show = true"
                    v-else-if="unverified_checks.total > 0"
                    class="bg-white rounded-3xl ring ring-neutral-200 p-4 text-center text-neutral-500"
                >
                    Show {{ unverified_checks.total }} Unverified Checks
                </button>
            </div>

            <!-- SECTION: Active Employees -->
            <div class="flex flex-col gap-2">
                <button
                    @click="
                        config.active_employees_show =
                            !config.active_employees_show
                    "
                    class="flex justify-between"
                >
                    <h2 class="font-semibold text-neutral-500">
                        Active Employees
                    </h2>
                    <p
                        v-if="active_employees.total > 0"
                        class="font-semibold text-neutral-500 bg-neutral-200 rounded-full px-2"
                    >
                        {{ active_employees.total }}
                    </p>
                </button>
                <EmployeeCard
                    v-if="config.active_employees_show"
                    v-for="item in active_employees.data"
                    :employee="item"
                />
                <button
                    @click="config.active_employees_show = true"
                    v-else-if="active_employees.total > 0"
                    class="bg-white rounded-3xl ring ring-neutral-200 p-4 text-center text-neutral-500"
                >
                    Show {{ active_employees.total }} Active Employees
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import SearchCard from '@/components/cards/SearchCard.vue'
import StatCard from '@/components/cards/StatCard.vue'
import CheckCard from '@/components/data/CheckCard.vue'
import EmployeeCard from '@/components/data/EmployeeCard.vue'
import SearchResultSection from '@/components/data/SearchResultSection.vue'
import { MenuItem } from '@headlessui/vue'
import { Icon } from '@iconify/vue'

import { Check, Employee, Paginate } from '@/globalInterfaces'
import { reactive } from 'vue'
import { useWindowSize } from '@vueuse/core'
import { router, Link } from '@inertiajs/vue3'
import { usePromptModalStore } from '@/stores/promptModal.store'

interface DashboardStats {
    active_checks: {
        this_month: number
        previous_month: number
    }
    active_employees: {
        this_month: number
        previous_month: number
    }
    pending_verifications: number
    checks_count: number
    employees_count: number
}

const { stats } = defineProps<{
    stats: DashboardStats
    active_employees: Paginate<Employee>
    unverified_checks: Paginate<Check>
}>()

const params = reactive<{
    search: string
}>({
    search: '',
})
const $promptModalStore = usePromptModalStore()
const { width } = useWindowSize()

const config = reactive({
    unverified_checks_show: width.value >= 640,
    active_employees_show: width.value >= 640,
})

function updateCheck(check_id: number): void {
    router.put(
        route('dashboard.checks.update', check_id),
        {
            type: 'verify',
        },
        {
            preserveState: true,
            preserveScroll: true,
            only: ['unverified_checks'],
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
