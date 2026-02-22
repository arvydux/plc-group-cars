<template>
    <div class="max-w-3xl mx-auto p-4">
            <h1>Cars</h1>
            <button v-if="!showForm && !editingCar" @click="showForm = true"
                    class="bg-blue-500 text-white text-sm px-3 py-1.5 rounded">
                Add Car
            </button>
        <AddCarForm v-if="showForm" @car-added="onCarAdded" @cancel="showForm = false" />
        <EditCarForm v-if="editingCar" :car="editingCar" @car-updated="onCarUpdated" @cancel="editingCar = null" />
        <CarsTable ref="carsTable" @edit="onEdit" />
    </div>
</template>

<script setup>
import { ref } from 'vue';
import AddCarForm from './components/AddCarForm.vue';
import EditCarForm from './components/EditCarForm.vue';
import CarsTable from './components/CarsTable.vue';

const carsTable = ref(null);
const showForm = ref(false);
const editingCar = ref(null);

function onCarAdded() {
    showForm.value = false;
    carsTable.value?.fetchCars();
}

function onEdit(car) {
    editingCar.value = car;
    showForm.value = false;
}

function onCarUpdated() {
    editingCar.value = null;
    carsTable.value?.fetchCars();
}
</script>
