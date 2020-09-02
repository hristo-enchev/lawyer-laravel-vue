<template>
  <div>
    <div class="row">
      <div class="col-md-3">Status</div>
      <div class="col-md-3">Order By</div>
      <div class="col-md-3">Sort</div>
      <div class="col-md-3">Search client</div>
    </div>
    <div class="row">
      <div class="col-md-3">
        <b-form-select v-model="status" :options="statusOptions"></b-form-select>
      </div>
      <div class="col-md-3">
        <b-form-select v-model="order" :options="orderOptions"></b-form-select>
      </div>
      <div class="col-md-3">
        <b-form-select v-model="sort" :options="sortOptions"></b-form-select>
      </div>
      <div class="col-md-3">
        <input type="text" name="search" placeholder="Search" class="form-control" v-model="search" />
      </div>
    </div>
    <div class="row">
      <hr />
    </div>
    <div class="row">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">Status</th>
            <th>Client</th>
            <td>Date</td>
            <td>From</td>
            <td>To</td>
            <td>Details</td>
            <td>Actions</td>
          </tr>
        </thead>
        <tbody>
          <lawyer-appointment-list
            v-for="appointment in appointments"
            :key="appointment.id"
            v-bind="appointment"
            @reloadTable="reloadTable"
          ></lawyer-appointment-list>
        </tbody>
      </table>
    </div>
    <pagination
      :data="paginationData"
      align="center"
      @pagination-change-page="getResults"
      :limit="2"
    >
      <span slot="prev-nav">&lt; Previous</span>
      <span slot="next-nav">Next &gt;</span>
    </pagination>
  </div>
</template>

<script>
import LawyerAppointmentList from "./../appointments/LawyerAppointmentList";

export default {
  components: {
    LawyerAppointmentList,
  },
  data() {
    return {
      appointments: {},
      paginationData: {},
      search: null,
      currentPage: null,
      status: "all",
      statusOptions: [
        { value: "all", text: "All" },
        { value: "pending", text: "Pending" },
        { value: "accepted", text: "Accepted" },
        { value: "rejected", text: "Rejected" },
      ],
      order: "from_time",
      orderOptions: [
        { value: "from_time", text: "Date" },
        { value: "status", text: "Status" },
        ,
      ],
      sort: "asc",
      sortOptions: [
        { value: "asc", text: "Ascending" },
        { value: "desc", text: "Descending" },
        ,
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
          `/api/dashboard/lawyer?page=${page}&status=${this.status}&order=${this.order}&sort=${this.sort}&search=${this.search}`
        )
        .then((responce) => {
          this.appointments = responce.data.data;
          this.paginationData = responce.data;
          this.currentPage = responce.data.meta.current_page;
        });
    },
    reloadTable() {
      this.getResults(this.currentPage);
    },
  },
  watch: {
    status: function () {
      this.getResults();
    },
    order: function () {
      this.getResults();
    },
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