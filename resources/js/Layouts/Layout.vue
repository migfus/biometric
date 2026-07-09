<template>
    <div class="bg-neutral-100 flex flex-col gap-4">
        <Head :title="$page.props.page_title" />

        <NotiWind />

        <SideNavigation v-if="$page.props.navigation == 'sidebar'">
            <div class="mx-auto max-w-7xl">
                <slot></slot>
            </div>
        </SideNavigation>

        <TopNavigation v-else>
            <slot></slot>
        </TopNavigation>

        <!-- SECTION: BOTTOM SHEET -->
        <ModalPrompt />
    </div>
</template>

<script setup lang="ts">
import NotiWind from '@/Components/notifications/NotiWind.vue'
import TopNavigation from './TopNavigation.vue'
import SideNavigation from './SideNavigation.vue'
import ModalPrompt from './ModalPrompt.vue'

import { Head, usePage } from '@inertiajs/vue3'
import { notify } from 'notiwind'
import { watch } from 'vue'

const $page = usePage()

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
