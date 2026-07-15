<template>
    <div class="flex flex-col">
        <Menu
            as="div"
            class="bg-white flex flex-col gap-2 p-2 border-y border-neutral-200 sm:rounded-3xl sm:border relative"
        >
            <MenuButton class="bg-white flex flex-col gap-1 p-2">
                <div class="flex flex-col gap-2">
                    <div class="flex gap-2 justify-between">
                        <p class="text-sm font-semibold truncate">
                            {{ office.name }}
                        </p>

                        <div
                            class="text-xs flex gap-1 items-center text-neutral-500 flex-none"
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
                                class="text-neutral-500 bg-neutral-100 px-2 rounded-full"
                            >
                                {{ office.employees_count }}
                            </p>
                            <p v-else class="text-neutral-500">No Employees</p>
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
                            :href="route('dashboard.offices.show', office.id)"
                            :class="[
                                active ? 'bg-neutral-50' : '',
                                'px-4 py-2 text-sm text-brand-200 flex hover:bg-neutral-200 dark:hover:bg-dark-003 gap-2 items-center',
                            ]"
                        >
                            <Icon icon="ic:outline-people" />
                            <p>Employees</p>
                        </Link>
                    </MenuItem>
                    <MenuItem
                        v-slot="{ active }"
                        class="flex items-center rounded-xl cursor-pointer"
                    >
                        <Link
                            :href="
                                route('dashboard.offices.showChecks', office.id)
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
                            :href="route('dashboard.offices.edit', office.id)"
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
                        v-if="office.employees_count == 0"
                        class="flex items-center rounded-xl cursor-pointer"
                    >
                        <button
                            type="button"
                            @click="removeOffice()"
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

import { Office } from '@/globalInterfaces'
import { usePromptModalStore } from '@/Stores/promptModal.store'
import { messengerStyleTime } from '@/utils'

const { office } = defineProps<{
    office: Office
}>()

const $prompModalStore = usePromptModalStore()

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
