<template>
    <div class="flex flex-col gap-4 p-4">
        <BasicCard title="Create Employee" icon="ic:baseline-plus">
            <form
                @submit.prevent="createEmployee()"
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
                    <AppButton color="brand" icon="ic:baseline-plus">
                        Create
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
import BasicCard from '@/components/cards/BasicCard.vue'
import AppButton from '@/components/form/AppButton.vue'
import AppInput from '@/components/form/AppInput.vue'

import { useForm } from '@inertiajs/vue3'

interface Form {
    id: string
    full_name: string
    college: string
    office: string
    email: string
}

const form = useForm<Form>({
    id: '',
    full_name: '',
    college: '',
    office: '',
    email: '',
})

function createEmployee(): void {
    form.post(route('dashboard.employees.store'))
}
</script>
