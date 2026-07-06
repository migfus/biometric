<template>
    <div>
        <div class="flex gap-2 flex-nowrap overflow-x-auto">
            <ImagePreview
                v-if="$cameraStore.taken_photos.length > 0"
                v-for="photo in $cameraStore.taken_photos"
                :key="photo.id"
                :photo="photo"
                @click="
                    $previewPhotoStore.photos = $cameraStore.taken_photos.map(
                        (item) => {
                            return { file_location: item.preview, id: item.id }
                        },
                    )
                "
            />

            <div
                v-else
                class="bg-white rounded-xl w-32 text-center flex flex-col items-center p-8 text-sm text-neutral-600 border-2 border-neutral-300 border-dashed justify-center"
            >
                No Images
            </div>
        </div>

        <div
            class="flex gap-2 items-center bg-white p-2 rounded-3xl mr-auto text-neutral-700"
        >
            <button
                v-for="item in camera_selection"
                @click="changeCamera(item.deviceId)"
                type="button"
                :key="item.name"
                :class="[
                    selected_camera_mode == item.deviceId
                        ? 'bg-emerald-200 text-emerald-800'
                        : '',
                    'rounded-xl px-2 flex items-center gap-1',
                ]"
            >
                <Icon
                    v-if="selected_camera_mode == item.deviceId"
                    icon="ic:baseline-check-circle"
                    class="size-4"
                />
                <Icon v-else :icon="item.icon" class="size-4" />
                {{ item.name }}
            </button>
        </div>

        <WebCam ref="webcam" @init="initCamera" @photoTaken="photoTakenEvent" />

        <div class="flex flex-col gap-4 mb-8">
            <div class="flex justify-center gap-2">
                <AppButton
                    @click="takePhoto()"
                    color="brand"
                    icon="material-symbols:camera"
                    >Capture</AppButton
                >
            </div>

            <div class="flex flex-col gap-2">
                <AppButton
                    icon="ic:outline-refresh"
                    type="button"
                    @click="openSheet()"
                >
                    Clear
                </AppButton>
                <AppButton
                    color="brand"
                    icon="material-symbols:check"
                    @click="$emit('back')"
                    type="button"
                    >Done</AppButton
                >
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppButton from '@/Components/form/AppButton.vue'
import ImagePreview from '@/Components/ImagePreview.vue'
import { Icon } from '@iconify/vue'
import { WebCam } from 'vue-camera-lib'

import { useCameraStore } from '@/Stores/camera.store'
import { usePreviewPhotoStore } from '@/Stores/previewPhoto.store'
import { computed, onMounted, ref, useTemplateRef, watch } from 'vue'
import { usePromptModalStore } from '@/Stores/promptModal.store'

const $previewPhotoStore = usePreviewPhotoStore()
const $promptModal = usePromptModalStore()
const $cameraStore = useCameraStore()
const $emit = defineEmits(['back', 'addHistory'])

const cameras = ref([])
const webcam = useTemplateRef('webcam')
const selected_camera_mode = ref<string>('')

const camera_selection = computed<
    { deviceId: string; icon: string; name: string }[]
>(() => {
    if (cameras.value.length > 0) {
        return cameras.value.map((cam: any) => {
            return {
                name: cam.label,
                icon: 'mdi:camera-outline',
                deviceId: cam.deviceId,
            }
        })
    } else {
        return []
    }
})

function changeCamera(deviceId: string) {
    // @ts-ignore
    webcam.value.changeCamera(deviceId)
    selected_camera_mode.value = deviceId
}

function initCamera(device_id: string) {
    selected_camera_mode.value = device_id
}

function photoTakenEvent({ blob }: { blob: Blob }) {
    const file = new File([blob], `photo-${Date.now()}.jpg`, {
        type: 'image/jpeg',
    })

    $cameraStore.taken_photos.push({
        id: Date.now().toString(),
        file,
        preview: URL.createObjectURL(file),
    })
}

async function takePhoto() {
    try {
        // @ts-ignore
        await webcam.value.takePhoto()
        console.log()
    } catch (err) {
        console.log(err)
    }
}

function clearImages() {
    $cameraStore.taken_photos = []
    $emit('addHistory', 'form')
}

function openSheet() {
    $promptModal.menu_items = [
        {
            name: 'Yes Clear Images',
            icon: 'mdi:trash-outline',
            callback: () => {
                clearImages()
            },
        },
        {
            name: 'No Cancel',
            icon: 'material-symbols:close',
            callback: () => {},
        },
    ]
}

function loadCameras() {
    // @ts-ignore
    webcam.value.loadCameras()
    // @ts-ignore
    cameras.value = webcam.cameras
}

function checkCameraDevices() {
    if (webcam.value) {
        // @ts-ignore
        cameras.value = webcam.value.cameras
        if (cameras.value.length === 0) {
            // if no camera found, we will try to refresh cameras list each second until there is some camera
            let reloadCamInterval = setInterval(() => {
                loadCameras()
                if (cameras.value.length > 0) {
                    clearInterval(reloadCamInterval)
                }
            }, 1000)
        }
    } else {
        alert(JSON.stringify(webcam.value))
    }
}

onMounted(() => {
    setTimeout(() => {
        checkCameraDevices()
    }, 1000)
})
</script>
