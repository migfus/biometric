<template>
    <BasicTransition class="flex flex-col gap-4 p-4">
        <!-- SECTION: CAPTURE MODE -->
        <div v-if="capture_mode">

            <div class="flex gap-2 overflow-x-auto">
                <img
                    v-if="captured_photos.length > 0"
                    v-for="photo in captured_photos"
                    :key="photo.preview"
                    :src="photo.preview"
                    class="w-auto object-cover rounded-xl size-24"
                />
                <div v-else class=" bg-white rounded-xl w-32 text-center flex flex-col items-center p-8 text-sm text-neutral-600">
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
            <div class="flex justify-center gap-2">
                <AppButton @click="takePhoto()">Capture</AppButton>
            </div>

            <div class="flex gap-4 justify-end">
                <AppButton icon="ic:outline-refresh" type="button" @click="clearImages">Clear</AppButton>
                <AppButton color="brand" icon="material-symbols:check" @click="capture_mode = false" type="button">Done</AppButton>
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
                <AppButton class="text-xs" icon="mingcute:ai-line" type="button">Rephrase</AppButton>
            </div>

            <div v-if="captured_photos.length > 0" class="flex gap-2 overflow-x-auto">
                <button @click="capture_mode = true" class=" bg-white rounded-xl text-center flex flex-col items-center p-8 text-neutral-600">
                    <Icon icon="mdi:camera-outline" class="size-8"></Icon>
                </button>
                <img
                    v-for="photo in captured_photos"
                    :key="photo.preview"
                    :src="photo.preview"
                    class="w-auto object-cover rounded-xl size-24"
                />
            </div>
            <button v-else @click="capture_mode = true" type="button" class="h-100 w-full bg-neutral-100 py-12 flex flex-col items-center gap-2 text-neutral-400 border-2 border-dashed rounded-3xl" >
                <Icon icon="ic:baseline-camera-alt" />
                <p>Capture an image to your work.</p>
            </button>

            <div class="flex gap-4 justify-end">
                <AppButton icon="ic:outline-refresh" type="button" @click="resetForm()">Reset</AppButton>
                <AppButton color="brand" icon="ic:baseline-send">Submit</AppButton>
            </div>
        </form>

    </BasicTransition>
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
import { computed, reactive, ref, useTemplateRef, watch } from 'vue'

interface Form {
    employee_no: string,
    full_name: string,
    department: string
    check: string
    work_description: string
}

interface Photo {
    file: File;
    preview: string;
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

const camera_types = [
    {
        name: 'Back Camera',
        icon: 'tabler:photo'
    },
    {
        name: 'Front Camera',
        icon: 'material-symbols:face'
    },
]

const formData = new FormData()

const form = reactive<Form>(initForm())
const capture_mode = ref<boolean>(false)

const cameras = ref([])
const webcam = useTemplateRef('webcam')
const captured_photos = ref<Photo []>([])

const selected_autofill = ref<string>(autofill_selections[0].name)
const selected_camera_mode = ref<string>('')

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
}

function submitForm() {
    router.post('/', form)
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
    capture_mode.value = false
}

watch(capture_mode, () => {
    setTimeout(() => {
        if(capture_mode.value) {
            checkCameraDevices()
        }
    },1000)
})
</script>
