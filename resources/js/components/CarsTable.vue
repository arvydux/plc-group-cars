<template>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
            <tr class="text-left">
                <th class="py-2 pr-4">Make</th>
                <th class="py-2 pr-4">Model</th>
                <th class="py-2 pr-4">Year</th>
                <th class="py-2 pr-4">Price</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="car in cars" :key="car.id">
                <td class="py-2.5 pr-4">{{ car.make }}</td>
                <td class="py-2.5 pr-4">{{ car.model }}</td>
                <td class="py-2.5 pr-4">{{ car.year }}</td>
                <td class="py-2.5 pr-4">{{ car.price }}</td>
                <td class="py-2.5">
                    <button @click="emit('edit', car)" class="text-blue-500">Edit</button>
                </td>
            </tr>
            </tbody>
        </table>
        <p v-if="!cars.length">No cars yet</p>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const emit = defineEmits(['edit']);
const cars = ref([]);

async function fetchCars() {
    try {
        const response = await axios.get('/api/cars');
        cars.value = response.data.data;
    } catch (error) {
        console.error('Failed to fetch cars:', error);
    }
}

defineExpose({ fetchCars });
onMounted(fetchCars);
</script>
