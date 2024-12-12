<template>
    <div>
        Легенда для маски серийного номера типа оборудования:
        <ul>
            <li>N – цифра от 0 до 9;</li>
            <li>A – прописная буква латинского алфавита;</li>
            <li>a – строчная буква латинского алфавита;</li>
            <li>X – прописная буква латинского алфавита либо цифра от 0 до 9;</li>
            <li>Q –символ из списка: “-“, “_”, “@”.</li>
        </ul>
    </div>
</template>

<script>
export default {
    name: "SerialMaskInfoComponent"
}
</script>

<style scoped>

</style>
