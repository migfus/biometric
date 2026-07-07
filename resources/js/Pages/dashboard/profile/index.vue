<template>
    <div class="p-4 flex flex-col gap-4">
        <BasicCard title="Avatar" icon="material-symbols:image-outline">
            <div class="flex flex-col gap-2 items-center">
                <img :src="form.avatar" class="size-24 rounded-full" />
                <AppButton
                    v-if="form.avatar != $page.props.auth?.avatar"
                    class="w-full"
                    icon="material-symbols:check"
                    color="brand"
                >
                    Update
                </AppButton>
                <AppButton
                    class="w-full"
                    icon="material-symbols:upload"
                    @click="triggerAvatarSelect()"
                >
                    Change
                </AppButton>

                <input
                    ref="avatarInput"
                    type="file"
                    class="hidden"
                    accept="image/jpeg,image/png"
                    @change="handleAvatarSelected"
                />

                <p v-if="avatarError" class="text-sm text-red-600">
                    {{ avatarError }}
                </p>
            </div>
        </BasicCard>

        <!-- SECTION: PROFILE -->
        <BasicCard title="Profile" icon="material-symbols:info-outline">
            <form @submit.prevent="updateProfile()" class="flex flex-col gap-8">
                <div class="flex flex-col gap-2">
                    <AppInput
                        name="Name"
                        v-model="form.name"
                        :error="$page.props.errors?.name"
                    />
                    <AppInput
                        name="Email"
                        v-model="form.email"
                        type="email"
                        :error="$page.props.errors?.email"
                    />
                </div>

                <div class="flex items-center flex-col gap-2">
                    <AppButton
                        class="w-full"
                        color="brand"
                        icon="material-symbols:check"
                    >
                        Update
                    </AppButton>
                    <AppButton
                        class="w-full"
                        type="button"
                        icon="ic:baseline-refresh"
                        @click="Object.assign(form, initForm())"
                    >
                        Reset
                    </AppButton>
                </div>
            </form>
        </BasicCard>

        <!-- SECTION: PASSWORD -->
        <BasicCard title="Change Password" icon="material-symbols:key-outline">
            <form
                @submit.prevent="updatePassword()"
                class="flex flex-col gap-8"
            >
                <div class="flex flex-col gap-2">
                    <AppInput
                        name="Old Password"
                        v-model="form.old_password"
                        type="password"
                        :error="$page.props.errors?.old_password"
                    />

                    <AppInput
                        name="New Password"
                        v-model="form.password"
                        type="password"
                        :error="$page.props.errors?.password"
                    />
                    <AppInput
                        name="Confirm Password"
                        v-model="form.password_confirmation"
                        type="password"
                        :error="$page.props.errors?.password_confirmation"
                    />
                </div>

                <div class="flex items-center flex-col gap-2">
                    <AppButton
                        class="w-full"
                        color="brand"
                        icon="material-symbols:check"
                    >
                        Update
                    </AppButton>
                    <AppButton
                        class="w-full"
                        type="button"
                        icon="ic:baseline-refresh"
                        @click="Object.assign(form, initForm())"
                    >
                        Reset
                    </AppButton>
                </div>
            </form>
        </BasicCard>

        <BottomSheet
            v-model="bottom_sheet"
            :transitionDuration="0.3"
            @closed="
                () => {
                    bottom_sheet = false
                    pic = ''
                }
            "
        >
            <div class="p-4 flex flex-col max-h-[80vh] overflow-y-auto gap-4">
                <div v-if="!pic" class="flex flex-col items-center gap-4">
                    <p class="text-sm text-neutral-500 text-center">
                        Select a JPG or PNG image to crop your avatar.
                    </p>
                    <AppButton
                        type="button"
                        color="brand"
                        icon="material-symbols:upload"
                        @click="triggerAvatarSelect"
                    >
                        Choose file
                    </AppButton>
                </div>

                <div v-else class="flex flex-col gap-4">
                    <VuePictureCropper
                        ref="vpcRef"
                        :img="pic"
                        :options="{
                            viewMode: 2,
                            aspectRatio: 1,
                        }"
                        data-vsbs-no-drag
                    />

                    <AppButton
                        type="button"
                        @click="upload()"
                        color="brand"
                        data-vsbs-no-drag
                        icon="material-symbols:upload"
                        :disabled="!pic"
                    >
                        Crop and Update
                    </AppButton>
                    <AppButton
                        type="button"
                        @click="
                            () => {
                                pic = ''
                                avatarError = null
                            }
                        "
                        data-vsbs-no-drag
                        icon="material-symbols:close"
                    >
                        Cancel
                    </AppButton>
                </div>
            </div>
        </BottomSheet>
    </div>
</template>

<script setup lang="ts">
import BasicCard from '@/Components/cards/BasicCard.vue'
import AppButton from '@/Components/form/AppButton.vue'
import AppInput from '@/Components/form/AppInput.vue'
import BottomSheet from '@douxcode/vue-spring-bottom-sheet'
import '@douxcode/vue-spring-bottom-sheet/dist/style.css'
import VuePictureCropper from 'vue-picture-cropper'
import 'cropperjs/dist/cropper.css'
import 'vue-picture-cropper/style.css'

import { router, usePage } from '@inertiajs/vue3'
import { reactive, ref, useTemplateRef } from 'vue'

const $page = usePage()

const form = reactive<{
    name: string
    email: string
    password: string
    password_confirmation: string
    old_password: string
    avatar: string
}>(initForm())

const bottom_sheet = ref<boolean>(false)
const pic = ref<string>('')
const avatarError = ref<string | null>(null)
const vpcRef = useTemplateRef<{
    cropper?: {
        getCroppedCanvas: (options?: {
            width?: number
            height?: number
        }) => HTMLCanvasElement
    }
} | null>('vpcRef')
const avatarInput = useTemplateRef('avatarInput')

function triggerAvatarSelect() {
    avatarError.value = null
    avatarInput.value?.click()
}

function handleAvatarSelected(event: Event) {
    const input = event.target as HTMLInputElement
    const file = input.files?.[0]

    if (!file) {
        return
    }

    if (!['image/jpeg', 'image/png'].includes(file.type)) {
        avatarError.value = 'Only JPG and PNG files are allowed.'
        return
    }

    const reader = new FileReader()
    reader.onload = () => {
        pic.value = reader.result as string
        bottom_sheet.value = true
    }
    reader.readAsDataURL(file)
    input.value = ''
}

async function canvasToFile(canvas: HTMLCanvasElement): Promise<File | null> {
    const maxBytes = 300 * 1024

    let quality = 0.95
    while (quality >= 0.4) {
        const jpegBlob = await new Promise<Blob | null>((resolve) => {
            canvas.toBlob((blob) => resolve(blob), 'image/jpeg', quality)
        })

        if (jpegBlob && jpegBlob.size <= maxBytes) {
            return new File([jpegBlob], 'avatar.jpg', { type: 'image/jpeg' })
        }

        quality -= 0.1
    }

    return null
}

async function upload() {
    const cropper = vpcRef.value?.cropper

    if (!cropper || typeof cropper.getCroppedCanvas !== 'function') {
        avatarError.value = 'Please select an image first.'
        return
    }

    const canvas = cropper.getCroppedCanvas({ width: 300, height: 300 })

    if (!canvas) {
        avatarError.value = 'Failed to crop image. Please try again.'
        return
    }

    const file = await canvasToFile(canvas)

    if (!file) {
        avatarError.value =
            'Cropped avatar is too large. Try a smaller image or crop smaller.'
        return
    }

    const formData = new FormData()
    formData.append('type', 'avatar')
    formData.append('avatar', file)

    router.post(route('dashboard.profile.store'), formData, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            bottom_sheet.value = false
            pic.value = ''
            avatarError.value = null
            Object.assign(form, initForm())
        },
    })
}

function initForm() {
    return {
        name: $page.props.auth?.name ?? '',
        email: $page.props.auth?.email ?? '',
        password: '',
        password_confirmation: '',
        old_password: '',
        avatar: $page.props.auth?.avatar ?? '',
    }
}

function updatePassword() {
    router.post(
        route('dashboard.profile.store'),
        {
            type: 'password',
            password: form.password,
            password_confirmation: form.password_confirmation,
            old_password: form.old_password,
        },
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                Object.assign(form, initForm())
            },
        },
    )
}

function updateProfile() {
    router.post(
        route('dashboard.profile.store'),
        {
            type: 'profile',
            name: form.name,
            email: form.email,
        },
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                Object.assign(form, initForm())
            },
        },
    )
}
</script>
