<template>
    <div class="flex flex-col">
        <Menu
            as="div"
            class="bg-white flex flex-col gap-2 p-2 border-y border-neutral-200 sm:rounded-3xl sm:border relative"
        >
            <MenuButton class="bg-white flex flex-col gap-1 p-2">
                <div class="flex gap-2 items-center justify-between">
                    <div class="flex gap-2">
                        <img :src="user.avatar" class="rounded-full size-10" />
                        <div class="flex flex-col gap-0 items-start">
                            <p class="text-sm font-semibold">{{ user.name }}</p>
                            <p class="text-sm">{{ user.email }}</p>
                        </div>
                    </div>
                    <Icon icon="nrk:more" />
                </div>
            </MenuButton>

            <BasicTransition>
                <MenuItems
                    class="py-2 absolute right-0 z-10 mr-4 mt-10 w-40 origin-top-right rounded-3xl bg-white shadow-lg ring-1 ring-neutral-200 ring-opacity-5 focus:outline-hidden"
                >
                    <MenuItem
                        v-slot="{ active, close }"
                        class="flex items-center rounded-xl cursor-pointer"
                    >
                        <Link
                            :href="
                                route('dashboard.users.edit', {
                                    user: user.id,
                                })
                            "
                            :class="[
                                active ? 'bg-neutral-50' : '',
                                'px-4 py-2 text-sm text-brand-200 flex hover:bg-neutral-200 dark:hover:bg-dark-003 gap-2 items-center',
                            ]"
                        >
                            <Icon icon="mdi:pencil" />
                            <p>Edit</p>
                        </Link>
                    </MenuItem>
                    <MenuItem
                        v-if="$page.props.auth?.id != user.id"
                        v-slot="{ active, close }"
                        class="flex items-center rounded-xl cursor-pointer"
                    >
                        <button
                            type="button"
                            @click="removeUser()"
                            class="w-full text-left hover:bg-neutral-100"
                        >
                            <div
                                :class="[
                                    'px-4 py-2 text-sm text-brand-200 flex gap-2 items-center',
                                ]"
                            >
                                <Icon icon="mdi:trash-outline" />
                                <p>Remove</p>
                            </div>
                        </button>
                    </MenuItem>
                </MenuItems>
            </BasicTransition>
        </Menu>
    </div>
</template>

<script setup lang="ts">
import { Icon } from '@iconify/vue'
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import BasicTransition from '@/components/transitions/BasicTransition.vue'

import { router, Link } from '@inertiajs/vue3'
import { User } from '@/globalInterfaces'
import { usePromptModalStore } from '@/stores/promptModal.store'

const { user } = defineProps<{
    user: User
}>()

const $prompModalStore = usePromptModalStore()

function removeUser(): void {
    $prompModalStore.menu_items = [
        {
            name: 'Yes, Remove',
            icon: 'mdi:trash-outline',
            color: 'danger',
            callback: () => {
                deleteUser()
            },
        },
        {
            name: 'Cancel',
            icon: 'mdi:trash-outline',
            color: '',
            callback: () => {
                $prompModalStore.menu_items = []
            },
        },
    ]
}

function deleteUser(): void {
    router.delete(route('dashboard.users.destroy', user.id), {
        preserveState: true,
    })
}
</script>
