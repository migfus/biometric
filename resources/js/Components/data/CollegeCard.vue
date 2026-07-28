<template>
    <div class="flex flex-col">
        <Menu
            as="div"
            class="bg-white dark:bg-neutral-800 dark:text-neutral-300 flex flex-col gap-2 p-2 border-y border-neutral-200 dark:border-neutral-700 sm:rounded-3xl sm:border relative"
        >
            <MenuButton
                class="bg-white dark:bg-neutral-800 flex flex-col gap-1 p-2"
            >
                <div class="flex gap-2 flex-col">
                    <div class="flex justify-between gap-2 items-center">
                        <p class="text-sm font-semibold truncate">
                            {{ college.name }}
                        </p>

                        <div class="flex items-center gap-1 text-xs flex-none">
                            <p>{{ messengerStyleTime(college.created_at) }}</p>
                            <Icon icon="nrk:more" class="size-4" />
                        </div>
                    </div>

                    <div class="flex gap-2 justify-between">
                        <div class="flex -space-x-1">
                            <img
                                v-for="employee in college.employees"
                                :key="employee.id"
                                :src="`https://ui-avatars.com/api/?name=${employee.full_name.replace(' ', '+')}`"
                                class="size-4 ring ring-white rounded-full"
                            />
                        </div>

                        <div class="font-semibold text-xs">
                            <p
                                v-if="college.employees_count > 0"
                                class="text-neutral-500 bg-neutral-100 px-2 rounded-full"
                            >
                                {{ college.employees_count }}
                            </p>
                            <p v-else class="text-neutral-500">No Employees</p>
                        </div>
                    </div>
                </div>
            </MenuButton>

            <DropdownMenu :dropdown_menu :target_id="college.id" />
        </Menu>
    </div>
</template>

<script setup lang="ts">
import { Menu, MenuButton } from '@headlessui/vue'
import { Icon } from '@iconify/vue'

import { College, DropdownMenuItem } from '@/globalInterfaces'
import { usePromptModalStore } from '@/stores/promptModal.store'
import { messengerStyleTime } from '@/utils'
import { router } from '@inertiajs/vue3'
import DropdownMenu from '../dropdown/DropdownMenu.vue'

const { college } = defineProps<{
    college: College
}>()

const $prompModalStore = usePromptModalStore()

const dropdown_menu: DropdownMenuItem[] = [
    {
        name: 'Employees',
        icon: 'ic:outline-people',
        color: '',
        callback: () => {
            router.get(route('dashboard.colleges.show', college.id))
        },
    },
    {
        name: 'Checks',
        icon: 'mingcute:time-line',
        color: '',
        callback: () => {
            router.get(route('dashboard.colleges.showChecks', college.id))
        },
    },
    {
        name: 'Edit',
        icon: 'mdi:pencil',
        color: '',
        callback: () => {
            router.get(route('dashboard.colleges.edit', college.id))
        },
    },
]

function removeCollege(): void {
    $prompModalStore.menu_items = [
        {
            name: 'Yes, Remove',
            icon: 'mdi:trash-outline',
            color: 'danger',
            callback: () => {
                deleteCollege()
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

function deleteCollege(): void {
    router.delete(route('dashboard.colleges.destroy', college.id), {
        preserveState: true,
    })
}
</script>
