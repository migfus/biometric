<template>
    <button v-if="attachments.length > 4" @click="openModal()" class="grid grid-cols-2 gap-2">
        <div v-for="(item, idx) in attachments.slice(0,4)" class="relative text-center flex justify-center" >
            <img :src="item.preview_location" class="rounded-lg" />
            <div v-if="idx == 3" class="absolute inset-0 flex items-center justify-center text-white bg-black/40 rounded-lg text-lg font-semibold">{{ attachments.length - 4 }}+</div>
        </div>
    </button>
    <button v-else-if="attachments.length > 3" @click="openModal()" class="grid grid-cols-2 gap-2">
        <div v-for="item in attachments" class="relative text-center flex justify-center" >
            <img :src="item.preview_location" class="rounded-lg" />
        </div>
    </button>
    <button v-else-if="attachments.length > 2" @click="openModal()" class="grid grid-cols-2 gap-2">
        <div v-for="item in attachments" class="relative text-center flex justify-center first:row-span-2" >
            <img :src="item.preview_location" class="rounded-lg h-full w-full object-cover" />
        </div>
    </button>
    <button v-else-if="attachments.length > 1" @click="openModal()" class="grid grid-cols-2 gap-2">
        <div v-for="item in attachments" class="relative text-center flex justify-center" >
            <img :src="item.preview_location" class="rounded-lg h-full w-full object-cover" />
        </div>
    </button>
    <button v-else-if="attachments.length > 0" @click="openModal()">
        <div v-for="item in attachments" class="relative text-center flex justify-center" >
            <img :src="item.preview_location" class="rounded-lg h-full w-full object-cover" />
        </div>
    </button>
    <button v-else>
        no image
    </button>
</template>

<script setup lang="ts">
import { Attachment } from '@/globalInterfaces'
import { usePreviewPhotoStore } from '@/Stores/previewPhotoStore.js';

const {attachments} = defineProps<{
    attachments: Attachment[]
}>()

const $previewPhotoStore = usePreviewPhotoStore()

function openModal() {
    $previewPhotoStore.photos = attachments.map(item => { return { file_location: item.file_location, id: item.id}})
}
</script>
