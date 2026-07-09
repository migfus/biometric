<template>
    <div class="flex flex-col gap-4 lg:w-120 lg:mx-auto">
        <BasicCard title="Edit Office" icon="mdi:pencil">
            <form @submit.prevent="update()" class="flex flex-col gap-2">
                <AppInput
                    name="Name"
                    v-model="form.name"
                    :error="$page.props.errors.name"
                />

                <div
                    class="flex flex-col gap-2 mt-4 md:flex-row md:justify-end"
                >
                    <AppButton color="brand" icon="material-symbols:check">
                        Update
                    </AppButton>
                    <AppButton
                        :href="route('dashboard.offices.index')"
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
import BasicCard from '@/Components/cards/BasicCard.vue'
import AppButton from '@/Components/form/AppButton.vue'
import AppInput from '@/Components/form/AppInput.vue'
import { Office } from '@/globalInterfaces'
import { useForm } from '@inertiajs/vue3'

const { office } = defineProps<{
    office: Office
}>()

const form = useForm<{
    name: string
}>(initForm())

function initForm() {
    return {
        name: office.name,
    }
}

function update() {
    form.put(route('dashboard.offices.update', office.id))
}
</script>
