<template>
    <!-- ✅ -->
    <BasicTransition>
        <div
            :class="[
                size === 'lg' && 'p-6',
                'bg-white p-4 shadow rounded-2xl group transition-all',
            ]"
        >
            <!-- NOTE: BASIC CARD HEADER -->
            <div>
                <div class="flex justify-between">
                    <h3
                        class="text-base font-semibold leading-7 text-gray-900 truncate"
                    >
                        <Icon
                            v-if="icon"
                            :icon
                            class="text-sm text-brand-700 h-4 w-4 inline mr-1 mb-0.75 align-middle"
                        />
                        <img
                            v-else-if="iconImg"
                            :src="iconImg"
                            class="inline mr-2 w-6 h-6 rounded shadow"
                        />
                        <div
                            v-else
                            class="inline-block h-4 w-4 pt-0.5 text-brand-700 mr-2"
                            v-html="iconHtml"
                        />
                        <span>{{ title }} </span>
                    </h3>
                    <div
                        class="pt-1.5 px-1 cursor-pointer hover:bg-white rounded-2xl group/expand"
                        @click="expand = !expand"
                    >
                        <Icon
                            v-if="expand"
                            icon="ic:outline-minus"
                            class="h-4 w-4 text-brand-800"
                        />
                        <div v-else class="flex">
                            <div
                                v-if="count"
                                class="bg-white group-hover/expand:bg-gray-100 rounded-full mr-2 text-brand-500 -mt-1 px-2 shadow"
                            >
                                {{ count }}
                            </div>
                            <Icon
                                icon="ic:outline-crop-square"
                                class="h-4 w-4 text-brand-800"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <!-- NOTE: BASIC CARD CONTENTS -->
            <div v-if="expand" class="mt-2 transition-all">
                <div
                    v-if="enableSearch"
                    class="flex flex-col sm:flex-row justify-end mb-4"
                >
                    <AppInput
                        v-model="$model"
                        name="Search"
                        size="sm"
                        placeholder="Search"
                        noLabel
                        class="sm:w-1/2"
                    />
                </div>
                <slot></slot>
            </div>
        </div>
    </BasicTransition>
</template>

<script setup lang="ts">
import BasicTransition from '@/Components/transitions/BasicTransition.vue'
import { Icon } from '@iconify/vue'
import AppInput from '../form/AppInput.vue'
import { usePage } from '@inertiajs/vue3'
import { ref } from 'vue'

defineProps<{
    icon?: string
    iconHtml?: string
    iconImg?: string
    title: string
    size?: 'lg'
    enableSearch?: boolean
    count?: number
}>()

const expand = ref(true)
const $model = defineModel<string>()
const page = usePage()
</script>

<style scoped>
.loading-card {
    position: relative;
}

@property --angle {
    syntax: '<angle>';
    initial-value: 0deg;
    inherits: false;
}

.loading-card::after,
.card::before {
    content: '';
    position: absolute;
    height: 100%;
    width: 100%;
    top: 50%;
    left: 50%;
    translate: -50% -50%;
    z-index: -1;
    padding: 4px;
    border-radius: 15px;
    background-image: conic-gradient(
        from var(--angle),
        transparent 70%,
        #00ff99
    );
    animation: 3s spin linear infinite;
}
.loading-card::before {
    filter: blur(1.5rem);
    opacity: 0.5;
}

@keyframes spin {
    from {
        --angle: 0deg;
    }
    to {
        --angle: 360deg;
    }
}
</style>
