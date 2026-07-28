<template>
    <BasicTransition>
        <div>
            <label
                v-if="!$props.noLabel"
                class="block text-sm font-medium leading-6 text-brand-700 dark:text-neutral-400"
                >{{ $props.name }}</label
            >
            <input
                v-model="$model"
                :id="name"
                :name
                :type="type ?? 'text'"
                :placeholder="placeholder ?? ''"
                :class="[
                    inputSize,
                    injectCSS,
                    error && 'border-red-500',
                    'h-10 px-4 bg-white dark:bg-neutral-900 dark:text-neutral-300 w-full rounded-3xl border border-gray-300 dark:border-neutral-700 placeholder-gray-400 dark:placeholder-neutral-500  focus:border-brand-500 focus:outline-none focus:ring-brand-500',
                ]"
                autocomplete="off"
            />
            <label
                v-if="$props.error"
                for="password"
                class="block text-sm font-medium text-red-600"
            >
                {{ $props.error }}
            </label>
        </div>
    </BasicTransition>
</template>

<script setup lang="ts">
import BasicTransition from '@/components/transitions/BasicTransition.vue'

import { computed } from 'vue'

type TProps = {
    error?: string | undefined
    name: string
    type?: 'text' | 'email' | 'password'
    placeholder?: string
    size?: 'sm' | 'xs'
    noLabel?: true | false
    injectCSS?: string
}

const $props = defineProps<TProps>()
const $model = defineModel<string>()

const inputSize = computed<string>(() => {
    switch ($props.size) {
        case 'sm':
            return 'text-sm h-[34px]'
        case 'xs':
            return 'text-xs h-[30px]'
        default:
            return ''
    }
})
</script>
