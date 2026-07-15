<template>
    <!-- NOTE: AUTH -->
    <div v-if="$page.props.auth" class="flex gap-4 items-center">
        <!-- NOTE NOTIFICATIONS -->
        <Menu as="div" class="relative">
            <MenuButton class="flex bg-brand-100 text-sm">
                <Icon
                    icon="ic:outline-notifications-none"
                    class="size-6 text-brand-600 bg-brand-50"
                />
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
                    class="absolute right-0 z-10 w-48 origin-top-right rounded-xl bg-white py-1 shadow-lg focus:outline-none"
                >
                    <div class="text-gray-400 ml-3 my-2 text-sm truncate">
                        Notifications
                    </div>
                    <MenuItem v-slot="{ active, close }">
                        <Link
                            @click="close"
                            :href="route('dashboard.my-groups.index')"
                            :class="[
                                active ? 'bg-gray-100' : '',
                                'block px-4 py-2 text-sm text-gray-700',
                            ]"
                        >
                            <Icon
                                icon="ic:outline-plus"
                                class="text-gray-500h-5 w-4 shrink-0 sm:-ml-1 mr-2 inline mb-1"
                            />
                            Group Notification 1
                        </Link>
                    </MenuItem>
                    <MenuItem v-slot="{ active, close }">
                        <Link
                            @click="close"
                            :href="route('dashboard.my-groups.index')"
                            :class="[
                                active ? 'bg-gray-100' : '',
                                'block px-4 py-2 text-sm text-gray-700',
                            ]"
                        >
                            <Icon
                                icon="ic:outline-plus"
                                class="text-gray-500h-5 w-4 shrink-0 sm:-ml-1 mr-2 inline mb-1"
                            />
                            Group Notification 2
                        </Link>
                    </MenuItem>

                    <MenuItem v-slot="{ active, close }">
                        <Link
                            @click="close"
                            :href="route('dashboard.my-groups.index')"
                            :class="[
                                active ? 'bg-gray-100' : '',
                                'block px-4 py-2 text-sm text-gray-700 truncate',
                            ]"
                        >
                            <Icon
                                icon="ic:outline-table-rows"
                                class="text-gray-500h-5 w-4 shrink-0 sm:-ml-1 mr-2 inline mb-1"
                            />
                            +1 New user added
                        </Link>
                    </MenuItem>

                    <MenuItem v-slot="{ active, close }">
                        <Link
                            @click="close"
                            :href="route('dashboard.my-groups.index')"
                            :class="[
                                active ? 'bg-gray-100' : '',
                                'block px-4 py-2 text-sm text-gray-700 truncate',
                            ]"
                        >
                            <Icon
                                icon="ic:outline-table-rows"
                                class="text-gray-500h-5 w-4 shrink-0 sm:-ml-1 mr-2 inline mb-1"
                            />
                            Groups has been updated
                        </Link>
                    </MenuItem>
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
                            @mouseup="() => close()"
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
import AppButton from '@/Components/form/AppButton.vue'
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { Link } from '@inertiajs/vue3'

import { Icon } from '@iconify/vue'
import { CSidebarNavigation } from '@/constants'
import { TopNavigation } from '@/globalInterfaces'

const menu_items: TopNavigation[] = [
    ...CSidebarNavigation(),
    {
        name: 'Logout',
        icon: 'ic:outline-logout',
        href: '',
        components: [],
    },
]
</script>
