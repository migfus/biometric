<template>
    <button :class="[active ? 'bg-emerald-600 text-emerald-50' : 'bg-white text-neutral-600', 'flex flex-col gap-0 rounded-full items-center text-xs px-4 py-2 ']">
        <Icon :icon="icon" class="size-4"/>
        <p>{{ name}}</p>
    </button>
</template>

<script setup lang="ts">
import {Icon} from '@iconify/vue'

defineProps<{
    name: string
    icon: string
    active: boolean
}>()
</script>
