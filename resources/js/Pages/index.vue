<template>
    <div>
        <BasicTransition class="flex flex-col gap-4 p-4">
            <!-- SECTION: IMAGE PREVIEW -->
            <div v-if="view_histories[view_histories.length - 1] == 'images'" class="flex flex-col gap-4 relative">
                <div v-for="photo in captured_photos" :key="photo.id" class="relative">
                    <img :src="photo.preview" alt="Captured Photo" class="w-full h-full object-cover rounded-xl"/>
                        <button class="absolute right-2 top-2 bg-red-50 text-red-700/75 p-2 rounded-3xl backdrop-blur-xl" @click="removePhoto(photo.id)">
                        <Icon icon="mdi:trash-outline" class="size-6"></Icon>
                    </button>
                </div>



                <div v-if="captured_photos.length <= 0" class="border-2 border-dashed p-4 rounded-3xl text-center py-24 flex flex-col items-center gap-4">
                    No Image to display
                    <AppButton @click="pushViewHistory('camera')" color="brand" icon="material-symbols:camera">Get a new photo</AppButton>
                </div>

                <div class="fixed bottom-0 left-0 right-0 p-4">
                    <div class="flex gap-4 justify-end">
                        <AppButton icon="material-symbols:close" type="button" @click="goBackViewHistory">Close</AppButton>
                    </div>
                </div>


            </div>

            <!-- SECTION: CAPTURE MODE -->
            <div v-else-if="view_histories[view_histories.length - 1] == 'camera'">
                <div class="flex gap-2 flex-nowrap overflow-x-auto h-[130px]">
                    <ImagePreview
                        v-if="captured_photos.length > 0"
                        v-for="photo in captured_photos"
                        :key="photo.id"
                        :photo="photo"
                        @removePhoto="removePhoto"
                        @click="pushViewHistory('images')"
                    />

                    <div v-else class="bg-white rounded-xl w-32 text-center flex flex-col items-center p-8 text-sm text-neutral-600">
                        No Images
                    </div>
                </div>


                <div class="flex gap-2 items-center bg-white p-2 rounded-3xl mr-auto text-neutral-700">
                    <button
                        v-for="item in camera_selection"
                        @click="changeCamera(item.deviceId)"
                        type="button"
                        :key="item.name"
                        :class="[selected_camera_mode == item.deviceId ? 'bg-emerald-200 text-emerald-800' : '', 'rounded-xl px-2 flex items-center gap-1']"
                    >
                        <Icon v-if="selected_camera_mode == item.deviceId" icon="ic:baseline-check-circle" class="size-4"></Icon>
                        <Icon v-else :icon="item.icon" class="size-4"></Icon>
                        {{  item.name }}
                    </button>
                </div>


                <WebCam ref="webcam" @init="initCamera" @photoTaken="photoTakenEvent" />


                <div class="fixed bottom-0 left-0 right-0 p-4">
                    <div class="flex justify-center gap-2">
                        <AppButton @click="takePhoto()" color="brand" icon="material-symbols:camera">Capture</AppButton>
                    </div>

                    <div class="flex gap-4 justify-end">
                        <AppButton
                        icon="ic:outline-refresh"
                        type="button"
                        @click="openSheet([
                                {
                                    name: 'Yes Clear Images',
                                    icon: 'mdi:trash-outline',
                                    callback: () => {clearImages(); promptSheet.close()}
                                },
                                {
                                    name: 'No Cancel',
                                    icon: 'material-symbols:close',
                                    callback: () => {
                                        promptSheet.close()
                                    }
                                }
                            ])"
                        >Clear</AppButton>
                        <AppButton color="brand" icon="material-symbols:check" @click="goBackViewHistory" type="button">Done</AppButton>
                    </div>
                </div>
            </div>

            <!-- SECTION: FORM -->
            <form v-else @submit.prevent="submitForm()" class="flex flex-col gap-4">
                <AppSwitch :switches="autofill_selections" v-model="selected_autofill"/>
                <AppInput v-model="form.employee_no" name="Employee No." noLabel placeholder="Employee No." :error="$page.props.errors.employee_no"/>
                <AppInput v-model="form.full_name" name="Full Name" noLabel placeholder="Full Name" :error="$page.props.errors.full_name"/>
                <AppInput v-model="form.department"name="Department" noLabel placeholder="Department" :error="$page.props.errors.department"/>

                <AppSwitch :switches="check_in_out" v-model="form.check"/>

                <AppTextArea v-model="form.work_description" name="Work Description" placeholder="Work Description" :error="$page.props.errors.work_description" />
                <div class="flex gap-1 items-center -mt-3 justify-end">
                    <AppButton class="text-xs" icon="mingcute:ai-line" type="button" @click="() => {rephrase(); rephraseSheet.open()}" :forceLoading="ai_loading">
                        {{  ai_loading ? 'Rephrasing...' : 'Rephrase' }}
                    </AppButton>
                </div>

                <div v-if="captured_photos.length > 0" class="flex gap-2 overflow-x-auto">
                    <button @click="pushViewHistory('camera')" class=" bg-white rounded-xl text-center flex flex-col items-center p-8 text-neutral-600 border-2 border-dashed border-neutral-400">
                        <Icon icon="ic:baseline-plus" class="size-8 mt-4"></Icon>
                    </button>

                    <ImagePreview
                        v-for="photo in captured_photos"
                        :key="photo.id"
                        :photo="photo"
                        @removePhoto="removePhoto"
                        @click="pushViewHistory('images')"
                    />
                </div>
                <button v-else @click="pushViewHistory('camera')" type="button" class="h-100 w-full bg-neutral-100 py-12 flex flex-col items-center gap-2 text-neutral-400 border-2 border-dashed rounded-3xl" >
                    <Icon icon="ic:baseline-camera-alt" />
                    <p>Capture an image to your work.</p>
                </button>
                <p class="text-red-500 text-sm font-semibold">{{ $page.props.errors.images}}</p>

                <div class="flex gap-4 justify-end mb-16">
                    <AppButton
                        icon="ic:outline-refresh"
                        type="button"
                        @click="openSheet([
                                {
                                    name: 'Yes Reset Data',
                                    icon: 'ic:outline-replay-circle-filled',
                                    callback: () => {resetForm(); promptSheet.close()}
                                },
                                {
                                    name: 'No Cancel',
                                    icon: 'material-symbols:close',
                                    callback: () => {
                                        promptSheet.close()
                                    }
                                }
                            ])"
                    >Reset</AppButton>
                    <AppButton color="brand" icon="ic:baseline-send">Submit</AppButton>
                </div>

                <div class="fixed bottom-0 left-0 right-0 flex items-center justify-center ">
                    <div class="flex gap-4 bg-neutral-200 m-2 p-2 rounded-3xl shadow-lg">
                        <AppButton icon="mingcute:time-line" type="button">Time In-Out</AppButton>
                        <AppButton icon="material-symbols:list" type="button">Records</AppButton>
                    </div>
                </div>
            </form>
        </BasicTransition>

        <VueBottomSheet ref="promptSheet" :transitionDuration="0.3">
            <div class="p-4 flex flex-col max-h-[80vh] overflow-y-auto gap-4">
                <div v-for="item in bottom_sheet_data" :key="item.name">
                    <AppButton @click="item.callback()" type="button" class="w-full justify-start" :icon="item.icon">{{ item.name }}</AppButton>
                </div>
            </div>
        </VueBottomSheet>

        <VueBottomSheet ref="rephraseSheet" :transitionDuration="0.3">
            <div class="p-4 flex flex-col max-h-[80vh] overflow-y-auto gap-4">
                <AppTextArea v-model="form.work_description" name="Work Description" placeholder="Work Description" :error="$page.props.errors.work_description"  />
                <AppTextArea v-model="new_rephrased_work_description" name="Rephrased Work Description" placeholder="Rephrased Work Description" :error="$page.props.errors.work_description" :ai_loading="ai_loading"/>

                <div class="flex gap-4 justify-end">
                    <AppButton icon="material-symbols:close" type="button" @click="rephraseSheet.close()">Cancel</AppButton>
                    <AppButton icon="mingcute:ai-line" type="button" @click="rephrase()" :forceLoading="ai_loading">Rephrase</AppButton>
                    <AppButton color="brand" icon="material-symbols:check" @click="() => {form.work_description = new_rephrased_work_description; rephraseSheet.close()}" :forceLoading="ai_loading">Update</AppButton>
                </div>
            </div>
        </VueBottomSheet>
    </div>

</template>

<script setup lang="ts">
import AppButton from '@/Components/form/AppButton.vue'
import AppInput from '@/Components/form/AppInput.vue'
import AppSwitch from '@/Components/form/AppSwitch.vue'
import AppTextArea from '@/Components/form/AppTextArea.vue'
import { Icon } from '@iconify/vue'
import { router } from '@inertiajs/vue3'
import { WebCam } from 'vue-camera-lib'
import BasicTransition from '@/Components/transitions/BasicTransition.vue'
import ImagePreview from '@/Components/ImagePreview.vue'
import VueBottomSheet from "@webzlodimir/vue-bottom-sheet"
import  "@webzlodimir/vue-bottom-sheet/dist/style.css"

import { BottomSheetData, CapturedPhoto } from '@/globalInterfaces'
import { computed, reactive, ref, useTemplateRef, watch } from 'vue'
import axios from 'axios'

interface Form {
    employee_no: string,
    full_name: string,
    department: string,
    check: string,
    work_description: string
}

const autofill_selections = [
    {
        name: 'Autofill',
        icon: 'ic:round-replay-circle-filled'
    },
    {
        name: 'Empty',
        icon: 'ic:outline-check-box-outline-blank'
    },
]

const check_in_out = [
    {
        name: 'Check In',
        icon: 'ic:baseline-login'
    },
    {
        name: 'Check Out',
        icon: 'ic:baseline-logout'
    },
]

const formData = new FormData()

const promptSheet = useTemplateRef('promptSheet')
const rephraseSheet = useTemplateRef('rephraseSheet')

const form = reactive<Form>(initForm())
const view_histories = ref<string[]>(['form'])

const cameras = ref([])
const webcam = useTemplateRef('webcam')
const captured_photos = ref<CapturedPhoto []>([])

const selected_autofill = ref<string>(autofill_selections[0].name)
const selected_camera_mode = ref<string>('')

const bottom_sheet_data = ref<BottomSheetData[]>([])
const ai_loading = ref<boolean>(false)
const new_rephrased_work_description = ref<string>('')

const camera_selection = computed<{deviceId: string, icon: string, name: string}[]>(() => {
    if(cameras.value.length > 0) {
        return cameras.value.map((cam: any) => {
            return {
                name: cam.label,
                icon: 'mdi:camera-outline',
                deviceId: cam.deviceId
            }
        })
    }
    else {
        return []
    }
})

function initForm(): Form {
    return {
        employee_no: '',
        full_name: '',
        department: '',
        check: 'Check In',
        work_description: '',
    }
}


function resetForm() {
    Object.assign(form, initForm())
    captured_photos.value = []
}

function submitForm() {
    formData.append('employee_no', form.employee_no)
    formData.append('full_name', form.full_name)
    formData.append('department', form.department)
    formData.append('check', form.check)
    formData.append('work_description', form.work_description)
    captured_photos.value.forEach((photo, index) => {
        formData.append(`images[${index}]`, photo.file)
    })

    router.post('/', formData)
}

function initCamera(device_id: string) {
    selected_camera_mode.value = device_id
}

function checkCameraDevices() {
    if(webcam.value) {
        // @ts-ignore
        cameras.value = webcam.value.cameras
        if (cameras.value.length === 0) {
            // if no camera found, we will try to refresh cameras list each second until there is some camera
            let reloadCamInterval = setInterval(() => {
                loadCameras()
                if (cameras.value.length > 0) {
                    clearInterval(reloadCamInterval)
                }
            }, 1000);
        }
    }
    else {
        alert(JSON.stringify(webcam.value))
    }
}

function changeCamera(deviceId: string) {
    // @ts-ignore
    webcam.value.changeCamera(deviceId)
    selected_camera_mode.value = deviceId
}


function loadCameras() {
    // @ts-ignore
    webcam.value.loadCameras()
    // @ts-ignore
    cameras.value = webcam.cameras
}

function photoTakenEvent({ blob }: { blob: Blob }) {
    const file = new File([blob], `photo-${Date.now()}.jpg`, {
        type: 'image/jpeg',
    })

    captured_photos.value.push({
        id: Date.now().toString(),
        file,
        preview: URL.createObjectURL(file)
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
    captured_photos.value = []
    view_histories.value.push('form')
}

function removePhoto(photoId: string) {
    captured_photos.value = captured_photos.value.filter(photo => photo.id !== photoId)
}

function pushViewHistory(newMode: string) {
    view_histories.value.push(newMode)
    if (view_histories.value.length > 10) {
        view_histories.value.shift()
    }
}

function goBackViewHistory() {
    if (view_histories.value.length > 1) {
        view_histories.value.pop()
    }
}

function openSheet(data: BottomSheetData[]) {
    bottom_sheet_data.value = data
    promptSheet.value.open()
}

async function rephrase() {
    if(form.work_description) {
        ai_loading.value = true
        try {
            const response = await axios.post('/api/rephrase', { work_description: form.work_description })
            new_rephrased_work_description.value = response.data.rephrased_work_description
        } catch (error) {
            console.error('Error rephrasing text:', error)
        } finally {
            ai_loading.value = false
        }
    }
}

watch(view_histories, () => {
    if(view_histories.value[view_histories.value.length - 1] == 'camera') {
        setTimeout(() => {
            checkCameraDevices()
        }, 1000)
    }
})
</script>

<style>
.bottom-sheet__content {
    height: auto !important;
    max-height: 70vh !important;
    min-height: 150px !important;
}

.bottom-sheet__main {
    max-height: calc(70vh - 80px) !important;
    overflow-y: auto !important;
}
</style>
