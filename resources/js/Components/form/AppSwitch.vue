<template>
    <DataTransition class="flex gap-2 items-center bg-white p-1 rounded-3xl mr-auto text-neutral-700 border border-neutral-300 text-sm">
        <button
            v-for="item in switches"
            @click="$model = item.name"
            type="button"
            :key="item.name"
            :class="[$model == item.name ? 'bg-emerald-200 text-emerald-800' : '', 'rounded-xl px-2 flex items-center gap-1']"
        >
            <Icon v-if="item.name == $model" icon="ic:baseline-check-circle" class="size-4"></Icon>
            <Icon v-else :icon="item.icon" class="size-4"></Icon>
            {{  item.name }}
        </button>
    </DataTransition>
</template>

<script setup lang="ts">
import { Icon } from '@iconify/vue'
import DataTransition from '@/Components/transitions/DataTransition.vue'

import { Switch } from '@/globalInterfaces'

const {switches} = defineProps<{
    switches: Switch[]
}>()

const $model = defineModel<string>()
</script>

