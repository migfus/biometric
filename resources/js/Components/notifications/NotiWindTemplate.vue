<template>
    <NotificationGroup :group="groupName" position="bottom">
        <div
            class="fixed inset-0 flex items-end justify-center p-6 px-4 py-6 pointer-events-none z-30"
        >
            <div class="w-auto">
                <Notification
                    v-slot="{ notifications, close }"
                    enter="transform ease-out duration-300 transition"
                    enter-from="translate-y-4 opacity-0"
                    enter-to="translate-y-0 opacity-100"
                    leave="transition ease-in duration-500"
                    leave-from="opacity-100"
                    leave-to="opacity-0 translate-y-4"
                    move="transition duration-500"
                    move-delay="delay-300"
                >
                    <div
                        class="flex w-auto mx-auto mt-2 overflow-hidden rounded-xl object-shadow shadow-md bg-white/80 backdrop-blur-sm"
                        v-for="notification in notifications"
                        :key="notification.id"
                    >
                        <div
                            class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg shadow-lg ring-1 ring-black ring-opacity-5"
                        >
                            <div class="p-4">
                                <div
                                    class="flex items-center justify-between gap-4"
                                >
                                    <Icon
                                        icon="material-symbols:check-circle"
                                        v-if="groupName == 'success'"
                                        class="size-4 text-green-700"
                                        aria-hidden="true"
                                    />
                                    <Icon
                                        icon="material-symbols:close-rounded"
                                        v-else
                                        class="size-4 text-red-400"
                                        aria-hidden="true"
                                    />

                                    <p class="grow text-sm text-neutral-700">
                                        {{ notification.content }}
                                    </p>

                                    <button
                                        @click="close(notification.id)"
                                        type="button"
                                        class="object-shadow inline-flex rounded-md bg-neutral-200 text-gray-600 hover:text-gray-500 focus:outline-hidden focus:ring-2 focus:ring-neutral-500 focus:ring-offset-2"
                                    >
                                        <Icon
                                            icon="material-symbols:close-rounded"
                                            class="size-4"
                                            aria-hidden="true"
                                        />
                                    </button>
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
