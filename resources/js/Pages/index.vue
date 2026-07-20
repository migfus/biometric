<template>
    <div class="flex flex-col sm:flex-row py-8">
        <form
            @submit.prevent="submitForm()"
            class="flex flex-col gap-4 px-4 sm:px-0 sm:mx-auto sm:w-100"
        >
            <div class="flex justify-between items-center">
                <AppSwitch
                    :switches="autofill_selections"
                    v-model="selected_autofill"
                />
                <div class="flex gap-2 items-center">
                    <p class="text-xs text-neutral-500">
                        {{ moment().format('MMM DD, Y') }}
                    </p>
                </div>
            </div>

            <AppInput
                v-model="form.employee_no"
                name="Employee No."
                noLabel
                placeholder="Employee No."
                :error="$page.props.errors.employee_no"
                uppercase
            />
            <AppInput
                v-model="form.full_name"
                name="Full Name"
                noLabel
                placeholder="Full Name"
                :error="$page.props.errors.full_name"
            />
            <AppInput
                v-model="form.college"
                name="College or Department"
                noLabel
                placeholder="College or Department (optional)"
                :error="$page.props.errors.college"
            />
            <AppInput
                v-model="form.office"
                name="Office"
                noLabel
                placeholder="Office"
                :error="$page.props.errors.office"
            />
            <!-- <AppInput
            v-model="form.email"
            name="Email"
            type="email"
            noLabel
            placeholder="Email (optional)"
            :error="$page.props.errors.email"
        /> -->

            <div class="flex justify-between items-center">
                <AppSwitch :switches="check_in_out" v-model="form.check" />
                <p class="text-neutral-700 text-sm">
                    {{ moment().format('h:mm A') }}
                </p>
            </div>

            <AppTextArea
                v-model="form.work_description"
                name="Work Description"
                placeholder="Work Description"
                :error="$page.props.errors.work_description"
            />
            <div class="flex gap-1 items-center -mt-3 justify-end">
                <AppButton
                    class="text-xs"
                    icon="mingcute:ai-line"
                    type="button"
                    @click="
                        () => {
                            rephrase()
                            rephraseSheet = true
                        }
                    "
                    :forceLoading="ai_loading"
                >
                    {{ ai_loading ? 'Rephrasing...' : 'Rephrase' }}
                </AppButton>
            </div>

            <div
                v-if="$cameraStore.taken_photos.length > 0"
                class="flex gap-2 overflow-x-auto"
            >
                <Link
                    :href="route('camera.index')"
                    type="button"
                    class="bg-white rounded-xl text-center flex flex-col items-center p-4 text-neutral-600 border-2 border-dashed border-neutral-400"
                >
                    <Icon icon="ic:baseline-plus" class="size-8 my-auto"></Icon>
                </Link>

                <ImagePreviewContent
                    :attachments="
                        $cameraStore.taken_photos.map((item) => {
                            return {
                                id: item.id,
                                file_location: item.preview,
                                preview_location: item.preview_location,
                            }
                        })
                    "
                />
            </div>
            <Link
                v-else
                :href="route('camera.index')"
                type="button"
                class="w-full bg-white py-12 flex flex-col items-center gap-2 text-neutral-400 border-2 border-dashed rounded-3xl"
            >
                <Icon icon="ic:baseline-camera-alt" />
                <p>Capture an image to your work.</p>
            </Link>
            <p class="text-red-500 text-sm font-semibold">
                {{ $page.props.errors.images }}
            </p>

            <div class="flex flex-col gap-2 mb-16">
                <AppButton
                    icon="ic:outline-refresh"
                    type="button"
                    @click="
                        $promptModalStore.menu_items = [
                            {
                                name: 'Yes Reset Data',
                                icon: 'ic:outline-replay-circle-filled',
                                color: 'danger',
                                callback: () => {
                                    resetForm()
                                },
                            },
                            {
                                name: 'No Cancel',
                                icon: 'material-symbols:close',
                                color: '',
                                callback: () => {},
                            },
                        ]
                    "
                >
                    Reset
                </AppButton>
                <AppButton color="brand" icon="ic:baseline-send"
                    >Submit</AppButton
                >
            </div>

            <BottomSheet v-model="rephraseSheet" :transitionDuration="0.3">
                <div
                    class="p-4 flex flex-col max-h-[80vh] overflow-y-auto gap-4"
                >
                    <AppTextArea
                        v-model="form.work_description"
                        name="Work Description"
                        placeholder="Work Description"
                        :error="$page.props.errors.work_description"
                    />
                    <AppTextArea
                        v-model="new_rephrased_work_description"
                        name="Rephrased Work Description"
                        placeholder="Rephrased Work Description"
                        :error="$page.props.errors.work_description"
                        :ai_loading="ai_loading"
                    />

                    <div class="flex flex-col gap-2 sm:flex-row sm:justify-end">
                        <AppButton
                            icon="material-symbols:close"
                            type="button"
                            @click="rephraseSheet = false"
                            data-vsbs-no-drag
                            :disabled="ai_loading"
                        >
                            Cancel
                        </AppButton>
                        <AppButton
                            icon="mingcute:ai-line"
                            type="button"
                            @click="rephrase()"
                            :forceLoading="ai_loading"
                            data-vsbs-no-drag
                        >
                            {{ ai_loading ? 'Rephrasing...' : 'Rephrase' }}
                        </AppButton>
                        <AppButton
                            color="brand"
                            icon="material-symbols:check"
                            @click="
                                () => {
                                    form.work_description =
                                        new_rephrased_work_description
                                    rephraseSheet = false
                                }
                            "
                            :disabled="ai_loading"
                            data-vsbs-no-drag
                        >
                            Update
                        </AppButton>
                    </div>
                </div>
            </BottomSheet>
        </form>
    </div>
</template>

<script setup lang="ts">
import AppButton from '@/components/form/AppButton.vue'
import AppInput from '@/components/form/AppInput.vue'
import AppSwitch from '@/components/form/AppSwitch.vue'
import AppTextArea from '@/components/form/AppTextArea.vue'
import BottomSheet from '@douxcode/vue-spring-bottom-sheet'
import '@douxcode/vue-spring-bottom-sheet/dist/style.css'
import { Icon } from '@iconify/vue'
import { Link } from '@inertiajs/vue3'
import ImagePreviewContent from '@/components/data/ImagePreviewContent.vue'

import { useCameraStore } from '@/stores/camera.store'
import { usePromptModalStore } from '@/stores/promptModal.store'
import { applyAutofillFields } from '@/autofill'
import { router } from '@inertiajs/vue3'
import { useStorage } from '@vueuse/core'
import axios from 'axios'
import moment from 'moment'
import { onMounted, ref, watch } from 'vue'

interface Form {
    employee_no: string
    full_name: string
    college: string
    office: string
    check: string
    work_description: string
    // email: string
}

const check_in_out: { name: string; icon: string }[] = [
    {
        name: 'Check In',
        icon: 'ic:baseline-login',
    },
    {
        name: 'Check Out',
        icon: 'ic:baseline-logout',
    },
]

const autofill_selections: { name: string; icon: string }[] = [
    {
        name: 'Autofill',
        icon: 'ic:round-replay-circle-filled',
    },
    {
        name: 'Empty',
        icon: 'ic:outline-check-box-outline-blank',
    },
]

const $cameraStore = useCameraStore()
const $promptModalStore = usePromptModalStore()

const form = useStorage<Form>('index_form', initForm(), localStorage)
const form_autofill = useStorage<Form>(
    'form_autofill',
    initForm(),
    localStorage,
)
const ai_loading = ref<boolean>(false)
const selected_autofill = useStorage<string>(
    'selected_autofill',
    autofill_selections[0].name,
    localStorage,
)
const rephraseSheet = ref<boolean>(false)
const new_rephrased_work_description = ref<string>('')
const rephrase_count = ref<number>(0)

async function submitForm(): Promise<void> {
    const formData = new FormData()

    form_autofill.value = applyAutofillFields(form_autofill.value, form.value)

    formData.append('employee_no', form.value.employee_no)
    formData.append('full_name', form.value.full_name)
    formData.append('office', form.value.office)
    formData.append('check', form.value.check)
    formData.append('work_description', form.value.work_description)
    formData.append('college', form.value.college) // or department
    // formData.append('email', form.email)
    formData.append('client_os', getClientOS())
    formData.append('rephrase_count', rephrase_count.value.toString())

    for (const [index, photo] of $cameraStore.taken_photos.entries()) {
        const image_file = await convertDataUrlToFile(
            photo.preview,
            `photo-${photo.id}`,
        )
        const preview_file = await convertDataUrlToFile(
            photo.preview_location,
            `photo-preview-${photo.id}`,
        )

        formData.append(`images[${index}]`, image_file)
        formData.append(`preview_images[${index}]`, preview_file)
    }

    router.post('/', formData, {
        preserveState: true,
        onSuccess: () => {
            rephrase_count.value = 0

            if (selected_autofill.value == autofill_selections[0].name) {
                form.value = applyAutofillFields(
                    initForm(),
                    form_autofill.value,
                )
            } else {
                form.value = initForm()
            }

            $cameraStore.taken_photos = []
        },
    })
}

async function convertDataUrlToFile(
    data_url: string,
    file_name: string,
): Promise<File> {
    const response = await fetch(data_url)
    const blob = await response.blob()
    const extension = getExtensionFromMime(blob.type)

    return new File([blob], `${file_name}.${extension}`, {
        type: blob.type || 'image/jpeg',
    })
}

function getExtensionFromMime(mime_type: string): string {
    if (mime_type === 'image/png') {
        return 'png'
    }

    return 'jpg'
}

function initForm(): Form {
    return {
        employee_no: '',
        full_name: '',
        office: '',
        check: getCurrentCheckStatus(),
        work_description: '',
        college: '',
        // email: '',
    }
}

function resetForm(): void {
    form.value = initForm()
    $cameraStore.taken_photos = []
}

function getCurrentCheckStatus(): string {
    // const now = new Date()
    const now = new Date()
    const totalMinutes = now.getHours() * 60 + now.getMinutes()

    const nineAm = 9 * 60 // 540
    const twelveThirtyPm = 12 * 60 + 30 // 750
    const threePm = 15 * 60 // 900

    // 12:00 AM–9:00 AM → Check In
    if (totalMinutes <= nineAm) {
        return check_in_out[0].name
    }

    // 9:01 AM–12:30 PM → Check Out
    if (totalMinutes <= twelveThirtyPm) {
        return check_in_out[1].name
    }

    // 12:31 PM–3:00 PM → Check In
    if (totalMinutes <= threePm) {
        return check_in_out[0].name
    }

    // 3:01 PM–11:59 PM → Check Out
    return check_in_out[1].name
}

async function rephrase(): Promise<void> {
    if (form.value.work_description) {
        ai_loading.value = true
        try {
            const response = await axios.post('/api/rephrase', {
                work_description: form.value.work_description,
            })
            new_rephrased_work_description.value =
                response.data.rephrased_work_description
            rephrase_count.value++
        } catch (error) {
            console.error('Error rephrasing text:', error)
        } finally {
            ai_loading.value = false
        }
    }
}

function getClientOS(): string {
    const ua = navigator.userAgent

    if (/android/i.test(ua)) return 'Android'
    if (/iPad|iPhone|iPod/.test(ua)) return 'iOS'
    if (/Windows NT/i.test(ua)) return 'Windows'
    if (/Mac OS X/i.test(ua)) return 'macOS'
    if (/Linux/i.test(ua)) return 'Linux'

    return 'Unknown'
}

onMounted((): void => {
    form.value.check = getCurrentCheckStatus()

    if (!form.value.employee_no) {
        if (autofill_selections[0].name == selected_autofill.value) {
            form.value = applyAutofillFields(form.value, form_autofill.value)
        }
    }
})

watch(selected_autofill, (newValue) => {
    if (newValue == autofill_selections[0].name) {
        form.value = applyAutofillFields(form.value, form_autofill.value)
    } else {
        form.value = initForm()
    }
})
</script>
