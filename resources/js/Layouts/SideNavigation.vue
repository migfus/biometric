<template>
    <div>
        <!-- SECTION: MOBILE VIEW -->
        <TransitionRoot as="template" :show="sidebar_open">
            <Dialog
                as="div"
                class="relative z-40 md:hidden"
                @close="sidebar_open = false"
            >
                <TransitionChild
                    as="template"
                    enter="transition-opacity ease-linear duration-300"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="transition-opacity ease-linear duration-300"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div
                        class="fixed inset-0 bg-gray-600/75 backdrop-blur-sm"
                    />
                </TransitionChild>

                <div class="fixed inset-0 z-40 flex">
                    <TransitionChild
                        as="template"
                        enter="transition ease-in-out duration-300 transform"
                        enter-from="-translate-x-full"
                        enter-to="translate-x-0"
                        leave="transition ease-in-out duration-300 transform"
                        leave-from="translate-x-0"
                        leave-to="-translate-x-full"
                    >
                        <DialogPanel
                            class="relative flex w-full max-w-xs flex-1 flex-col bg-white pt-5 pb-4"
                        >
                            <TransitionChild
                                as="template"
                                enter="ease-in-out duration-300"
                                enter-from="opacity-0"
                                enter-to="opacity-100"
                                leave="ease-in-out duration-300"
                                leave-from="opacity-100"
                                leave-to="opacity-0"
                            >
                                <div class="absolute top-0 right-0 -mr-12 pt-2">
                                    <button
                                        type="button"
                                        class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                                        @click="sidebar_open = false"
                                    >
                                        <Icon
                                            icon="ic:outline-close"
                                            class="h-6 w-6 text-white"
                                            aria-hidden="true"
                                        />
                                    </button>
                                </div>
                            </TransitionChild>
                            <div
                                class="flex shrink-0 items-center px-4 mx-4 text-neutral-700 rounded-2xl bg-neutral-100 border border-neutral-300"
                            >
                                <TopNavigationLogo />
                                <div class="flex flex-col gap-1">
                                    <p class="text-sm font-semibold">
                                        Check System
                                    </p>
                                    <p class="text-sm font-semibold">OHRM</p>
                                </div>
                            </div>
                            <div class="mt-5 h-0 flex-1 overflow-y-auto">
                                <nav class="space-y-8 px-2">
                                    <SideNavigationContent
                                        title="Dashboard"
                                        :data="CSidebarNavigation()"
                                        v-model="sidebar_open"
                                    />
                                    <SideNavigationContent
                                        title="Pages"
                                        :data="CTopNavigation"
                                        v-model="sidebar_open"
                                    />
                                </nav>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                    <div class="w-14 shrink-0" aria-hidden="true">
                        <!-- Dummy element to force sidebar to shrink to fit close icon -->
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- SECTION: DESKTOP VIEW-->
        <div class="hidden md:fixed md:inset-y-0 md:flex md:w-64 md:flex-col">
            <div class="flex h-full w-80">
                <!-- SECTION: GROUP SIDE -->
                <div
                    v-if="$page.props.auth"
                    class="flex items-center flex-col bg-neutral-950 p-2 gap-0"
                >
                    <Link
                        href="/"
                        class="flex items-center bg-brand p-2 shadow cursor-pointer bg-neutral-700 rounded-2xl"
                    >
                        <img
                            class="size-10"
                            src="https://cmuohrm.site/images/ohrm.png"
                            alt="OHRM Logo"
                        />
                    </Link>

                    <Link
                        href="/dashboard"
                        @click="selected_nav = 'dashboard'"
                        class="flex items-center p-1 cursor-pointer my-4 relative"
                    >
                        <div
                            v-if="selected_nav === 'dashboard'"
                            class="size-4 border-l-4 border-yellow-500 absolute -ml-3 transition-all"
                        />
                        <img
                            :src="$page.props.auth.avatar"
                            class="rounded-full size-12"
                            alt="OHRM Logo"
                        />
                    </Link>

                    <!-- <Link
                        v-for="item in $page.props.auth.my_groups"
                        @click="selected_nav = item.group.id"
                        :href="`/dashboard/g/${item.group.id}`"
                        class="rounded-xl p-2 w-14 relative"
                    >
                        <div
                            v-if="selected_nav === item.group.id"
                            class="size-4 border-l-4 border-yellow-500 absolute -ml-3 top-5 transition-all"
                        />
                        <img
                            :src="item.group.avatar"
                            class="size-10 rounded-xl"
                        />
                    </Link> -->

                    <Link
                        href="/dashboard/my-groups"
                        @click="selected_nav = 'dashboard'"
                        class="rounded-xl p-2 relative bg-neutral-600"
                    >
                        <!-- <div v-if="selected_nav === item.group.id" class="size-4 border-l-4 border-yellow-500 absolute -ml-3 top-5 transition-all" /> -->
                        <Icon
                            icon="ic:outline-dashboard-customize"
                            class="size-6 rounded-xl text-neutral-50"
                        />
                    </Link>
                </div>

                <!-- SECTION: MENU SIDE -->
                <div class="flex min-h-0 flex-1 flex-col bg-neutral-700 w-200">
                    <div class="flex flex-1 flex-col overflow-y-auto">
                        <nav class="flex-1 space-y-6 px-2 py-4">
                            <SideNavigationContent
                                title="Dashboard"
                                :data="CSidebarNavigation()"
                                v-model="sidebar_open"
                            />
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION: TOP NAVIGATION -->
        <div class="flex flex-col md:pl-80">
            <div
                class="sticky top-0 z-20 flex h-16 shrink-0 bg-neutral-50 shadow"
            >
                <button
                    type="button"
                    class="border-r border-gray-200 px-4 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-neutral-500 md:hidden"
                    @click="sidebar_open = true"
                >
                    <span class="sr-only">Open sidebar</span>
                    <Icon
                        icon="ic:outline-dehaze"
                        class="h-6 w-6"
                        aria-hidden="true"
                    />
                </button>
                <div class="flex flex-1 justify-end px-4 max-w-7xl mx-auto">
                    <div class="ml-4 flex items-center md:ml-6">
                        <TopNavigationProfileDropdown />
                    </div>
                </div>
            </div>

            <!-- NOTE: CONTENTS -->
            <main class="flex-1">
                <div class="py-6 sm:mx-4">
                    <div class="max-w-7xl mx-auto">
                        <slot></slot>
                    </div>
                </div>

                <!-- <slot name="footer"></slot> -->
            </main>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppInput from '@/Components/form/AppInput.vue'
import {
    Dialog,
    DialogPanel,
    TransitionChild,
    TransitionRoot,
} from '@headlessui/vue'
import { Link } from '@inertiajs/vue3'
import SideNavigationContent from './SideNavigationContent.vue'
import TopNavigationLogo from './TopNavigationLogo.vue'
import TopNavigationProfileDropdown from './TopNavigationProfileDropdown.vue'

import {
    CAdminNavigation,
    CSidebarNavigation,
    CTopNavigation,
} from '@/constants'
import { Icon } from '@iconify/vue'
import { ref } from 'vue'

const sidebar_open = ref(false)
const c_admin_navigation = CAdminNavigation()

const selected_nav = ref('dashboard')
</script>
