<template>
    <div class="flex flex-col gap-4">
        <SearchCard
            :index_data_id="[]"
            v-model:search="search_params.search"
            no_print
            @search="getChecks"
            :create="route('dashboard.checks.create')"
        />

        <div
            class="flex flex-col gap-0 lg:grid lg:grid-cols-2 lg:gap-1 2xl:grid-cols-3"
        >
            <CheckCard
                v-for="check in checks.data"
                :key="check.id"
                :check="check"
            />
        </div>

        <div
            v-if="checks.total === 0"
            class="border border-dashed border-neutral-300 text-center p-8 rounded-3xl m-4 text-neutral-500 flex flex-col gap-4 items-center"
        >
            <p class="text-sm">No checks found</p>
            <AppButton
                v-if="search_params.search !== ''"
                @click="resetSearch"
                icon="ic:baseline-autorenew"
            >
                Reset Search
            </AppButton>
        </div>

        <PaginationCard :data="checks" @paginationChangePage="getChecks" />
    </div>
</template>

<script setup lang="ts">
import PaginationCard from '@/Components/cards/PaginationCard.vue'
import SearchCard from '@/Components/cards/SearchCard.vue'
import AppButton from '@/Components/form/AppButton.vue'
import { Check, Paginate } from '@/globalInterfaces'
import { router } from '@inertiajs/vue3'
import { reactive } from 'vue'
import CheckCard from './CheckCard.vue'

defineProps<{
    checks: Paginate<Check>
}>()

const search_params = reactive({
    search: '',
})

function getChecks(page = 1) {
    router.get(
        route('dashboard.checks.index'),
        { page, search: search_params.search },
        { preserveState: true, only: ['checks'] },
    )
}

function resetSearch() {
    search_params.search = ''
    getChecks(1)
}
</script>
