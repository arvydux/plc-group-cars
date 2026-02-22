<template>
    <form @submit.prevent="submit" class="bg-white border rounded p-4 sm:p-5 mb-5">
        <h2 class="font-bold mb-3">Add Car</h2>

        <p v-if="errors.general" class="text-red-500 text-sm mb-3">{{ errors.general }}</p>

        <div class="grid sm:grid-cols-2 gap-3 mb-4">
            <div>
                <label for="make" class="block text-sm mb-1">Make</label>
                <input id="make" v-model="form.make" type="text" maxlength="255"
                       class="border rounded px-2.5 py-1.5 w-full text-sm" />
                <p v-if="errors.make" class="text-red-500 text-xs">{{ errors.make }}</p>
            </div>
            <div>
                <label for="model" class="block text-sm mb-1">Model</label>
                <input id="model" v-model="form.model" type="text" maxlength="255"
                       class="border rounded px-2.5 py-1.5 w-full text-sm" />
                <p v-if="errors.model" class="text-red-500 text-xs">{{ errors.model }}</p>
            </div>
            <div>
                <label for="year" class="block text-sm mb-1">Year</label>
                <input id="year" v-model.number="form.year" type="number" min="1900" :max="maxYear"
                       class="border rounded px-2.5 py-1.5 w-full text-sm" />
                <p v-if="errors.year" class="text-red-500 text-xs">{{ errors.year }}</p>
            </div>
            <div>
                <label for="price" class="block text-sm mb-1">Price</label>
                <input id="price" v-model.number="form.price" type="number" min="0" step="1"
                       class="border rounded px-2.5 py-1.5 w-full text-sm" />
                <p v-if="errors.price" class="text-red-500 text-xs">{{ errors.price }}</p>
            </div>
        </div>

        <div class="flex gap-2">
            <button type="submit" :disabled="submitting"
                    class="bg-blue-500 text-white text-sm px-3 py-1.5 rounded hover:bg-blue-600 disabled:opacity-50">
                {{ submitting ? 'Adding...' : 'Add Car' }}
            </button>
            <button type="button" @click="emit('cancel')"
                    class="text-sm px-3 py-1.5 rounded border hover:bg-gray-50">Cancel</button>
        </div>
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
