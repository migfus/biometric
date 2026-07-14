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
                    class="fixed bottom-0 left-0 right-0 flex items-center justify-center"
                >
                    <div
                        class="flex gap-2 bg-neutral-200/50 backdrop-blur-lg m-2 p-1 rounded-3xl shadow-lg"
                    >
                        <MenuButton
                            name="Dashboard"
                            icon="ic:outline-space-dashboard"
                            :href="route('dashboard.index')"
                        />
                        <MenuButton
                            name="Checks"
                            icon="mingcute:time-line"
                            :href="route('dashboard.checks.index')"
                        />
                        <!-- <MenuButton
                            name="Employees"
                            icon="ic:outline-people"
                            :href="route('dashboard.employees.index')"
                        /> -->
                        <MenuButton
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
                        class="flex gap-2 bg-neutral-200/50 backdrop-blur-lg m-2 p-1 rounded-3xl shadow-lg"
                    >
                        <MenuButton
                            name="Time In-Out"
                            icon="mingcute:time-line"
                            :href="route('index')"
                        />
                        <MenuButton
                            name="Camera"
                            icon="mdi:camera-outline"
                            :href="route('camera.index')"
                        />
                        <MenuButton
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
import NotiWind from '@/Components/notifications/NotiWind.vue'
import TopNavigation from './TopNavigation.vue'
import SideNavigation from './SideNavigation.vue'
import ModalPrompt from './ModalPrompt.vue'
import ImageModal from '@/Components/modals/ImageModal.vue'
import MenuButton from '../Pages/MenuButton.vue'

import { Head, usePage } from '@inertiajs/vue3'
import { notify } from 'notiwind'
import { watch, ref } from 'vue'
import { storeToRefs } from 'pinia'
import { usePreviewPhotoStore } from '@/Stores/previewPhoto.store'
import BasicTransition from '@/Components/transitions/BasicTransition.vue'

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
