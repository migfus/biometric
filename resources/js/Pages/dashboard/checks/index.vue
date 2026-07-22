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
                            route('dashboard.employees.show', check.employee.id)
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

                <MenuItem class="flex items-center rounded-xl cursor-pointer">
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
import { MenuItem } from '@headlessui/vue'
import { Link } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'

import { Check, Paginate } from '@/globalInterfaces'
import { router } from '@inertiajs/vue3'
import { reactive } from 'vue'
import { usePromptModalStore } from '@/stores/promptModal.store'
import SearchResultSection from '@/components/data/SearchResultSection.vue'

defineProps<{
    checks: Paginate<Check>
}>()

const search_params = reactive({
    search: '',
})

const $promptModalStore = usePromptModalStore()

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

function updateCheck(check_id: number): void {
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

function print(): void {
    const params = new URLSearchParams({
        search: search_params.search,
    })

    window.location.href = `${route('dashboard.checks.print')}?${params.toString()}`
}
</script>
