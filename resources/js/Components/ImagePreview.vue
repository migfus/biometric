<template>
    <button class="relative group h-32 w-auto object-cover rounded-xl">
        <img
            :src="photo.preview"
            class="object-cover rounded-xl w-full h-full"
        />
        <!-- <button class="absolute top-1 bg-red-50/80 rounded-xl px-1 right-1 opacity-0 group-hover:opacity-100" @click="$emits('removePhoto', photo.id)">
            <Icon icon="ic:baseline-close" class="size-5 text-red-600"></Icon>
        </button> -->
    </button>
</template>

<script setup lang="ts">
import { Icon } from '@iconify/vue'

import { CapturedPhoto } from '@/globalInterfaces'

defineProps<{
    photo: CapturedPhoto
}>()

const $emits = defineEmits(['removePhoto'])
</script>
