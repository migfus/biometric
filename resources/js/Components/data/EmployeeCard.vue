<template>
    <div class="flex flex-col">
        <Menu
            as="div"
            class="bg-white dark:bg-neutral-800 flex flex-col gap-2 p-2 border-y border-neutral-200 dark:border-neutral-700 sm:rounded-3xl sm:border relative dark:text-neutral-300"
        >
            <MenuButton
                class="bg-white dark:bg-neutral-800 flex flex-col gap-2 p-2"
            >
                <div class="flex flex-col gap-1">
                    <div class="flex gap-2 justify-between items-center">
                        <div class="flex gap-2 items-center">
                            <img
                                :src="`https://ui-avatars.com/api/?name=${employee.full_name.replace(' ', '+')}`"
                                class="size-4 ring ring-white rounded-full"
                            />
                            <p
                                class="text-sm font-semibold text-start truncate"
                            >
                                {{ employee.full_name }}
                            </p>
                        </div>

                        <div class="flex gap-2 justify-end flex-none">
                            <p
                                class="text-neutral-500 dark:text-neutral-400 flex-none text-xs"
                            >
                                {{ employee.id }}
                            </p>
                            <Icon icon="nrk:more" class="flex-none" />
                        </div>
                    </div>

                    <div v-if="employee.office" class="flex">
                        <p
                            class="text-xs text-neutral-500 dark:text-neutral-400 truncate"
                        >
                            {{ employee.office.name }}
                        </p>
                    </div>
                </div>
            </MenuButton>

            <DropdownMenu
                :dropdown_menu="dropdown_menu"
                :target_id="employee.id"
            />
        </Menu>
    </div>
</template>

<script setup lang="ts">
import DropdownMenu from '@/components/dropdown/DropdownMenu.vue'
import { Menu, MenuButton } from '@headlessui/vue'
import { Icon } from '@iconify/vue'

import { DropdownMenuItem, Employee } from '@/globalInterfaces'
import { usePromptModalStore } from '@/stores/promptModal.store'
import { router } from '@inertiajs/vue3'

const { employee } = defineProps<{
    employee: Employee
}>()

const $promptModalStore = usePromptModalStore()

const dropdown_menu: DropdownMenuItem[] = [
    {
        name: 'Checks',
        icon: 'mingcute:time-line',
        color: '',
        callback: function () {
            router.get(route('dashboard.employees.show', employee.id))
        },
    },
    {
        name: 'Edit',
        icon: 'mdi:pencil',
        color: '',
        callback: function () {
            router.get(route('dashboard.employees.edit', employee.id))
        },
    },
    {
        name: 'Remove',
        icon: 'mdi:trash-outline',
        color: 'danger',
        callback: function () {
            removeEmployee()
        },
    },
]

function removeEmployee(): void {
    $promptModalStore.menu_items = [
        {
            name: 'Yes, Remove',
            icon: 'mdi:trash-outline',
            color: 'danger',
            callback: function () {
                deleteEmployee()
            },
        },
        {
            name: 'Cancel',
            icon: 'mdi:trash-outline',
            color: '',
            callback: function () {
                $promptModalStore.menu_items = []
            },
        },
    ]
}

function deleteEmployee(): void {
    router.delete(route('dashboard.employees.destroy', employee.id), {
        preserveState: true,
    })
}
</script>
