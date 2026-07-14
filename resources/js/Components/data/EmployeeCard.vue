<template>
    <div class="flex flex-col">
        <Menu
            as="div"
            class="bg-white flex flex-col gap-2 p-2 border-y border-neutral-200 sm:rounded-3xl sm:border relative"
        >
            <MenuButton class="bg-white flex flex-col gap-2 p-2">
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
                            <p class="text-neutral-500 flex-non text-xs">
                                {{ employee.id }}
                            </p>
                            <Icon icon="nrk:more" class="flex-none" />
                        </div>
                    </div>

                    <div class="flex">
                        <p class="text-xs text-neutral-500 truncate">
                            {{ employee.office?.name }},
                            {{ employee.college?.name }}
                        </p>
                    </div>

                    <div v-if="employee.checks.length > 0" class="flex gap-1">
                        <div v-for="item in employee.checks" :key="item.id">
                            <p
                                :class="[
                                    'text-xs rounded-full px-2 py-1',
                                    item.check_in
                                        ? 'bg-green-100 text-green-700'
                                        : 'bg-yellow-100 text-yellow-700',
                                ]"
                            >
                                {{
                                    item.check_in
                                        ? 'In: ' +
                                          messengerStyleTime(item.created_at)
                                        : 'Out: ' +
                                          messengerStyleTime(item.created_at)
                                }}
                            </p>
                        </div>
                    </div>
                </div>
            </MenuButton>

            <BasicTransition>
                <MenuItems
                    class="py-2 absolute right-0 z-10 mr-4 mt-10 w-40 origin-top-right rounded-3xl bg-white shadow-lg ring-1 ring-neutral-200 ring-opacity-5 focus:outline-hidden"
                >
                    <MenuItem
                        v-slot="{ active }"
                        class="flex items-center rounded-xl cursor-pointer"
                    >
                        <Link
                            :href="
                                route('dashboard.employees.show', employee.id)
                            "
                            :class="[
                                active ? 'bg-neutral-50' : '',
                                'px-4 py-2 text-sm text-brand-200 flex hover:bg-neutral-200 dark:hover:bg-dark-003 gap-2 items-center',
                            ]"
                        >
                            <Icon icon="mingcute:time-line" />
                            <p>Checks</p>
                        </Link>
                    </MenuItem>
                    <MenuItem
                        v-slot="{ active }"
                        class="flex items-center rounded-xl cursor-pointer"
                    >
                        <Link
                            :href="
                                route('dashboard.employees.edit', employee.id)
                            "
                            :class="[
                                active ? 'bg-neutral-50' : '',
                                'px-4 py-2 text-sm text-brand-200 flex hover:bg-neutral-200 dark:hover:bg-dark-003 gap-2 items-center',
                            ]"
                        >
                            <Icon icon="mdi:pencil" />
                            <p>Edit</p>
                        </Link>
                    </MenuItem>

                    <MenuItem
                        class="flex items-center rounded-xl cursor-pointer"
                    >
                        <button
                            type="button"
                            @click="removeEmployee()"
                            class="w-full text-left hover:bg-neutral-100"
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
                </MenuItems>
            </BasicTransition>
        </Menu>
    </div>
</template>

<script setup lang="ts">
import BasicTransition from '@/Components/transitions/BasicTransition.vue'
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { Icon } from '@iconify/vue'

import { Link, router } from '@inertiajs/vue3'
import { Employee } from '@/globalInterfaces'
import { usePromptModalStore } from '@/Stores/promptModal.store'
import { messengerStyleTime } from '@/utils'

const { employee } = defineProps<{
    employee: Employee
}>()

const $promptModalStore = usePromptModalStore()

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
