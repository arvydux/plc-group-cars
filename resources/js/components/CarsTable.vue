@ -0,0 +1,40 @@
<template>
    <div>
        <h1>Cars</h1>

        <table>
            <thead>
            <tr>
                <th>Make</th>
                <th>Model</th>
                <th>Year</th>
                <th>Price</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="car in cars" :key="car.id">
                <td>{{ car.make }}</td>
                <td>{{ car.model }}</td>
                <td>{{ car.year }}</td>
                <td>{{ car.price }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const cars = ref([]);

onMounted(async () => {
    try {
        const response = await axios.get('/api/cars');
        cars.value = response.data.data;
    } catch (error) {
        console.error('Failed to fetch cars:', error);
    }
});
</script>
