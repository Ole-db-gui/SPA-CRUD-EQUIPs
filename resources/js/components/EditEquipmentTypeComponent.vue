<template>
    <div class="container">
        <h1>Редактирование типа оборудования</h1>
        <serial-mask-info-component></serial-mask-info-component>
        <form @submit.prevent="onSubmit">
            <div class="row">
                <div class="col-md-6">
                    <label for="name">Название типа оборудования</label>
                    <input class="ms-2" type="text" id="name" v-model="form.name" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label for="sn_mask">Маска серийного номера</label>
                    <input class="ms-2" type="text" id="sn_mask" v-model="form.sn_mask" required>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Обновить</button>
                    <button type="button" class="btn btn-secondary ms-2" @click="$router.push({ name: 'EquipmentTypes' })">Отмена</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import axios from 'axios';
import SerialMaskInfoComponent from "@/components/SerialMaskInfoComponent.vue";
export default {
    name: "EditEquipmentTypeComponent",
    components: {SerialMaskInfoComponent},
    data() {
        return {
            form: {}
        };
    },
    created() {
        this.fetchData();
    },
    methods: {
        fetchData() {
            axios.get(`/api/equipment-type/${this.$route.params.id}`)
                .then(response => {
                    this.form = response.data.data;
                })
                .catch(error => console.log(error));
        },
        onSubmit() {
            axios.put(`/api/equipment-type/${this.$route.params.id}`, this.form)
                .then(() => {
                    alert("Тип оборудования успешно обновлен!");
                    this.$router.push({ name: 'EquipmentTypes' });
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


