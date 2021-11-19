<template>
  <div>
    <Header />
    <md-button
      class="md-raised ml-2"
      v-on:click="
        () => {
          this.$router.push('/videos');
        }
      "
      >Back</md-button
    >
    <div
      class="d-flex justify-content-center align-items-center w-100 flex-column"
    >
      <h1 class="mt-5 pl-4">Upload une vidéo</h1>
      <h6>{{ errorMessage }}</h6>
      <div class="p-5 w-50">
        <div class="d-flex align-items-center">
          <div class="mr-3 w-100">
            <md-field class="mr-4" md-clearable>
              <label>Nom</label>
              <md-input v-model="name" />
            </md-field>
          </div>
          <div class="ml-3 w-100">
            <md-field class="ml-4" md-clearable>
              <input
                v-on:change="handleOnChangeVideo"
                type="file"
                accept="video/mp4, video/mov"
              />
            </md-field>
          </div>
        </div>

        <div>
          <md-checkbox v-model="private_bool">Private</md-checkbox>
        </div>
        <div class="d-flex justify-content-center mt-3">
          <md-button @click="sendVideo" class="md-raised md-primary w-25"
            >UPLOAD</md-button
          >
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      private_bool: false,
      video: null,
      name: ""
    };
  },
  methods: {
    handleOnChangeVideo(e) {
      this.video = e.target.files[0];
    },
    async sendVideo() {
      this.errorMessage = "";
      let data = new FormData();
      data.append("name", this.name);
      data.append("enabled", !this.private_bool);
      data.append("source", this.video);
      let userId = this.$getCookie("user");
      let token = this.$getCookie("token");
      let config = {
        headers: {
          Authorization: "Bearer " + token
        }
      };

      if (this.password === this.passwordV)
        await this.$axios
          .$post(`user/${userId}/video`, data, config)
          .then(rep => {
            console.log(rep.data);
            this.errorMessage = "Welcome, " + rep.data.username + " !";
          })
          .catch(err => {
            console.log(err);
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
