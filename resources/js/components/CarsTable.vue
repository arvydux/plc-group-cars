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
            <tr v-for="car in cars" :key="car.id" class="border-b hover:bg-gray-50">
                <td class="py-2.5 pr-4">{{ car.make }}</td>
                <td class="py-2.5 pr-4">{{ car.model }}</td>
                <td class="py-2.5 pr-4">{{ car.year }}</td>
                <td class="py-2.5 pr-4">{{ car.price }}</td>
                <td class="py-2.5 flex gap-3">
                    <button @click="emit('edit', car)" class="text-blue-500">Edit</button>
                    <button @click="deleteCar(car)" class="text-red-400">Delete</button>
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

const emit = defineEmits(['edit', 'deleted']);
const cars = ref([]);

async function fetchCars() {
    try {
        const response = await axios.get('/api/cars');
        cars.value = response.data.data;
    } catch (error) {
        console.error('Failed to fetch cars:', error);
    }
}

async function deleteCar(car) {
    if (!confirm(`Delete ${car.make} ${car.model}?`)) return;
    try {
        await axios.delete(`/api/cars/${car.id}`);
        emit('deleted');
    } catch (error) {
        console.error('Failed to delete:', error);
    }
}

defineExpose({ fetchCars });
onMounted(fetchCars);
</script>
