<template>
    <DataTransition
        class="flex gap-2 items-center p-1 rounded-3xl mr-auto text-neutral-700 dark:text-neutral-300 text-sm flex-wrap"
    >
        <button
            v-for="item in switches"
            @click="$model = item.name"
            type="button"
            :key="item.name"
            :class="[
                $model == item.name
                    ? 'bg-brand-200 dark:bg-brand-800 text-brand-800 dark:text-brand-100'
                    : '',
                'rounded-xl px-2 flex items-center gap-1 border border-neutral-300 dark:border-neutral-700',
            ]"
        >
            <Icon
                v-if="item.name == $model"
                icon="ic:baseline-check-circle"
                class="size-4"
            ></Icon>
            <Icon v-else :icon="item.icon" class="size-4"></Icon>
            {{ item.name }}
        </button>
    </DataTransition>
</template>

<script setup lang="ts">
import { Icon } from '@iconify/vue'
import DataTransition from '@/components/transitions/DataTransition.vue'

import { Switch } from '@/globalInterfaces'

const { switches } = defineProps<{
    switches: Switch[]
}>()

const $model = defineModel<string>()
</script>
