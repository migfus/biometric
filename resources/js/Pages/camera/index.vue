<template>
    <div>
        <div class="camera-stage flex flex-col gap-2 md:flex-row relative mb-8">
            <div class="camera-viewport">
                <WebCam
                    class="camera-preview"
                    ref="webcam"
                    @init="initCamera"
                    @photoTaken="photoTakenEvent"
                />

                <div
                    class="camera-top-controls flex justify-between items-start w-full"
                >
                    <div
                        class="flex gap-2 items-center bg-white/70 backdrop-blur-lg p-1 rounded-3xl text-neutral-700"
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
                                'rounded-xl px-2 flex items-center gap-1 py-1',
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

                    <Link
                        :href="route('index')"
                        class="bg-emerald-600/80 backdrop-blur-lg p-2 text-emerald-50 rounded-full"
                    >
                        <Icon
                            icon="material-symbols:check"
                            class="size-6"
                        ></Icon>
                    </Link>
                </div>

                <div class="camera-bottom-controls">
                    <!-- LEFT -->
                    <button
                        @click="
                            $previewPhotoStore.photos =
                                $cameraStore.taken_photos.map((item) => {
                                    return {
                                        file_location: item.preview,
                                        id: item.id,
                                        created_at: new Date().toISOString(),
                                    }
                                })
                        "
                        class="bg-white/80 backdrop-blur-lg p-1 text-emerald-50 my-auto md:my-0 rounded-lg relative justify-center"
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
                            v-else
                            class="h-8 w-16 rounded text-xs bg-transparent text-neutral-700 flex items-center justify-center"
                        >
                            No photos
                        </div>
                        <div
                            v-if="$cameraStore.taken_photos.length > 0"
                            class="text-white absolute bottom-0 left-0 w-full h-full bg-black/10 flex items-center justify-center rounded-lg"
                        >
                            <p>
                                {{ $cameraStore.taken_photos.length }}
                            </p>
                        </div>
                    </button>

                    <button
                        @click="takePhoto()"
                        class="bg-emerald-600/80 backdrop-blur-lg p-4 text-emerald-50 my-auto rounded-full"
                    >
                        <Icon
                            icon="material-symbols:camera"
                            class="size-4"
                        ></Icon>
                    </button>

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
                            <Icon
                                icon="mdi:trash-outline"
                                class="size-6"
                            ></Icon>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Icon } from '@iconify/vue'
import { WebCam } from 'vue-camera-lib'
import { Link } from '@inertiajs/vue3'

import { useCameraStore } from '@/Stores/camera.store'
import { usePreviewPhotoStore } from '@/Stores/previewPhoto.store'
import { usePromptModalStore } from '@/Stores/promptModal.store'
import { computed, onMounted, onUnmounted, ref, useTemplateRef } from 'vue'
import { router } from '@inertiajs/vue3'

const $cameraStore = useCameraStore()
const $promptModalStore = usePromptModalStore()
const $previewPhotoStore = usePreviewPhotoStore()

const $emit = defineEmits(['back', 'addHistory'])

const cameras = ref<{ deviceId: string; label: string }[]>([])
const webcam = useTemplateRef('webcam')
const selected_camera_mode = ref<string>('')

const DeviceOrientation =
    DeviceOrientationEvent as typeof DeviceOrientationEvent & {
        requestPermission?: () => Promise<'granted' | 'denied'>
    }
const DeviceMotion = DeviceMotionEvent as typeof DeviceMotionEvent & {
    requestPermission?: () => Promise<'granted' | 'denied'>
}
const device_orientation = ref<string>('unknown')
const orientation_permission = ref<'unknown' | 'granted' | 'denied'>('unknown')
const tilt_gamma = ref<number | null>(null)
const gravity_x = ref<number | null>(null)
const gravity_y = ref<number | null>(null)
let device_orientation_handler:
    ((event: DeviceOrientationEvent) => void) | null = null
let viewport_orientation_handler: (() => void) | null = null
let motion_orientation_handler: ((event: DeviceMotionEvent) => void) | null =
    null

const is_mobile_device = computed(() => {
    if (typeof window === 'undefined') {
        return false
    }

    return window.innerWidth < 768
})

const is_physical_landscape = computed(() => {
    if (tilt_gamma.value !== null) {
        return Math.abs(tilt_gamma.value) > 35
    }

    if (gravity_x.value !== null && gravity_y.value !== null) {
        return Math.abs(gravity_x.value) > Math.abs(gravity_y.value)
    }

    return false
})

function updateOrientationFromViewport(): void {
    if (typeof window === 'undefined') {
        return
    }

    const is_landscape = window.matchMedia('(orientation: landscape)').matches
    device_orientation.value = is_landscape ? 'landscape' : 'portrait'
}

function updateOrientationLabel(): void {
    if (is_physical_landscape.value) {
        device_orientation.value = 'landscape'
        return
    }

    if (tilt_gamma.value !== null || gravity_y.value !== null) {
        device_orientation.value = 'portrait'
        return
    }

    updateOrientationFromViewport()
}

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

function changeCamera(deviceId: string): void {
    // @ts-ignore
    webcam.value.changeCamera(deviceId)
    selected_camera_mode.value = deviceId
}

function initCamera(device_id: string): void {
    selected_camera_mode.value = device_id
}

async function photoTakenEvent({ blob }: { blob: Blob }): Promise<void> {
    const photo_id = Date.now().toString()
    let normalized_blob = blob

    try {
        normalized_blob = await normalizeCapturedPhoto(blob)
    } catch (err) {
        console.log(err)
    }

    const preview_blob = await createPreviewBlob(normalized_blob, 300)

    const file_base64 = await convertBlobToBase64(normalized_blob)
    const preview_base64 = await convertBlobToBase64(preview_blob)

    $cameraStore.taken_photos.push({
        id: photo_id,
        preview: file_base64,
        preview_location: preview_base64,
    })
}

function shouldConvertToLandscape(): boolean {
    if (typeof window === 'undefined') {
        return false
    }

    return (
        is_mobile_device.value &&
        (is_physical_landscape.value ||
            window.matchMedia('(orientation: landscape)').matches)
    )
}

async function normalizeCapturedPhoto(blob: Blob): Promise<Blob> {
    if (!shouldConvertToLandscape()) {
        return blob
    }

    const image = await loadImageFromBlob(blob)

    if (image.naturalWidth >= image.naturalHeight) {
        return blob
    }

    const canvas = document.createElement('canvas')
    canvas.width = image.naturalHeight
    canvas.height = image.naturalWidth

    const context = canvas.getContext('2d')

    if (!context) {
        return blob
    }

    context.translate(canvas.width / 2, canvas.height / 2)
    context.rotate(Math.PI / 2)
    context.drawImage(image, -image.naturalWidth / 2, -image.naturalHeight / 2)

    const rotated_blob = await convertCanvasToBlob(
        canvas,
        blob.type || 'image/jpeg',
    )

    return rotated_blob || blob
}

async function createPreviewBlob(blob: Blob, maxSize: number): Promise<Blob> {
    const image = await loadImageFromBlob(blob)
    const { width, height } = getScaledDimensions(
        image.naturalWidth,
        image.naturalHeight,
        maxSize,
    )

    if (width === image.naturalWidth && height === image.naturalHeight) {
        return blob
    }

    const canvas = document.createElement('canvas')
    canvas.width = width
    canvas.height = height

    const context = canvas.getContext('2d')

    if (!context) {
        return blob
    }

    context.drawImage(image, 0, 0, width, height)

    const preview_blob = await convertCanvasToBlob(
        canvas,
        blob.type || 'image/jpeg',
    )

    return preview_blob || blob
}

function getScaledDimensions(
    width: number,
    height: number,
    maxSize: number,
): { width: number; height: number } {
    if (width <= maxSize && height <= maxSize) {
        return { width, height }
    }

    const scale = Math.min(maxSize / width, maxSize / height)

    return {
        width: Math.round(width * scale),
        height: Math.round(height * scale),
    }
}

function loadImageFromBlob(blob: Blob): Promise<HTMLImageElement> {
    return new Promise((resolve, reject) => {
        const object_url = URL.createObjectURL(blob)
        const image = new Image()

        image.onload = function () {
            URL.revokeObjectURL(object_url)
            resolve(image)
        }

        image.onerror = function () {
            URL.revokeObjectURL(object_url)
            reject(new Error('Failed to load captured image.'))
        }

        image.src = object_url
    })
}

function convertCanvasToBlob(
    canvas: HTMLCanvasElement,
    type: string,
): Promise<Blob | null> {
    return new Promise((resolve) => {
        canvas.toBlob(function (result) {
            resolve(result)
        }, type)
    })
}

function convertBlobToBase64(blob: Blob): Promise<string> {
    return new Promise((resolve, reject) => {
        const reader = new FileReader()

        reader.onload = function () {
            if (typeof reader.result === 'string') {
                resolve(reader.result)
                return
            }

            reject(new Error('Failed to convert blob to base64.'))
        }

        reader.onerror = function () {
            reject(new Error('Failed to read captured image blob.'))
        }

        reader.readAsDataURL(blob)
    })
}

async function takePhoto(): Promise<void> {
    try {
        await ensureDeviceOrientationAccess()

        // @ts-ignore
        await webcam.value.takePhoto()
        console.log()
    } catch (err) {
        console.log(err)
    }
}

function clearImages(): void {
    $cameraStore.taken_photos = []
    router.replace({ url: '/' })
}

function loadCameras(): void {
    // @ts-ignore
    webcam.value.loadCameras()
    // @ts-ignore
    cameras.value = webcam.cameras
}

function checkCameraDevices(): void {
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

function startListening() {
    if (device_orientation_handler !== null) {
        return
    }

    device_orientation_handler = function (
        event: DeviceOrientationEvent,
    ): void {
        tilt_gamma.value = event.gamma ?? null

        updateOrientationLabel()
    }

    window.addEventListener('deviceorientation', device_orientation_handler)

    if (motion_orientation_handler === null) {
        motion_orientation_handler = function (event: DeviceMotionEvent): void {
            gravity_x.value = event.accelerationIncludingGravity?.x ?? null
            gravity_y.value = event.accelerationIncludingGravity?.y ?? null
            updateOrientationLabel()
        }

        window.addEventListener('devicemotion', motion_orientation_handler)
    }

    if (viewport_orientation_handler === null) {
        viewport_orientation_handler = function (): void {
            updateOrientationFromViewport()
        }

        window.addEventListener('resize', viewport_orientation_handler)
        window.addEventListener(
            'orientationchange',
            viewport_orientation_handler,
        )
    }
}

function stopListening(): void {
    if (device_orientation_handler === null) {
        return
    }

    window.removeEventListener('deviceorientation', device_orientation_handler)
    device_orientation_handler = null

    if (viewport_orientation_handler !== null) {
        window.removeEventListener('resize', viewport_orientation_handler)
        window.removeEventListener(
            'orientationchange',
            viewport_orientation_handler,
        )
        viewport_orientation_handler = null
    }

    if (motion_orientation_handler !== null) {
        window.removeEventListener('devicemotion', motion_orientation_handler)
        motion_orientation_handler = null
    }
}

async function ensureDeviceOrientationAccess(): Promise<void> {
    if (!is_mobile_device.value) {
        return
    }

    if (typeof DeviceOrientation.requestPermission === 'function') {
        if (orientation_permission.value === 'granted') {
            startListening()
            return
        }

        try {
            const permission = await DeviceOrientation.requestPermission()
            orientation_permission.value = permission

            if (permission === 'granted') {
                startListening()
            } else {
                updateOrientationFromViewport()
            }
        } catch {
            orientation_permission.value = 'denied'
            updateOrientationFromViewport()
        }
    } else {
        orientation_permission.value = 'granted'
        startListening()
    }

    if (typeof DeviceMotion.requestPermission === 'function') {
        try {
            const motion_permission = await DeviceMotion.requestPermission()

            if (motion_permission === 'granted') {
                startListening()
            }
        } catch {
            // Ignore motion permission failures and keep orientation fallback.
        }
    }
}

onMounted(async (): Promise<void> => {
    setTimeout((): void => {
        checkCameraDevices()
    }, 1000)

    updateOrientationFromViewport()
    await ensureDeviceOrientationAccess()
})

onUnmounted((): void => {
    stopListening()
})
</script>

<style scoped>
.camera-preview {
    width: 100%;
    height: auto;
    overflow: hidden;
}

.camera-stage {
    width: 100%;
}

.camera-viewport {
    position: relative;
    width: 100%;
    max-width: 100%;
    margin-inline: auto;
}

.camera-top-controls {
    position: absolute;
    top: 0;
    left: 0;
    padding: 0.5rem;
}

.camera-bottom-controls {
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    justify-content: space-between;
    gap: 0.5rem;
    padding: 0.5rem;
}

@media (min-width: 768px) {
    .camera-bottom-controls {
        top: 0;
        bottom: 0;
        left: auto;
        right: 0;
        width: auto;
        flex-direction: column;
        align-items: center;
    }
    .camera-top-controls {
        top: 0;
        bottom: 0;
        left: 0;
        flex-direction: column;
        height: auto;
        width: auto;
        align-items: start;
    }
}

.camera-preview :deep(video),
.camera-preview :deep(canvas),
.camera-preview :deep(img) {
    width: 100% !important;
    height: auto !important;
    max-width: 100% !important;
    max-height: none !important;
    display: block;
    object-fit: contain;
}
</style>
