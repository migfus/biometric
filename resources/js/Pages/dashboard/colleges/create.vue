<template>
    <div class="flex flex-col gap-4 p-4 sm:w-120 mx-auto">
        <BasicCard title="Create College or Department" icon="ic:baseline-plus">
            <form @submit.prevent="create()" class="flex flex-col gap-2">
                <AppInput
                    name="Name"
                    v-model="form.name"
                    :error="$page.props.errors.name"
                />

                <div class="flex flex-col gap-2 mt-4">
                    <AppButton color="brand" icon="ic:baseline-plus">
                        Create
                    </AppButton>
                    <AppButton
                        :href="route('dashboard.colleges.index')"
                        type="button"
                        icon="material-symbols:close"
                    >
                        Cancel
                    </AppButton>
                </div>
            </form>
        </BasicCard>
    </div>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'
import AppButton from '@/components/form/AppButton.vue'
import AppInput from '@/components/form/AppInput.vue'

import { useForm } from '@inertiajs/vue3'

interface Form {
    name: string
}

const form = useForm<Form>({
    name: '',
})

function create(): void {
    form.post(route('dashboard.colleges.store'))
}
</script>
