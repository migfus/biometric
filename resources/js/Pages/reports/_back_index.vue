<template>
    <div class="flex flex-col sm:flex-row py-8">
        <form
            @submit.prevent="submitForm()"
            class="flex flex-col gap-8 px-4 sm:px-0 sm:mx-auto sm:w-100"
        >
            <div class="flex justify-between items-center">
                <AppSwitch
                    :switches="autofill_selections"
                    v-model="selected_autofill"
                />
                <div class="flex gap-2 items-center">
                    <p class="text-xs text-neutral-500">
                        {{ current_time }}
                    </p>
                </div>
            </div>

            <div class="flex flex-col gap-2">
                <AppSelect
                    name="Biometric Device"
                    v-model="form.biometric_device"
                    :suggestions="biometric_devices"
                />

                <!-- <AppInput
                    v-model="form.biometric_device_name"
                    name="Biometric Device Name"
                    placeholder="Biometric Device Name"
                    :error="$page.props.errors.biometric_device_name"
                    uppercase
                    color="alt"
                /> -->

                <!-- <div class="flex justify-between items-center">
                    <AppOption :switches="check_in_out" v-model="form.check" />
                </div> -->

                <!-- <AppInput
                    v-model="form.date_time_accident"
                    name="Type of Issue"
                    placeholder="Type of Issue"
                    :error="$page.props.errors.date_time_accident"
                    color="alt"
                /> -->
            </div>

            <div class="flex flex-col gap-4">
                <!-- <AppInput
                    v-model="form.employee_no"
                    name="Employee ID No."
                    placeholder="Employee ID No."
                    :error="$page.props.errors.employee_no"
                    uppercase
                    color="alt"
                />
                <AppInput
                    v-model="form.full_name"
                    name="Full Name"
                    placeholder="Full Name"
                    :error="$page.props.errors.full_name"
                    color="alt"
                />
                <AppInput
                    v-model="form.college"
                    name="College/Office/Unit"
                    placeholder="College/Office/Unit"
                    :error="$page.props.errors.college"
                    color="alt"
                />
                <AppInput
                    v-model="form.date_time_accident"
                    name="Date Time Accident"
                    placeholder="Date Time Accident"
                    :error="$page.props.errors.date_time_accident"
                    color="alt"
                    type="datetime-local"
                /> -->
            </div>

            <!-- <AppInput
            v-model="form.email"
            name="Email"
            type="email"
            noLabel
            placeholder="Email (optional)"
            :error="$page.props.errors.email"
        /> -->

            <div class="relative">
                <!-- <AppTextArea
                    v-model="form.work_description"
                    name="Description of the Issue"
                    placeholder="Description of the Issue"
                    :error="$page.props.errors.work_description"
                    color="alt"
                    class="relative"
                >
                </AppTextArea>
                <AppButton
                    class="text-xs absolute bottom-2 right-2"
                    icon="mingcute:ai-line"
                    type="button"
                    size="sm"
                    noLabel
                    @click="
                        () => {
                            rephrase()
                            rephraseSheet = true
                        }
                    "
                    :forceLoading="ai_loading"
                >
                    {{ ai_loading ? 'Rephrasing...' : 'Rephrase' }}
                </AppButton> -->
            </div>

            <!-- <AppTextArea
                v-model="form.work_description"
                name="Description of the Issue"
                placeholder="Description of the Issue"
                :error="$page.props.errors.work_description"
                color="alt"
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
            </div> -->

            <div
                v-if="$cameraStore.taken_photos.length > 0"
                class="flex gap-2 overflow-x-auto"
            >
                <Link
                    :href="route('camera.index')"
                    type="button"
                    class="bg-white dark:bg-neutral-800 rounded-xl text-center flex flex-col items-center p-4 text-neutral-600 dark:text-neutral-400 border-2 border-dashed border-neutral-400 dark:border-neutral-500"
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
                class="w-full bg-white dark:bg-neutral-800 py-12 flex flex-col items-center gap-2 text-neutral-400 border-2 border-dashed dark:border-neutral-700 rounded-3xl"
            >
                <Icon icon="ic:baseline-camera-alt" />
                <p>Capture an image to your work.</p>
            </Link>
            <p class="text-red-500 text-sm font-semibold">
                {{ $page.props.errors.images }}
            </p>

            <div class="flex flex-col gap-2 sm:flex-row sm:justify-end">
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
                <AppButton color="brand" icon="ic:baseline-send">
                    Submit
                </AppButton>
            </div>

            <BottomSheet v-model="rephraseSheet" :transitionDuration="0.3">
                <div
                    class="p-4 flex flex-col max-h-[80vh] overflow-y-auto gap-4"
                >
                    <!-- <AppTextArea
                        v-model="form.work_description"
                        name="Work Description"
                        placeholder="Work Description"
                        :error="$page.props.errors.work_description"
                        color="alt"
                    /> -->
                    <AppTextArea
                        v-model="new_rephrased_description"
                        name="Rephrased Description"
                        placeholder="Rephrased Description"
                        :error="$page.props.errors.description"
                        :ai_loading="ai_loading"
                        color="alt"
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
                        <!-- <AppButton
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
                        </AppButton> -->
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
import AppSelect from '@/components/form/AppSelect.vue'

import { useCameraStore } from '@/stores/camera.store'
import { usePromptModalStore } from '@/stores/promptModal.store'
import { router } from '@inertiajs/vue3'
import { useStorage } from '@vueuse/core'
import axios from 'axios'
import moment from 'moment'
import { onMounted, ref, watch } from 'vue'
import {
    BiometricDevice,
    CheckStatus,
    ReportType,
    Office,
    EmploymentType,
    Select,
} from '@/globalInterfaces'

const {
    biometric_devices,
    report_types,
    check_statuses,
    offices,
    employment_types,
} = defineProps<{
    biometric_devices: BiometricDevice[]
    report_types: ReportType[]
    check_statuses: CheckStatus[]
    offices: Office[]
    employment_types: EmploymentType[]
}>()

interface Form {
    biometric_device: Select
    report_type: Select
    check_status: Select
    description: string
    action_taken: string
    employee_id: string
    full_name: string
    office: string
    employment_type: Select
}

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
const new_rephrased_description = ref<string>('')
const rephrase_count = ref<number>(0)
const current_time = ref<string>(moment().format('h:mm:ss A'))

async function submitForm(): Promise<void> {
    const formData = new FormData()

    form_autofill.value = applyAutofillFields(form_autofill.value, form.value)

    formData.append('employee_id', form.value.employee_id)
    formData.append('full_name', form.value.full_name)
    formData.append('office', form.value.office)
    formData.append('check_status_id', form.value.check_status.id.toString())
    formData.append('description', form.value.description)
    formData.append('action_taken', form.value.action_taken)
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
        biometric_device: biometric_devices[0] || { id: 0, name: 'N/A' },
        report_type: report_types[0] || { id: 0, name: 'N/A' },
        check_status: check_statuses[0] || { id: 0, name: 'N/A' },
        description: '',
        action_taken: '',
        employee_id: '',
        full_name: '',
        office: offices[0].name || '',
        employment_type: employment_types[0] || { id: 0, name: 'N/A' },
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
    if (form.value.description) {
        ai_loading.value = true
        try {
            const response = await axios.post('/api/rephrase', {
                description: form.value.description,
            })
            new_rephrased_description.value =
                response.data.rephrased_description
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

function applyAutofillFields(current_form: Form, autofill_form: Form): Form {
    return {
        ...current_form,
        employee_id: autofill_form.employee_id,
        full_name: autofill_form.full_name,
    }
}

onMounted((): void => {
    // form.value.check = getCurrentCheckStatus()

    if (!form.value.employee_id) {
        if (autofill_selections[0].name == selected_autofill.value) {
            form.value = applyAutofillFields(form.value, form_autofill.value)
        }
    }

    setInterval(() => {
        current_time.value = moment().format('MMM DD, h:mm:ss A')
    }, 1000)
})

watch(selected_autofill, (newValue) => {
    if (newValue == autofill_selections[0].name) {
        form.value = applyAutofillFields(form.value, form_autofill.value)
    } else {
        form.value = initForm()
    }
})
</script>
