<template>
    <div class="bg-neutral-100 dark:bg-neutral-900 flex flex-col gap-4">
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
                <div class="mx-auto max-w-7xl">
                    <slot></slot>
                </div>

                <div
                    class="flex md:hidden fixed bottom-0 left-0 right-0 items-center justify-center"
                >
                    <div
                        class="flex gap-2 bg-white/80 dark:bg-neutral-800/80 backdrop-blur-lg shadow-lg ring ring-neutral-200 dark:ring-neutral-700 w-full sm:w-100 sm:rounded-full justify-between p-2"
                    >
                        <BottomMenu
                            name="Dashboard"
                            icon="ic:outline-space-dashboard"
                            :href="route('dashboard.index')"
                        />
                        <!-- <BottomMenu
                            name="Checks"
                            icon="mingcute:time-line"
                            :href="route('dashboard.checks.index')"
                        />
                        <BottomMenu
                            name="Employees"
                            icon="ic:outline-people"
                            :href="route('dashboard.employees.index')"
                        />
                        <BottomMenu
                            name="More"
                            icon="material-symbols:list"
                            :callback="
                                () => {
                                    sidebar_open = true
                                }
                            "
                        /> -->
                    </div>
                </div>

                <template #footer>
                    <Footer />
                </template>
            </SideNavigation>

            <TopNavigation v-else>
                <slot></slot>

                <!-- SECTION: BOTTOM MENU -->
                <div
                    class="fixed bottom-0 left-0 right-0 flex items-center justify-center sm:mb-2"
                >
                    <div
                        class="flex gap-2 bg-white/80 dark:bg-neutral-800/80 backdrop-blur-lg shadow-lg ring ring-neutral-200 dark:ring-neutral-700 w-full sm:w-100 sm:rounded-full justify-between p-2"
                    >
                        <BottomMenu
                            name="Report"
                            icon="material-symbols:report-outline"
                            :href="route('reports.create')"
                        />
                        <BottomMenu
                            name="Camera"
                            icon="mdi:camera-outline"
                            :href="route('camera.index')"
                        />
                        <BottomMenu
                            name="Records"
                            icon="material-symbols:list"
                            :href="route('reports.index')"
                        />
                    </div>
                </div>

                <Footer />
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
import Footer from './Footer.vue'
import BasicTransition from '@/components/transitions/BasicTransition.vue'

import { Head, usePage } from '@inertiajs/vue3'
import { notify } from 'notiwind'
import { watch, ref, onMounted, onUnmounted } from 'vue'
import { storeToRefs } from 'pinia'
import { usePreviewPhotoStore } from '@/stores/previewPhoto.store'
import { ably } from '@/ably'

const $page = usePage()
const $previewPhotoStore = usePreviewPhotoStore()
const { photos } = storeToRefs($previewPhotoStore)
const channel = ably.channels.get('notifications')

const sidebar_open = ref<boolean>(false)

channel.subscribe('notifications', (msg) => {
    console.log(msg.data)
})

const handler = (message: any) => {
    console.log(message.data)
    alert('New notification received: ' + message.data)
}

onMounted(() => {
    channel.subscribe('new-notification', handler)
})

onUnmounted(() => {
    channel.unsubscribe('new-notification', handler)
})

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
