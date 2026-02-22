<template>
    <form @submit.prevent="submit">
        <h2 class="font-bold">Edit Car</h2>

        <p v-if="errors.general" class="text-red-500 text-sm">{{ errors.general }}</p>

        <div class="grid sm:grid-cols-2 gap-3">
            <div>
                <label for="edit-make" class="block text-sm">Make</label>
                <input id="edit-make" v-model="form.make" type="text" maxlength="255"
                       class="border rounded px-2.5 py-1.5 w-full text-sm" />
                <p v-if="errors.make" class="text-red-500 text-xs">{{ errors.make }}</p>
            </div>
            <div>
                <label for="edit-model" class="block text-sm">Model</label>
                <input id="edit-model" v-model="form.model" type="text" maxlength="255"
                       class="border rounded px-2.5 py-1.5 w-full text-sm" />
                <p v-if="errors.model" class="text-red-500 text-xs">{{ errors.model }}</p>
            </div>
            <div>
                <label for="edit-year" class="block text-sm">Year</label>
                <input id="edit-year" v-model.number="form.year" type="number" min="1900" :max="maxYear"
                       class="border rounded px-2.5 py-1.5 w-full text-sm" />
                <p v-if="errors.year" class="text-red-500 text-xs">{{ errors.year }}</p>
            </div>
            <div>
                <label for="edit-price" class="block text-sm">Price</label>
                <input id="edit-price" v-model.number="form.price" type="number" min="0" step="1"
                       class="border rounded px-2.5 py-1.5 w-full text-sm" />
                <p v-if="errors.price" class="text-red-500 text-xs">{{ errors.price }}</p>
            </div>
        </div>

        <div class="flex gap-2">
            <button type="submit" :disabled="submitting"
                    class="bg-blue-500 text-white text-sm px-3 py-1.5 rounded">
                {{ submitting ? 'Saving...' : 'Save' }}
            </button>
            <button type="button" @click="emit('cancel')"
                    class="text-sm px-3 py-1.5 rounded border">Cancel</button>
        </div>
    </form>
</template>

<script setup>
import { ref, reactive } from 'vue';
import axios from 'axios';

const props = defineProps({
    car: { type: Object, required: true },
});

const emit = defineEmits(['car-updated', 'cancel']);

const maxYear = new Date().getFullYear() + 1;

const form = reactive({
    make: props.car.make,
    model: props.car.model,
    year: props.car.year,
    price: props.car.price,
});

const errors = reactive({});
const submitting = ref(false);

function clearErrors() {
    Object.keys(errors).forEach(key => delete errors[key]);
}

async function submit() {
    clearErrors();
    submitting.value = true;

    try {
        await axios.put(`/api/cars/${props.car.id}`, form);
        emit('car-updated');
    } catch (error) {
        if (error.response?.status === 422) {
            const serverErrors = error.response.data.errors;
            for (const [field, messages] of Object.entries(serverErrors)) {
                errors[field] = messages[0];
            }
        } else {
            errors.general = 'Failed to update car.';
        }
    } finally {
        submitting.value = false;
    }
}
</script>
