<template>
  <div>
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th
            v-for="title in titles"
            :key="title.key"
            @click="sortBy(title.sortable, title.key)"
          >
            {{ title.name }}
            <i
              v-bind:class="[
                'arrow',
                sortDirection === 'ASC' ? 'down' : 'up',
                sortKey === title.key ? 'active' : ''
              ]"
            ></i>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(row, index) in paginatedData" :key="index">
          <td>{{ row["num"] }}</td>
          <td v-for="title in titles" :key="title.key">{{ row[title.key] }}</td>
        </tr>
      </tbody>
    </table>
    <nav class="d-flex justify-content-center" v-if="pageCount > 1">
      <ul class="pagination">
        <li v-bind:class="['page-item', pageNumber == 1 ? 'disabled' : '']">
          <a
            class="page-link"
            href="#"
            aria-label="Previous"
            @click="prevPage"
            :disabled="pageNumber == 1"
          >
            <span aria-hidden="true">&laquo;</span>
          </a>
        </li>
        <li v-bind:class="['page-item', pageNumber == 1 ? 'disabled' : '']">
          <a class="page-link" href="#" @click="firstPage">1</a>
        </li>
        <li class="page-item page-link">...</li>
        <li
          v-bind:class="[
            'page-item',
            pageNumber >= pageCount - 1 ? 'disabled' : ''
          ]"
        >
          <a class="page-link" href="#" @click="lastPage">{{ pageCount }}</a>
        </li>
        <li
          v-bind:class="[
            'page-item',
            pageNumber >= pageCount - 1 ? 'disabled' : ''
          ]"
        >
          <a class="page-link" href="#" aria-label="Next" @click="nextPage">
            <span aria-hidden="true">&raquo;</span>
          </a>
        </li>
      </ul>
    </nav>
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
export default {
  props: {
    data: Array,
    titles: Array
  },
  data: function() {
    return {
      sortDirection: "ASC",
      sortKey: "",
      pageNumber: 1,
      perPage: 10,
      filterFrom: null,
      filterType: null,
      searchText: "",
      pageCount: 0
    };
  },
  methods: {
    ololo(newValue) {
      let testArr = this.data;
      if (this.searchText.trim() != "" && this.filterFrom && this.filterType) {
        console.log("ebat");
        let sortedArr = testArr.filter(row => {
          let some = row[this.filterFrom].toString().toLowerCase();
          return some.includes(newValue);
        });
        console.log(sortedArr);
      }
    },
    nextPage() {
      if (this.pageNumber * this.perPage < this.data.length) {
        this.pageNumber++;
      }
    },
    prevPage() {
      if (this.pageNumber > 1) {
        this.pageNumber--;
      }
    },
    lastPage() {
      this.pageNumber = this.pageCount - 1;
    },
    firstPage() {
      this.pageNumber = 1;
    },
    sortBy(sortable, key) {
      if (!sortable) return;
      this.sortKey = key;

      let modificator = 1;
      if (this.sortDirection === "DESC") modificator = -1;

      if (this.sortDirection === "DESC") {
        this.sortDirection = "ASC";
      } else {
        this.sortDirection = "DESC";
      }

      this.data.sort((a, b) =>
        a[key] > b[key] ? modificator : -1 * modificator
      );
    },
    compareValues(row) {
      //.toString().toLowerCase()
      let value = row[this.filterFrom];
      let mask = this.searchText;

      if (this.filterType == "like")
        return value
          .toString()
          .toLowerCase()
          .includes(mask.toString().toLowerCase());

      if (typeof value == "number")
        return this.compareValuesAsNumber(value, mask);
      if (typeof value == "string")
        return this.compareValuesAsString(value, mask);
      //Содержит
    },
    compareValuesAsString(value, mask) {
      let strValue = value.trim().toLowerCase();
      let strMask = mask.trim().toLowerCase();

      if (this.filterType == "eq") return strValue.localeCompare(strMask) === 0;
      if (this.filterType == "gt") return strValue.localeCompare(strMask) > 0;
      if (this.filterType == "lt") return strValue.localeCompare(strMask) < 0;
    },
    compareValuesAsNumber(value, mask) {
      let maskNumber = parseInt(mask);

      if (this.filterType == "gt") return value > maskNumber;
      if (this.filterType == "lt") return value < maskNumber;
      if (this.filterType == "eq") return value === maskNumber;
    },
    updatePageCount(arrLength) {
      this.pageCount = Math.ceil(arrLength / this.perPage);
    },
    addRowMumber(tmpArray) {
      if (typeof tmpArray !== "undefined" && tmpArray.length > 0) {
        let i = 1;
        tmpArray.forEach(row => {
          row["num"] = i++;
        });
        return tmpArray;
      }
    },
    paginateData(tmpArray) {
      if (typeof tmpArray !== "undefined" && tmpArray.length > 0) {
        if (this.pageNumber == 1) return tmpArray.slice(0, this.perPage);
        const start = this.pageNumber * this.perPage;
        const end = start + this.perPage;
        return tmpArray.slice(start, end);
      }
    }
  },
  computed: {
    columnListToSort() {
      return this.titles.filter(title => title.sortable === true);
    },
    paginatedData() {
      let tmpArray = this.data;
      if (this.searchText.trim() != "" && this.filterFrom && this.filterType) {
        tmpArray = tmpArray.filter(row => this.compareValues(row));
      }
      this.updatePageCount(tmpArray.length);
      tmpArray = this.addRowMumber(tmpArray);

      return this.paginateData(tmpArray);
    }
  }
};
</script>

<style scoped>
tr:first-child {
  width: 12%;
}
th:not(:first-child) {
  width: 22%;
}
.arrow {
  border: solid #ccc;
  border-width: 0 3px 3px 0;
  display: inline-block;
  padding: 3px;
}
.up {
  transform: rotate(-135deg);
}
.down {
  transform: rotate(45deg);
}
.active {
  border: solid black;
  border-width: 0 3px 3px 0;
}
</style>
