<template>
  <div>
    <div class="row">
      <div class="col-md-3">Sort</div>
      <div class="col-md-9">Search</div>
    </div>
    <div class="row">
      <div class="col-md-3">
        <b-form-select v-model="sort" :options="sortOptions"></b-form-select>
      </div>
      <div class="col-md-9">
        <input type="text" name="search" placeholder="Search" class="form-control" v-model="search" />
      </div>
    </div>
    <hr />
    <div class="row">
      <div class="col-lg-12" v-if="lawyers && !Object.entries(lawyers).length">
        <div class="alert alert-warning text-center" role="alert">No lawyers found!</div>
      </div>
      <div v-for="lawyer in lawyers" :key="lawyer.id" class="col-md-4">
        <lawyer-list-item v-bind="lawyer" class></lawyer-list-item>
      </div>
    </div>
    <pagination :data="paginationData" align="center" @pagination-change-page="getResults">
      <span slot="prev-nav">&lt; Previous</span>
      <span slot="next-nav">Next &gt;</span>
    </pagination>
  </div>
</template>

<script>
import LawyerListItem from "./LawyerListItem";

export default {
  components: {
    LawyerListItem,
  },
  data() {
    return {
      lawyers: null,
      paginationData: {},
      currentPage: null,
      search: "",
      sort: "null",
      sortOptions: [
        { value: "null", text: "None" },
        { value: "asc", text: "Name Ascending" },
        { value: "desc", text: "Name Descending" },
      ],
    };
  },
  mounted() {
    this.getResults();
  },
  methods: {
    getResults(page = 1) {
      axios
        .get(
          `/api/lawyers?page=${page}&sort=${this.sort}&search=${this.search}`
        )
        .then((responce) => {
          this.lawyers = responce.data.data;
          this.paginationData = responce.data;
        });
    },
  },
  watch: {
    sort: function () {
      this.getResults();
    },
    search: function () {
      this.getResults();
    },
  },
};
</script>

<style>
</style>