<template>
    <div>
        <!-- <div class="flex gap-2 flex-nowrap overflow-x-auto">
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
        </div> -->

        <div class="flex flex-col gap-2 md:flex-row relative">
            <WebCam
                ref="webcam"
                @init="initCamera"
                @photoTaken="photoTakenEvent"
            />

            <div
                class="flex gap-2 items-center bg-white p-1 rounded-3xl mr-auto text-neutral-700 absolute top-1"
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
                    <p class="line-clamp-1 text-xs">
                        {{ item.name }}
                    </p>
                </button>
            </div>

            <div
                :class="[
                    'absolute p-2',
                    is_landscape
                        ? 'right-0 h-full flex justify-between items-center'
                        : 'bottom-0 w-full flex justify-between gap-2',
                ]"
            >
                <!-- LEFT -->
                <button
                    @click="
                        $previewPhotoStore.photos =
                            $cameraStore.taken_photos.map((item) => {
                                return {
                                    file_location: item.preview,
                                    id: item.id,
                                }
                            })
                    "
                    class="bg-white/80 backdrop-blur-lg p-1 text-emerald-50 my-auto rounded-lg relative justify-center"
                >
                    <img
                        v-if="$cameraStore.taken_photos.length > 0"
                        :src="
                            $cameraStore.taken_photos[
                                $cameraStore.taken_photos.length - 1
                            ].preview
                        "
                        class="h-8 w-16 rounded"
                    />
                    <div
                        class="text-white absolute bottom-0 left-0 w-full h-full bg-black/10 flex items-center justify-center rounded-lg"
                    >
                        <p>
                            {{ $cameraStore.taken_photos.length }}
                        </p>
                    </div>
                </button>

                <!-- CENTER -->
                <button
                    @click="takePhoto()"
                    class="bg-emerald-600/80 backdrop-blur-lg p-4 text-emerald-50 my-auto rounded-full"
                >
                    <Icon icon="material-symbols:camera" class="size-4"></Icon>
                </button>

                <!-- RIGHT -->
                <div class="flex p-1 rounded-full gap-2">
                    <button
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
                        class="bg-white/80 backdrop-blur-lg p-2 text-neutral-700 my-auto rounded-full"
                    >
                        <Icon icon="ic:outline-refresh" class="size-6"></Icon>
                    </button>
                    <button
                        @click="$emit('back')"
                        class="bg-emerald-600/80 backdrop-blur-lg p-2 text-emerald-50 my-auto rounded-full"
                    >
                        <Icon
                            icon="material-symbols:check"
                            class="size-6"
                        ></Icon>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Icon } from '@iconify/vue'
import { WebCam } from 'vue-camera-lib'

import { useCameraStore } from '@/Stores/camera.store'
import { usePromptModalStore } from '@/Stores/promptModal.store'
import { computed, onBeforeUnmount, onMounted, ref, useTemplateRef } from 'vue'
import ImagePreviewContent from './ImagePreviewContent.vue'
import { usePreviewPhotoStore } from '@/Stores/previewPhoto.store'

const $cameraStore = useCameraStore()
const $promptModalStore = usePromptModalStore()
const $previewPhotoStore = usePreviewPhotoStore()

const $emit = defineEmits(['back', 'addHistory'])

const cameras = ref([])
const webcam = useTemplateRef('webcam')
const selected_camera_mode = ref<string>('')
const is_landscape = ref(false)

let orientation_query: MediaQueryList | null = null

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

function updateOrientationMode() {
    if (typeof window === 'undefined') {
        return
    }

    is_landscape.value = window.matchMedia('(orientation: landscape)').matches
}

function handleOrientationChange() {
    updateOrientationMode()
}

function startOrientationWatcher() {
    if (typeof window === 'undefined') {
        return
    }

    orientation_query = window.matchMedia('(orientation: landscape)')
    updateOrientationMode()

    if (orientation_query.addEventListener) {
        orientation_query.addEventListener('change', handleOrientationChange)
    } else {
        orientation_query.addListener(handleOrientationChange)
    }

    window.addEventListener('resize', handleOrientationChange)
}

function stopOrientationWatcher() {
    if (!orientation_query) {
        return
    }

    if (orientation_query.removeEventListener) {
        orientation_query.removeEventListener('change', handleOrientationChange)
    } else {
        orientation_query.removeListener(handleOrientationChange)
    }

    window.removeEventListener('resize', handleOrientationChange)
}

onMounted(() => {
    startOrientationWatcher()

    setTimeout(() => {
        checkCameraDevices()
    }, 1000)
})

onBeforeUnmount(() => {
    stopOrientationWatcher()
})
</script>
