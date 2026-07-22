<template>
    <div class="flex flex-col gap-4">
        <SearchCard
            :index_data_id="[]"
            v-model:search="search_params.search"
            @print="print()"
            @search="getUsers"
            :create="route('dashboard.users.create')"
        />

        <SearchResultSection
            :total="users.total"
            :searched="search_params.search"
        />

        <div
            class="flex flex-col gap-0 lg:grid lg:grid-cols-2 lg:gap-1 2xl:grid-cols-3"
        >
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

        <PaginationCard :data="users" @paginationChangePage="getUsers" />
    </div>
</template>

<script setup lang="ts">
import PaginationCard from '@/components/cards/PaginationCard.vue'
import SearchCard from '@/components/cards/SearchCard.vue'
import AppButton from '@/components/form/AppButton.vue'
import UserCard from '@/components/data/UserCard.vue'
import SearchResultSection from '@/components/data/SearchResultSection.vue'

import { Paginate, User } from '@/globalInterfaces'
import { router } from '@inertiajs/vue3'
import { reactive } from 'vue'

defineProps<{
    users: Paginate<User>
}>()

const search_params = reactive<{
    search: string
}>(initParams())

function initParams(): { search: string } {
    return {
        search: '',
    }
}

function getUsers(page = 1): void {
    router.get(
        route('dashboard.users.index'),
        { page, search: search_params.search },
        { preserveState: true, only: ['users'] },
    )
}

function print(): void {
    const params = new URLSearchParams({
        search: search_params.search,
    })

    window.location.href = `${route('dashboard.users.print')}?${params.toString()}`
}
</script>
