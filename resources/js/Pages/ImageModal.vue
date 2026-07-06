<template>
    <div class="flex flex-col gap-2 mb-10">
        <div v-for="item in file_locations" :key="item" class="relative">
            <img :src="item" class="rounded-xl"/>
            <button class="bg-red-50 text-red-700 absolute top-2 right-2 rounded-full p-1">
                <Icon icon="mdi:trash-outline" class="size-4" />
            </button>
        </div>

        <div class="flex flex-col gap-2">
            <AppButton icon="material-symbols:close" @click="$previewPhotoStore.initPhoto()">Close</AppButton>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppButton from '@/Components/form/AppButton.vue';
import AppInput from '@/Components/form/AppInput.vue';
import { usePreviewPhotoStore } from '@/Stores/previewPhotoStore'
import { Icon } from '@iconify/vue';

defineProps<{
    file_locations: string []
}>()

const $previewPhotoStore = usePreviewPhotoStore()
</script>
