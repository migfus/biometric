<template>
    <Link
        v-if="href"
        :href="href"
        :class="[
            isActive
                ? 'bg-emerald-600/90 text-emerald-50'
                : ' text-neutral-600',
            'flex flex-col gap-0 rounded-full items-center text-sm px-4 py-2 truncate font-semibold grow',
        ]"
    >
        <Icon :icon="icon" class="size-5" />
        <p class="truncate text-[0.7rem]">{{ name }}</p>
    </Link>
    <button
        v-else-if="callback"
        @click="callback()"
        :class="[
            isActive
                ? 'bg-emerald-600/90 text-emerald-50'
                : ' text-neutral-600',
            'flex flex-col gap-0 rounded-full items-center text-sm px-4 py-2 truncate font-semibold grow',
        ]"
    >
        <Icon :icon="icon" class="size-4" />
        <p class="truncate text-xs">{{ name }}</p>
    </button>
</template>

<script setup lang="ts">
import { Icon } from '@iconify/vue'
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const { href, callback } = defineProps<{
    name: string
    icon: string
    href?: string
    callback?: () => void
}>()

const $page = usePage()

const isActive = computed(() => {
    if (href) {
        const currentPath = normalizePath($page.url)
        const targetPath = normalizePath(href)

        return currentPath === targetPath
    } else {
        return false
    }
})

function normalizePath(value: string): string {
    try {
        const parsed = new URL(value, window.location.origin)

        return parsed.pathname.replace(/\/+$/, '') || '/'
    } catch {
        return value.replace(/\/+$/, '') || '/'
    }
}
</script>
