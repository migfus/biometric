<template>
    <div class="flex flex-col gap-4">
        <SearchCard
            :index_data_id="[]"
            v-model:search="search_params.search"
            no_print
            @search="getOffices"
            :create="route('dashboard.offices.create')"
        />

        <div
            class="flex flex-col gap-0 lg:grid lg:grid-cols-2 lg:gap-1 2xl:grid-cols-3"
        >
            <OfficeCard
                v-for="office in offices.data"
                :key="office.id"
                :office="office"
            />
        </div>

        <div
            v-if="offices.total === 0"
            class="border border-dashed border-neutral-300 text-center p-8 rounded-3xl m-4 text-neutral-500 flex flex-col gap-4 items-center"
        >
            <p class="text-sm">No offices found</p>
            <AppButton
                v-if="search_params.search !== ''"
                @click="resetSearch"
                icon="ic:baseline-autorenew"
            >
                Reset Search
            </AppButton>
        </div>

        <PaginationCard :data="offices" @paginationChangePage="getOffices" />
    </div>
</template>

<script setup lang="ts">
import PaginationCard from '@/Components/cards/PaginationCard.vue'
import SearchCard from '@/Components/cards/SearchCard.vue'
import AppButton from '@/Components/form/AppButton.vue'
import { Office, Paginate } from '@/globalInterfaces'
import { router } from '@inertiajs/vue3'
import { reactive } from 'vue'
import OfficeCard from './OfficeCard.vue'

const props = defineProps<{
    offices: Paginate<Office>
}>()

const search_params = reactive({
    search: '',
})

function getOffices(page = 1) {
    router.get(
        route('dashboard.offices.index'),
        { page, search: search_params.search },
        { preserveState: true, only: ['offices'] },
    )
}

function resetSearch() {
    search_params.search = ''
    getOffices(1)
}
</script>
