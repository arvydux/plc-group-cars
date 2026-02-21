<template>
    <form @submit.prevent="submit">
        <h2>Edit Car</h2>

        <div v-if="errors.general">{{ errors.general }}</div>

        <div>
            <label for="edit-make">Make</label>
            <input id="edit-make" v-model="form.make" type="text" maxlength="255" />
            <p v-if="errors.make">{{ errors.make }}</p>
        </div>

        <div>
            <label for="edit-model">Model</label>
            <input id="edit-model" v-model="form.model" type="text" maxlength="255" />
            <p v-if="errors.model">{{ errors.model }}</p>
        </div>

        <div>
            <label for="edit-year">Year</label>
            <input id="edit-year" v-model.number="form.year" type="number" min="1900" :max="maxYear" />
            <p v-if="errors.year">{{ errors.year }}</p>
        </div>

        <div>
            <label for="edit-price">Price</label>
            <input id="edit-price" v-model.number="form.price" type="number" min="0" step="0.01" />
            <p v-if="errors.price">{{ errors.price }}</p>
        </div>

        <button type="submit" :disabled="submitting">
            {{ submitting ? 'Saving...' : 'Save' }}
        </button>
        <button type="button" @click="emit('cancel')" :disabled="submitting">Cancel</button>
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
