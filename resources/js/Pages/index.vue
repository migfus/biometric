<template>
    <BasicTransition class="flex flex-col gap-4 p-4">
        <!-- SECTION: CAPTURE MODE -->
        <div v-if="capture_mode">
            <AppSwitch :switches="camera_types" v-model="selected_camera_mode"/>

            <Camera :resolution="{ width: 800, height: 1600 }" autoplay ref="camera"></Camera>

            <div class="flex gap-4 justify-center">
                <!-- <AppButton icon="ic:outline-refresh" type="button" @click="resetForm()">Clear</AppButton> -->
                <AppButton color="brand" icon="material-symbols:check" @click="capture()">Capture</AppButton>
            </div>

            <div class="flex gap-4 justify-end">
                <AppButton icon="ic:outline-refresh" type="button" @click="resetForm()">Clear</AppButton>
                <AppButton color="brand" icon="material-symbols:check" @click="capture_mode = false">Done</AppButton>
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

            <button @click="capture_mode = true" type="button" class="h-100 w-full bg-neutral-100 py-12 flex flex-col items-center gap-2 text-neutral-400 border-2 border-dashed rounded-3xl" >
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
import AppInput from '@/Components/form/AppInput.vue'
import AppSwitch from '@/Components/form/AppSwitch.vue'
import AppTextArea from '@/Components/form/AppTextArea.vue'
import { Icon } from '@iconify/vue'
import AppButton from '@/Components/form/AppButton.vue'
import { router } from '@inertiajs/vue3'
import Camera from "simple-vue-camera"

import { reactive, ref, useTemplateRef } from 'vue'
import BasicTransition from '@/Components/transitions/BasicTransition.vue'

interface Form {
    employee_no: string,
    full_name: string,
    department: string
    check: string
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


const form = reactive<Form>(initForm())
const capture_mode = ref<boolean>(false)

const camera = useTemplateRef('camera')
const devices = camera.value?.devices(["videoinput"]);
const images = ref<Blob[]>([])

const selected_autofill = ref<string>(autofill_selections[0].name)
const selected_camera_mode = ref<string>(camera_types[0].name)

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

async function capture() {
    const blob = await camera.value?.snapshot()

    if(blob) {
        images.value.push(blob)

        console.log(blob)
    }
}
</script>
