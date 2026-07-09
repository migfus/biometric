<template>
    <div class="flex flex-col gap-4 lg:w-120 lg:mx-auto">
        <BasicCard title="Edit User" icon="mdi:pencil">
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

                <div
                    class="flex flex-col gap-2 mt-4 md:flex-row md:justify-end"
                >
                    <AppButton color="brand" icon="material-symbols:check">
                        Update
                    </AppButton>
                    <AppButton
                        v-if="$page.props.auth?.id != user.id"
                        color="danger"
                        icon="material-symbols:delete"
                    >
                        Remove
                    </AppButton>
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

interface Form {
    name: string
    email: string
    password: string
    password_confirmation: string
}

const { user } = defineProps<{
    user: User
}>()

const form = useForm<Form>(initForm())

function initForm(): Form {
    return {
        name: user.name,
        email: user.email,
        password: '',
        password_confirmation: '',
    }
}

function update(): void {
    form.put(route('dashboard.users.update', user.id))
}
</script>
