<template>
    <div class="flex flex-col gap-4">
        <SearchCard
            :index_data_id="[]"
            v-model:search="search_params.search"
            @search="getNotifications"
        />

        <div class="flex flex-col gap-2 lg:grid grid-cols-2">
            <div class="flex flex-col gap-2">
                <h2 class="text-neutral-500 text-sm">Active</h2>
                <DataTransition class="flex flex-col">
                    <NotificationCard
                        v-for="notification in active_notifications.data"
                        :key="notification.id"
                        :notification="notification"
                    />
                </DataTransition>

                <div
                    v-if="active_notifications.total === 0"
                    class="border border-dashed border-neutral-300 text-center p-8 rounded-3xl m-4 text-neutral-500 flex flex-col gap-4 items-center"
                >
                    <p class="text-sm">No notifications found</p>
                    <AppButton
                        v-if="search_params.search !== ''"
                        @click="resetSearch"
                        icon="ic:baseline-autorenew"
                    >
                        Reset Search
                    </AppButton>
                </div>

                <PaginationCard
                    :data="active_notifications"
                    @paginationChangePage="getNotifications"
                />
            </div>

            <div class="flex flex-col gap-2">
                <h2 class="text-neutral-500 text-sm">Marked Read</h2>
                <DataTransition class="flex flex-col">
                    <NotificationCard
                        v-for="notification in read_notifications.data"
                        :key="notification.id"
                        :notification="notification"
                        read
                    />
                </DataTransition>

                <div
                    v-if="read_notifications.total === 0"
                    class="border border-dashed border-neutral-300 text-center p-8 rounded-3xl m-4 text-neutral-500 flex flex-col gap-4 items-center"
                >
                    <p class="text-sm">No notifications found</p>
                    <AppButton
                        v-if="search_params.search !== ''"
                        @click="resetSearch"
                        icon="ic:baseline-autorenew"
                    >
                        Reset Search
                    </AppButton>
                </div>

                <PaginationCard
                    :data="read_notifications"
                    @paginationChangePage="getNotifications"
                />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import PaginationCard from '@/components/cards/PaginationCard.vue'
import SearchCard from '@/components/cards/SearchCard.vue'
import NotificationCard from '@/components/data/NotificationCard.vue'
import AppButton from '@/components/form/AppButton.vue'
import DataTransition from '@/components/transitions/DataTransition.vue'

import { Paginate, AppNotification } from '@/globalInterfaces'
import { router } from '@inertiajs/vue3'
import { reactive } from 'vue'

defineProps<{
    active_notifications: Paginate<AppNotification>
    read_notifications: Paginate<AppNotification>
}>()

const search_params = reactive<{ search: string }>({
    search: '',
})

function getNotifications(page = 1): void {
    router.get(
        route('dashboard.notifications.index'),
        { page, search: search_params.search },
        {
            preserveState: true,
            only: ['active_notifications', 'read_notifications'],
        },
    )
}

function resetSearch(): void {
    search_params.search = ''
    getNotifications(1)
}
</script>
