<template>
    <div>
        <BasicTransition class="flex flex-col gap-4 p-4">
            <!-- SECTION: RECORDS -->
            <RecordsContent v-if="view_histories[view_histories.length - 1] == 'records'" :checks />

            <!-- SECTION: IMAGE PREVIEW -->
            <div v-else-if="view_histories[view_histories.length - 1] == 'images'" class="flex flex-col gap-4 relative">
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


                <div class="flex gap-4 justify-end">
                    <AppButton icon="material-symbols:close" type="button" @click="goBackViewHistory">Close</AppButton>
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

                    <div v-else class="bg-white rounded-xl w-32 text-center flex flex-col items-center p-8 text-sm text-neutral-600 border-2 border-neutral-300 border-dashed justify-center">
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


                <div class="flex flex-col gap-4 mb-8">
                    <div class="flex justify-center gap-2">
                        <AppButton @click="takePhoto()" color="brand" icon="material-symbols:camera">Capture</AppButton>
                    </div>

                    <div class="flex flex-col gap-2">
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
                        >
                        Clear
                        </AppButton>
                        <AppButton color="brand" icon="material-symbols:check" @click="goBackViewHistory" type="button">Done</AppButton>
                    </div>
                </div>
            </div>

            <!-- SECTION: FORM -->
            <form v-else @submit.prevent="submitForm()" class="flex flex-col gap-4">
                <div class="flex justify-between items-center">
                    <AppSwitch :switches="autofill_selections" v-model="selected_autofill"/>
                    <div class="flex gap-2 items-center">
                        <p class="text-xs text-neutral-500">{{ moment().format('MMM DD, Y') }}</p>
                    </div>
                </div>

                <AppInput v-model="form.employee_no" name="Employee No." noLabel placeholder="Employee No." :error="$page.props.errors.employee_no" uppercase/>
                <AppInput v-model="form.full_name" name="Full Name" noLabel placeholder="Full Name" :error="$page.props.errors.full_name"/>
                <AppInput v-model="form.college"name="College" noLabel placeholder="College (optional)" :error="$page.props.errors.college"/>
                <AppInput v-model="form.department" name="Department or Office" noLabel placeholder="Department or Office" :error="$page.props.errors.department"/>

                <div class="flex justify-between items-center">
                    <AppSwitch :switches="check_in_out" v-model="form.check"/>
                    <p class="text-neutral-700 text-sm">{{ moment().format('h:mm A') }}</p>
                </div>


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
                <button v-else @click="pushViewHistory('camera')" type="button" class="h-100 w-full bg-white py-12 flex flex-col items-center gap-2 text-neutral-400 border-2 border-dashed rounded-3xl" >
                    <Icon icon="ic:baseline-camera-alt" />
                    <p>Capture an image to your work.</p>
                </button>
                <p class="text-red-500 text-sm font-semibold">{{ $page.props.errors.images}}</p>

                <div class="flex flex-col gap-2 mb-16">
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

                <div class="flex flex-col gap-2">
                    <AppButton icon="material-symbols:close" type="button" @click="rephraseSheet.close()">Cancel</AppButton>
                    <AppButton icon="mingcute:ai-line" type="button" @click="rephrase()" :forceLoading="ai_loading">Rephrase</AppButton>
                    <AppButton color="brand" icon="material-symbols:check" @click="() => {form.work_description = new_rephrased_work_description; rephraseSheet.close()}" :disabled="ai_loading">Update</AppButton>
                </div>
            </div>
        </VueBottomSheet>

        <div v-if="view_histories[view_histories.length - 1] != 'image'" class="fixed bottom-0 left-0 right-0 flex items-center justify-center ">
            <div class="flex gap-2 bg-neutral-200/50 backdrop-blur-lg m-2 p-1 rounded-3xl shadow-lg">
                <MenuButton name="Time In-Out" icon="mingcute:time-line" :active="view_histories[view_histories.length - 1] == 'form'" @click="view_histories.push('form')"/>
                <MenuButton name="Camera" icon="mdi:camera-outline" :active="view_histories[view_histories.length - 1] == 'camera'" @click="view_histories.push('camera')"/>
                <MenuButton name="Records" icon="material-symbols:list" :active="view_histories[view_histories.length - 1] == 'records'" @click="view_histories.push('records')"/>
            </div>
        </div>
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
import MenuButton from './MenuButton.vue'
import RecordsContent from './RecordsContent.vue'

import { useStorage } from '@vueuse/core'
import { BottomSheetData, CapturedPhoto, Check, Pagination } from '@/globalInterfaces'
import { computed, onMounted, reactive, ref, useTemplateRef, watch } from 'vue'
import axios from 'axios'
import moment from 'moment'

defineProps<{
    checks: Pagination<Check>
}>()

interface Form {
    employee_no: string,
    full_name: string,
    college: string,
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
const form_autofill = useStorage<Form>('form_autofill', initForm(), localStorage)
const view_histories = ref<string[]>(['form'])

const cameras = ref([])
const webcam = useTemplateRef('webcam')
const captured_photos = ref<CapturedPhoto []>([])

const selected_autofill = useStorage<string>('selected_autofill', autofill_selections[0].name, localStorage)
const selected_camera_mode = ref<string>('')

const bottom_sheet_data = ref<BottomSheetData[]>([])
const ai_loading = ref<boolean>(false)
const new_rephrased_work_description = ref<string>('')
const rephrase_count = ref(0)

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

function getCurrentCheckStatus(): string {
    // const now = new Date()
    const now = new Date()
    const totalMinutes = now.getHours() * 60 + now.getMinutes()

    const nineAm = 9 * 60 // 540
    const twelveThirtyPm = 12 * 60 + 30 // 750
    const threePm = 15 * 60 // 900

    // 12:00 AM–9:00 AM → Check In
    if (totalMinutes <= nineAm) {
        return check_in_out[0].name
    }

    // 9:01 AM–12:30 PM → Check Out
    if (totalMinutes <= twelveThirtyPm) {
        return check_in_out[1].name
    }

    // 12:31 PM–3:00 PM → Check In
    if (totalMinutes <= threePm) {
        return check_in_out[0].name
    }

    // 3:01 PM–11:59 PM → Check Out
    return check_in_out[1].name
}

function initForm(): Form {
    return {
        employee_no: '',
        full_name: '',
        department: '',
        check: getCurrentCheckStatus(),
        work_description: '',
        college: '',
    }
}


function resetForm() {
    Object.assign(form, initForm())
    captured_photos.value = []
}

function submitForm() {
    form_autofill.value = form


    formData.append('employee_no', form.employee_no)
    formData.append('full_name', form.full_name)
    formData.append('department', form.department)
    formData.append('check', form.check)
    formData.append('work_description', form.work_description)
    formData.append('college', form.college)
    formData.append('client_os', getClientOS())
    formData.append('rephrase_count', rephrase_count.value.toString())

    captured_photos.value.forEach((photo, index) => {
        formData.append(`images[${index}]`, photo.file)
    })

    router.post('/', formData, { preserveState: true})
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
            rephrase_count.value++
        } catch (error) {
            console.error('Error rephrasing text:', error)
        } finally {
            ai_loading.value = false
        }
    }
}

function getClientOS(): string {
    const ua = navigator.userAgent

    if (/android/i.test(ua)) return 'Android'
    if (/iPad|iPhone|iPod/.test(ua)) return 'iOS'
    if (/Windows NT/i.test(ua)) return 'Windows'
    if (/Mac OS X/i.test(ua)) return 'macOS'
    if (/Linux/i.test(ua)) return 'Linux'

    return 'Unknown'
}

watch(view_histories, () => {
    if(view_histories.value[view_histories.value.length - 1] == 'camera') {
        setTimeout(() => {
            checkCameraDevices()
        }, 1000)
    }
})

watch(selected_autofill, (newValue) => {
    if(newValue == autofill_selections[0].name) { // autofill mode
        Object.assign(form, form_autofill.value)
    }
    else { // empty mode
        Object.assign(form, initForm())
    }
})

onMounted(() => {
    form.check = getCurrentCheckStatus()

    if(autofill_selections[0].name == selected_autofill.value) { // autofill mode
        // alert('autofill')
        Object.assign(form, form_autofill.value)
    }
})

router.on('success', () => {
    rephrase_count.value = 0

    if(selected_autofill.value == autofill_selections[0].name) {
        Object.assign(form, form_autofill.value)
    }
    else {
        Object.assign(form, initForm())
    }

    captured_photos.value = []
    view_histories.value.push('records')
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
