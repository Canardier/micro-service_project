<template>
  <div>
    <Header />
    <div
      class="d-flex justify-content-center align-items-center w-100 flex-column"
    >
      <h1 class="mt-5 pl-4">SingUp</h1>
      <h6>{{ errorMessage }}</h6>
      <div class="p-5 w-50">
        <div class="d-flex align-items-center">
          <div class="mr-3 w-100">
            <md-field class="mr-4" md-clearable>
              <label>Username</label>
              <md-input v-model="username" />
            </md-field>
          </div>
          <div class="ml-3 w-100">
            <md-field class="ml-4" md-clearable>
              <label>Pseudo</label>
              <md-input v-model="pseudo" />
            </md-field>
          </div>
        </div>
        <md-field md-clearable>
          <label>Email</label>
          <md-input v-model="email" />
        </md-field>
        <div class="d-flex align-items-center">
          <div class="mr-3 w-100">
            <md-field class="mr-4" md-clearable>
              <label>Password</label>
              <md-input type="password" v-model="password" />
            </md-field>
          </div>
          <div class="ml-3 w-100">
            <md-field class="ml-4" md-clearable>
              <label>Verify Password</label>
              <md-input type="password" v-model="passwordV" />
            </md-field>
          </div>
        </div>
        <div class="d-flex justify-content-center mt-3">
          <md-button @click="signup" class="md-raised md-primary w-25"
            >Create account</md-button
          >
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Header from "../components/Header.vue";
export default {
  components: { Header },
  data() {
    return {
      username: "",
      pseudo: "",
      email: "",
      password: "",
      passwordV: "",
      errorMessage: ""
    };
  },
  beforeCreate() {
    let userId = this.$getCookie("user");
    let token = this.$getCookie("token");
    if (userId && token) this.$router.push("/videos");
  },
  methods: {
    async signup() {
      let data = new FormData();
      data.append("username", this.username);
      data.append("pseudo", this.pseudo);
      data.append("email", this.email);
      data.append("password", this.password);

      if (this.password === this.passwordV)
        await this.$axios
          .$post("user", data)
          .then(rep => {
            console.log(rep.data);
            this.errorMessage = "Welcome, " + rep.data.username + " !";
          })
          .catch(({ response }) => {
            let rep = response.data.data;
            if (rep.email) this.errorMessage = rep.email[0];
            else this.errorMessage = "Check if your entry are correct";
          });
      else this.errorMessage = "Not the same password";
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
