<template>
    <button
        :class="[
            active
                ? 'bg-emerald-600 text-emerald-50'
                : 'bg-white text-neutral-600',
            'flex gap-2 rounded-full items-center text-xs px-4 py-2 truncate',
        ]"
    >
        <Icon :icon="icon" class="size-4" />
        <p class="truncate">{{ name }}</p>
    </button>
</template>

<script setup lang="ts">
import { Icon } from '@iconify/vue'

defineProps<{
    name: string
    icon: string
    active: boolean
}>()
</script>
