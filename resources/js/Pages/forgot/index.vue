<template>
    <div class="p-4 flex flex-col gap-4">
        <BasicCard
            v-if="$page.props.flash?.success"
            title="Link sent!"
            icon="material-symbols:check"
        >
            <p class="text-green-900">
                {{ $page.props.flash?.success.content }}
            </p>

            <div
                v-if="mail_links.length"
                class="flex flex-col gap-2 text-sm text-neutral-600 mt-4 w-full"
            >
                <div class="flex flex-wrap gap-2 w-full">
                    <AppButton
                        v-for="link in mail_links"
                        :key="link.domain"
                        :href="link.url"
                        :icon="link.icon"
                        target="_blank"
                        rel="noreferrer noopener"
                        color="white"
                        class="border border-neutral-200 text-neutral-700 w-full"
                    >
                        Open your {{ link.label }}
                    </AppButton>
                </div>
            </div>
        </BasicCard>

        <BasicCard title="Forgot Password" icon="hugeicons:forgot-password">
            <form @submit.prevent="submit" class="flex flex-col gap-4">
                <AppInput
                    name="Email"
                    v-model="form.email"
                    type="email"
                    :error="$page.props.errors.email"
                />

                <div class="flex flex-col gap-2 items-center">
                    <div
                        v-if="formattedCooldown"
                        class="text-sm text-neutral-600 mr-auto"
                    >
                        You can request another reset link in
                        {{ formattedCooldown }}.
                    </div>
                    <AppButton
                        icon="material-symbols:link"
                        color="brand"
                        class="w-full"
                    >
                        Submit Link
                    </AppButton>

                    <AppButton
                        :href="route('login.index')"
                        icon="material-symbols:login"
                        class="w-full"
                        type="button"
                    >
                        Login
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
import { computed, onBeforeUnmount, ref } from 'vue'

const form = useForm({
    email: '',
})

const cooldown = ref(0)
let cooldownTimer: number | undefined

const formattedCooldown = computed(() => {
    if (cooldown.value <= 0) {
        return ''
    }

    const minutes = Math.floor(cooldown.value / 60)
    const seconds = cooldown.value % 60

    return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`
})

function startCooldown(seconds: number) {
    cooldown.value = seconds

    if (cooldownTimer) {
        clearInterval(cooldownTimer)
    }

    cooldownTimer = window.setInterval(() => {
        if (cooldown.value <= 1) {
            clearInterval(cooldownTimer)
            cooldown.value = 0
            cooldownTimer = undefined
            return
        }

        cooldown.value -= 1
    }, 1000)
}

onBeforeUnmount(() => {
    if (cooldownTimer) {
        clearInterval(cooldownTimer)
    }
})

const mail_links = computed(() => {
    const email = form.email.trim().toLowerCase()
    const providers = [
        {
            domain: 'gmail.com',
            label: 'Gmail',
            url: 'https://mail.google.com',
            icon: 'mdi:gmail',
        },
        {
            domain: 'yahoo.com',
            label: 'Yahoo Mail',
            url: 'https://mail.yahoo.com',
            icon: 'mdi:yahoo',
        },
        {
            domain: 'outlook.com',
            label: 'Outlook',
            url: 'https://outlook.live.com',
            icon: 'file-icons:microsoft-outlook',
        },
        {
            domain: 'hotmail.com',
            label: 'Hotmail',
            url: 'https://outlook.live.com',
            icon: 'simple-icons:icloud',
        },
        {
            domain: 'icloud.com',
            label: 'iCloud Mail',
            url: 'https://www.icloud.com/mail',
            icon: 'simple-icons:icloud',
        },
        {
            domain: 'aol.com',
            label: 'AOL Mail',
            url: 'https://mail.aol.com',
            icon: 'selfhst:aol-light',
        },
        {
            domain: 'proton.me',
            label: 'Proton Mail',
            url: 'https://mail.proton.me',
            icon: 'simple-icons:proton',
        },
    ]

    const domain = email.split('@')[1] || ''
    if (!domain) {
        return []
    }

    return providers.filter(
        (provider) =>
            domain === provider.domain ||
            domain.endsWith(`.${provider.domain}`),
    )
})

function submit() {
    form.post(route('forgot.store'), {
        onSuccess: () => {
            startCooldown(180)
        },
    })
}
</script>
