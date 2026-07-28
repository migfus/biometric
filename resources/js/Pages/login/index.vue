<template>
    <div class="flex flex-col py-4 sm:px-2">
        <Head title="Log in" />

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <BasicCard
            title="Login"
            icon="material-symbols:login"
            class="md:w-120 md:mx-auto"
        >
            <form @submit.prevent="submit" class="flex flex-col gap-2">
                <AppInput
                    name="Email"
                    v-model="form.email"
                    type="email"
                    :error="form.errors.email"
                />
                <AppInput
                    name="Password"
                    v-model="form.password"
                    type="password"
                    :error="form.errors.password"
                />

                <Link
                    :href="route('forgot.index')"
                    class="rounded-md text-sm text-neutral-600 dark:text-neutral-400 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Forgot your password?
                </Link>

                <div
                    class="mt-4 flex items-center flex-col sm:flex-row sm:justify-end"
                >
                    <AppButton
                        :disabled="form.processing"
                        icon="material-symbols:login"
                        color="brand"
                        class="w-full sm:w-auto"
                    >
                        Log in
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
import { Head, Link, useForm } from '@inertiajs/vue3'

defineProps<{
    canResetPassword?: boolean
    status?: string
}>()

const form = useForm<{
    email: string
    password: string
    remember: boolean
}>({
    email: '',
    password: '',
    remember: false,
})

function submit(): void {
    form.post(route('login.store'), {
        preserveState: true,
        preserveScroll: true,
        onFinish: () => {
            form.reset('password')
        },
    })
}
</script>
