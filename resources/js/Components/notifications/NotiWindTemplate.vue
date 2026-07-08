<template>
    <NotificationGroup :group="groupName" position="bottom">
        <div class="fixed inset-0 flex items-end justify-end p-6 px-4 py-6 pointer-events-none z-30 pt-16">
            <div class="w-full max-w-sm">
                <Notification
                    v-slot="{ notifications, close }"
                    enter="transform ease-out duration-300 transition"
                    enter-from="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-4"
                    enter-to="translate-y-0 opacity-100 sm:translate-x-0"
                    leave="transition ease-in duration-500"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                    move="transition duration-500"
                    move-delay="delay-300"
                >
                    <div
                        class="flex w-full max-w-sm mx-auto mt-2 overflow-hidden rounded-xl object-shadow shadow-md bg-brand-800/80 backdrop-blur-sm"
                        v-for="notification in notifications"
                        :key="notification.id"
                    >
                        <div
                            class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg shadow-lg ring-1 ring-black ring-opacity-5"
                        >
                            <div class="p-4">
                                <div class="flex items-start">
                                    <div class="shrink-0">
                                        <Icon
                                            icon="material-symbols:check-circle"
                                            v-if="groupName == 'success'"
                                            class="size-6 text-brand-200"
                                            aria-hidden="true"
                                        />
                                        <Icon icon="material-symbols:close-rounded" v-else class="size-6 text-red-400" aria-hidden="true" />
                                    </div>
                                    <div class="ml-3 w-0 flex-1 pt-0.5">
                                        <p class="text-sm font-medium text-neutral-200">{{ notification.title }}</p>
                                        <p class="mt-1 text-sm text-neutral-300">{{ notification.content }}</p>
                                    </div>
                                    <div class="ml-4 flex shrink-0">
                                        <button
                                            @click="close(notification.id)"
                                            type="button"
                                            class="object-shadow inline-flex rounded-md bg-neutral-950 text-gray-400 hover:text-gray-500 focus:outline-hidden focus:ring-2 focus:ring-brand-500 focus:ring-offset-2"
                                        >
                                            <span class="sr-only">Close</span>
                                            <Icon icon="material-symbols:close-rounded" class="h-5 w-5" aria-hidden="true" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </Notification>
            </div>
        </div>
    </NotificationGroup>
</template>

<script setup lang="ts">
import { Icon } from '@iconify/vue'
import { NotificationGroup, Notification } from 'notiwind'

defineProps<{
    groupName: string
}>()
</script>
