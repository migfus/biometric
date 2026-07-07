<template>
    <div>
        <div class="flex justify-between gap-4 p-4 py-2 items-center bg-white">
            <div>
                <img
                    src="https://upload.wikimedia.org/wikipedia/en/a/a0/Central_Mindanao_University_logo.png"
                    class="size-12"
                />
            </div>
            <TopNavigationProfileDropdown />
        </div>
        <slot></slot>
    </div>
</template>

<script setup lang="ts">
import TopNavigationProfileDropdown from './TopNavigationProfileDropdown.vue'
</script>
