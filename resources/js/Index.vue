<template>
  <div>
    <nav class="navbar navbar-expand-lg bg-white border-bottom navbar-light">
      <div class="navbar-brand mr-auto">
        <router-link v-if="!isLoggedIn" class="navbar-brand" :to="{name: 'home'}">Lawyers</router-link>
        <router-link
          v-else-if="this.$store.state.user.role.title == 'lawyer'"
          class="navbar-brand mr-auto"
          :to="{name: 'lawyerDashboard'}"
        >{{ this.$store.state.user.name }}</router-link>
        <router-link
          v-else
          class="navbar-brand mr-auto"
          :to="{name: 'clientDashboard'}"
        >{{ this.$store.state.user.name }}</router-link>
      </div>

      <!--no logged user-->
      <ul class="navbar-nav" v-if="!isLoggedIn">
        <li class="nav-item">
          <router-link :to="{name: 'home'}" class="nav-link">Home</router-link>
        </li>
        <li class="nav-item">
          <router-link :to="{name: 'register'}" class="nav-link">Register</router-link>
        </li>
        <li class="nav-item">
          <router-link :to="{name: 'login'}" class="nav-link">Sign-in</router-link>
        </li>
      </ul>
      <!--client-->
      <ul class="navbar-nav" v-else-if="this.$store.state.user.role.title == 'client'">
        <li class="nav-item">
          <router-link :to="{name: 'home'}" class="nav-link">Home</router-link>
        </li>
        <li class="nav-item">
          <router-link :to="{name: 'clientDashboard'}" class="nav-link">Dashboard</router-link>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" @click.prevent="logout">Logout</a>
        </li>
      </ul>
      <!--lawyer-->
      <ul class="navbar-nav" v-else-if="this.$store.state.user.role.title == 'lawyer'">
        <li class="nav-item">
          <router-link :to="{name: 'home'}" class="nav-link">Home</router-link>
        </li>
        <li class="nav-item">
          <router-link :to="{name: 'lawyerDashboard'}" class="nav-link">Dashboard</router-link>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" @click.prevent="logout">Logout</a>
        </li>
      </ul>
    </nav>

    <div class="container mt-4 mb-4 pr-4 pl-4">
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";

export default {
  data() {
    return {
      lastSearch: this.$store.state.lastSearch,
    };
  },
  computed: {
    ...mapState({
      lastSearchComputed: "lastSearch",
      isLoggedIn: "isLoggedIn",
    }),
  },
  methods: {
    async logout() {
      try {
        await axios.post("/logout");
        this.$store.dispatch("logout");
      } catch (error) {
        this.$store.dispatch("logout");
      }

      this.$router.push({ name: "home" }).catch((err) => {});
    },
  },
};
</script>