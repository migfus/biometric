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
                            <div class="flex items-center gap-2">
                                <img
                                    :src="`https://ui-avatars.com/api/?name=${employment_type.name.replace(' ', '+')}`"
                                    class="size-4 ring ring-white rounded-full"
                                />
                                <p
                                    class="text-neutral-700 dark:text-neutral-300 text-sm px-2 rounded-full flex items-center font-semibold"
                                >
                                    {{ employment_type.name }}
                                </p>
                            </div>

                            <div class="flex gap-2 items-center">
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
                :target_id="employment_type.id"
            />
        </Menu>
    </div>
</template>

<script setup lang="ts">
import { Menu, MenuButton } from '@headlessui/vue'
import { Icon } from '@iconify/vue'

import { DropdownMenuItem, EmploymentType } from '@/globalInterfaces'
import DropdownMenu from '../dropdown/DropdownMenu.vue'

defineProps<{
    employment_type: EmploymentType
    dropdown_menu: DropdownMenuItem[]
}>()
const $emit = defineEmits(['remove'])
</script>
