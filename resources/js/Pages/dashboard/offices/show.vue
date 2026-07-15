<template>
    <div class="flex flex-col gap-2">
        <BasicCard title="Office" icon="mingcute:department-fill">
            <div class="flex flex-col gap-2">
                <div class="font-semibold">{{ office.name }}</div>
            </div>

            <div class="flex flex-col gap-2 mt-4 sm:flex-row sm:justify-end">
                <AppButton
                    :href="route('dashboard.offices.showChecks', office.id)"
                    icon="mingcute:time-line"
                >
                    Checks
                </AppButton>
                <AppButton
                    :href="route('dashboard.offices.index')"
                    icon="mingcute:arrow-left-line"
                >
                    Office Lists
                </AppButton>
            </div>
        </BasicCard>

        <SearchCard
            :index_data_id="[]"
            v-model:search="params.search"
            @search="getEmployees"
            no_print
        />

        <div
            class="flex flex-col gap-0 lg:gap-2 lg:grid lg:grid-cols-2 xl:grid-cols-3"
        >
            <EmployeeCard
                v-for="employee in employees.data"
                :key="employee.id"
                :employee="employee"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import BasicCard from '@/Components/cards/BasicCard.vue'
import SearchCard from '@/Components/cards/SearchCard.vue'
import AppButton from '@/Components/form/AppButton.vue'

import { Office, Paginate, Employee } from '@/globalInterfaces'
import { reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import EmployeeCard from '@/Components/data/EmployeeCard.vue'

const { office } = defineProps<{
    office: Office
    employees: Paginate<Employee>
}>()

const params = reactive({
    search: '',
})

function getEmployees(page = 1) {
    router.get(
        route('dashboard.offices.show', office.id),
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
