<template>
    <div class="flex flex-col">
        <Menu
            as="div"
            class="bg-white dark:bg-neutral-800 flex flex-col gap-2 p-2 border-y border-neutral-200 dark:border-neutral-700 sm:rounded-3xl sm:border relative dark:text-neutral-300"
        >
            <MenuButton
                class="bg-white dark:bg-neutral-800 flex flex-col gap-1 p-2"
            >
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

            <DropdownMenu :dropdown_menu :target_id="user.id" />
        </Menu>
    </div>
</template>

<script setup lang="ts">
import { Menu, MenuButton } from '@headlessui/vue'
import { Icon } from '@iconify/vue'
import DropdownMenu from '@/components/dropdown/DropdownMenu.vue'

import { User, DropdownMenuItem } from '@/globalInterfaces'
import { usePromptModalStore } from '@/stores/promptModal.store'
import { router } from '@inertiajs/vue3'

const { user } = defineProps<{
    user: User
}>()

const $prompModalStore = usePromptModalStore()

const dropdown_menu: DropdownMenuItem[] = [
    {
        name: 'Edit',
        icon: 'mdi:pencil',
        color: '',
        callback: () => {
            router.get(route('dashboard.users.edit', user.id))
        },
    },
    {
        name: 'Remove',
        icon: 'mdi:trash-outline',
        color: 'danger',
        callback: () => {
            removeUser()
        },
    },
]

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
