<template>
    <div class="flex flex-col gap-4">
        <SearchCard
            :index_data_id="[]"
            v-model:search="search_params.search"
            no_print
            @search="getEmployees"
            :create="route('dashboard.employees.create')"
        />

        <div
            class="flex flex-col gap-0 lg:grid lg:grid-cols-2 lg:gap-1 2xl:grid-cols-3"
        >
            <EmployeeCard
                v-for="employee in employees.data"
                :key="employee.id"
                :employee="employee"
            />
        </div>

        <div
            v-if="employees.total === 0"
            class="border border-dashed border-neutral-300 text-center p-8 rounded-3xl m-4 text-neutral-500 flex flex-col gap-4 items-center"
        >
            <p class="text-sm">No employees found</p>
            <AppButton
                v-if="search_params.search !== ''"
                @click="resetSearch"
                icon="ic:baseline-autorenew"
            >
                Reset Search
            </AppButton>
        </div>

        <PaginationCard
            :data="employees"
            @paginationChangePage="getEmployees"
        />
    </div>
</template>

<script setup lang="ts">
import PaginationCard from '@/Components/cards/PaginationCard.vue'
import SearchCard from '@/Components/cards/SearchCard.vue'
import AppButton from '@/Components/form/AppButton.vue'
import EmployeeCard from './EmployeeCard.vue'

import { Employee, Paginate } from '@/globalInterfaces'
import { router } from '@inertiajs/vue3'
import { reactive } from 'vue'

defineProps<{
    employees: Paginate<Employee>
}>()

const search_params = reactive({
    search: '',
})

function getEmployees(page = 1): void {
    router.get(
        route('dashboard.employees.index'),
        { page, search: search_params.search },
        { preserveState: true, only: ['employees'] },
    )
}

function resetSearch(): void {
    search_params.search = ''
    getEmployees(1)
}
</script>
