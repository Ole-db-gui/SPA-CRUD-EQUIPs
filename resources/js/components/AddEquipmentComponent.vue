<template>
    <div class="container">
        <h1>Добавление нового оборудования</h1>
        <form @submit.prevent="addEquipment">
            <div class="row">
                <div class="col-md-6">
                    <label for="equipmentType">Тип оборудования</label>
                    <select id="equipmentType" v-model="newEquipment.equipmentTypeId" required>
                        <option value="" disabled selected>Выберите тип оборудования</option>
                        <option v-for="type in types" :key="type.id" :value="type.id">{{ type.name }}</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label for="serialNumber">Серийный номер</label>
                    <input type="text" id="serialNumber" v-model="newEquipment.serialNumber" required pattern="^[0-9A-Za-z]{{ mask }}$" title="Формат серийного номера должен соответствовать маске {{ mask }}" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <label for="description">Описание</label>
                    <textarea id="description" v-model="newEquipment.description" rows="5"></textarea>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Добавить</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import axios from 'axios';
export default {
    name: "AddEquipmentComponent",
    data() {
        return {
            newEquipment: {
                equipmentTypeId: '',
                serialNumber: '',
                description: ''
            },
            types: []
        };
    },
    created() {
        this.fetchTypes();
    },
    methods: {
        addEquipment() {
            const { equipmentTypeId, serialNumber, description } = this.newEquipment;
            axios.post('http://localhost:8000/api/equipments', {
                equipment_type_id: equipmentTypeId,
                serial_number: serialNumber,
                description: description
            }).then(response => {
                alert("Новое оборудование успешно добавлено!");
                this.newEquipment = {};
                this.$router.push({ name: 'listEquipment' });
            }).catch(error => {
                alert("Произошла ошибка при добавлении оборудования.");
                console.log(error);
            });
        },
        fetchTypes() {
            axios.get('http://localhost:8000/api/equipment-types')
                .then(response => {
                    this.types = response.data;
                })
                .catch(error => console.log(error));
        }
    }
};
</script>

<style scoped>
.container {
    max-width: 1200px;
    margin: 0 auto;
}
</style>
