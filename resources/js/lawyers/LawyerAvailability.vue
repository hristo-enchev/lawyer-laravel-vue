<template>
  <div>
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
              v-on:click="appointmentCheck(index, slot)"
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
    lawyer: Object,
  },
  data() {
    return {
      from: this.$store.state.lastCheck.from,
      to: this.$store.state.lastCheck.to,
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
    if (this.from == null || this.from == "") {
      this.from = moment().format();
      this.to = moment().format();
    }

    this.check();
  },
  methods: {
    async check() {
      this.loading = true;
      this.errors = null;

      this.$store.dispatch("setLastCheck", {
        from: this.from,
        to: this.to,
      });

      var from = moment(this.from).format("YYYY-MM-DD");
      var to = moment(this.to).format("YYYY-MM-DD");

      axios
        .get(
          `/api/lawyers/${this.$route.params.id}/appointment?from_time=${from}&to_time=${to}`
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
    appointmentCheck(index, slot) {
      if (
        this.$store.state.isLoggedIn &&
        this.$store.state.user.role.title == "client"
      ) {
        this.$router.push({
          name: "createAppointment",
          params: {
            date: index,
            from: slot.slot_from,
            to: slot.slot_to,
            lawyer: this.lawyer,
          },
        });
      } else if (
        this.$store.state.isLoggedIn &&
        this.$store.state.user.role.title != "client"
      ) {
        this.errorMsg =
          "Only clients can make appointments - without reason just validation.";
      } else {
        this.errorMsg =
          "Please login or register first to be able to make appointments.";
      }
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

      return moment((date + ' ' + time + ':00')) > moment();
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
    fromNow(value) {
      return moment(value).fromNow();
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