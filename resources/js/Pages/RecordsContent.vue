<template>
    <div class="flex flex-col gap-4">
        <h3 class="text-neutral-600 font-semibold">Records</h3>

        <DataTransition v-if="checks.data.length > 0" class="flex flex-col gap-2">
            <div v-for="item in checks.data" :key="item.id" class="bg-white rounded-3xl p-4 flex flex-col gap-4">
                <div class="gap-2 justify-between items-center flex">
                    <div v-if="item.check_in" class="bg-emerald-600 px-2 py-1 rounded-3xl text-emerald-50 text-sm flex gap-2 items-center">
                        <Icon icon="material-symbols:login" />
                        Check In</div>
                    <div v-else class="bg-yellow-600 px-2 py-1 rounded-3xl text-emerald-50 text-sm flex gap-2 items-center">
                        <Icon icon="material-symbols:login" />
                        Check Out
                    </div>



                    <p class="text-xs text-neutral-500 ">{{ moment(item.created_at).format('MMM DD, Y hh:mm A') }}</p>
                </div>

                <div class="flex gap-2 items-center overflow-x-auto">
                    <div v-for="photo in item.attachments" class="shrink-0">
                        <img :src="photo.preview_location" class="h-12 w-auto rounded block"/>
                    </div>
                </div>

                <p class="text-neutral-500 text-base whitespace-normal">{{  item.work_description }}</p>
            </div>
        </DataTransition>
    </div>
</template>

<script setup lang="ts">
import DataTransition from '@/Components/transitions/DataTransition.vue'
import { Check, Pagination } from '@/globalInterfaces'

import { Icon } from '@iconify/vue'
import moment from 'moment'

defineProps<{
    checks: Pagination<Check>
}>()
</script>
