<template>
    <div class="flex h-full flex-col">
        <Menu
            as="div"
            class="relative flex h-full flex-col gap-2 border-y border-neutral-200 bg-white p-2 dark:border-neutral-700 dark:bg-neutral-800 sm:rounded-3xl sm:border"
        >
            <div
                class="flex flex-1 flex-col gap-2 bg-white p-2 dark:bg-neutral-800"
            >
                <div class="flex h-full flex-col items-start gap-2">
                    <MenuButton
                        class="flex h-full w-full flex-1 flex-col items-start gap-2 text-left"
                    >
                        <!-- SECTION: UPPER -->
                        <div class="flex justify-between items-center w-full">
                            <div
                                class="flex items-center gap-2 dark:text-neutral-300 truncate"
                            >
                                <img
                                    :src="`https://ui-avatars.com/api/?name=${report.employee.full_name.replace(' ', '+')}`"
                                    class="size-4 ring ring-white rounded-full ml-0.5"
                                />
                                <p
                                    class="text-sm font-semibold truncate text-neutral-700 dark:text-neutral-300"
                                >
                                    {{ report.employee.full_name }}
                                </p>
                            </div>

                            <div class="flex gap-2 items-center flex-none">
                                <div class="flex items-center gap-1">
                                    <p
                                        class="text-red-700 dark:text-neutral-300 flex items-center text-xs"
                                    >
                                        {{
                                            messengerStyleTime(
                                                report.created_at,
                                            )
                                        }}
                                    </p>
                                </div>

                                <Icon
                                    icon="nrk:more"
                                    class="flex-none dark:text-neutral-300"
                                />
                            </div>
                        </div>

                        <!-- SECTION: MID -->
                        <div class="flex justify-between items-center w-full">
                            <div
                                class="flex items-center gap-1 dark:text-neutral-300 truncate"
                            >
                                <Icon
                                    icon="hugeicons:biometric-device"
                                    class="flex-none"
                                />
                                <p
                                    class="text-xs text-neutral-700 dark:text-neutral-300"
                                >
                                    {{ report.biometric_device.name }}
                                </p>
                                <Icon
                                    icon="mdi:dot"
                                    class="text-green-500 flex-none"
                                />
                                <p
                                    class="text-red-700 dark:text-neutral-300 flex items-center text-xs"
                                >
                                    {{ report.biometric_device.area.name }}
                                </p>
                            </div>
                        </div>

                        <!-- SECTION: LOWER -->
                        <div class="flex justify-between items-center w-full">
                            <div
                                class="flex items-center gap-1 dark:text-neutral-300 truncate"
                            >
                                <div
                                    class="text-xs text-neutral-700 dark:text-neutral-300 dark:bg-neutral-900 px-2 py-0.5 rounded-full flex gap-1 items-center"
                                >
                                    <Icon
                                        v-if="report.check_status.icon"
                                        :icon="report.check_status.icon"
                                        class="flex-none"
                                    />
                                    <Icon
                                        v-else
                                        icon="mdi:alert-circle-outline"
                                        class="flex-none"
                                    />
                                    <p>
                                        {{ report.check_status.name }}
                                    </p>
                                </div>

                                <div
                                    class="text-xs text-neutral-700 dark:text-red-100 dark:bg-red-950/50 px-2 py-0.5 rounded-full flex gap-1 items-center"
                                >
                                    <Icon
                                        v-if="report.report_type.icon"
                                        :icon="report.report_type.icon"
                                        class="flex-none"
                                    />
                                    <Icon
                                        v-else
                                        icon="mdi:alert-circle-outline"
                                        class="flex-none"
                                    />
                                    <p class="">
                                        {{ report.report_type.name }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION: Description -->
                        <div
                            class="w-full flex-1 rounded-lg p-2 text-start dark:bg-neutral-900"
                        >
                            <p class="dark:text-neutral-400 text-xs mb-1">
                                Description
                            </p>
                            <p
                                class="text-xs text-neutral-700 dark:text-neutral-300"
                            >
                                {{ report.description }}
                            </p>
                        </div>
                        <!-- SECTION: Action Taken -->
                        <div
                            v-if="report.action_taken"
                            class="w-full text-start dark:bg-neutral-900 p-2 rounded-lg"
                        >
                            <p class="dark:text-neutral-400 text-xs mb-1">
                                Action Taken
                            </p>
                            <p
                                class="text-xs text-neutral-700 dark:text-neutral-300"
                            >
                                {{ report.action_taken }}
                            </p>
                        </div>

                        <div class="w-full flex justify-end gap-2">
                            <AppButton size="sm" icon="mingcute:time-line">
                                Pending
                            </AppButton>
                            <AppButton
                                size="sm"
                                icon="material-symbols:print-outline"
                                color="brand"
                            >
                                Print
                            </AppButton>
                        </div>
                    </MenuButton>
                </div>
            </div>

            <DropdownMenu
                :dropdown_menu="dropdown_menu"
                :target_id="report.id"
            />
        </Menu>
    </div>
</template>

<script setup lang="ts">
import { Menu, MenuButton } from '@headlessui/vue'
import { Icon } from '@iconify/vue'
import DropdownMenu from '../dropdown/DropdownMenu.vue'
import AppButton from '@/components/form/AppButton.vue'

import { DropdownMenuItem, Report } from '@/globalInterfaces'
import { messengerStyleTime } from '@/utils'

defineProps<{
    report: Report
    no_address?: boolean
    minified?: boolean
    dropdown_menu: DropdownMenuItem[]
}>()
const $emit = defineEmits(['remove'])
</script>
