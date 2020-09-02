<template>
  <tr>
    <td>{{ status }}</td>
    <td>{{ user }}</td>
    <td>{{ from_time | dateParse('YYYY-MM-DD') }}</td>
    <td>{{ from_time | dateParse('HH:mm') }}</td>
    <td>{{ to_time | dateParse('HH:mm') }}</td>
    <td class="details" :title="details">{{ details }}</td>
    <td>
      <button
        type="button"
        v-on:click="updateAppointment(id, 'accepted')"
        v-if="status == 'pending'"
        class="btn btn-success btn-sm"
      >Accept</button>
      <button
        type="button"
        v-on:click="updateAppointment(id, 'rejected')"
        v-if="status == 'pending' || status == 'accepted'"
        class="btn btn-danger btn-sm"
      >Reject</button>
    </td>
  </tr>
</template>

<script>
import moment from "moment";

export default {
  props: {
    id: {
      type: Number,
    },
    from_time: {
      type: String,
    },
    to_time: {
      type: String,
    },
    status: {
      type: String,
    },
    details: {
      type: String,
    },
    lawyer: {
      type: String,
    },
    user : {
      type: String,
    },
  },
  filters: {
    dateParse: (date, format) => {
      return moment(date).format(format);
    },
  },
  methods: {
    updateAppointment(id, status) {
      axios
        .post("/api/lawyer/appointment/update", { id, status })
        .then((responce) => {
          this.$emit("reloadTable");
        });
    },
  },
};
</script>

<style scoped>
.details {
  max-width: 200px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
</style>