<template>
  <header class="d-flex justify-content-between w-100 p-3 bg-dark">
    <h2 class="text-bold text-white">MyYoutube</h2>
    <div>
      <NuxtLink v-if="auth" to="/editProfile"
        ><md-button class="md-raised" :md-ripple="false"
          >Edit Profile
        </md-button>
      </NuxtLink>
      <NuxtLink
        :to="auth ? '/videos' : $nuxt.$route.path === '/login' ? '/' : '/login'"
        ><md-button @click="removeCookies" class="md-raised" :md-ripple="false"
          >{{
            auth
              ? "Videos"
              : $nuxt.$route.path === "/login"
              ? "Inscription"
              : "Connexion"
          }}
        </md-button>
      </NuxtLink>
      <NuxtLink v-if="auth" to="/myvideos"
        ><md-button class="md-raised" :md-ripple="false">My videos </md-button>
      </NuxtLink>
    </div>
  </header>
</template>

<script>
export default {
  data() {
    return {
      user: {},
      auth: false,
    };
  },
  beforeCreate() {
    let userId = this.$getCookie("user");
    let token = this.$getCookie("token");

    if (!userId && !token && $nuxt.$route.path !== "/login")
      this.$router.push("/");
  },
  created() {
    this.getAuth();
  },
  methods: {
    removeCookies() {
      if (!this.auth) this.$deleteAllCookies();
    },
    async getAuth() {
      let userId = this.$getCookie("user");
      let token = this.$getCookie("token");
      let config = {
        headers: {
          Authorization: "Bearer " + token,
        },
      };
      if (!userId || !token) return;
      await this.$axios
        .$get(`user/${userId}`, config)
        .then((rep) => {
          this.user = rep.data;
          this.auth = true;
        })
        .catch((err) => {
          console.log(err);
        });
    },
  },
};
</script>

<style>
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
