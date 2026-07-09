<template>
    <div class="flex flex-col gap-4 p-4">
        <BasicCard title="Create Check" icon="ic:baseline-plus">
            <form @submit.prevent="createCheck()" class="flex flex-col gap-2">
                <AppInput
                    name="Employee No."
                    v-model="form.employee_id"
                    :error="$page.props.errors.employee_id"
                />

                <div class="flex flex-col gap-1">
                    <p class="text-sm text-neutral-600">Type</p>
                    <AppSwitch :switches="check_in_out" v-model="form.check" />
                    <p class="text-xs text-red-500">{{ $page.props.errors.check }}</p>
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
                    <AppButton color="brand" icon="ic:baseline-plus">
                        Create
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
import { useForm } from '@inertiajs/vue3'

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
}>({
    employee_id: '',
    check: 'Check In',
    work_description: '',
    os: '',
    ip_address: '',
})

function createCheck()
{
    form.post(route('dashboard.checks.store'))
}
</script>