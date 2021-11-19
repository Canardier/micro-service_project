<template>
  <div>
    <Header />
    <div class="col-12 p-5">
      <div class="d-flex align-items-center">
        <h3>List of Video</h3>
        <md-button
          class="md-raised ml-2"
          v-on:click="
            () => {
              this.$router.push('/upload');
            }
          "
          >Upload video</md-button
        >
      </div>
      <div class="d-flex align-items-center">
        <md-field>
          <label>Search</label>
          <md-input v-model="searchedValue" />
        </md-field>
        <md-button v-on:click="getVideos()">Search..</md-button>
      </div>

      <div
        class="d-flex w-100 h-100 justify-content-center align-items-center"
        style="height: 500px"
      >
        <md-progress-spinner
          v-if="loading"
          class="md-accent"
          :md-stroke="3"
          md-mode="indeterminate"
        ></md-progress-spinner>
      </div>

      <ul class="pl-0">
        <li v-for="item in videos" :key="item.id" class="mt-3 mb-3">
          <md-card>
            <md-card-header>
              <div class="md-title">{{ item.name }}</div>
            </md-card-header>

            <md-card-content>
              <p>
                <b>Video of {{ item.user.username }}</b>
              </p>
              <p>
                {{
                  item.created_at
                    .split(" ")
                    .join(" at ")
                    .split(":")
                    .slice(0, 2)
                    .join(":")
                    .split("-")
                    .join("/")
                }}
              </p>
            </md-card-content>

            <md-card-actions>
              <NuxtLink :to="`/videos/${item.id}`">
                <md-button>Show</md-button>
              </NuxtLink>
            </md-card-actions>
          </md-card>
        </li>
      </ul>

      <nav aria-label="Page navigation example">
        <ul class="pagination">
          <li @click="getVideos(currentPage - 1)" class="page-item">
            <span class="page-link">Previous</span>
          </li>
          <li
            v-for="n in pager.total"
            v-bind:key="n"
            class="page-item"
            v-bind:class="{ active: n === currentPage }"
            @click="getVideos(n)"
          >
            <span class="page-link">{{ n }}</span>
          </li>
          <li @click="getVideos(currentPage + 1)" class="page-item">
            <span class="page-link">Next</span>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</template>

<script>
import moment from "moment";
import Header from "../../components/Header.vue";
export default {
  components: { Header },
  data() {
    return {
      videos: [],
      pager: {},
      loading: true,
      searchedValue: "",
      currentPage: 1,
      totalPage: 1,
    };
  },
  created() {
    this.getVideos();
  },
  methods: {
    moment: function() {
      return moment();
    },
    async getVideos(page) {
      this.loading = true;
      if (page) this.currentPage = page;

      const data = {
        name: this.searchedValue ? this.searchedValue : undefined,
      };
      const pageValue = { page: page };
      if (page) Object.assign(data, pageValue);

      await this.$axios
        .$get("videos", {
          params: data,
        })
        .then((rep) => {
          this.videos = rep.data;
          this.totalPage = rep.pager.totalPage;
          this.pager = rep.pager;
          this.loading = false;
        });
    },
  },
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
