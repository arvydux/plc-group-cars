<template>
    <form @submit.prevent="submit">
        <h2>Add Car</h2>

        <div v-if="errors.general">{{ errors.general }}</div>

        <div>
            <label for="make">Make</label>
            <input id="make" v-model="form.make" type="text" maxlength="255" />
            <p v-if="errors.make">{{ errors.make }}</p>
        </div>

        <div>
            <label for="model">Model</label>
            <input id="model" v-model="form.model" type="text" maxlength="255" />
            <p v-if="errors.model">{{ errors.model }}</p>
        </div>

        <div>
            <label for="year">Year</label>
            <input id="year" v-model.number="form.year" type="number" min="1900" :max="maxYear" />
            <p v-if="errors.year">{{ errors.year }}</p>
        </div>

        <div>
            <label for="price">Price</label>
            <input id="price" v-model.number="form.price" type="number" min="0" step="0.01" />
            <p v-if="errors.price">{{ errors.price }}</p>
        </div>

        <button type="submit" :disabled="submitting">
            {{ submitting ? 'Adding...' : 'Add Car' }}
        </button>
        <button type="button" @click="emit('cancel')" :disabled="submitting">Cancel</button>
    </form>
</template>

<script setup>
import { ref, reactive } from 'vue';
import axios from 'axios';

const emit = defineEmits(['car-added', 'cancel']);

const maxYear = new Date().getFullYear() + 1;

const form = reactive({
    make: '',
    model: '',
    year: null,
    price: null,
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
        const response = await axios.post('/api/cars', form);
        emit('car-added', response.data);
    } catch (error) {
        if (error.response?.status === 422) {
            const serverErrors = error.response.data.errors;
            for (const [field, messages] of Object.entries(serverErrors)) {
                errors[field] = messages[0];
            }
        } else {
            errors.general = 'Error with saving car.';
        }
    } finally {
        submitting.value = false;
    }
}
</script>
