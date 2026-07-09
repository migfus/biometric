<template>
    <div>
        <ImageModal v-if="photos.length > 0" :photos />

        <div v-else class="flex flex-col gap-4">
            <BasicCard title="Check Information" icon="mingcute:time-line">
                <div class="flex flex-col gap-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1">
                            <p class="text-xs uppercase tracking-wide text-neutral-500">
                                Check ID
                            </p>
                            <p class="text-sm font-semibold text-neutral-800">
                                {{ check.id }}
                            </p>
                        </div>

                        <div class="flex flex-col gap-1">
                            <p class="text-xs uppercase tracking-wide text-neutral-500">
                                Employee No.
                            </p>
                            <p class="text-sm font-semibold text-neutral-800">
                                {{ check.employee_id }}
                            </p>
                        </div>

                        <div class="flex flex-col gap-1">
                            <p class="text-xs uppercase tracking-wide text-neutral-500">
                                Employee Name
                            </p>
                            <p class="text-sm font-semibold text-neutral-800">
                                {{ check.employee?.full_name ?? 'Unknown Employee' }}
                            </p>
                        </div>

                        <div class="flex flex-col gap-1">
                            <p class="text-xs uppercase tracking-wide text-neutral-500">
                                Type
                            </p>
                            <p class="text-sm font-semibold text-neutral-800">
                                {{ check.check_in ? 'Check In' : 'Check Out' }}
                            </p>
                        </div>

                        <div class="flex flex-col gap-1">
                            <p class="text-xs uppercase tracking-wide text-neutral-500">
                                Date and Time
                            </p>
                            <p class="text-sm font-semibold text-neutral-800">
                                {{ moment(check.created_at).format('MMM D, YYYY - h:mm:ss a') }}
                            </p>
                        </div>

                        <div class="flex flex-col gap-1">
                            <p class="text-xs uppercase tracking-wide text-neutral-500">
                                IP Address
                            </p>
                            <p class="text-sm font-semibold text-neutral-800">
                                {{ check.ip_address }}
                            </p>
                        </div>

                        <div class="flex flex-col gap-1 sm:col-span-2">
                            <p class="text-xs uppercase tracking-wide text-neutral-500">
                                OS
                            </p>
                            <p class="text-sm font-semibold text-neutral-800">
                                {{ check.os }}
                            </p>
                        </div>

                        <div class="flex flex-col gap-1 sm:col-span-2">
                            <p class="text-xs uppercase tracking-wide text-neutral-500">
                                Work Description
                            </p>
                            <p class="text-sm font-semibold text-neutral-800 whitespace-pre-line">
                                {{ check.work_description }}
                            </p>
                        </div>
                    </div>

                    <ImagePreviewContent
                        :attachments="check.attachments"
                        @open="openAttachments"
                    />

                    <div class="flex flex-col gap-2">
                        <AppButton
                            :href="route('dashboard.checks.edit', check.id)"
                            type="button"
                            icon="mdi:pencil"
                            color="brand"
                        >
                            Edit
                        </AppButton>

                        <AppButton
                            :href="route('dashboard.checks.index')"
                            type="button"
                            icon="material-symbols:arrow-back"
                        >
                            Back
                        </AppButton>
                    </div>
                </div>
            </BasicCard>
        </div>
    </div>
</template>

<script setup lang="ts">
import BasicCard from '@/Components/cards/BasicCard.vue'
import AppButton from '@/Components/form/AppButton.vue'
import { Check } from '@/globalInterfaces'
import moment from 'moment'
import ImagePreviewContent from '../../ImagePreviewContent.vue'
import ImageModal from '../../ImageModal.vue'
import { usePreviewPhotoStore } from '@/Stores/previewPhoto.store'
import { storeToRefs } from 'pinia'

const { check } = defineProps<{
    check: Check
}>()

const $previewPhotoStore = usePreviewPhotoStore()
const { photos } = storeToRefs($previewPhotoStore)

function openAttachments()
{
    photos.value = check.attachments.map(function (item) {
        return {
            file_location: item.file_location,
            id: item.id,
        }
    })
}
</script>