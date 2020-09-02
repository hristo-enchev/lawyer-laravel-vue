<template>
  <div class="row">
    <div class="col-md-7 pb-4">
      <div class="card">
        <div class="card-body">
          <div v-if="!loading">
            <div class="row">
              <div class="col-md-3">
                <img class="rounded img-fluid" :src="getImgUrl()" alt="Lights" />
              </div>
              <div class="col md-9">
                <h2>{{ lawyer.name }}</h2>
              </div>
            </div>

            <hr />
            <p>
              <span>&#9993;</span>
              {{ lawyer.email }}
            </p>
            <hr />
            <h3>About me</h3>
            <hr />
            <p>Etiam aliquam sed lectus eget aliquam. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nam vehicula eget eros sit amet laoreet. Aliquam erat volutpat. Nunc molestie turpis id sem semper, sodales venenatis metus venenatis. Sed ut tellus eget ligula eleifend egestas placerat et lorem. Interdum et malesuada fames ac ante ipsum primis in faucibus. Morbi venenatis lacinia leo ut placerat. Interdum et malesuada fames ac ante ipsum primis in faucibus. Phasellus eu massa mattis, faucibus ligula sit amet, imperdiet lectus.</p>
          </div>
          <div v-else>Loading...</div>
        </div>
      </div>
    </div>
    <div class="col-md-5 pb-4">
      <lawyer-availability
        :lawyer="lawyer"
        class="mb-4"
      ></lawyer-availability>
    </div>
  </div>
</template>

<script>
import LawyerAvailability from "./LawyerAvailability";

export default {
  components: {
    LawyerAvailability,
  },
  data() {
    return {
      lawyer: null,
      loading: false,
    };
  },
  created() {
    this.loading = true;

    axios.get("/api/lawyers/" + this.$route.params.id).then((response) => {
      this.lawyer = response.data;
      this.loading = false;
    });
  },
  methods: {
    getImgUrl() {
      // Just random picture based on first char of the name
      var charNumber = this.lawyer.name.toLowerCase().charCodeAt(0) - 96;
      var picName =
        charNumber > 2 && charNumber < 21 ? charNumber + ".jpg" : "12.jpg";

      return require("../../assets/" + picName);
    },
  },
};
</script>

<style>
</style>