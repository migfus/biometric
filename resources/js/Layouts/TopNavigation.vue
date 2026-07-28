<template>
    <div class="mb-4">
        <div
            class="flex justify-between gap-4 p-4 py-2 items-center bg-white dark:bg-neutral-800 border-b border-neutral-200 dark:border-neutral-700"
        >
            <Link :href="route('index')">
                <img
                    src="https://upload.wikimedia.org/wikipedia/en/a/a0/Central_Mindanao_University_logo.png"
                    class="size-12"
                />
            </Link>
            <div class="flex items-center gap-4">
                <div
                    class="text-blue-500 px-2 border rounded-full text-sm font-semibold"
                >
                    Beta
                </div>
                <TopNavigationProfileDropdown />
            </div>
        </div>
        <slot></slot>
    </div>
</template>

<script setup lang="ts">
import TopNavigationProfileDropdown from './TopNavigationProfileDropdown.vue'
import { Link } from '@inertiajs/vue3'
</script>
