<template>
  <div class="w-50 m-auto">
    <div class="card card-body">
      <form>
        <h2 for="name" class="text-center">Create appointment</h2>
        <hr />
        <div class="form-group">
          <label for="email">Lawyer</label>
          <h4>{{ lawyer.name }}</h4>
        </div>
        <div class="form-group">
          <label for="email">Appointment date</label>
          <h4>{{ date }}</h4>
        </div>
        <div class="form-group">
          <label for="email">Appointment</label>
          <h4>{{ from }} - {{ to }}</h4>
        </div>
        <div class="form-group">
          <label for="details">Details</label>
          <input
            type="text"
            name="name"
            placeholder="Add details to appointment"
            class="form-control"
            v-model="details"
          />
        </div>
        <button
          type="submit"
          class="btn btn-primary btn-lg btn-block"
          :disabled="loading"
          @click.prevent="createAppointment"
        >Create appointment</button>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    date: {
      type: String,
    },
    from: {
      type: String,
    },
    to: {
      type: String,
    },
    lawyer: {
      type: Object,
    },
  },
  data() {
    return {
      details: "",
      loading: false,
    };
  },
  methods: {
    async createAppointment() {
      this.loading = true;
      let data = {
        date: this.date,
        from: this.from,
        to: this.to,
        details: this.details,
        lawyerid: this.lawyer.id,
      };

      axios.post("/api/appointment/create", data).then((responce) => {
        this.$router.go(-1);
      });

      this.loading = false;
    },
  },
  beforeMount() {
    if (!this.lawyer) {
      this.$router.push({ name: "home" });
    }
  },
};
</script>