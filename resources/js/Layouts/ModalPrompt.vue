<template>
    <BottomSheet
        v-model="open_modal"
        :transitionDuration="0.3"
        @closed="menu_items = []"
    >
        <div
            ref="menu_actions_container"
            class="p-4 flex flex-col max-h-[80vh] overflow-y-auto gap-2 md:flex-row md:justify-end"
        >
            <div v-for="item in menu_items" :key="item.name">
                <AppButton
                    @click="
                        () => {
                            open_modal = false
                            item.callback()
                        }
                    "
                    type="button"
                    class="w-full justify-start"
                    :icon="item.icon"
                    data-vsbs-no-drag
                    :color="item.color"
                >
                    {{ item.name }}
                </AppButton>
            </div>
        </div>
    </BottomSheet>
</template>

<script setup lang="ts">
import BottomSheet from '@douxcode/vue-spring-bottom-sheet'
import '@douxcode/vue-spring-bottom-sheet/dist/style.css'
import AppButton from '@/Components/form/AppButton.vue'

import { usePromptModalStore } from '@/Stores/promptModal.store'
import { storeToRefs } from 'pinia'
import { nextTick, useTemplateRef, watch } from 'vue'

const $promptModalStore = usePromptModalStore()
const { open_modal, menu_items } = storeToRefs($promptModalStore)

const menu_actions_container = useTemplateRef<HTMLElement>(
    'menu_actions_container',
)

async function focusLastMenuAction(): Promise<void> {
    await nextTick()

    const container = menu_actions_container.value
    if (!container) {
        return
    }

    const action_buttons = container.querySelectorAll<HTMLElement>(
        'button:not([disabled]), a[href], [tabindex]:not([tabindex="-1"])',
    )

    if (action_buttons.length === 0) {
        return
    }

    const last_action = action_buttons[
        action_buttons.length - 1
    ] as HTMLButtonElement

    last_action.focus()
}

watch(
    function () {
        return menu_items.value
    },
    async function (new_data) {
        open_modal.value = new_data.length > 0

        if (new_data.length > 0) {
            // Allow bottom-sheet focus trap to initialize before setting the safe default action.
            window.setTimeout(function () {
                focusLastMenuAction()
            }, 80)
        }
    },
)
</script>

<style>
[data-vsbs-backdrop] {
    z-index: 60;
}

[data-vsbs-sheet] {
    z-index: 70;
}
</style>
