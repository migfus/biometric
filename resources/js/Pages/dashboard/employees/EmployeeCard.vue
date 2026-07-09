<template>
    <div class="flex flex-col">
        <Menu
            as="div"
            class="bg-white flex flex-col gap-2 p-2 border-y border-neutral-200"
        >
            <MenuButton class="bg-white flex flex-col gap-2 p-2">
                <div class="flex gap-2 items-start justify-between">
                    <div class="flex flex-col gap-0 items-start">
                        <p class="text-sm font-semibold">
                            {{ employee.full_name }}
                        </p>

                        <div class="flex gap-2 items-center flex-wrap">
                            <p
                                v-if="employee.office"
                                class="text-sm text-neutral-500"
                            >
                                {{ employee.office.name }},
                            </p>
                            <p
                                v-if="employee.college"
                                class="text-sm text-neutral-500"
                            >
                                {{ employee.college.name }}
                            </p>
                        </div>
                        <p
                            v-if="employee.checks.length > 0"
                            v-for="item in employee.checks"
                            :key="item.id"
                            class="text-xs text-green-100 bg-green-700 rounded-full px-2 py-1"
                        >
                            {{
                                item.check_in
                                    ? 'Last Check In: ' +
                                      messengerStyleTime(item.created_at)
                                    : 'Last Check Out: ' +
                                      messengerStyleTime(item.created_at)
                            }}
                        </p>

                        <p v-else class="text-sm text-neutral-500">No Checks</p>
                    </div>

                    <div class="flex flex-col">
                        <div class="flex gap-2 justify-end">
                            <p class="text-sm text-neutral-500">
                                {{ employee.id }}
                            </p>
                            <Icon icon="nrk:more" />
                        </div>

                        <p
                            v-if="employee.email"
                            class="text-sm text-neutral-500"
                        >
                            {{ employee.email }}
                        </p>
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

function removeEmployee() {
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

function deleteEmployee() {
    router.delete(route('dashboard.employees.destroy', employee.id), {
        preserveState: true,
    })
}
</script>
