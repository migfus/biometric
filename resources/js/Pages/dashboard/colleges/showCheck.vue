<template>
    <div class="flex flex-col gap-2">
        <BasicCard
            title="College or Department"
            icon="mingcute:department-fill"
        >
            <div class="flex flex-col gap-2">
                <div class="font-semibold">{{ college.name }}</div>
            </div>

            <div class="flex flex-col gap-2 mt-4 sm:flex-row sm:justify-end">
                <AppButton
                    :href="route('dashboard.colleges.show', college.id)"
                    icon="ic:outline-people"
                >
                    Employees
                </AppButton>
                <AppButton
                    :href="route('dashboard.colleges.index')"
                    icon="mingcute:arrow-left-line"
                >
                    College Lists
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
    </div>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'
import SearchCard from '@/components/cards/SearchCard.vue'
import AppButton from '@/components/form/AppButton.vue'
import CheckCard from '@/components/data/CheckCard.vue'
import { MenuItem } from '@headlessui/vue'
import { Icon } from '@iconify/vue'

import { Check, College, Paginate } from '@/globalInterfaces'
import { router } from '@inertiajs/vue3'
import { reactive } from 'vue'
import { usePromptModalStore } from '@/stores/promptModal.store'

const { college } = defineProps<{
    college: College
    checks: Paginate<Check>
}>()

const params = reactive({
    search: '',
})

const $promptModalStore = usePromptModalStore()

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
</script>
