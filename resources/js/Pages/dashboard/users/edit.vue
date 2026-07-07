<template>
    <div class="flex flex-col gap-4">
        <BasicCard title="Edit User" icon="ic:baseline-plus">
            <form @submit.prevent="update()" class="flex flex-col gap-2">
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
                    <AppButton color="brand" icon="material-symbols:check"
                        >Update</AppButton
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
import { User } from '@/globalInterfaces'
import { useForm } from '@inertiajs/vue3'

const { user } = defineProps<{
    user: User
}>()

const form = useForm<{
    name: string
    email: string
    password: string
    password_confirmation: string
}>(initForm())

function initForm() {
    return {
        name: user.name,
        email: user.email,
        password: '',
        password_confirmation: '',
    }
}

function update() {
    form.put(route('dashboard.users.update', user.id))
}
</script>
