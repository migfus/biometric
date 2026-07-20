<template>
    <div class="py-4 px-0 sm:px-2">
        <BasicCard
            title="Reset Link Sent"
            icon="material-symbols:mail-outline"
            class="md:w-120 md:mx-auto"
        >
            <form
                @submit.prevent="changePassword()"
                class="flex flex-col gap-4"
            >
                <p class="text-sm text-neutral-600">
                    You can now reset the password.
                </p>
                <!-- <div
                    class="rounded-xl border border-neutral-200 bg-neutral-50 p-4 text-sm text-neutral-700 break-all"
                >
                    {{ id }}
                </div> -->
                <p class="text-sm text-neutral-500">
                    Email: {{ email ?? 'Not provided' }}
                </p>

                <input type="hidden" name="email" v-model="form.email" />

                <AppInput
                    name="New Password"
                    v-model="form.password"
                    :error="$page.props.errors.password"
                />
                <AppInput
                    name="Confirm Password"
                    v-model="form.password_confirmation"
                />

                <AppButton
                    class="w-full"
                    color="brand"
                    icon="material-symbols:login"
                >
                    Update Password
                </AppButton>
            </form>
        </BasicCard>
    </div>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'
import AppButton from '@/components/form/AppButton.vue'
import AppInput from '@/components/form/AppInput.vue'

import { useForm } from '@inertiajs/vue3'

const { id, email } = defineProps<{
    id: string
    email?: string
}>()

const form = useForm<{
    email: string
    password: string
    password_confirmation: string
}>({
    email: email ?? '',
    password: '',
    password_confirmation: '',
})

function changePassword(): void {
    form.put(route('forgot.update', id))
}
</script>
