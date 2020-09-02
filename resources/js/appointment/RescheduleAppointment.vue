<template>
  <div>
    <h2 class="text-center">Reschedule appointment</h2>
    <h4>Rejected appointment:</h4>
    <div class="row">
      <div class="col-md-2">Date</div>
      <div class="col-md-2">From</div>
      <div class="col-md-2">To</div>
      <div class="col-md-6">Details</div>
    </div>
    <div class="row">
      <div class="col-md-2">{{ fromDate | dateParse('YYYY-MM-DD') }}</div>
      <div class="col-md-2">{{ fromDate | dateParse('HH:mm') }}</div>
      <div class="col-md-2">{{ toDate | dateParse('HH:mm') }}</div>
      <div class="col-md-6">
        <input
          type="text"
          name="name"
          placeholder="Add details to appointment"
          class="form-control"
          v-model="details"
        />
      </div>
    </div>
    <hr />
    <h6 class="text-uppercase text-secondary font-weight-bolder">Check Availability</h6>
    <div class="form-row">
      <div class="form-group col-md-6">
        <datetime
          v-model="from"
          input-class="form-control form-control-sm"
          name="from"
          @keyup.enter="check"
        >
          <label for="startDate" slot="before">From</label>
        </datetime>
        <v-errors :errors="errorFor('from_time')"></v-errors>
      </div>
      <div class="form-group col-md-6">
        <datetime
          v-model="to"
          input-class="form-control form-control-sm"
          name="to"
          @keyup.enter="check"
        >
          <label for="startDate" slot="before">To</label>
        </datetime>
        <v-errors :errors="errorFor('to_time')"></v-errors>
      </div>
    </div>

    <button class="btn btn-secondary btn-block" @click="check" :disabled="loading">
      <span v-if="!loading">Check!</span>
      <span v-if="loading">
        <i class="fas fa-circle-notch fa-spin"></i> Checking...
      </span>
    </button>
    <hr />
    <div v-if="errorMsg" class="alert alert-danger" role="alert">{{ errorMsg }}</div>
    <hr />
    <div v-if="appointments !== null">
      <h5 class="head text-center">Appointment days and hours</h5>
      <h6 class="head text-center">Create appointment by selection the available hour</h6>
      <hr />
      <div class="d-none d-md-block" v-for="(slots, index) in appointments" :key="index">
        <h5>{{ index }}</h5>

        <div class="row">
          <div class="col-md-4" v-for="(slot, index2) in slots" :key="index2">
            <button
              type="button"
              class="btn btn-outline-primary"
              v-if="isFutureDate(index, slot.slot_from)"
              v-on:click="rescheduleAppointment(index, slot)"
            >{{ slot.slot_from }}-{{ slot.slot_to }}</button>
          </div>
        </div>
        <hr />
      </div>
    </div>
  </div>
</template>

<script>
import { is422 } from "./../shared/utils/response";
import validationErrors from "./../shared/mixins/validationErrors";
import moment from "moment";
import { Datetime } from "vue-datetime";
import "vue-datetime/dist/vue-datetime.css";
import BookAppointment from "./../appointment/BookAppointment";

export default {
  mixins: [validationErrors],
  props: {
    appointmentId: Number,
    lawyerId: Number,
    fromDate: String,
    toDate: String,
    details: String,
  },
  data() {
    return {
      from: null,
      to: null,
      appointments: null,
      loading: false,
      status: null,
      showModal: false,
      errorMsg: null,
    };
  },
  components: {
    datetime: Datetime,
    BookAppointment,
  },
  mounted: function () {
    let test = this.fromDate;
    let test2 = this.toDate;

    if (this.from == null || this.from == "") {
      this.from = moment(this.fromDate).format();
      this.to = moment(this.toDate).format();
    }

    this.check();
  },
  methods: {
    async check() {
      this.loading = true;
      this.errors = null;

      if (this.lawyerId == null || this.appointmentId == null) {
        alert("Oops! Something went wrong.");

        this.$router.go(-1);

        return;
      }

      this.$store.dispatch("setLastCheck", {
        from: this.from,
        to: this.to,
      });

      var from = moment(this.from).format("YYYY-MM-DD");
      var to = moment(this.to).format("YYYY-MM-DD");

      axios
        .get(
          `/api/lawyers/${this.lawyerId}/appointment?from_time=${from}&to_time=${to}`
        )
        .then((response) => {
          this.appointments = response.data.slots;

          this.loading = false;
        })
        .catch((error) => {
          this.appointments = null;

          if (422 == error.response.status) {
            this.errors = error.response.data.errors;
          }

          this.status = error.response.status;
        })
        .then(() => {
          this.loading = false;
        });
    },
    errorFor(field) {
      return this.hasErrors && this.errors[field] ? this.errors[field] : null;
    },
    rescheduleAppointment(index, slot) {
      let data = {
        id: this.appointmentId,
        date: index,
        from: slot.slot_from,
        to: slot.slot_to,
        details: this.details,
      };

      axios.post("/api/appointment/reschedule", data).then((responce) => {
        this.$router.go(-1);
      });
    },
    checkDates() {
      if (
        moment(this.to)
          .startOf("day")
          .diff(moment(this.from).startOf("day"), "days") > 2
      ) {
        this.errorMsg = "A maximum of 3 days check is allowed!";
        this.to = moment(this.from).add(2, "days").format();
      }
    },
    isFutureDate(date, time) {
      return moment(date + " " + time + ":00") > moment();
    },
  },
  computed: {
    hasErrors() {
      return 422 === this.status && this.errors !== null;
    },
    hasAvailability() {
      return 200 === this.status;
    },
    noAvailability() {
      return 404 === this.status;
    },
  },
  filters: {
    dateParse: (date, format) => {
      return moment(date).format(format);
    },
  },
  watch: {
    from: function (val, oldVal) {
      if (this.from > this.to) {
        this.to = this.from;
      }

      this.checkDates();
    },
    to: function (val, oldVal) {
      if (this.from > this.to) {
        this.from = this.to;
      }

      this.checkDates();
    },
    errorMsg: function (val, oldVal) {
      setTimeout(() => (this.errorMsg = false), 3000);
    },
  },
};
</script>

<style scoped>
label {
  font-size: 0.7rem;
  text-transform: uppercase;
  color: gray;
  font-weight: bolder;
}

.is-invalid {
  border-color: #b22222;
  background-image: none;
}

.invalid-feedback {
  color: #b22222;
}
</style>