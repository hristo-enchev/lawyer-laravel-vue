<template>
  <tr>
    <td>{{ status }}</td>
    <td>{{ lawyer }}</td>
    <td>{{ from_time | dateParse('YYYY-MM-DD') }}</td>
    <td>{{ from_time | dateParse('HH:mm') }}</td>
    <td>{{ to_time | dateParse('HH:mm') }}</td>
    <td class="details" :title="details">{{ details }}</td>
    <td v-if="isFutureDate(from_time) == false">Past appointment</td>
    <td v-else>
      <div v-if="status == 'rejected'">
        <button
          type="button"
          v-on:click="rescheduleAppointment()"
          class="btn btn-info btn-sm"
        >Reschedule</button>
        <button
          type="button"
          v-on:click="deleteAppointment(id)"
          class="btn btn-danger btn-sm"
        >Delete</button>
        
      </div>
      <div v-else>
        <button
          type="button"
          v-on:click="updateAppointment(id, 'rejected')"
          class="btn btn-warning btn-sm"
        >Reject</button>
      </div>
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
    lawyer_id: {
      type: Number,
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
        .post("/api/client/appointment/update", { id, status })
        .then((responce) => {
          this.$emit("reloadTable");
        });
    },
    deleteAppointment(id) {
      axios.post("/api/client/appointment/delete", { id }).then((responce) => {
        this.$emit("reloadTable");
      });
    },
    isFutureDate(date) {
      return moment(date) > moment();
    },
    rescheduleAppointment(appointmentId, lawyerId, from, to) {
      this.$router.push({
        name: "reschedule",
        params: {
          appointmentId: this.id,
          lawyerId: this.lawyer_id,
          fromDate: this.from,
          toDate: this.to,
          details: this.details,
        },
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