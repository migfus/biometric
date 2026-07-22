<template>
    <div class="flex justify-between">
        <p>Searched {{ searched }}</p>
        <p>{{ total }} results</p>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    total: number
    searched: string
}>()
</script>
