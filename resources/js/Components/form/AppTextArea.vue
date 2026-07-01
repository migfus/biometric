<template>
    <div class="col-span-full">
        <label
            v-if="!noLabel"
            :for="name"
            class="block text-sm font-medium leading-6 text-brand-700"
        >
            {{ name }}
        </label>
        <BasicTransition class="mt-1">
            <textarea

                v-model="$model"
                :id="name"
                :placeholder
                :name="name"
                :rows="lines ?? 5"
                :class="textareaClasses"
            />

        </BasicTransition>

        <label
            v-if="error"
            for="password"
            class="block text-sm font-medium text-red-600"
        >
            {{ error }}
        </label>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import BasicTransition from "@/Components/transitions/BasicTransition.vue"

const $model = defineModel<string>()

const props = defineProps<{
    name: string
    noLabel?: boolean
    lines?: string
    placeholder?: string
    error?: string
    ai_loading?: boolean
}>()

const textareaClasses = computed(() => [
    props.error ? 'ring-red-300' : props.ai_loading ? 'ring-transparent focus:ring-transparent ai-loading' : 'ring-gray-300',
    'p-4 bg-white block w-full rounded-3xl border-0 py-1.5 text-gray-900 ring-1 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-600 sm:text-sm sm:leading-6 transition-shadow duration-300'
])
</script>

