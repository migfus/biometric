<template>
    <div>
        <div class="flex flex-col gap-4 lg:w-120 lg:mx-auto">
            <BasicCard title="Check Information" icon="mingcute:time-line">
                <div class="flex flex-col gap-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1">
                            <p
                                class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-300"
                            >
                                Check ID
                            </p>
                            <p
                                class="text-sm font-semibold text-neutral-800 dark:text-neutral-200"
                            >
                                {{ check.id }}
                            </p>
                        </div>

                        <div class="flex flex-col gap-1">
                            <p
                                class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-300"
                            >
                                Employee No.
                            </p>
                            <p
                                class="text-sm font-semibold text-neutral-800 dark:text-neutral-200"
                            >
                                {{ check.employee_id }}
                            </p>
                        </div>

                        <div class="flex flex-col gap-1">
                            <p
                                class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-300"
                            >
                                Employee Name
                            </p>
                            <p
                                :class="[
                                    check.deleted_at
                                        ? 'text-sm font-semibold text-neutral-400 line-through'
                                        : 'text-sm font-semibold text-neutral-800 dark:text-neutral-200',
                                ]"
                            >
                                {{
                                    check.employee?.full_name ??
                                    'Unknown Employee'
                                }}
                            </p>
                        </div>

                        <div class="flex flex-col gap-1">
                            <p
                                class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-300"
                            >
                                Type
                            </p>
                            <p
                                class="text-sm font-semibold text-neutral-800 dark:text-neutral-200"
                            >
                                {{ check.check_in ? 'Check In' : 'Check Out' }}
                            </p>
                        </div>

                        <div class="flex flex-col gap-1">
                            <p
                                class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-300"
                            >
                                Date and Time
                            </p>
                            <p
                                class="text-sm font-semibold text-neutral-800 dark:text-neutral-200"
                            >
                                {{
                                    moment(check.created_at).format(
                                        'MMM D, YYYY - h:mm:ss a',
                                    )
                                }}
                            </p>
                        </div>

                        <div class="flex flex-col gap-1">
                            <p
                                class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-300"
                            >
                                IP Address
                            </p>
                            <p
                                class="text-sm font-semibold text-neutral-800 dark:text-neutral-200"
                            >
                                {{ check.ip_address }}
                            </p>
                        </div>

                        <div class="flex flex-col gap-1 sm:col-span-2">
                            <p
                                class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-300"
                            >
                                OS
                            </p>
                            <p
                                class="text-sm font-semibold text-neutral-800 dark:text-neutral-200"
                            >
                                {{ check.os }}
                            </p>
                        </div>

                        <div class="flex flex-col gap-1 sm:col-span-2">
                            <p
                                class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-300"
                            >
                                Work Description
                            </p>
                            <p
                                class="text-sm font-semibold text-neutral-800 whitespace-pre-line"
                            >
                                {{ check.work_description }}
                            </p>
                        </div>
                    </div>

                    <ImagePreviewContent
                        :attachments="check.attachments"
                        @open="openAttachments"
                    />

                    <div class="flex flex-col gap-2 md:flex-row md:justify-end">
                        <AppButton
                            v-if="check.deleted_at"
                            @click="recover(check.id)"
                            type="button"
                            icon="mdi:trash-outline"
                            color="brand"
                        >
                            Recover
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
import BasicCard from '@/components/cards/BasicCard.vue'
import AppButton from '@/components/form/AppButton.vue'
import ImagePreviewContent from '@/components/data/ImagePreviewContent.vue'

import { Check } from '@/globalInterfaces'
import moment from 'moment'
import { usePreviewPhotoStore } from '@/stores/previewPhoto.store'
import { storeToRefs } from 'pinia'
import { router } from '@inertiajs/vue3'

const { check } = defineProps<{
    check: Check
}>()

const $previewPhotoStore = usePreviewPhotoStore()
const { photos } = storeToRefs($previewPhotoStore)

function openAttachments(): void {
    photos.value = check.attachments.map(function (item) {
        return {
            file_location: item.file_location,
            id: item.id,
            created_at: item.created_at,
        }
    })
}

function recover(check_id: number): void {
    router.put(
        route('dashboard.checks.update', check_id),
        {
            type: 'recover',
        },
        {
            preserveState: true,
            preserveScroll: true,
            only: ['checks'],
        },
    )
}
</script>
