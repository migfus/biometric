<template>
    <div class="flex flex-col">
        <Menu
            as="div"
            class="bg-white dark:bg-neutral-800 flex flex-col gap-2 p-2 border-y border-neutral-200 dark:border-neutral-700 sm:rounded-3xl sm:border relative"
        >
            <MenuButton
                class="bg-white dark:bg-neutral-800 flex flex-col gap-1 p-2"
            >
                <div class="flex flex-col gap-2">
                    <div class="flex gap-2 justify-between">
                        <p
                            class="text-sm font-semibold truncate dark:text-neutral-300"
                        >
                            {{ office.name }}
                        </p>

                        <div
                            class="text-xs flex gap-1 items-center text-neutral-500 dark:text-neutral-400 flex-none"
                        >
                            <p>
                                {{ messengerStyleTime(office.created_at) }}
                            </p>
                            <Icon icon="nrk:more" class="size-4" />
                        </div>
                    </div>

                    <div class="flex gap-2 justify-between">
                        <div class="flex -space-x-1">
                            <img
                                v-for="employee in office.employees"
                                :key="employee.id"
                                :src="`https://ui-avatars.com/api/?name=${employee.full_name.replace(' ', '+')}`"
                                class="size-4 ring ring-white rounded-full"
                            />
                        </div>

                        <div class="font-semibold text-xs">
                            <p
                                v-if="office.employees_count > 0"
                                class="text-neutral-500 dark:text-neutral-400 bg-neutral-100 dark:bg-neutral-700 px-2 rounded-full"
                            >
                                {{ office.employees_count }}
                            </p>
                            <p v-else class="text-neutral-500">No Employees</p>
                        </div>
                    </div>
                </div>
            </MenuButton>

            <DropdownMenu :dropdown_menu :target_id="office.id" />
        </Menu>
    </div>
</template>

<script setup lang="ts">
import { Menu, MenuButton } from '@headlessui/vue'
import { Icon } from '@iconify/vue'
import { router } from '@inertiajs/vue3'

import { DropdownMenuItem, Office } from '@/globalInterfaces'
import { usePromptModalStore } from '@/stores/promptModal.store'
import { messengerStyleTime } from '@/utils'
import DropdownMenu from '../dropdown/DropdownMenu.vue'

const { office } = defineProps<{
    office: Office
}>()

const $prompModalStore = usePromptModalStore()

const dropdown_menu: DropdownMenuItem[] = [
    {
        name: 'Employees',
        icon: 'ic:outline-people',
        color: '',
        callback: function () {
            router.get(route('dashboard.offices.show', office.id))
        },
    },
    {
        name: 'Checks',
        icon: 'mingcute:time-line',
        color: '',
        callback: function () {
            router.get(route('dashboard.offices.showChecks', office.id))
        },
    },
    {
        name: 'Edit',
        icon: 'mdi:pencil',
        color: '',
        callback: function () {
            router.get(route('dashboard.offices.edit', office.id))
        },
    },
]

function removeOffice(): void {
    $prompModalStore.menu_items = [
        {
            name: 'Yes, Remove',
            icon: 'mdi:trash-outline',
            color: 'danger',
            callback: () => {
                deleteOffice()
            },
        },
        {
            name: 'Cancel',
            icon: 'mdi:trash-outline',
            color: '',
            callback: () => {
                $prompModalStore.menu_items = []
            },
        },
    ]
}

function deleteOffice(): void {
    router.delete(route('dashboard.offices.destroy', office.id), {
        preserveState: true,
    })
}
</script>
