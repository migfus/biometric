<template>
    <!-- NOTE: AUTH -->
    <div v-if="$page.props.auth" class="flex gap-4 items-center">
        <!-- NOTE NOTIFICATIONS -->
        <Menu as="div" class="relative">
            <MenuButton class="relative flex bg-brand-100 text-sm">
                <Icon
                    icon="ic:outline-notifications-none"
                    class="size-6 text-brand-600 bg-brand-50"
                />
                <span
                    v-if="$page.props.unread_notifications_count"
                    class="absolute -right-2 -top-2 rounded-full bg-rose-100 px-1.5 py-0.5 text-center text-[10px] font-semibold leading-none text-rose-700 ring ring-rose-100"
                >
                    {{ $page.props.unread_notifications_count }}
                </span>
            </MenuButton>

            <transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95"
            >
                <MenuItems
                    class="absolute right-0 z-10 w-80 origin-top-right rounded-xl bg-white py-1 shadow-lg focus:outline-none"
                >
                    <div class="flex justify-between mx-4 my-2 items-center">
                        <Link
                            :href="route('dashboard.notifications.index')"
                            class="text-gray-400 text-sm truncate"
                        >
                            Notifications
                        </Link>
                        <button
                            @click="readAll()"
                            class="text-gray-400 truncate ring p-1 rounded-full text-xs"
                        >
                            Read All
                        </button>
                    </div>

                    <template
                        v-if="
                            $page.props.notifications &&
                            $page.props.notifications.length > 0
                        "
                    >
                        <MenuItem
                            v-for="notification in $page.props.notifications"
                            :key="notification.id"
                            v-slot="{ active, close }"
                        >
                            <Link
                                @click="close"
                                :href="notification.href"
                                @mouseup="
                                    () => {
                                        close()
                                        markRead(notification.id)
                                    }
                                "
                                :class="[
                                    active ? 'bg-gray-100' : '',
                                    'block px-4 py-3 text-sm text-gray-700',
                                ]"
                            >
                                <div class="flex items-start gap-3">
                                    <Icon
                                        :icon="
                                            notification.read_at
                                                ? 'ic:outline-notifications-none'
                                                : 'material-symbols:notifications-active-outline-rounded'
                                        "
                                        class="mt-0.5 size-4 shrink-0 text-brand-600"
                                    />
                                    <div class="min-w-0">
                                        <p
                                            class="truncate font-semibold text-gray-800"
                                        >
                                            {{ notification.title }}
                                        </p>
                                        <p
                                            class="mt-1 line-clamp-2 text-xs text-gray-500"
                                        >
                                            {{ notification.content }}
                                        </p>
                                    </div>
                                </div>
                            </Link>
                        </MenuItem>
                    </template>

                    <div v-else class="px-4 py-3 text-sm text-gray-500">
                        No notifications yet.
                    </div>
                </MenuItems>
            </transition>
        </Menu>

        <!-- NOTE PROFILE -->
        <Menu as="div" class="relative">
            <MenuButton
                class="flex rounded-full bg-brand-100 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 w-8"
            >
                <img
                    class="h-8 w-8 rounded-full"
                    :src="$page.props.auth.avatar"
                    alt="Avatar"
                />
                <!-- <span
                    v-if="avatar_count > 0"
                    class="bg-white shadow rounded-full px-1.5 absolute right-0 bottom-0 -mb-1 -mr-1 text-xs font-medium text-brand-900 animate-bounce"
                >
                    {{ avatar_count }}
                </span> -->
            </MenuButton>
            <transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95"
            >
                <MenuItems
                    class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-3xl bg-white py-1 shadow-lg focus:outline-none"
                >
                    <div class="bg-brand-50 text-brand-800 m-2 rounded-xl p-2">
                        <div class="text-sm truncate font-semibold">
                            {{ $page.props.auth.name }}
                        </div>
                        <div class="text-xs truncate">
                            {{ $page.props.auth.email }}
                        </div>
                    </div>

                    <MenuItem
                        v-for="item in menu_items"
                        v-slot="{ active, close }"
                        class="flex flex-row align-middle"
                    >
                        <Link
                            v-if="item.href"
                            :href="item.href"
                            :class="[
                                active ? 'bg-gray-100' : '',
                                'px-4 py-2 text-sm text-gray-700 flex items-center gap-2 font-semibold w-full',
                            ]"
                        >
                            <Icon
                                :icon="item.icon"
                                class="text-gray-500 size-4 shrink-0 flex-none"
                            />
                            <p class="truncate min-w-0">
                                {{ item.name }}
                            </p>
                        </Link>
                        <Link
                            v-else
                            @click="close"
                            :href="route('login.logout')"
                            method="post"
                            as="button"
                            :class="[
                                active ? 'bg-gray-100' : '',
                                'px-4 py-2 text-sm text-gray-700 flex items-center gap-2 w-full rounded-b-2xl font-semibold',
                            ]"
                        >
                            <Icon
                                :icon="item.icon"
                                class="text-gray-500 size-4 shrink-0"
                            />
                            <p>
                                {{ item.name }}
                            </p>
                        </Link>
                    </MenuItem>
                </MenuItems>
            </transition>
        </Menu>
    </div>

    <!-- NOTE: GUEST -->
    <div v-else>
        <Link :href="route('login.index')">
            <AppButton color="brand" icon="material-symbols:login" size="sm">
                Login
            </AppButton>
        </Link>
    </div>
</template>

<script setup lang="ts">
import AppButton from '@/components/form/AppButton.vue'
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { Link } from '@inertiajs/vue3'

import { Icon } from '@iconify/vue'
import { CSidebarNavigation } from '@/constants'
import { TopNavigation } from '@/globalInterfaces'
import { router } from '@inertiajs/vue3'

const menu_items: TopNavigation[] = [
    ...CSidebarNavigation(),
    {
        name: 'Logout',
        icon: 'ic:outline-logout',
        href: '',
        components: [],
    },
]

function markRead(notification_id: string) {
    router.put(
        route('dashboard.notifications.update', notification_id),
        {},
        { preserveState: true, only: ['notifications'] },
    )
}

function readAll() {
    router.put(
        route('dashboard.notifications.update', 0),
        {},
        { preserveState: true, only: ['notifications'] },
    )
}
</script>
