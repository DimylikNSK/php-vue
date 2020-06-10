<template>
  <div class>
    <Tbl :titles="titles" :data="data" />
  </div>
</template>

<script>
import Tbl from "./components/Tbl";

export default {
  components: {
    Tbl
  },
  mounted() {
    this.getData();
  },
  data() {
    return {
      data: [],
      titles: [
        { name: "Дата", key: "date", sortable: false },
        { name: "Название", key: "name", sortable: true },
        { name: "Количество", key: "count", sortable: true },
        { name: "Расстояние", key: "distance", sortable: true }
      ]
    };
  },
  methods: {
    getData() {
      this.$http
        .get("/php/")
        .then(response => (this.data = response.data))
        .catch(err => console.log(err));
    }
  }
};
</script>

<style>
/* @import "https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"; */
</style>
