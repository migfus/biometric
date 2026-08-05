<template>
    <div class="flex flex-col">
        <Menu
            as="div"
            class="bg-white dark:bg-neutral-800 flex flex-col gap-2 p-2 border-y border-neutral-200 dark:border-neutral-700 sm:rounded-3xl sm:border relative"
        >
            <div class="bg-white dark:bg-neutral-800 flex flex-col gap-2 p-2">
                <div class="flex flex-col gap-2 items-start">
                    <MenuButton class="flex flex-col w-full items-start gap-2">
                        <div class="flex justify-between items-center w-full">
                            <div
                                class="flex items-center gap-2 dark:text-neutral-300"
                            >
                                <Icon
                                    v-if="check_status.icon"
                                    :icon="check_status.icon"
                                    class="size-4"
                                />
                                <Icon
                                    v-else
                                    icon="material-symbols:circle-outline"
                                    class="size-4"
                                />
                                <p
                                    class="text-sm font-semibold truncate text-neutral-700 dark:text-neutral-300"
                                >
                                    {{ check_status.name }}
                                </p>
                            </div>

                            <div class="flex gap-2 items-center">
                                <div class="flex items-center gap-1">
                                    <p
                                        class="bg-red-50 dark:bg-red-950 text-red-700 dark:text-red-300 text-sm px-2 rounded-full flex items-center font-semibold"
                                    >
                                        {{ check_status.reports_count }}
                                    </p>
                                </div>

                                <Icon
                                    icon="nrk:more"
                                    class="flex-none dark:text-neutral-300"
                                />
                            </div>
                        </div>
                    </MenuButton>
                </div>
            </div>

            <DropdownMenu
                :dropdown_menu="dropdown_menu"
                :target_id="check_status.id"
            />
        </Menu>
    </div>
</template>

<script setup lang="ts">
import { Menu, MenuButton } from '@headlessui/vue'
import { Icon } from '@iconify/vue'

import { CheckStatus, DropdownMenuItem } from '@/globalInterfaces'
import DropdownMenu from '../dropdown/DropdownMenu.vue'

defineProps<{
    check_status: CheckStatus
    no_address?: boolean
    minified?: boolean
    dropdown_menu: DropdownMenuItem[]
}>()
const $emit = defineEmits(['remove'])
</script>
