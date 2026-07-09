<template>
    <div class="flex flex-col gap-4">
        <BasicCard title="Edit Employee" icon="mdi:pencil">
            <form
                @submit.prevent="updateEmployee()"
                class="flex flex-col gap-2"
            >
                <AppInput
                    name="Employee No."
                    v-model="form.id"
                    :error="$page.props.errors.id"
                />

                <AppInput
                    name="Full Name"
                    v-model="form.full_name"
                    :error="$page.props.errors.full_name"
                />

                <AppInput
                    name="College or Department"
                    v-model="form.college"
                    :error="$page.props.errors.college"
                />

                <AppInput
                    name="Office"
                    v-model="form.office"
                    :error="$page.props.errors.office"
                />

                <AppInput
                    name="Email"
                    type="email"
                    v-model="form.email"
                    :error="$page.props.errors.email"
                />

                <div class="flex flex-col gap-2 mt-4">
                    <AppButton color="brand" icon="material-symbols:check">
                        Update
                    </AppButton>
                    <AppButton
                        :href="route('dashboard.employees.index')"
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
import { Employee } from '@/globalInterfaces'
import { useForm } from '@inertiajs/vue3'

const { employee } = defineProps<{
    employee: Employee
}>()

const form = useForm<{
    id: string
    full_name: string
    college: string
    office: string
    email: string
}>(initForm())

function initForm() {
    return {
        id: employee.id,
        full_name: employee.full_name,
        college: employee.college?.name ?? '',
        office: employee.office?.name ?? '',
        email: employee.email ?? '',
    }
}

function updateEmployee() {
    form.put(route('dashboard.employees.update', employee.id))
}
</script>
