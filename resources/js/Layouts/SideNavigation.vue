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
                        class="fixed inset-0 bg-gray-600/75 dark:bg-black/75 backdrop-blur-sm"
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
                            class="relative flex w-full max-w-xs flex-1 flex-col bg-white dark:bg-neutral-800 pt-5 pb-4"
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
                            <Link
                                :href="route('index')"
                                class="flex shrink-0 items-center px-4 mx-4 text-neutral-700 dark:text-neutral-300 rounded-2xl bg-neutral-100 dark:bg-neutral-900 border border-neutral-300 dark:border-neutral-800"
                            >
                                <TopNavigationLogo />
                                <div class="flex flex-col gap-1">
                                    <p class="text-sm font-semibold">
                                        Check System
                                    </p>
                                    <p class="text-sm font-semibold">OHRM</p>
                                </div>
                            </Link>
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
            <div class="flex h-full w-60">
                <!-- SECTION: MENU SIDE -->
                <div
                    class="flex min-h-0 flex-1 flex-col bg-white dark:bg-neutral-800 w-200 border-r border-gray-200 dark:border-neutral-700"
                >
                    <div class="flex flex-1 flex-col overflow-y-auto">
                        <Link
                            :href="route('index')"
                            class="bg-neutral-100 dark:bg-neutral-900 flex items-center justify-start m-1 rounded-2xl border border-neutral-300 dark:border-neutral-700"
                        >
                            <TopNavigationLogo />
                            <div class="font-semibold text-sm text-neutral-500">
                                <p
                                    class="text-neutral-700 dark:text-neutral-300"
                                >
                                    Check System
                                </p>
                                <p class="dark:text-neutral-300">OHRM</p>
                            </div>
                        </Link>

                        <nav class="space-y-6 px-2 py-4">
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
                </div>
            </div>
        </div>

        <!-- SECTION: TOP NAVIGATION -->
        <div class="flex flex-col md:pl-60">
            <div
                class="sticky top-0 flex h-16 shrink-0 bg-white/80 dark:bg-neutral-800/80 backdrop-blur-sm border-b border-gray-200 dark:border-neutral-700 z-10"
            >
                <button
                    type="button"
                    class="border-r border-gray-200 dark:border-neutral-700 px-4 text-gray-500 dark:text-neutral-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-neutral-500 md:hidden"
                    @click="sidebar_open = true"
                >
                    <span class="sr-only">Open sidebar</span>
                    <Icon
                        icon="ic:outline-dehaze"
                        class="size-6"
                        aria-hidden="true"
                    />
                </button>
                <div class="flex flex-1 justify-end px-4 max-w-7xl mx-auto">
                    <div class="ml-4 flex items-center md:ml-6 gap-4">
                        <div
                            class="text-blue-500 px-2 border rounded-full text-sm font-semibold"
                        >
                            Beta
                        </div>
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

                <slot name="footer"></slot>
            </main>
        </div>
    </div>
</template>

<script setup lang="ts">
import SideNavigationContent from '@/layouts/SideNavigationContent.vue'
import TopNavigationLogo from '@/layouts/TopNavigationLogo.vue'
import TopNavigationProfileDropdown from '@/layouts/TopNavigationProfileDropdown.vue'
import {
    Dialog,
    DialogPanel,
    TransitionChild,
    TransitionRoot,
} from '@headlessui/vue'
import { Link } from '@inertiajs/vue3'

import { CSidebarNavigation, CTopNavigation } from '@/constants'
import { Icon } from '@iconify/vue'

const sidebar_open = defineModel<boolean>({ default: false })
</script>
