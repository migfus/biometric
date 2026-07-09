<template>
    <div>
        <div class="flex gap-2 flex-nowrap overflow-x-auto">
            <ImagePreviewContent
                v-if="$cameraStore.taken_photos.length > 0"
                :attachments="
                    $cameraStore.taken_photos.map((item) => {
                        return {
                            id: item.id,
                            file_location: item.preview,
                            preview_location: item.preview,
                        }
                    })
                "
            />

            <div
                v-else
                class="bg-white rounded-xl w-full text-center flex flex-col items-center p-8 text-sm text-neutral-600 border-2 border-neutral-300 border-dashed justify-center"
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
                <p class="line-clamp-1">
                    {{ item.name }}
                </p>
            </button>
        </div>

        <div class="flex flex-col gap-2 md:flex-row relative">
            <WebCam
                ref="webcam"
                @init="initCamera"
                @photoTaken="photoTakenEvent"
            />

            <div
                class="flex justify-center gap-2 md:hidden absolute bottom-2 w-full"
            >
                <button
                    @click="takePhoto()"
                    class="bg-emerald-600/80 backdrop-blur-lg p-4 text-emerald-50 my-auto rounded-full"
                >
                    <Icon icon="material-symbols:camera" class="size-4"></Icon>
                </button>
            </div>
            <div
                class="hidden md:flex absolute right-2 h-full justify-center items-center"
            >
                <button
                    @click="takePhoto()"
                    class="bg-emerald-600/80 backdrop-blur-lg p-4 text-emerald-50 my-auto rounded-full"
                >
                    <Icon icon="material-symbols:camera" class="size-4"></Icon>
                </button>
            </div>
        </div>

        <div class="flex flex-col gap-4 mb-8">
            <div class="flex flex-col gap-2">
                <AppButton
                    icon="ic:outline-refresh"
                    type="button"
                    @click="
                        () => {
                            $promptModalStore.menu_items = [
                                {
                                    name: 'Yes, Clear images',
                                    icon: 'mdi:trash-outline',
                                    color: 'danger',
                                    callback: () => {
                                        clearImages()
                                    },
                                },
                                {
                                    name: 'Cancel',
                                    icon: 'material-symbols:close',
                                    color: '',
                                    callback: () => {},
                                },
                            ]
                        }
                    "
                >
                    Clear
                </AppButton>
                <AppButton
                    color="brand"
                    icon="material-symbols:check"
                    @click="$emit('back')"
                    type="button"
                >
                    Done
                </AppButton>
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
import { usePromptModalStore } from '@/Stores/promptModal.store'
import { computed, onMounted, ref, useTemplateRef } from 'vue'
import ImagePreviewContent from './ImagePreviewContent.vue'

const $previewPhotoStore = usePreviewPhotoStore()
const $cameraStore = useCameraStore()
const $promptModalStore = usePromptModalStore()

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
    }
}

onMounted(() => {
    setTimeout(() => {
        checkCameraDevices()
    }, 1000)
})
</script>
