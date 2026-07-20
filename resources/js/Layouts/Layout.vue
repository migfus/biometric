<template>
    <div class="bg-neutral-100 flex flex-col gap-4">
        <Head :title="$page.props.page_title" />

        <NotiWind />
        <BasicTransition>
            <ImageModal v-if="photos.length > 0" :photos="photos" />

            <div v-else-if="$page.props.navigation == 'camera'">
                <slot></slot>
            </div>

            <SideNavigation
                v-else-if="$page.props.navigation == 'sidebar'"
                v-model="sidebar_open"
            >
                <div class="mx-auto max-w-7xl mb-12">
                    <slot></slot>
                </div>

                <div
                    class="flex md:hidden fixed bottom-0 left-0 right-0 items-center justify-center"
                >
                    <div
                        class="flex gap-2 bg-white/80 backdrop-blur-lg m-2 p-1 rounded-3xl shadow-lg"
                    >
                        <BottomMenu
                            name="Dashboard"
                            icon="ic:outline-space-dashboard"
                            :href="route('dashboard.index')"
                        />
                        <BottomMenu
                            name="Checks"
                            icon="mingcute:time-line"
                            :href="route('dashboard.checks.index')"
                        />
                        <!-- <MenuButton
                            name="Employees"
                            icon="ic:outline-people"
                            :href="route('dashboard.employees.index')"
                        /> -->
                        <BottomMenu
                            name="More"
                            icon="material-symbols:list"
                            :callback="
                                () => {
                                    sidebar_open = true
                                }
                            "
                        />
                    </div>
                </div>
            </SideNavigation>

            <TopNavigation v-else>
                <slot></slot>

                <!-- SECTION: BOTTOM MENU -->
                <div
                    class="fixed bottom-0 left-0 right-0 flex items-center justify-center"
                >
                    <div
                        class="flex gap-2 bg-white/80 backdrop-blur-lg m-2 p-1 rounded-3xl shadow-lg"
                    >
                        <BottomMenu
                            name="Time In-Out"
                            icon="mingcute:time-line"
                            :href="route('index')"
                        />
                        <BottomMenu
                            name="Camera"
                            icon="mdi:camera-outline"
                            :href="route('camera.index')"
                        />
                        <BottomMenu
                            name="Records"
                            icon="material-symbols:list"
                            :href="route('records.index')"
                        />
                    </div>
                </div>
            </TopNavigation>
        </BasicTransition>

        <!-- SECTION: BOTTOM SHEET -->
        <ModalPrompt />
    </div>
</template>

<script setup lang="ts">
import NotiWind from '@/components/notifications/NotiWind.vue'
import TopNavigation from '@/layouts/TopNavigation.vue'
import SideNavigation from '@/layouts/SideNavigation.vue'
import ModalPrompt from '@/layouts/ModalPrompt.vue'
import ImageModal from '@/components/modals/ImageModal.vue'
import BottomMenu from '@/layouts/BottomMenu.vue'

import { Head, usePage } from '@inertiajs/vue3'
import { notify } from 'notiwind'
import { watch, ref } from 'vue'
import { storeToRefs } from 'pinia'
import { usePreviewPhotoStore } from '@/stores/previewPhoto.store'
import BasicTransition from '@/components/transitions/BasicTransition.vue'

const $page = usePage()
const $previewPhotoStore = usePreviewPhotoStore()
const { photos } = storeToRefs($previewPhotoStore)

const sidebar_open = ref<boolean>(false)

watch(
    () => $page.props.flash,
    () => {
        if ($page.props.flash?.success) {
            notify(
                {
                    group: 'success',
                    title: $page.props.flash?.success.title,
                    content: $page.props.flash?.success.content,
                },
                4000,
            )
        }
        if ($page.props.flash?.error) {
            notify(
                {
                    group: 'error',
                    title: $page.props.flash?.error.title,
                    content: $page.props.flash?.error.content,
                },
                4000,
            )
        }
    },
)
</script>
