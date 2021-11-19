<template>
  <div>
    <Header />
    <div
      class="d-flex justify-content-center align-items-center w-100 flex-column"
    >
      <h1 class="mt-5 pl-4">Login</h1>
      {{ errorMessage }}
      <div class="p-5 w-50">
        <div class="d-flex align-items-center">
          <md-field class="mr-4" md-clearable>
            <label>Email</label>
            <md-input v-model="email" />
          </md-field>
        </div>

        <div class="mr-3 w-100">
          <md-field class="mr-4" md-clearable>
            <label>Password</label>
            <md-input type="password" v-model="password" />
          </md-field>
        </div>
      </div>
      <md-button
        @click="login(email, password)"
        class="md-raised md-primary w-25"
        >Connexion</md-button
      >
    </div>
  </div>
</template>

<script>
import Header from "../components/Header.vue";
export default {
  components: { Header },
  data() {
    return { email: "", password: "", errorMessage: "" };
  },
  beforeCreate() {
    let userId = this.$getCookie("user");
    let token = this.$getCookie("token");
    if (userId && token) this.$router.push("/videos");
  },
  methods: {
    async login(email, password) {
      let data = new FormData();
      data.append("login", email);
      data.append("password", password);

      await this.$axios
        .$post("auth", data)
        .then(rep => {
          this.errorMessage = "Welcome, " + rep.data.user.username + " !";
          this.$setCookie("token", rep.data.token);
          this.$setCookie("user", rep.data.user.id);
          this.$router.push("/videos");
        })
        .catch(err => {
          this.errorMessage = "The login or password is incorrect.";
        });
    }
  }
};
</script>

<style>
/* Sample `apply` at-rules with Tailwind CSS
.container {
@apply min-h-screen flex justify-center items-center text-center mx-auto;
}
*/
.container {
  margin: 0 auto;
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
}

.title {
  font-family: "Quicksand", "Source Sans Pro", -apple-system, BlinkMacSystemFont,
    "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
  display: block;
  font-weight: 300;
  font-size: 100px;
  color: #35495e;
  letter-spacing: 1px;
}

.subtitle {
  font-weight: 300;
  font-size: 42px;
  color: #526488;
  word-spacing: 5px;
  padding-bottom: 15px;
}

.links {
  padding-top: 15px;
}
</style>
