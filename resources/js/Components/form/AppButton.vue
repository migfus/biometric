<template>
    <BasicTransition>
        <component
            :is="componentTag"
            v-bind="componentProps"
            :class="[
                buttonColor,
                textAlignment,
                buttonSize,
                'flex items-center gap-2 rounded-2xl font-medium hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all',
            ]"
            @click="clicked = true"
            :preserveState
        >
            <Icon
                v-if="(loading && !noLoading) || forceLoading"
                icon="line-md:loading-twotone-loop"
                :class="[
                    $props.size === 'sm' && 'size-4 mt-px',
                    '-ml-1 h-5 w-5 animate-spin',
                    iconColor,
                ]"
                aria-hidden="true"
            />
            <Icon
                v-else-if="icon"
                :icon
                :class="[
                    $props.size === 'xs'
                        ? 'size-1'
                        : $props.size === 'sm'
                          ? 'size-4 mt-px'
                          : 'size-5',
                    '-ml-1 size-5',
                    iconColor,
                ]"
                aria-hidden="true"
            />
            <slot />
        </component>
    </BasicTransition>
</template>

<script setup lang="ts">
import BasicTransition from '@/Components/transitions/BasicTransition.vue'
import { Icon } from '@iconify/vue'

import { Link, router } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const $props = defineProps<{
    icon?: string
    color?: string
    type?: 'button' | 'submit' | 'reset'
    alignment?: 'l' | 'c' | 'r'
    size?: 'sm' | 'md' | 'xs'
    href?: string
    externalLinkOnly?: boolean
    noLoading?: boolean
    disabled?: boolean
    forceLoading?: boolean
    preserveState?: boolean
    noLabel?: boolean
    autoFocus?: boolean
}>()

const loading = ref<boolean>(false)
const clicked = ref<boolean>(false)

const componentTag = computed<keyof HTMLElementTagNameMap | typeof Link>(() => {
    if ($props.externalLinkOnly) return 'a'
    if ($props.href) return Link
    return 'button'
})

const componentProps = computed<Partial<Record<string, any>>>(() => {
    const base = {
        type: $props.type,
        disabled: loading.value || $props.disabled || $props.forceLoading,
        autofocus: $props.autoFocus,
    }
    if ($props.externalLinkOnly)
        return { ...base, href: $props.href, target: '_blank' }
    if ($props.href) return { ...base, href: $props.href }
    return base
})

const buttonColor = computed<string>(() => {
    if ($props.disabled) return 'bg-gray-200 text-gray-400 cursor-not-allowed'
    const colors: Record<string, string> = {
        'brand-dark':
            'bg-brand-50 hover:bg-brand-100 text-brand-700 focus:ring-brand-500 ',
        brand: 'bg-emerald-600 hover:bg-emerald-700 text-emerald-50 focus:ring-emerald-500',
        danger: 'bg-red-50 text-red-700 hover:bg-red-100 focus:ring-red-500 border border-red-200',
        transparent: 'bg-inherit shadow-none hover:shadow-none focus:ring-none',
    }
    return (
        colors[$props.color ?? ''] ??
        'bg-white hover:bg-gray-50 text-brand-700 hover:bg-gray-100 focus:ring-brand-500 border border-neutral-300'
    )
})

const iconColor = computed<string>(() => {
    if ($props.disabled) return 'text-gray-400'
    const colors: Record<string, string> = {
        danger: 'text-red-700',
        brand: 'text-brand-50',
    }
    return colors[$props.color ?? ''] ?? 'text-brand-700'
})

const textAlignment = computed<string>(() =>
    $props.alignment === 'l' ? 'justify-left' : 'justify-center',
)

const buttonSize = computed<string>(() =>
    $props.size === 'xs'
        ? 'text-xs font px-2 py-1'
        : $props.size === 'sm'
          ? 'text-xs font px-3 py-2'
          : 'text-sm px-4 py-2',
)

router.on('start', () => {
    if (clicked.value) loading.value = true
})
router.on('finish', () => {
    loading.value = false
    clicked.value = false
})
</script>
