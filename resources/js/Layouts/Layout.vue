<template>
    <div class="bg-neutral-100 flex flex-col gap-4">
        <Head :title="$page.props.page_title" />

        <SideNavigation v-if="$page.props.sidebar">
            <div class="mx-auto max-w-7xl">
                <slot></slot>
            </div>
        </SideNavigation>

        <TopNavigation v-else>
            <slot></slot>
        </TopNavigation>
    </div>
</template>

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import TopNavigation from './TopNavigation.vue'
import SideNavigation from './SideNavigation.vue'
</script>
