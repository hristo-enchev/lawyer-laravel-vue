<template>
  <div class="thumbnail">
    <router-link :to="{ name: 'details', params: { id } }">
      <img class="rounded imgsize" :src="getImgUrl()" alt="Lights" />
      <h3>{{ name }}</h3>
    </router-link>
    <p>{{ email }}</p>
  </div>
</template>

<script>
export default {
  props: {
    id: {
      type: Number,
    },
    name: {
      type: String,
      default: "name",
    },
    email: {
      type: String,
      default: "content",
    },
  },
  created() {},
  methods: {
    getImgUrl() {
      // Just random picture based on first char of the name
      var charNumber = this.name.toLowerCase().charCodeAt(0) - 96;
      var picName =
        charNumber > 2 && charNumber < 21 ? charNumber + ".jpg" : "12.jpg";

      return require("../../assets/" + picName);
    },
  },
};
</script>

<style scoped>
img {
  width: 250px;
  height: 250px;
  object-fit: cover;
}
</style>