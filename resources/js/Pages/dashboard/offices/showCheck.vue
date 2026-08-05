<template>
    <div class="flex flex-col gap-2 xl:grid xl:grid-cols-3">
        <div class="order-1 flex flex-col xl:order-2 xl:col-span-1">
            <BasicCard title="Office" icon="mingcute:department-fill">
                <div class="flex flex-col gap-2">
                    <div class="font-semibold dark:text-neutral-300">
                        {{ office.name }}
                    </div>
                </div>

                <div
                    class="flex flex-col gap-2 mt-4 sm:flex-row sm:justify-end"
                >
                    <AppButton
                        :href="route('dashboard.offices.show', office.id)"
                        icon="ic:outline-people"
                    >
                        Employees
                    </AppButton>
                    <AppButton
                        :href="route('dashboard.offices.index')"
                        icon="mingcute:arrow-left-line"
                    >
                        Office Lists
                    </AppButton>
                </div>
            </BasicCard>
        </div>

        <div class="order-2 flex flex-col gap-2 xl:order-1 xl:col-span-2">
            <SearchCard
                :index_data_id="[]"
                v-model:search="params.search"
                @search="getEmployees"
                @print="print()"
            />

            <SearchResultSection
                :total="checks.total"
                :searched="params.search"
            />

            <div class="flex flex-col gap-0 lg:gap-2 lg:grid lg:grid-cols-2">
                <CheckCard
                    v-for="check in checks.data"
                    :key="check.id"
                    :check="check"
                    :dropdown_menu
                />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'
import SearchCard from '@/components/cards/SearchCard.vue'
import AppButton from '@/components/form/AppButton.vue'
import CheckCard from '@/components/data/CheckCard.vue'

import { Check, Office, Paginate, DropdownMenuItem } from '@/globalInterfaces'
import { router } from '@inertiajs/vue3'
import { reactive } from 'vue'
import { usePromptModalStore } from '@/stores/promptModal.store'
import SearchResultSection from '@/components/data/SearchResultSection.vue'

const { office } = defineProps<{
    office: Office
    checks: Paginate<Check>
}>()

const params = reactive({
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
    const param = new URLSearchParams({
        search: params.search,
    })

    window.location.href = `${route('dashboard.offices.showCheckPrint', office.id)}?${param.toString()}`
}
</script>
