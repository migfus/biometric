<template>
    <div class="flex flex-col">
        <Menu
            as="div"
            class="bg-white flex flex-col gap-2 p-2 border-y border-neutral-200 sm:rounded-3xl sm:border relative"
        >
            <MenuButton class="bg-white flex flex-col gap-2 p-2">
                <div class="flex gap-2 items-start justify-between">
                    <div class="flex flex-col gap-0 items-start">
                        <p class="text-sm font-semibold">
                            {{
                                check.employee?.full_name ?? 'Unknown Employee'
                            }}
                        </p>

                        <div class="flex gap-2 items-center flex-wrap">
                            <p
                                class="text-xs rounded-full px-2 py-1"
                                :class="
                                    check.check_in
                                        ? 'bg-green-100 text-green-700'
                                        : 'bg-amber-100 text-amber-700'
                                "
                            >
                                {{ check.check_in ? 'Check In' : 'Check Out' }}
                            </p>

                            <p class="text-sm text-neutral-500">
                                {{ messengerStyleTime(check.created_at) }}
                            </p>
                        </div>

                        <p class="text-sm text-neutral-500 whitespace-pre-line">
                            {{ check.work_description }}
                        </p>
                    </div>

                    <div class="flex flex-col">
                        <div class="flex gap-2 justify-end">
                            <p class="text-sm text-neutral-500">
                                #{{ check.id }}
                            </p>
                            <Icon icon="nrk:more" />
                        </div>

                        <p
                            class="text-sm text-neutral-500 text-right"
                            v-if="check.attachments.length > 0"
                        >
                            {{ check.attachments.length }} attachment(s)
                        </p>
                    </div>
                </div>
            </MenuButton>

            <BasicTransition>
                <MenuItems
                    class="py-2 absolute right-0 z-10 mr-4 mt-10 w-44 origin-top-right rounded-3xl bg-white shadow-lg ring-1 ring-neutral-200 ring-opacity-5 focus:outline-hidden"
                >
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
                        v-slot="{ active }"
                        class="flex items-center rounded-xl cursor-pointer"
                    >
                        <Link
                            :href="route('dashboard.checks.edit', check.id)"
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
                        v-if="check.employee"
                        v-slot="{ active }"
                        class="flex items-center rounded-xl cursor-pointer"
                    >
                        <Link
                            :href="
                                route(
                                    'dashboard.employees.show',
                                    check.employee.id,
                                )
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

                    <MenuItem
                        class="flex items-center rounded-xl cursor-pointer"
                    >
                        <button
                            type="button"
                            @click="removeCheck()"
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
import { Check } from '@/globalInterfaces'
import { usePromptModalStore } from '@/Stores/promptModal.store'
import { messengerStyleTime } from '@/utils'

const { check } = defineProps<{
    check: Check
}>()

const $promptModalStore = usePromptModalStore()

function removeCheck(): void {
    $promptModalStore.menu_items = [
        {
            name: 'Yes, Remove',
            icon: 'mdi:trash-outline',
            color: 'danger',
            callback: function () {
                deleteCheck()
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

function deleteCheck(): void {
    router.delete(route('dashboard.checks.destroy', check.id), {
        preserveState: true,
    })
}
</script>
