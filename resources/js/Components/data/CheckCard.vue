<template>
    <div class="flex flex-col">
        <Menu
            as="div"
            class="bg-white dark:bg-neutral-800 flex flex-col gap-2 p-2 border-y border-neutral-200 dark:border-neutral-700 sm:rounded-3xl sm:border relative"
        >
            <div
                :class="[
                    'bg-white dark:bg-neutral-800 flex flex-col gap-2 p-2',
                    check.deleted_at ? 'opacity-80' : '',
                ]"
            >
                <div class="flex flex-col gap-2 items-start">
                    <MenuButton class="flex flex-col w-full items-start gap-2">
                        <div class="flex justify-between items-center w-full">
                            <div class="flex items-center gap-1">
                                <img
                                    :src="`https://ui-avatars.com/api/?name=${check.employee?.full_name.replace(' ', '+')}`"
                                    class="size-4 ring ring-white rounded-full"
                                />
                                <p
                                    :class="[
                                        check.deleted_at
                                            ? 'line-through text-neutral-400'
                                            : '',
                                        'text-sm font-semibold truncate text-neutral-700 dark:text-neutral-300',
                                    ]"
                                >
                                    {{ check.employee?.full_name }}
                                </p>
                            </div>

                            <div class="flex gap-2 items-center">
                                <div class="flex items-center gap-1">
                                    <p
                                        v-if="check.deleted_at"
                                        class="bg-red-50 text-red-700 text-sm px-2 rounded-full flex items-center gap-1 font-semibold"
                                    >
                                        <Icon
                                            icon="mdi:trash-outline"
                                            class="size-3"
                                        />
                                        Deleted
                                    </p>
                                    <p
                                        v-else-if="check.verified_user"
                                        class="bg-green-50 dark:bg-green-900 text-green-700 dark:text-green-300 text-sm px-2 rounded-full flex items-center font-semibold"
                                    >
                                        <img
                                            :src="check.verified_user?.avatar"
                                            class="size-3 rounded-full mr-1"
                                        />
                                        Verified
                                    </p>
                                    <p
                                        v-else
                                        class="bg-red-50 dark:bg-red-950 text-red-700 dark:text-red-300 text-sm px-2 rounded-full flex items-center font-semibold"
                                    >
                                        Unverified
                                    </p>
                                </div>

                                <Icon
                                    icon="nrk:more"
                                    class="flex-none dark:text-neutral-300"
                                />
                            </div>
                        </div>
                        <div
                            class="flex gap-2 items-center justify-between w-full"
                        >
                            <p
                                class="text-xs text-neutral-500 dark:text-neutral-400 truncate"
                            >
                                {{
                                    moment(check.created_at).format(
                                        'MMM DD, YYYY',
                                    )
                                }}

                                {{
                                    check.employee.office &&
                                    ` - ${check.employee.office.name}`
                                }}
                                {{
                                    check.employee.college
                                        ? `, ${check.employee.college.name}`
                                        : ''
                                }}
                            </p>

                            <p
                                :class="[
                                    check.check_in
                                        ? 'bg-green-100 dark:bg-green-950 text-green-700 dark:text-green-300 text-sm px-2 rounded-full font-semibold'
                                        : 'bg-yellow-100 dark:bg-yellow-950 text-yellow-700 dark:text-yellow-200',
                                    'text-xs px-2 rounded-full font-semibold flex-none flex items-center gap-1',
                                ]"
                            >
                                {{ check.check_in ? 'In - ' : 'Out - ' }}
                                {{ moment(check.created_at).format('hh:mm A') }}
                            </p>
                        </div>

                        <div
                            v-if="!no_address"
                            class="flex justify-between gap-2 items-center w-full"
                        >
                            <div
                                v-if="check.ip_location"
                                class="flex items-center gap-1 text-neutral-500"
                            >
                                <Icon icon="tabler:map-pin" class="size-3" />
                                <p class="text-xs">
                                    {{ check.ip_location }}
                                </p>
                            </div>
                            <div v-else-if="resolved_ip_location">
                                <p class="text-xs">
                                    {{ resolved_ip_location }}
                                </p>
                            </div>
                            <div v-else-if="check.ip_address">
                                <p class="text-xs">
                                    {{ check.ip_address }}
                                </p>
                            </div>

                            <div
                                class="flex items-center gap-1 text-neutral-500 text-xs"
                            >
                                {{ check.os }}
                            </div>
                        </div>
                    </MenuButton>

                    <p class="text-sm dark:text-neutral-300">
                        {{ check.work_description }}
                    </p>

                    <div v-if="minified" class="flex gap-2 items-center w-full">
                        <img
                            v-for="item in check.attachments"
                            class="size-4 rounded"
                            :src="item.preview_location"
                        />
                    </div>
                    <div v-else class="flex gap-2 items-center w-full">
                        <ImagePreviewContent :attachments="check.attachments" />
                    </div>
                </div>
            </div>

            <DropdownMenu
                :dropdown_menu="dropdown_menu"
                :target_id="check.id"
                :remove_names="check.verified_user ? ['Verify'] : ['Unverify']"
            />
        </Menu>
    </div>
</template>

<script setup lang="ts">
import ImagePreviewContent from '@/components/data/ImagePreviewContent.vue'
import { Menu, MenuButton, MenuItems } from '@headlessui/vue'
import { Icon } from '@iconify/vue'

import { Check, DropdownMenuItem } from '@/globalInterfaces'
import moment from 'moment'
import axios from 'axios'
import { onMounted, ref } from 'vue'
import DropdownMenu from '../dropdown/DropdownMenu.vue'

const { check } = defineProps<{
    check: Check
    no_address?: boolean
    minified?: boolean
    dropdown_menu: DropdownMenuItem[]
}>()
const $emit = defineEmits(['remove'])
const resolved_ip_location = ref<string | null>(check.ip_location ?? null)

async function syncIpLocation(): Promise<void> {
    if (check.ip_location || !check.ip_address) {
        return
    }

    const normalized_ip = check.ip_address.trim()
    let ip_location = ''

    if (normalized_ip === '127.0.0.1' || normalized_ip === '::1') {
        ip_location = 'local'
    } else {
        const response = await axios.get(`https://ipwho.is/${normalized_ip}`, {
            params: {
                fields: 'success,country,region,city',
            },
        })

        if (!response.data?.success) {
            return
        }

        ip_location = [
            response.data.city,
            response.data.region,
            response.data.country,
        ]
            .filter(Boolean)
            .join(', ')
    }

    if (!ip_location) {
        return
    }

    await axios.patch(`/records/${check.id}`, {
        ip_location,
    })

    resolved_ip_location.value = ip_location
}

onMounted(() => {
    syncIpLocation()
})
</script>
