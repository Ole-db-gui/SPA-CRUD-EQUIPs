<template>
    <div class="container">
        <h1>Список типов оборудования</h1>
        <div class="row">
            <div class="col-md-12">
                <router-link :to="{ name: 'CreateEquipmentType'}" class="btn btn-primary">Добавить новый тип оборудования</router-link>
                <span class="m-lg-3"><router-link :to="{ name: 'Equipments'}" class="btn btn-info">Список оборудования</router-link></span>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Название</th>
                        <th scope="col">Маска серийного номера</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="type in types" :key="type.id">
                        <td>{{ type.id }}</td>
                        <td>{{ type.name }}</td>
                        <td>{{ type.sn_mask }}</td>
                        <td>
                            <router-link :to="{ name: 'EditEquipmentType', params: { id: type.id }}" class="btn btn-primary">Редактировать</router-link>
                            <button class="btn btn-danger ml-2" @click="deleteEquipmentType(type.id)">Удалить</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
export default {
    name: "EquipmentTypeComponent",
    data() {
        return {
            types: {}
        };
    },
    created() {
        this.fetchData();
    },
    methods: {
        fetchData() {
            axios.get('/api/equipment-type')
                .then(response => {
                    this.types = response.data.data;
                })
                .catch(error => console.log(error));
        },
        deleteEquipmentType(id) {
            if (confirm("Вы уверены, что хотите удалить этот тип оборудования?")) {
                axios.delete(`/api/equipment-type/${id}`)
                    .then(() => {
                        this.fetchData();
                    })
                    .catch(error => console.log(error));
            }
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
