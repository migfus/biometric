<template>
    <div class="flex flex-col gap-4">
        <SearchCard
            :index_data_id="[]"
            v-model:search="search_params.search"
            no_print
            @search="getColleges"
            :create="route('dashboard.colleges.create')"
        />

        <div class="flex flex-col gap-0">
            <CollegeCard
                v-for="college in colleges.data"
                :key="college.id"
                :college="college"
            />
        </div>

        <div
            v-if="colleges.total === 0"
            class="border border-dashed border-neutral-300 text-center p-8 rounded-3xl m-4 text-neutral-500 flex flex-col gap-4 items-center"
        >
            <p class="text-sm">No colleges found</p>
            <AppButton
                v-if="search_params.search !== ''"
                @click="resetSearch"
                icon="ic:baseline-autorenew"
            >
                Reset Search
            </AppButton>
        </div>

        <PaginationCard :data="colleges" @paginationChangePage="getColleges" />

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
import CollegeCard from './CollegeCard.vue'
import AppButton from '@/Components/form/AppButton.vue'
import { reactive, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import { Paginate, College } from '@/globalInterfaces'
import PaginationCard from '@/Components/cards/PaginationCard.vue'
import { usePromptModalStore } from '@/Stores/promptModal.store'
import BottomSheet from '@douxcode/vue-spring-bottom-sheet'
import '@douxcode/vue-spring-bottom-sheet/dist/style.css'
import { storeToRefs } from 'pinia'
import DataTransition from '@/Components/transitions/DataTransition.vue'

const props = defineProps<{
    colleges: Paginate<College>
}>()

const $promptModal = usePromptModalStore()
const { open_modal, menu_items } = storeToRefs($promptModal)

const search_params = reactive({
    search: '',
})

function getColleges(page = 1) {
    router.get(
        route('dashboard.colleges.index'),
        { page, search: search_params.search },
        { preserveState: true, only: ['colleges'] },
    )
}

function resetSearch() {
    search_params.search = ''
    getColleges(1)
}

watch(
    () => menu_items.value,
    (new_data) => {
        open_modal.value = new_data.length > 0
    },
)
</script>
