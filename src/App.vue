<template>
  <div class="container-fluid">
    <Tbl :titles="titles" :data="data" />
    <div class="row">
      <h2>А вот тут фильтруются данные на сервере</h2>
    </div>
    <div class="row">
      <div class="col-md-4">
        <label class="col-form-label">Выберите столбец</label>
        <select class="custom-select custom-select-sm" v-model="filterFrom">
          <option disabled>Выберите солбец для сортировки</option>
          <option
            v-for="(column, index) in columnListToSort"
            :key="index"
            v-bind:value="column.key"
            >{{ column.name }}</option
          >
        </select>
      </div>
      <div class="col-md-4">
        <label class="col-form-label">Фильтр</label>
        <select class="custom-select custom-select-sm" v-model="filterType">
          <option disabled>Выберите тип</option>
          <option value="gt">Больше</option>
          <option value="lt">Меньше</option>
          <option value="eq">Равно</option>
          <option value="like">Содержит</option>
        </select>
      </div>
      <div class="col-md-4">
        <label class="col-form-label">Текст</label>
        <input
          type="text"
          class="form-control form-control-sm"
          placeholder="Введите текст для поиска"
          v-model="searchText"
        />
      </div>
    </div>
  </div>
</template>

<script>
const debounce = require("lodash/debounce");
import Tbl from "./components/Tbl";

export default {
  components: {
    Tbl
  },
  created() {
    this.debouncedGetDbData = debounce(this.getDbData, 1000);
  },
  mounted() {
    this.getDbData();
  },
  data() {
    return {
      data: [],
      filterFrom: null,
      filterType: null,
      searchText: "",
      titles: [
        { name: "Дата", key: "date", sortable: false },
        { name: "Название", key: "name", sortable: true },
        { name: "Количество", key: "count", sortable: true },
        { name: "Расстояние", key: "distance", sortable: true }
      ]
    };
  },
  watch: {
    filterType() {
      this.checkFilters();
    },
    filterFrom() {
      this.checkFilters();
    },
    searchText() {
      this.checkFilters();
    }
  },
  computed: {
    columnListToSort() {
      return this.titles.filter(title => title.sortable === true);
    }
  },
  methods: {
    checkFilters() {
      if (this.searchText !== "" && this.filterFrom && this.filterType) {
        let body = {};
        body.column = this.filterFrom;
        body.filterType = this.filterType;
        body.value = this.searchText;

        this.debouncedGetDbData(body);
      }
    },
    getDbData(body = null) {
      this.$http
        .get("php/", { params: body })
        .then(response => {
          this.data = response.data;
        })
        .catch(err => console.log(err));
    }
  }
};
</script>

<style>
/* @import "https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"; */
</style>
