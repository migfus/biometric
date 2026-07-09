<template>
    <div>
        <ImageModal v-if="photos.length > 0" :photos />
        <div v-else class="flex flex-col gap-4">
            <BasicCard title="Employee Information" icon="ic:outline-people">
                <div class="flex flex-col gap-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1">
                            <p
                                class="text-xs uppercase tracking-wide text-neutral-500"
                            >
                                Employee No.
                            </p>
                            <p class="text-sm font-semibold text-neutral-800">
                                {{ employee.id }}
                            </p>
                        </div>

                        <div class="flex flex-col gap-1">
                            <p
                                class="text-xs uppercase tracking-wide text-neutral-500"
                            >
                                Full Name
                            </p>
                            <p class="text-sm font-semibold text-neutral-800">
                                {{ employee.full_name }}
                            </p>
                        </div>

                        <div class="flex flex-col gap-1">
                            <p
                                class="text-xs uppercase tracking-wide text-neutral-500"
                            >
                                College or Department
                            </p>
                            <p class="text-sm font-semibold text-neutral-800">
                                {{
                                    employee.college?.name ??
                                    'No college or department'
                                }}
                            </p>
                        </div>

                        <div class="flex flex-col gap-1">
                            <p
                                class="text-xs uppercase tracking-wide text-neutral-500"
                            >
                                Office
                            </p>
                            <p class="text-sm font-semibold text-neutral-800">
                                {{ employee.office?.name ?? 'No office' }}
                            </p>
                        </div>

                        <div class="flex flex-col gap-1 sm:col-span-2">
                            <p
                                class="text-xs uppercase tracking-wide text-neutral-500"
                            >
                                Email
                            </p>
                            <p class="text-sm font-semibold text-neutral-800">
                                {{ employee.email ?? 'No email' }}
                            </p>
                        </div>
                    </div>

                    <AppButton
                        :href="route('dashboard.employees.index')"
                        type="button"
                        icon="material-symbols:arrow-back"
                        class="w-full"
                    >
                        Back
                    </AppButton>
                </div>
            </BasicCard>

            <div class="flex flex-col gap-2">
                <SearchCard
                    :index_data_id="[]"
                    v-model:search="query.search"
                    no_print
                />
            </div>

            <div v-if="employee.checks.length > 0" class="flex flex-col gap-3">
                <div
                    v-for="check in employee.checks"
                    :key="check.id"
                    class="border border-neutral-200 bg-white p-4 flex flex-col gap-2"
                >
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex flex-col gap-1">
                            <p
                                :class="[
                                    'rounded-full px-3 py-1 text-xs font-semibold',
                                    check.check_in
                                        ? 'bg-green-100 text-green-700'
                                        : 'bg-amber-100 text-amber-700',
                                ]"
                            >
                                {{ check.check_in ? 'In' : 'Out' }}
                            </p>
                        </div>

                        <p class="text-xs text-neutral-500">
                            {{
                                moment(check.created_at).format(
                                    'MMM D, YYYY - h:mm:ss a',
                                )
                            }}
                        </p>
                    </div>

                    <ImagePreviewContent
                        :attachments="check.attachments"
                        @open="
                            () => {
                                photos = check.attachments.map((item) => {
                                    return {
                                        file_location: `${item.file_location}`,
                                        id: item.id,
                                    }
                                })
                            }
                        "
                    />

                    <p class="text-sm text-neutral-700 whitespace-pre-line">
                        {{ check.work_description }}
                    </p>

                    <div class="flex flex-wrap gap-2 text-xs text-neutral-500">
                        <span class="rounded-full bg-neutral-100 px-3 py-1">
                            OS: {{ check.os }}
                        </span>
                        <span class="rounded-full bg-neutral-100 px-3 py-1">
                            IP: {{ check.ip_address }}
                        </span>
                    </div>
                </div>
            </div>

            <div
                v-else
                class="rounded-3xl border border-dashed border-neutral-300 p-6 text-center text-sm text-neutral-500"
            >
                No checks found for this employee.
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import BasicCard from '@/Components/cards/BasicCard.vue'
import SearchCard from '@/Components/cards/SearchCard.vue'
import AppButton from '@/Components/form/AppButton.vue'
import ImagePreviewContent from '../../ImagePreviewContent.vue'
import ImageModal from '../../ImageModal.vue'

import { Employee } from '@/globalInterfaces'
import moment from 'moment'
import { reactive } from 'vue'
import { usePreviewPhotoStore } from '@/Stores/previewPhoto.store'
import { storeToRefs } from 'pinia'

const { employee } = defineProps<{
    employee: Employee
}>()

const query = reactive({
    search: '',
})

const $previewPhotoStore = usePreviewPhotoStore()
const { photos } = storeToRefs($previewPhotoStore)
</script>
