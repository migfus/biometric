<template>
    <div class="flex flex-col gap-4">
        <SearchCard
            :index_data_id="[]"
            v-model:search="search_params.search"
            no_print
            @search="getChecks"
            :create="route('dashboard.checks.create')"
        />

        <div class="flex flex-col gap-0">
            <CheckCard
                v-for="check in checks.data"
                :key="check.id"
                :check="check"
            />
        </div>

        <div
            v-if="checks.total === 0"
            class="border border-dashed border-neutral-300 text-center p-8 rounded-3xl m-4 text-neutral-500 flex flex-col gap-4 items-center"
        >
            <p class="text-sm">No checks found</p>
            <AppButton
                v-if="search_params.search !== ''"
                @click="resetSearch"
                icon="ic:baseline-autorenew"
            >
                Reset Search
            </AppButton>
        </div>

        <PaginationCard
            :data="checks"
            @paginationChangePage="getChecks"
        />

        <BottomSheet
            v-model="open_modal"
            :transitionDuration="0.3"
            @closed="menu_items = []"
        >
            <DataTransition
                class="p-4 flex flex-col max-h-[80vh] overflow-y-auto gap-2"
            >
                <div v-for="item in menu_items" :key="item.name">
                    <AppButton
                        @click="selectMenuItem(item)"
                        type="button"
                        class="w-full justify-start"
                        :icon="item.icon"
                        data-vsbs-no-drag
                        :color="item.color"
                    >
                        {{ item.name }}
                    </AppButton>
                </div>
            </DataTransition>
        </BottomSheet>
    </div>
</template>

<script setup lang="ts">
import SearchCard from '@/Components/cards/SearchCard.vue'
import CheckCard from './CheckCard.vue'
import AppButton from '@/Components/form/AppButton.vue'
import { reactive, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import { Paginate, Check } from '@/globalInterfaces'
import PaginationCard from '@/Components/cards/PaginationCard.vue'
import BottomSheet from '@douxcode/vue-spring-bottom-sheet'
import '@douxcode/vue-spring-bottom-sheet/dist/style.css'
import { usePromptModalStore } from '@/Stores/promptModal.store'
import { storeToRefs } from 'pinia'
import DataTransition from '@/Components/transitions/DataTransition.vue'

defineProps<{
    checks: Paginate<Check>
}>()

const $promptModalStore = usePromptModalStore()
const { open_modal, menu_items } = storeToRefs($promptModalStore)

const search_params = reactive({
    search: '',
})

function getChecks(page = 1)
{
    router.get(
        route('dashboard.checks.index'),
        { page, search: search_params.search },
        { preserveState: true, only: ['checks'] },
    )
}

function resetSearch()
{
    search_params.search = ''
    getChecks(1)
}

function selectMenuItem(item: { callback: () => void })
{
    open_modal.value = false
    item.callback()
}

watch(
    function () {
        return menu_items.value
    },
    function (new_data) {
        open_modal.value = new_data.length > 0
    },
)
</script>
