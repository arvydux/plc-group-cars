<template>
    <div>
        <button v-if="!showForm" @click="showForm = true">Add Car</button>
        <AddCarForm v-if="showForm" @car-added="onCarAdded" @cancel="showForm = false" />
        <CarTable ref="carTable" />
    </div>
</template>

<script setup>
import { ref } from 'vue';
import AddCarForm from './components/AddCarForm.vue';
import CarTable from './components/CarsTable.vue';

const carTable = ref(null);
const showForm = ref(false);

function onCarAdded() {
    showForm.value = false;
    carTable.value?.fetchCars();
}
</script>
