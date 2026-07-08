<template>
    <div class="flex flex-col">
        <Menu
            as="div"
            class="bg-white flex flex-col gap-1 p-2 border-y border-neutral-200"
        >
            <MenuButton class="bg-white flex flex-col gap-1 p-2">
                <div class="flex gap-2 items-center justify-between">
                    <div class="flex flex-col gap-0 items-start">
                        <p class="text-sm font-semibold">{{ office.name }}</p>
                        <div class="flex gap-2 items-center">
                            <p
                                v-if="office.employees_count > 0"
                                class="text-sm text-neutral-500"
                            >
                                {{ office.employees_count }} Employees
                            </p>
                            <p v-else class="text-sm text-neutral-500">
                                No Employees
                            </p>
                        </div>
                    </div>

                    <Icon icon="nrk:more" />
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

const { office } = defineProps<{
    office: Office
}>()

const $prompModalStore = usePromptModalStore()

function removeOffice() {
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

function deleteOffice() {
    router.delete(route('dashboard.offices.destroy', office.id), {
        preserveState: true,
    })
}
</script>
