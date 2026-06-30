<template>
    <div class="flex flex-col gap-4 p-4">
        <form class="flex flex-col gap-4">
            <AppSwitch :switches="autofill_selections" v-model="selected_autofill"/>
            <AppInput v-model="form.employee_no" name="Employee No." noLabel placeholder="Employee No."/>
            <AppInput v-model="form.full_name" name="Full Name" noLabel placeholder="Full Name" />
            <AppInput v-model="form.department"name="Department" noLabel placeholder="Department"/>

            <AppSwitch :switches="check_in_out" v-model="form.check"/>

            <AppTextArea v-model="form.work_description" name="Work Description" placeholder="Work Description"></AppTextArea>

            <button type="button" class="h-100 w-full bg-neutral-100 py-12 flex flex-col items-center gap-2 text-neutral-400 border-2 border-dashed rounded-3xl" >
                <Icon icon="ic:baseline-camera-alt" />
                <p>Capture an image to your work.</p>
            </button>

            <div class="flex gap-4 justify-end">
                <AppButton icon="ic:outline-refresh" type="button" @click="resetForm()">Reset</AppButton>
                <AppButton color="brand" icon="ic:baseline-send">Submit</AppButton>
            </div>

        </form>

    </div>
</template>

<script setup lang="ts">
import AppInput from '@/Components/form/AppInput.vue'
import AppSwitch from '@/Components/form/AppSwitch.vue'
import AppTextArea from '@/Components/form/AppTextArea.vue'
import { Icon } from '@iconify/vue'
import AppButton from '@/Components/form/AppButton.vue'

import { reactive, ref } from 'vue'

interface Form {
    employee_no: string,
    full_name: string,
    department: string
    check: string
    work_description: string
}

const form = reactive<Form>(initForm())


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


const selected_autofill = ref<string>(autofill_selections[0].name)

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
</script>
