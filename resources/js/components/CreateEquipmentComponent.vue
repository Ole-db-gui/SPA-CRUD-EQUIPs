<template>
    <div class="container">
        <h1>Добавление нового оборудования</h1>
        <serial-mask-info-component></serial-mask-info-component>
        <form @submit.prevent="addEquipments">
            <!-- Таблица для добавления оборудования -->
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Тип оборудования</th>
                    <th scope="col">Серийный номер</th>
                    <th scope="col">Описание</th>
                    <th scope="col">Действия</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(equipment, index) in equipments" :key="index">
                    <td>
                        <select v-model="equipment.equipmentTypeId" required>
                            <option value="" disabled selected>Выберите тип оборудования</option>
                            <option v-for="type in types" :key="type.id" :value="type.id">{{ type.name }}, маска SN: {{ type.sn_mask}}</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" v-model="equipment.serialNumber" required/>
                    </td>
                    <td>
                        <textarea v-model="equipment.description" rows="2"></textarea>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger" @click="removeEquipment(index)">Удалить</button>
                    </td>
                </tr>
                </tbody>
            </table>

            <!-- Кнопки управления -->
            <div class="d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-success" @click="addNewRow">Добавить строку</button>
                <div>
                    <button type="submit" class="btn btn-primary">Добавить оборудование</button>
                    <button type="button" class="btn btn-secondary ms-2" @click="$router.push({ name: 'Equipments' })">Отмена</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import axios from 'axios';
import SerialMaskInfoComponent from "@/components/SerialMaskInfoComponent.vue";
export default {
    name: "AddEquipmentComponent",
    components: {SerialMaskInfoComponent},
    data() {
        return {
            equipments: [
                {
                    equipmentTypeId: '',
                    serialNumber: '',
                    description: ''
                }
            ],
            types: []
        };
    },
    created() {
        this.fetchTypes();
    },
    methods: {
        addEquipments() {
            const requests = this.equipments.map(equipment => {
                return axios.post('/api/equipment/', {
                    equipment_type_id: equipment.equipmentTypeId,
                    serial_number: equipment.serialNumber,
                    description: equipment.description
                });
            });

            Promise.all(requests)
                .then(() => {
                    alert("Оборудование успешно добавлено!");
                    this.equipments = [{ equipmentTypeId: '', serialNumber: '', description: '' }];
                    this.$router.push({ name: 'Equipments' });
                })
                .catch(error => {
                    alert("Произошла ошибка при добавлении оборудования.");
                    this.$router.push({ name: 'Equipments' });
                });
        },
        addNewRow() {
            this.equipments.push({
                equipmentTypeId: '',
                serialNumber: '',
                description: ''
            });
        },
        removeEquipment(index) {
            this.equipments.splice(index, 1);
        },
        fetchTypes() {
            axios.get('/api/equipment-type')
                .then(response => {
                    this.types = response.data.data;
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
