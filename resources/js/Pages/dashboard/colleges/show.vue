<template>
    <div class="flex flex-col gap-2 xl:grid xl:grid-cols-3">
        <div class="order-1 flex flex-col xl:order-2 xl:col-span-1">
            <BasicCard
                title="College or Department"
                icon="mingcute:department-fill"
            >
                <div class="flex flex-col gap-2">
                    <div class="font-semibold">{{ college.name }}</div>
                </div>

                <div
                    class="flex flex-col gap-2 mt-4 sm:flex-row sm:justify-end"
                >
                    <AppButton
                        :href="
                            route('dashboard.colleges.showChecks', college.id)
                        "
                        icon="mingcute:time-line"
                    >
                        Checks
                    </AppButton>
                    <AppButton
                        :href="route('dashboard.colleges.index')"
                        icon="mingcute:arrow-left-line"
                    >
                        College Lists
                    </AppButton>
                </div>
            </BasicCard>
        </div>
        <div class="order-2 flex flex-col gap-2 xl:order-1 xl:col-span-2">
            <SearchCard
                :index_data_id="[]"
                v-model:search="params.search"
                @search="getEmployees"
                no_print
            />

            <SearchResultSection
                :total="employees.total"
                :searched="params.search"
            />

            <div class="flex flex-col gap-0 lg:gap-2 lg:grid lg:grid-cols-2">
                <EmployeeCard
                    v-for="employee in employees.data"
                    :key="employee.id"
                    :employee="employee"
                />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'
import SearchCard from '@/components/cards/SearchCard.vue'
import AppButton from '@/components/form/AppButton.vue'

import { College, Paginate, Employee } from '@/globalInterfaces'
import { reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import EmployeeCard from '@/components/data/EmployeeCard.vue'
import SearchResultSection from '@/components/data/SearchResultSection.vue'

const { college } = defineProps<{
    college: College
    employees: Paginate<Employee>
}>()

const params = reactive({
    search: '',
})

function getEmployees(page = 1) {
    router.get(
        route('dashboard.colleges.show', college.id),
        {
            ...params,
            page,
        },
        {
            preserveState: true,
            preserveScroll: true,
            only: ['employees'],
        },
    )
}
</script>
