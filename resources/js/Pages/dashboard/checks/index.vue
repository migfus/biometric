<template>
    <div class="flex flex-col gap-4">
        <SearchCard
            :index_data_id="[]"
            v-model:search="search_params.search"
            @print="print()"
            @search="getChecks"
        />

        <SearchResultSection
            :total="checks.total"
            :searched="search_params.search"
        />

        <div
            class="flex flex-col gap-0 lg:grid lg:grid-cols-2 lg:gap-1 xl:grid-cols-3"
        >
            <CheckCard
                v-for="check in checks.data"
                :key="check.id"
                :check="check"
                :dropdown_menu
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
import PaginationCard from '@/components/cards/PaginationCard.vue'
import SearchCard from '@/components/cards/SearchCard.vue'
import CheckCard from '@/components/data/CheckCard.vue'
import AppButton from '@/components/form/AppButton.vue'

import SearchResultSection from '@/components/data/SearchResultSection.vue'
import { Check, DropdownMenuItem, Paginate } from '@/globalInterfaces'
import { usePromptModalStore } from '@/stores/promptModal.store'
import { router } from '@inertiajs/vue3'
import { reactive } from 'vue'

defineProps<{
    checks: Paginate<Check>
}>()

const search_params = reactive({
    search: '',
})

const $promptModalStore = usePromptModalStore()

const dropdown_menu: DropdownMenuItem[] = [
    {
        name: 'Verify',
        icon: 'material-symbols:check-circle',
        color: '',
        callback: (check_id) => updateCheck(check_id),
    },
    {
        name: 'Unverify',
        icon: 'mdi:close-circle',
        color: 'danger',
        callback: (check_id) => updateCheck(check_id),
    },
    {
        name: 'Details',
        icon: 'mingcute:time-line',
        color: '',
        callback: function (check_id) {
            router.get(route('dashboard.checks.show', check_id))
        },
    },
    {
        name: 'Remove',
        icon: 'mdi:trash-outline',
        color: 'danger',
        callback: (check_id) => removeCheck(check_id),
    },
]

function getChecks(page = 1): void {
    router.get(
        route('dashboard.checks.index'),
        { page, search: search_params.search },
        { preserveState: true, only: ['checks'] },
    )
}

function resetSearch(): void {
    search_params.search = ''
    getChecks(1)
}

function updateCheck(check_id: number | string): void {
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

function removeCheck(check_id: number | string): void {
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

function deleteCheck(check_id: number | string): void {
    router.delete(route('dashboard.checks.destroy', check_id), {
        preserveState: true,
    })
}

function print(): void {
    const params = new URLSearchParams({
        search: search_params.search,
    })

    window.location.href = `${route('dashboard.checks.print')}?${params.toString()}`
}
</script>
