<template>
    <div class="flex flex-col gap-4 mb-9">
        <h3 class="text-neutral-600 font-semibold">Records</h3>

        <DataTransition
            v-if="checks.data.length > 0"
            class="flex flex-col gap-2"
        >
            <div
                v-for="item in checks.data"
                :key="item.id"
                class="bg-white rounded-3xl p-4 flex flex-col gap-4"
            >
                <div class="gap-2 justify-between items-center flex">
                    <div
                        v-if="item.check_in"
                        class="bg-emerald-600 px-2 py-1 rounded-3xl text-emerald-50 text-sm flex gap-2 items-center"
                    >
                        <Icon icon="material-symbols:login" />
                        Check In
                    </div>
                    <div
                        v-else
                        class="bg-yellow-600 px-2 py-1 rounded-3xl text-emerald-50 text-sm flex gap-2 items-center"
                    >
                        <Icon icon="material-symbols:login" />
                        Check Out
                    </div>

                    <Menu as="div" class="relative mr-3 mt-1">
                        <MenuButton
                            class="flex gap-2 bg-neutral-50 rounded-xl px-2"
                        >
                            <p class="text-xs text-neutral-500">
                                {{
                                    moment(item.created_at).format(
                                        'MMM DD, Y hh:mm A',
                                    )
                                }}
                            </p>
                            <Icon icon="nrk:more" class="-mr-2" />
                        </MenuButton>

                        <BasicTransition>
                            <MenuItems
                                class="py-2 absolute right-0 z-10 mt-2 w-40 origin-top-right rounded-xl bg-white shadow-lg ring-1 ring-neutral-200 ring-opacity-5 focus:outline-hidden"
                            >
                                <MenuItem
                                    v-slot="{ active, close }"
                                    class="flex items-center rounded-xl cursor-pointer"
                                    @click="
                                        $prompModalStore.menu_items = [
                                            {
                                                name: 'Yes, Remove',
                                                icon: 'mdi:trash-outline',
                                                color: 'danger',
                                                callback: () => {
                                                    removeCheck(item.id)
                                                },
                                            },
                                            {
                                                name: 'Cancel',
                                                icon: 'material-symbols:close',
                                                color: '',
                                                callback: () => {},
                                            },
                                        ]
                                    "
                                >
                                    <div
                                        :class="[
                                            active ? 'bg-neutral-50' : '',
                                            'px-4 py-2 text-sm text-brand-200 flex hover:bg-neutral-200 dark:hover:bg-dark-003 gap-2 items-center',
                                        ]"
                                    >
                                        <Icon icon="mdi:trash-outline" />
                                        <p>Remove</p>
                                    </div>
                                </MenuItem>
                            </MenuItems>
                        </BasicTransition>
                    </Menu>
                </div>

                <div class="flex gap-2 items-center overflow-x-auto">
                    <ImagePreviewContent :attachments="item.attachments" />
                </div>

                <p class="text-neutral-500 text-base whitespace-normal">
                    {{ item.work_description }}
                </p>
            </div>
        </DataTransition>
        <div
            v-if="checks.data.length === 0"
            class="text-sm text-neutral-500 text-center border border-dashed rounded-3xl p-8 flex justify-center items-center flex-col gap-4"
        >
            No records yet
            <AppButton @click="newHistory('form')" color="brand"
                >Start Now</AppButton
            >
        </div>
    </div>
</template>

<script setup lang="ts">
import DataTransition from '@/Components/transitions/DataTransition.vue'
import ImagePreviewContent from './ImagePreviewContent.vue'
import { Icon } from '@iconify/vue'
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import BasicTransition from '@/Components/transitions/BasicTransition.vue'
import AppButton from '@/Components/form/AppButton.vue'

import moment from 'moment'
import { Check, Pagination } from '@/globalInterfaces'
import { usePromptModalStore } from '@/Stores/promptModal.store'
import { router } from '@inertiajs/vue3'
import { useHistoryNavigation } from '@/Stores/historyNavigation.store.js'

const { checks } = defineProps<{
    checks: Pagination<Check>
}>()

const $prompModalStore = usePromptModalStore()
const $historyNavigationStore = useHistoryNavigation()
const { newHistory } = $historyNavigationStore

function removeCheck(id: number) {
    router.delete(`/checks/${id}`, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            checks.data = checks.data.filter((item) => item.id !== id)
        },
    })
}
</script>
