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
                <AppInput
                    v-model="form.employee_id"
                    name="Employee ID No."
                    placeholder="Employee ID No."
                    :error="$page.props.errors.employee_id"
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
                    v-model="form.office"
                    name="College/Office/Unit"
                    placeholder="College/Office/Unit"
                    :error="$page.props.errors.office"
                    color="alt"
                />
                <AppInput
                    v-model="form.email"
                    name="Email"
                    type="email"
                    placeholder="Email"
                    :error="$page.props.errors.email"
                    color="alt"
                />
                <AppInput
                    v-model="form.phone"
                    name="Phone Number"
                    placeholder="Phone Number"
                    :error="$page.props.errors.phone"
                    color="alt"
                    type="number"
                />
            </div>

            <div class="flex flex-col gap-2">
                <div class="flex flex-col gap-2">
                    <AppSelect
                        :name="`Biometric Device ${form.biometric_device.id == url_biometric_id ? '(auto from QR)' : ''}`"
                        v-model="form.biometric_device"
                        :suggestions="biometric_devices"
                        class="w-full"
                    />

                    <AppSelect
                        name="Check Type"
                        v-model="form.check_status"
                        :suggestions="check_statuses"
                        class="w-full"
                    />

                    <AppSelect
                        name="Issue"
                        v-model="form.report_type"
                        :suggestions="report_types"
                        class="w-full"
                    />

                    <AppTextArea
                        name="Description"
                        v-model="form.description"
                        color="alt"
                        placeholder="Describe what happened and any error message shown by the machine."
                    />

                    <AppTextArea
                        name="Immediate Action Taken"
                        v-model="form.action_taken"
                        color="alt"
                        placeholder="Example: Tried scanneing again, used another machine, or informed the supervisor."
                    />
                </div>
            </div>

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
                <Icon
                    icon="material-symbols:photo"
                    class="size-10 text-neutral-500"
                />
                <p>Add photos.</p>
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
        </form>
    </div>
</template>

<script setup lang="ts">
import ImagePreviewContent from '@/components/data/ImagePreviewContent.vue'
import AppButton from '@/components/form/AppButton.vue'
import AppSwitch from '@/components/form/AppSwitch.vue'
import '@douxcode/vue-spring-bottom-sheet/dist/style.css'
import { Icon } from '@iconify/vue'
import { Link } from '@inertiajs/vue3'
import AppInput from '@/components/form/AppInput.vue'

import {
    BiometricDevice,
    CheckStatus,
    EmploymentType,
    Office,
    ReportType,
    Select,
} from '@/globalInterfaces'
import { useCameraStore } from '@/stores/camera.store'
import { usePromptModalStore } from '@/stores/promptModal.store'
import { useStorage } from '@vueuse/core'
import { ref, onMounted } from 'vue'
import moment from 'moment'
import AppSelect from '@/components/form/AppSelect.vue'
import AppTextArea from '@/components/form/AppTextArea.vue'
import { router } from '@inertiajs/vue3'

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
    email: string
    phone: string
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

const selected_autofill = useStorage<string>(
    'selected_autofill',
    autofill_selections[0].name,
    localStorage,
)

const current_time = ref<string>(moment().format('MMM D - h:mm A'))

const $cameraStore = useCameraStore()
const $promptModalStore = usePromptModalStore()
const url_biometric_id = ref(-1)
const rephrase_count = useStorage<number>('rephrase_count', 0, localStorage)
const form_autofill = useStorage<Form>(
    'form_autofill',
    initForm(),
    localStorage,
)

const form = useStorage<Form>('index_form', initForm(), localStorage)
function initForm(): Form {
    return {
        biometric_device: biometric_devices[0] || { id: 0, name: 'N/A' },
        report_type: report_types[0] || { id: 0, name: 'N/A' },
        check_status: check_statuses[0] || { id: 0, name: 'N/A' },
        description: '',
        action_taken: '',
        employee_id: '',
        full_name: '',
        email: '',
        phone: '',

        office: offices[0].name || '',
        employment_type: employment_types[0] || { id: 0, name: 'N/A' },
    }
}

function resetForm(): void {
    form.value = initForm()
    $cameraStore.taken_photos = []
}

async function submitForm(): Promise<void> {
    const formData = new FormData()

    formData.append('employee_id', form.value.employee_id)
    formData.append(
        'biometric_device_id',
        form.value.biometric_device.id.toString(),
    )
    formData.append('report_type_id', form.value.report_type.id.toString())
    formData.append('check_status_id', form.value.check_status.id.toString())
    formData.append(
        'employment_type_id',
        form.value.employment_type.id.toString(),
    )

    formData.append('full_name', form.value.full_name)
    formData.append('office', form.value.office)
    formData.append('description', form.value.description)
    formData.append('action_taken', form.value.action_taken)
    formData.append('email', form.value.email)
    formData.append('phone', form.value.phone)
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

    router.post('/reports', formData, {
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

function getClientOS(): string {
    const ua = navigator.userAgent

    if (/android/i.test(ua)) return 'Android'
    if (/iPad|iPhone|iPod/.test(ua)) return 'iOS'
    if (/Windows NT/i.test(ua)) return 'Windows'
    if (/Mac OS X/i.test(ua)) return 'macOS'
    if (/Linux/i.test(ua)) return 'Linux'

    return 'Unknown'
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

function applyAutofillFields(current_form: Form, autofill_form: Form): Form {
    return {
        ...current_form,
        employee_id: autofill_form.employee_id,
        full_name: autofill_form.full_name,
    }
}

onMounted((): void => {
    setInterval(() => {
        current_time.value = moment().format('MMM D - h:mm A')
    }, 1000)

    const defaultBiometricDevice = biometric_devices[0] || {
        id: 0,
        name: 'N/A',
    }

    const param_biometric_id = new URLSearchParams(window.location.search).get(
        'biometric_id',
    )
    if (param_biometric_id === null) {
        form.value.biometric_device = defaultBiometricDevice
        return
    }

    url_biometric_id.value = Number.parseInt(param_biometric_id, 10)

    if (Number.isNaN(url_biometric_id.value)) {
        form.value.biometric_device = defaultBiometricDevice
        return
    }

    const matchedBiometricDevice = biometric_devices.find(
        (device) => device.id === url_biometric_id.value,
    )
    form.value.biometric_device =
        matchedBiometricDevice || defaultBiometricDevice
})
</script>
