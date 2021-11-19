<template>
  <div>
    <Header />
    <div
      class="d-flex justify-content-center align-items-center w-100 flex-column"
    >
      <h1 class="mt-5 pl-4">Modify your information</h1>
      <h3>{{ errorMessage }}</h3>
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
        <div class="d-flex justify-content-center mt-3">
          <md-button @click="modify" class="md-raised md-primary w-25"
            >Modify information</md-button
          >
          <md-button @click="logout" class="md-raised md-primary w-25"
            >Logout</md-button
          >
        </div>
      </div>
      <div class="d-flex align-items-center">
        <div class="mr-3 w-100">
          <md-field class="mr-4" md-clearable>
            <label>Password</label>
            <md-input v-model="password" />
          </md-field>
        </div>
        <div class="ml-3 w-100">
          <md-field class="ml-4" md-clearable>
            <label>Verify Password</label>
            <md-input v-model="passwordV" />
          </md-field>
        </div>
        <md-button @click="modify('password')" class="md-raised md-primary"
          >Change</md-button
        >
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
  created() {
    this.getUser();
  },
  methods: {
    logout() {
      this.$deleteAllCookies();
      this.$router.push("/");
    },
    async getUser() {
      let userId = this.$getCookie("user");
      let token = this.$getCookie("token");
      let config = {
        headers: {
          Authorization: "Bearer " + token
        }
      };
      await this.$axios
        .$get(`user/${userId}`, config)
        .then(rep => {
          console.log(rep.data);
          this.username = rep.data.username;
          this.pseudo = rep.data.pseudo;
          this.email = rep.data.email;
        })
        .catch(err => {
          console.log(err);
          this.errorMessage = "Check if your entry are correct";
        });
    },

    async modify(edit) {
      let userId = this.$getCookie("user");
      let token = this.$getCookie("token");

      let config = {
        headers: {
          Authorization: "Bearer " + token
        }
      };

      let data = {};

      if (this.username) data.username = this.username;
      if (this.pseudo) data.pseudo = this.pseudo;
      if (this.email) data.email = this.email;
      if (this.password) data.password = this.password;

      if (edit === "password" && !this.password)
        return (this.errorMessage = "Please enter a password");

      if (this.password === this.passwordV)
        await this.$axios
          .$put(`user/${userId}`, data, config)
          .then(rep => {
            this.errorMessage = "Changements saved";
          })
          .catch(err => {
            this.errorMessage = "Check if your entry are correct";
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
