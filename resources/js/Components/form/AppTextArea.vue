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
import BasicTransition from '@/Components/transitions/BasicTransition.vue'

import { computed } from 'vue'

const $model = defineModel<string>()

const props = defineProps<{
    name: string
    noLabel?: boolean
    lines?: string
    placeholder?: string
    error?: string
    ai_loading?: boolean
}>()

const textareaClasses = computed<string[]>(() => [
    props.error
        ? 'ring-red-300'
        : props.ai_loading
          ? 'ring-transparent focus:ring-transparent glowing-border'
          : 'ring-gray-300',
    ' p-4 bg-white block w-full rounded-3xl border-0 py-1.5 text-gray-900 ring-1 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-600 sm:text-sm sm:leading-6 transition-shadow duration-300',
])
</script>

<style>
@property --glow-deg {
    syntax: '<angle>';
    inherits: true;
    initial-value: -90deg;
}

@property --glow-strength {
    syntax: '<number>';
    inherits: true;
    initial-value: 5;
}

/* the colors don't need to be registed */
@property --clr-1 {
    syntax: '<color>';
    inherits: true;
    initial-value: red;
}

@property --clr-2 {
    syntax: '<color>';
    inherits: true;
    initial-value: yellow;
}

@property --clr-3 {
    syntax: '<color>';
    inherits: true;
    initial-value: green;
}

@property --clr-4 {
    syntax: '<color>';
    inherits: true;
    initial-value: blue;
}

@property --clr-5 {
    syntax: '<color>';
    inherits: true;
    initial-value: purple;
}

.glowing-border {
    --gradient-glow:
        var(--clr-1), var(--clr-2), var(--clr-3), var(--clr-4), var(--clr-5),
        var(--clr-1);

    border: var(--border-width, 1px) solid transparent;
    background:
        linear-gradient(var(--surface, canvas) 0 0) padding-box,
        conic-gradient(from var(--glow-deg), var(--gradient-glow)) border-box;

    position: relative;
    isolation: isolate;

    animation: glow 10s infinite linear;
    &::after {
        content: '';
        position: absolute;
        border-radius: inherit;
        z-index: -1;
        inset: calc(var(--border-width, 1px) * -1 * var(--glow-strength));
        border: calc(var(--border-width, 1px) * 2 * var(--glow-strength)) solid
            transparent;
        background:
            linear-gradient(var(--surface, canvas) 0 0) padding-box,
            conic-gradient(from var(--glow-deg), var(--gradient-glow))
                border-box;
        filter: blur(var(--glow-size, 1rem));

        animation: pulse 15s infinite ease-in-out;
    }
    &:not(.right) {
        &,
        &::after {
        }
    }

    &.right {
        margin-inline-start: auto;
        &,
        &::after {
        }
    }
}

@keyframes glow {
    100% {
        --glow-deg: 270deg;
    }
}
@keyframes pulse {
    0% {
        opacity: 0.2;
    }
    .5% {
        opacity: 0.3;
    }
    1% {
        opacity: 0.2;
    }
    2.5% {
        opacity: 0.4;
    }
    2.75% {
        opacity: 0.2;
    }
    3% {
        opacity: 0.3;
    }
    5% {
        opacity: 0.2;
    }
    7.5% {
        opacity: 0.3;
    }
    8% {
        opacity: 0.1;
    }
    8.5% {
        opacity: 0.3;
    }
    9% {
        opacity: 0.2;
    }
    9.5% {
        opacity: 0.3;
    }
    10% {
        opacity: 0.2;
    }
    10.5% {
        opacity: 0.3;
    }
    15% {
        opacity: 0.1;
    }
    20% {
        opacity: 0.2;
    }
    20.5% {
        opacity: 1;
    }
    to {
        opacity: 1;
    }
}
</style>
