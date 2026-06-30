

<template>
    <div
        class="bg-neutral-100 flex flex-col gap-4"
    >
        <TopNavigation />

        <slot />
    </div>
</template>

<script setup lang="ts">
import ApplicationLogo from '@/Components/ApplicationLogo.vue'
import { Link } from '@inertiajs/vue3'
import TopNavigation from './TopNavigation.vue';
</script>
