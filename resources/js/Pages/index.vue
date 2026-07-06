<template>
    <div>
        <BasicTransition class="flex flex-col gap-4 p-4">
            <!-- SECTION: PREVIEW IMAGE -->
            <ImageModal v-if="photos.length > 0" :photos/>

            <!-- SECTION: RECORDS -->
            <RecordsContent v-else-if="histories[histories.length - 1] == 'records'" :checks/>

            <!-- SECTION: CAPTURE MODE -->
            <CaptureImage v-else-if="histories[histories.length - 1] == 'camera'" @back="$historyNavigationStore.goBack()" @addHistory="history => histories.push(history)"/>

            <!-- SECTION: FORM -->
            <FormContent v-else/>
        </BasicTransition>


        <!-- SECTION: BOTTOM SHEET -->
        <VueBottomSheet ref="promptSheet" :transitionDuration="0.3">
            <div class="p-4 flex flex-col max-h-[80vh] overflow-y-auto gap-4">
                <div v-for="item in bottom_sheet_data" :key="item.name">
                    <AppButton @click="item.callback()" type="button" class="w-full justify-start" :icon="item.icon">{{ item.name }}</AppButton>
                </div>
            </div>
        </VueBottomSheet>

        <!-- SECTION: BOTTOM MENU -->
        <div v-if="histories[histories.length - 1] != 'image'" class="fixed bottom-0 left-0 right-0 flex items-center justify-center ">
            <div class="flex gap-2 bg-neutral-200/50 backdrop-blur-lg m-2 p-1 rounded-3xl shadow-lg">
                <MenuButton name="Time In-Out" icon="mingcute:time-line" :active="histories[histories.length - 1] == 'form'" @click="histories.push('form'); photos = []"/>
                <MenuButton name="Camera" icon="mdi:camera-outline" :active="histories[histories.length - 1] == 'camera'" @click="histories.push('camera'); photos = []"/>
                <MenuButton name="Records" icon="material-symbols:list" :active="histories[histories.length - 1] == 'records'" @click="histories.push('records'); photos = []"/>
            </div>
        </div>

    </div>

</template>

<script setup lang="ts">
import AppButton from '@/Components/form/AppButton.vue'

import BasicTransition from '@/Components/transitions/BasicTransition.vue'
import VueBottomSheet from "@webzlodimir/vue-bottom-sheet"
import "@webzlodimir/vue-bottom-sheet/dist/style.css"
import ImageModal from './ImageModal.vue'
import MenuButton from './MenuButton.vue'
import RecordsContent from './RecordsContent.vue'

import { BottomSheetData, Check, Pagination } from '@/globalInterfaces'
import { usePreviewPhotoStore } from '@/Stores/previewPhotoStore'
import { usePromptModalStore } from '@/Stores/promptModal.store'
import { ref, useTemplateRef, watch } from 'vue'
import CaptureImage from './CaptureImage.vue'
import FormContent from './FormContent.vue'

import { useHistoryNavigation } from '@/Stores/historyNavigation.store'
import { storeToRefs } from 'pinia'

defineProps<{
    checks: Pagination<Check>
}>()



const $previewPhotoStore = usePreviewPhotoStore()
const { photos } = storeToRefs($previewPhotoStore)
const $promptModalStore = usePromptModalStore()
const $historyNavigationStore =useHistoryNavigation()
const { histories } = storeToRefs($historyNavigationStore)

const promptSheet = useTemplateRef('promptSheet')
const bottom_sheet_data = ref<BottomSheetData[]>([])

watch(() => $promptModalStore.menu_items, (new_data) => {
    if(new_data.length > 0) {
        promptSheet.value?.open()
    }
})
</script>

<style>
.bottom-sheet__content {
    height: auto !important;
    max-height: 70vh !important;
    min-height: 150px !important;
    /* background: var(--color-neutral-100) !important; */
}

.bottom-sheet__main {
    max-height: calc(70vh - 80px) !important;
    overflow-y: auto !important;
}
</style>
