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
                class="p-4 bg-white block w-full rounded-3xl border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-600 sm:text-sm sm:leading-6"
            />


        </BasicTransition>
    </div>
</template>

<script setup lang="ts">
import BasicTransition from "@/Components/transitions/BasicTransition.vue"

const $model = defineModel<string>()

defineProps<{
    name: string
    noLabel?: boolean
    lines?: string
    placeholder?: string
}>()
</script>
