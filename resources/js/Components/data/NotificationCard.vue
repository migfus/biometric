<template>
    <div class="flex flex-col">
        <Menu as="div" :class="['flex flex-col gap-2 relative']">
            <MenuButton
                :class="[
                    read
                        ? 'bg-white/50 text-neutral-400 dark:bg-neutral-900/50 dark:text-neutral-500'
                        : 'bg-white dark:bg-neutral-800',
                    ' flex flex-col gap-1 p-3 rounded-2xl border border-neutral-200 dark:border-neutral-700 dark:text-neutral-300',
                ]"
            >
                <div class="flex flex-col gap-2">
                    <div class="flex gap-2 justify-between">
                        <p class="text-sm font-semibold truncate">
                            {{ notification.data.title }}
                        </p>

                        <div
                            class="text-xs flex gap-1 items-center text-neutral-500 flex-none"
                        >
                            <p>
                                {{
                                    messengerStyleTime(notification.created_at)
                                }}
                            </p>
                            <Icon icon="nrk:more" class="size-4" />
                        </div>
                    </div>

                    <div class="flex justify-start">
                        <p class="text-sm font-normal truncate">
                            {{ notification.data.content }}
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
                        <button
                            @click="markToRead()"
                            :class="[
                                active ? 'bg-neutral-50' : '',
                                'px-4 py-2 text-sm text-brand-200 flex hover:bg-neutral-200 dark:hover:bg-dark-003 gap-2 items-center w-full',
                            ]"
                        >
                            <Icon icon="ic:baseline-notifications-none" />
                            <p v-if="read">Mark Unread</p>
                            <p v-else>Mark Read</p>
                        </button>
                    </MenuItem>

                    <MenuItem
                        class="flex items-center rounded-xl cursor-pointer"
                    >
                        <button
                            type="button"
                            @click="deleteNotification()"
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
import BasicTransition from '@/components/transitions/BasicTransition.vue'
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { Icon } from '@iconify/vue'
import { router } from '@inertiajs/vue3'

import { AppNotification } from '@/globalInterfaces'
import { messengerStyleTime } from '@/utils'

const { notification } = defineProps<{
    notification: AppNotification
    read?: boolean
}>()

function deleteNotification(): void {
    router.delete(route('dashboard.notifications.destroy', notification.id), {
        preserveState: true,
        only: ['active_notifications', 'read_notifications'],
    })
}

function markToRead(): void {
    router.put(route('dashboard.notifications.update', notification.id), {
        preserveState: true,
        only: ['active_notifications', 'read_notifications'],
    })
}
</script>
