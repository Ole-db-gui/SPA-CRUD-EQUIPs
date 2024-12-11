<template>
    <div class="container">
        <h1>Редактирование оборудования</h1>
        <form @submit.prevent="updateEquipment">
            <div class="row">
                <div class="col-md-6">
                    <label for="equipmentType">Тип оборудования</label>
                    <select id="equipmentType" v-model="editedEquipment.equipmentTypeId" required>
                        <option value="" disabled selected>Выберите тип оборудования</option>
                        <option v-for="type in types" :key="type.id" :value="type.id">{{ type.name }}</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label for="serialNumber">Серийный номер</label>
                    <input type="text" id="serialNumber" v-model="editedEquipment.serialNumber" required pattern="^[0-9A-Za-z]{{ mask }}$" title="Формат серийного номера должен соответствовать маске {{ mask }}" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <label for="description">Описание</label>
                    <textarea id="description" v-model="editedEquipment.description" rows="5"></textarea>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Обновить</button>
                    <button type="button" class="btn btn-secondary ml-2" @click="$router.push({ name: 'listEquipment' })">Отмена</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import axios from 'axios';
export default {
    name: "EditEquipmentComponent",
    data() {
        return {
            editedEquipment: {},
            types: []
        };
    },
    created() {
        // this.fetchEquipment();
        // this.fetchTypes();
    },
    methods: {
        fetchEquipment() {
            axios.get(`http://localhost:8000/api/equipments/${this.$route.params.id}`)
                .then(response => {
                    this.editedEquipment = response.data;
                })
                .catch(error => console.log(error));
        },
        updateEquipment() {
            const { equipmentTypeId, serialNumber, description } = this.editedEquipment;
            axios.put(`http://localhost:8000/api/equipments/${this.$route.params.id}`, {
                equipment_type_id: equipmentTypeId,
                serial_number: serialNumber,
                description: description
            }).then(() => {
                alert("Изменения сохранены!");
                this.$router.push({ name: 'listEquipment' });
            }).catch(error => {
                alert("Произошла ошибка при сохранении изменений.");
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
