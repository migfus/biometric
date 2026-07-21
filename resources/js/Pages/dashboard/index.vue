<template>
    <div class="flex flex-col gap-4">
        <!-- SECTION: STATS -->
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-2 px-2 sm:px-0">
            <StatCard
                class="col-span-2 lg:col-span-1"
                title="PENDING VERIFICATION"
                :this_month="stats.pending_verifications"
                :previous_month="stats.pending_verifications"
                icon="mingcute:time-line"
            />

            <StatCard
                title="Checks this Month"
                :this_month="stats.active_checks.this_month"
                :previous_month="stats.active_checks.previous_month"
                icon="mingcute:time-line"
            />

            <StatCard
                title="Active Employees"
                :this_month="stats.active_employees.this_month"
                :previous_month="stats.active_employees.previous_month"
                icon="ic:outline-people"
            />
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
            <!-- SECTION: ACTIVE EMPLOYEES -->
            <BasicCard title="Recent Active Employees" icon="ic:outline-people">
                <div class="flex flex-col gap-2">
                    <Link
                        v-for="employee in recent_active_employees"
                        :key="employee.id"
                        :href="route('dashboard.employees.show', employee.id)"
                        class="border border-neutral-200 rounded-2xl p-3 flex items-start justify-between gap-3 bg-neutral-100"
                    >
                        <div class="flex flex-col gap-1">
                            <p class="text-sm font-semibold text-neutral-800">
                                {{ employee.full_name }}
                            </p>
                            <p class="text-xs text-neutral-500">
                                {{ employee.id }}
                                <span v-if="employee.office">
                                    • {{ employee.office.name }}</span
                                >
                            </p>
                        </div>

                        <div class="text-right flex flex-col gap-1">
                            <p class="text-xs text-neutral-500">
                                {{ employee.checks_count }} checks
                            </p>
                            <p
                                class="text-xs text-emerald-700"
                                v-if="employee.checks_max_created_at"
                            >
                                Active
                                {{
                                    messengerStyleTime(
                                        employee.checks_max_created_at,
                                    )
                                }}
                            </p>
                        </div>
                    </Link>

                    <div
                        v-if="recent_active_employees.length === 0"
                        class="border border-dashed border-neutral-300 rounded-2xl p-6 text-center text-sm text-neutral-500"
                    >
                        No active employees this month.
                    </div>
                </div>
            </BasicCard>

            <!-- SECTION: RECENT CHECKS -->
            <BasicCard title="Recent Checks" icon="mingcute:time-line">
                <div class="flex flex-col gap-2">
                    <Link
                        v-for="check in recent_checks"
                        :key="check.id"
                        :href="route('dashboard.checks.show', check.id)"
                        class="border border-neutral-200 rounded-2xl p-3 flex items-start justify-between gap-3 hover:bg-neutral-100 bg-neutral-100"
                    >
                        <div class="flex flex-col gap-1">
                            <p class="text-sm font-semibold text-neutral-800">
                                {{
                                    check.employee?.full_name ??
                                    'Unknown Employee'
                                }}
                            </p>
                            <p class="text-xs text-neutral-500">
                                #{{ check.id }} • {{ check.employee_id }}
                            </p>
                            <p class="text-xs text-neutral-500 line-clamp-1">
                                {{ check.work_description }}
                            </p>
                        </div>

                        <div class="text-right flex flex-col gap-1">
                            <p
                                class="text-xs rounded-full px-2 py-1"
                                :class="
                                    check.check_in
                                        ? 'bg-green-100 text-green-700'
                                        : 'bg-amber-100 text-amber-700'
                                "
                            >
                                {{ check.check_in ? 'Check In' : 'Check Out' }}
                            </p>
                            <p class="text-xs text-neutral-500">
                                {{ messengerStyleTime(check.created_at) }}
                            </p>
                            <p class="text-xs text-neutral-500">
                                {{ check.attachments_count }} attachments
                            </p>
                        </div>
                    </Link>

                    <div
                        v-if="recent_checks.length === 0"
                        class="border border-dashed border-neutral-300 rounded-2xl p-6 text-center text-sm text-neutral-500"
                    >
                        No checks yet.
                    </div>
                </div>
            </BasicCard>
        </div>
    </div>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'
import StatCard from '@/components/cards/StatCard.vue'

import { Check, Employee } from '@/globalInterfaces'
import { messengerStyleTime } from '@/utils'
import { Link } from '@inertiajs/vue3'

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
}

interface ActiveEmployee extends Employee {
    checks_max_created_at?: string | null
}

interface RecentCheck extends Check {
    attachments_count: number
}

const { stats, recent_active_employees, recent_checks } = defineProps<{
    stats: DashboardStats
    recent_active_employees: ActiveEmployee[]
    recent_checks: RecentCheck[]
}>()
</script>
