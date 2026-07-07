<template>
    <div class="flex flex-col gap-4">
        <SearchCard
            :index_data_id="[]"
            v-model:search="search_params.search"
            no_print
            @search="getUsers"
            :create="route('dashboard.users.create')"
        />

        <div class="flex flex-col gap-2">
            <UserCard v-for="item in users.data" :user="item" />
        </div>

        <div
            v-if="users.total == 0"
            class="border border-dashed border-neutral-300 text-center p-8 rounded-3xl m-4 text-neutral-500 flex flex-col gap-4 items-center"
        >
            <p class="text-sm">Empty Query</p>
            <AppButton
                v-if="search_params.search != ''"
                @click="Object.assign(search_params, initParams())"
                icon="ic:baseline-autorenew"
            >
                Reset Search
            </AppButton>
        </div>

        <!-- SECTION: BOTTOM SHEET -->
        <BottomSheet
            v-model="open_modal"
            :transitionDuration="0.3"
            @closed="$promptModalStore.menu_items = []"
        >
            <div class="p-4 flex flex-col max-h-[80vh] overflow-y-auto gap-2">
                <div
                    v-for="item in $promptModalStore.menu_items"
                    :key="item.name"
                >
                    <AppButton
                        @click="
                            () => {
                                open_modal = false
                                item.callback()
                            }
                        "
                        type="button"
                        class="w-full justify-start"
                        :icon="item.icon"
                        data-vsbs-no-drag
                        :color="item.color"
                    >
                        {{ item.name }}
                    </AppButton>
                </div>
            </div>
        </BottomSheet>
    </div>
</template>

<script setup lang="ts">
import SearchCard from '@/Components/cards/SearchCard.vue'
import BottomSheet from '@douxcode/vue-spring-bottom-sheet'
import '@douxcode/vue-spring-bottom-sheet/dist/style.css'

import { reactive, ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import UserCard from './UserCard.vue'
import { Paginate, User } from '@/globalInterfaces.js'
import AppButton from '@/Components/form/AppButton.vue'
import { usePromptModalStore } from '@/Stores/promptModal.store.js'

defineProps<{
    users: Paginate<User>
}>()

const $promptModalStore = usePromptModalStore()
const open_modal = ref(false)

const search_params = reactive<{
    search: string
}>(initParams())

function initParams() {
    return {
        search: '',
    }
}

function getUsers(page = 1) {
    router.get(
        route('dashboard.users.index'),
        { page, search: search_params.search },
        { preserveState: true, only: ['users'] },
    )
}

watch(
    () => $promptModalStore.menu_items,
    (new_data) => {
        open_modal.value = new_data.length > 0
    },
)
</script>
