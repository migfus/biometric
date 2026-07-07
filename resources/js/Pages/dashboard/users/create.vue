<template>
    <div class="flex flex-col gap-4">
        <BasicCard title="Create User" icon="ic:baseline-plus">
            <form @submit.prevent="create()" class="flex flex-col gap-2">
                <AppInput
                    name="Name"
                    v-model="form.name"
                    :error="$page.props.errors.name"
                />
                <AppInput
                    name="Email"
                    v-model="form.email"
                    :error="$page.props.errors.email"
                />
                <AppInput
                    name="Password"
                    v-model="form.password"
                    :error="$page.props.errors.password"
                />
                <AppInput
                    name="Confirm Password"
                    v-model="form.password_confirmation"
                />

                <div class="flex flex-col gap-2 mt-4">
                    <AppButton color="brand" icon="ic:baseline-plus"
                        >Create</AppButton
                    >
                    <AppButton
                        :href="route('dashboard.users.index')"
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
import { useForm } from '@inertiajs/vue3'

const form = useForm<{
    name: string
    email: string
    password: string
    password_confirmation: string
}>({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
})

function create() {
    form.post(route('dashboard.users.store'))
}
</script>
