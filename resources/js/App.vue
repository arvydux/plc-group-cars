<template>
    <div class="max-w-3xl mx-auto p-4">
        <h1>Cars</h1>
        <button v-if="!showForm && !editingCar" @click="showForm = true"
                class="bg-blue-500 text-white text-sm px-3 py-1.5 rounded">
            Add Car
        </button>

        <p v-if="message" class="text-green-600 text-sm my-2">{{ message }}</p>

        <AddCarForm v-if="showForm" @car-added="onCarAdded" @cancel="showForm = false" />
        <EditCarForm v-if="editingCar" :car="editingCar" @car-updated="onCarUpdated" @cancel="editingCar = null" />
        <CarsTable ref="carsTable" @edit="onEdit" @deleted="onDeleted" />
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
const message = ref('');

function flash(text) {
    message.value = text;
    setTimeout(() => message.value = '', 2500);
}

function onCarAdded() {
    showForm.value = false;
    carsTable.value?.fetchCars();
    flash('Car added.');
}

function onEdit(car) {
    editingCar.value = car;
    showForm.value = false;
}

function onCarUpdated() {
    editingCar.value = null;
    carsTable.value?.fetchCars();
    flash('Car updated.');
}

function onDeleted() {
    carsTable.value?.fetchCars();
    flash('Car deleted.');
}
</script>
