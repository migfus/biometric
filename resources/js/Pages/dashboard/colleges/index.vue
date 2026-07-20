<template>
    <div class="flex flex-col gap-4">
        <SearchCard
            :index_data_id="[]"
            v-model:search="search_params.search"
            @print="print()"
            @search="getColleges"
            :create="route('dashboard.colleges.create')"
        />

        <div
            class="flex flex-col gap-0 lg:grid lg:grid-cols-2 lg:gap-1 2xl:grid-cols-3"
        >
            <CollegeCard
                v-for="college in colleges.data"
                :key="college.id"
                :college="college"
            />
        </div>

        <div
            v-if="colleges.total === 0"
            class="border border-dashed border-neutral-300 text-center p-8 rounded-3xl m-4 text-neutral-500 flex flex-col gap-4 items-center"
        >
            <p class="text-sm">No colleges found</p>
            <AppButton
                v-if="search_params.search !== ''"
                @click="resetSearch"
                icon="ic:baseline-autorenew"
            >
                Reset Search
            </AppButton>
        </div>

        <PaginationCard :data="colleges" @paginationChangePage="getColleges" />
    </div>
</template>

<script setup lang="ts">
import PaginationCard from '@/components/cards/PaginationCard.vue'
import SearchCard from '@/components/cards/SearchCard.vue'
import AppButton from '@/components/form/AppButton.vue'
import CollegeCard from '@/components/data/CollegeCard.vue'

import { College, Paginate } from '@/globalInterfaces'
import { router } from '@inertiajs/vue3'
import { reactive } from 'vue'

const props = defineProps<{
    colleges: Paginate<College>
}>()

const search_params = reactive({
    search: '',
})

function getColleges(page = 1): void {
    router.get(
        route('dashboard.colleges.index'),
        { page, search: search_params.search },
        { preserveState: true, only: ['colleges'] },
    )
}

function resetSearch(): void {
    search_params.search = ''
    getColleges(1)
}

function print(): void {
    const params = new URLSearchParams({
        search: search_params.search,
    })

    window.location.href = `${route('dashboard.colleges.print')}?${params.toString()}`
}
</script>
