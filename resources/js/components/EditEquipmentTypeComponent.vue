<template>
    <div class="container">
        <h1>Редактирование типа оборудования</h1>
        <form @submit.prevent="onSubmit">
            <div class="row">
                <div class="col-md-6">
                    <label for="name">Название типа оборудования</label>
                    <input type="text" id="name" v-model="form.name" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label for="sn_mask">Маска серийного номера</label>
                    <input type="text" id="sn_mask" v-model="form.sn_mask" required>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Обновить</button>
                    <button type="button" class="btn btn-secondary ml-2" @click="$router.push({ name: 'listEquipmentTypes' })">Отмена</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import axios from 'axios';
export default {
    name: "EditEquipmentTypeComponent",
    data() {
        return {
            form: {}
        };
    },
    created() {
        // this.fetchData();
    },
    methods: {
        fetchData() {
            axios.get(`http://localhost:8000/api/equipment-types/${this.$route.params.id}`)
                .then(response => {
                    this.form = response.data;
                })
                .catch(error => console.log(error));
        },
        onSubmit() {
            axios.put(`http://localhost:8000/api/equipment-types/${this.$route.params.id}`, this.form)
                .then(() => {
                    alert("Тип оборудования успешно обновлен!");
                    this.$router.push({ name: 'listEquipmentTypes' });
                })
                .catch(error => {
                    alert("Произошла ошибка при обновлении типа оборудования.");
                    console.log(error);
                });
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


