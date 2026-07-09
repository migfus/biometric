<template>
    <div class="flex flex-col gap-4 lg:w-120 lg:mx-auto">
        <BasicCard title="Edit Check" icon="mdi:pencil">
            <form @submit.prevent="updateCheck()" class="flex flex-col gap-2">
                <AppInput
                    name="Employee No."
                    v-model="form.employee_id"
                    :error="$page.props.errors.employee_id"
                />

                <div class="flex flex-col gap-1">
                    <p class="text-sm text-neutral-600">Type</p>
                    <AppSwitch :switches="check_in_out" v-model="form.check" />
                    <p class="text-xs text-red-500">
                        {{ $page.props.errors.check }}
                    </p>
                </div>

                <AppTextArea
                    name="Work Description"
                    v-model="form.work_description"
                    :error="$page.props.errors.work_description"
                />

                <AppInput
                    name="OS"
                    v-model="form.os"
                    :error="$page.props.errors.os"
                />

                <AppInput
                    name="IP Address"
                    v-model="form.ip_address"
                    :error="$page.props.errors.ip_address"
                />

                <div class="flex flex-col gap-2 mt-4">
                    <AppButton color="brand" icon="material-symbols:check">
                        Update
                    </AppButton>
                    <AppButton
                        :href="route('dashboard.checks.index')"
                        type="button"
                        icon="material-symbols:close"
                    >
                        Cancel
                    </AppButton>
                </div>
            </form>
        </BasicCard>
    </div>
</template>

<script setup lang="ts">
import BasicCard from '@/Components/cards/BasicCard.vue'
import AppButton from '@/Components/form/AppButton.vue'
import AppInput from '@/Components/form/AppInput.vue'
import AppSwitch from '@/Components/form/AppSwitch.vue'
import AppTextArea from '@/Components/form/AppTextArea.vue'
import { Check } from '@/globalInterfaces'
import { useForm } from '@inertiajs/vue3'

const { check } = defineProps<{
    check: Check
}>()

const check_in_out = [
    {
        name: 'Check In',
        icon: 'ic:baseline-login',
    },
    {
        name: 'Check Out',
        icon: 'ic:baseline-logout',
    },
]

const form = useForm<{
    employee_id: string
    check: string
    work_description: string
    os: string
    ip_address: string
}>(initForm())

function initForm() {
    return {
        employee_id: check.employee_id,
        check: check.check_in ? 'Check In' : 'Check Out',
        work_description: check.work_description,
        os: check.os ?? '',
        ip_address: check.ip_address ?? '',
    }
}

function updateCheck() {
    form.put(route('dashboard.checks.update', check.id))
}
</script>
