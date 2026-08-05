<template>
    <BasicTransition>
        <MenuItems
            class="py-2 absolute right-0 z-10 mr-4 mt-10 w-44 origin-top-right rounded-3xl bg-white dark:bg-neutral-800/90 backdrop-blur-lg shadow-lg ring-1 ring-neutral-200 dark:ring-neutral-600 ring-opacity-5 focus:outline-hidden"
        >
            <MenuItem
                v-for="item in dropdown_menu.filter(
                    (item) => !(remove_names ?? []).includes(item.name),
                )"
                :key="item.name"
                class="flex items-center rounded-xl cursor-pointer font-semibold"
            >
                <button
                    v-if="item.color == 'danger'"
                    type="button"
                    @click="item.callback(target_id)"
                    class="w-full text-left hover:bg-red-50 dark:hover:bg-red-950 hover:text-red-700 dark:hover:text-red-200 text-red-500 dark:text-red-300"
                >
                    <div :class="['px-4 py-2 text-sm flex gap-2 items-center']">
                        <Icon :icon="item.icon" />
                        <p>{{ item.name }}</p>
                    </div>
                </button>
                <button
                    v-else
                    type="button"
                    @click="item.callback(target_id)"
                    class="w-full text-left hover:bg-neutral-600 dark:hover:bg-neutral-600 hover:text-neutral-700 dark:hover:text-neutral-200 text-brand-200 dark:text-neutral-300"
                >
                    <div :class="['px-4 py-2 text-sm flex gap-2 items-center']">
                        <Icon :icon="item.icon" />
                        <p>{{ item.name }}</p>
                    </div>
                </button>
            </MenuItem>
        </MenuItems>
    </BasicTransition>
</template>

<script setup lang="ts">
import BasicTransition from '@/components/transitions/BasicTransition.vue'
import { MenuItems, MenuItem } from '@headlessui/vue'
import { Icon } from '@iconify/vue'

import { DropdownMenuItem } from '@/globalInterfaces'

defineProps<{
    dropdown_menu: DropdownMenuItem[]
    target_id: number | string
    remove_names?: string[]
}>()
</script>
