<template>
    <form @submit.prevent="submitForm()" class="flex flex-col gap-4">
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
            name="College"
            noLabel
            placeholder="College (optional)"
            :error="$page.props.errors.college"
        />
        <AppInput
            v-model="form.department"
            name="Department or Office"
            noLabel
            placeholder="Department or Office"
            :error="$page.props.errors.department"
        />

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
                        rephraseSheet.open()
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
            <button
                @click="newHistory('camera')"
                type="button"
                class="bg-white rounded-xl text-center flex flex-col items-center p-8 text-neutral-600 border-2 border-dashed border-neutral-400"
            >
                <Icon icon="ic:baseline-plus" class="size-8 mt-4"></Icon>
            </button>

            <ImagePreview
                v-for="photo in $cameraStore.taken_photos"
                :key="photo.id"
                :image_preview="[photo]"
                @click="
                    photos = $cameraStore.taken_photos.map((item) => {
                        return { file_location: item.preview, id: item.id }
                    })
                "
            />
        </div>
        <button
            v-else
            @click="newHistory('camera')"
            type="button"
            class="w-full bg-white py-12 flex flex-col items-center gap-2 text-neutral-400 border-2 border-dashed rounded-3xl"
        >
            <Icon icon="ic:baseline-camera-alt" />
            <p>Capture an image to your work.</p>
        </button>
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
                            callback: () => {
                                resetForm()
                            },
                        },
                        {
                            name: 'No Cancel',
                            icon: 'material-symbols:close',
                            callback: () => {},
                        },
                    ]
                "
                >Reset</AppButton
            >
            <AppButton color="brand" icon="ic:baseline-send">Submit</AppButton>
        </div>

        <VueBottomSheet ref="rephraseSheet" :transitionDuration="0.3">
            <div class="p-4 flex flex-col max-h-[80vh] overflow-y-auto gap-4">
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

                <div class="flex flex-col gap-2">
                    <AppButton
                        icon="material-symbols:close"
                        type="button"
                        @click="rephraseSheet.close()"
                        >Cancel</AppButton
                    >
                    <AppButton
                        icon="mingcute:ai-line"
                        type="button"
                        @click="rephrase()"
                        :forceLoading="ai_loading"
                        >Rephrase</AppButton
                    >
                    <AppButton
                        color="brand"
                        icon="material-symbols:check"
                        @click="
                            () => {
                                form.work_description =
                                    new_rephrased_work_description
                                rephraseSheet.close()
                            }
                        "
                        :disabled="ai_loading"
                        >Update</AppButton
                    >
                </div>
            </div>
        </VueBottomSheet>
    </form>
</template>

<script setup lang="ts">
import AppButton from '@/Components/form/AppButton.vue'
import { useCameraStore } from '@/Stores/camera.store'
import AppSwitch from '@/Components/form/AppSwitch.vue'
import AppInput from '@/Components/form/AppInput.vue'
import AppTextArea from '@/Components/form/AppTextArea.vue'
import { Icon } from '@iconify/vue'
import ImagePreview from './ImagePreview.vue'

import moment from 'moment'
import { router } from '@inertiajs/vue3'
import axios from 'axios'
import { useHistoryNavigation } from '@/Stores/historyNavigation.store'
import { storeToRefs } from 'pinia'
import { usePromptModalStore } from '@/Stores/promptModal.store'
import { usePreviewPhotoStore } from '@/Stores/previewPhoto.store'
import { onMounted, reactive, ref, useTemplateRef, watch } from 'vue'
import { useStorage } from '@vueuse/core'

interface Form {
    employee_no: string
    full_name: string
    college: string
    department: string
    check: string
    work_description: string
}

const check_in_out = [
    {
        name: 'Check In',
        icon: 'ic:baseline-login',
    },
    {
        name: 'Check Out',
        icon: 'ic:baseline-logout',
    },
]

const autofill_selections = [
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
const $previewPhotosStore = usePreviewPhotoStore()
const { photos } = storeToRefs($previewPhotosStore)
const $historyNavigationStore = useHistoryNavigation()
const { newHistory } = $historyNavigationStore
const $promptModalStore = usePromptModalStore()

const formData = new FormData()
const form = reactive<Form>(initForm())
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
const rephraseSheet = useTemplateRef('rephraseSheet')
const new_rephrased_work_description = ref<string>('')
const rephrase_count = ref(0)

function submitForm() {
    form_autofill.value = form

    formData.append('employee_no', form.employee_no)
    formData.append('full_name', form.full_name)
    formData.append('department', form.department)
    formData.append('check', form.check)
    formData.append('work_description', form.work_description)
    formData.append('college', form.college)
    formData.append('client_os', getClientOS())
    formData.append('rephrase_count', rephrase_count.value.toString())

    $cameraStore.taken_photos.forEach((photo, index) => {
        formData.append(`images[${index}]`, photo.file)
    })

    router.post('/', formData, { preserveState: true })
}

function initForm(): Form {
    return {
        employee_no: '',
        full_name: '',
        department: '',
        check: getCurrentCheckStatus(),
        work_description: '',
        college: '',
    }
}

function resetForm() {
    Object.assign(form, initForm())
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

async function rephrase() {
    if (form.work_description) {
        ai_loading.value = true
        try {
            const response = await axios.post('/api/rephrase', {
                work_description: form.work_description,
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

onMounted(() => {
    form.check = getCurrentCheckStatus()

    if (autofill_selections[0].name == selected_autofill.value) {
        // autofill mode
        // alert('autofill')
        Object.assign(form, form_autofill.value)
    }
})

watch(selected_autofill, (newValue) => {
    if (newValue == autofill_selections[0].name) {
        // autofill mode
        Object.assign(form, form_autofill.value)
    } else {
        // empty mode
        Object.assign(form, initForm())
    }
})

router.on('success', () => {
    rephrase_count.value = 0

    if (selected_autofill.value == autofill_selections[0].name) {
        Object.assign(form, form_autofill.value)
    } else {
        Object.assign(form, initForm())
    }

    $cameraStore.taken_photos = []
    newHistory('records')
})
</script>
