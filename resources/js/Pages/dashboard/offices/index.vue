<template>
    <div class="flex flex-col gap-4">
        <SearchCard
            :index_data_id="[]"
            v-model:search="search_params.search"
            no_print
            @search="getOffices"
            :create="route('dashboard.offices.create')"
        />

        <div class="flex flex-col">
            <OfficeCard
                v-for="office in offices.data"
                :key="office.id"
                :office="office"
            />
        </div>

        <div
            v-if="offices.total === 0"
            class="border border-dashed border-neutral-300 text-center p-8 rounded-3xl m-4 text-neutral-500 flex flex-col gap-4 items-center"
        >
            <p class="text-sm">No offices found</p>
            <AppButton
                v-if="search_params.search !== ''"
                @click="resetSearch"
                icon="ic:baseline-autorenew"
            >
                Reset Search
            </AppButton>
        </div>

        <PaginationCard :data="offices" @paginationChangePage="getOffices" />

        <!-- SECTION: BOTTOM SHEET -->
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
            </DataTransition>
        </BottomSheet>
    </div>
</template>

<script setup lang="ts">
import SearchCard from '@/Components/cards/SearchCard.vue'
import OfficeCard from './OfficeCard.vue'
import AppButton from '@/Components/form/AppButton.vue'
import { reactive, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import { Paginate, Office } from '@/globalInterfaces'
import PaginationCard from '@/Components/cards/PaginationCard.vue'
import BottomSheet from '@douxcode/vue-spring-bottom-sheet'
import '@douxcode/vue-spring-bottom-sheet/dist/style.css'
import { usePromptModalStore } from '@/Stores/promptModal.store'
import { storeToRefs } from 'pinia'
import DataTransition from '@/Components/transitions/DataTransition.vue'

const props = defineProps<{
    offices: Paginate<Office>
}>()

const $prompModalStore = usePromptModalStore()
const { open_modal, menu_items } = storeToRefs($prompModalStore)

const search_params = reactive({
    search: '',
})

function getOffices(page = 1) {
    router.get(
        route('dashboard.offices.index'),
        { page, search: search_params.search },
        { preserveState: true, only: ['offices'] },
    )
}

function resetSearch() {
    search_params.search = ''
    getOffices(1)
}

watch(
    () => menu_items.value,
    (new_data) => {
        open_modal.value = new_data.length > 0
    },
)
</script>
