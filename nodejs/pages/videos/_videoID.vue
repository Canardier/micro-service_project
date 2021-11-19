<template>
  <div>
    <Header />
    <div class="col-12 p-5">
      <h1>{{ this.video.name }}</h1>
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

      <video v-if="!loading" class="w-100" controls width="250">
        <source :src="this.video.source" type="video/webm" />
        Sorry, your browser doesn't support embedded videos.
      </video>

      <h3 class="mt-5">Comments</h3>
      <md-field :class="messageClass">
        <label>Write your comment</label>
        <md-textarea v-model="comment" required></md-textarea>
      </md-field>
      <md-button class="md-raised" @click="sendComment" :md-ripple="false"
        >Send</md-button
      >
      <ul class="pl-0">
        <li v-for="item in comments" :key="item.id" class="mt-3 mb-3">
          <md-card>
            <md-card-header>
              <div class="md-title">{{ item.body }}</div>
            </md-card-header>

            <md-card-content>
              <p>
                <b>{{
                  item.created_at
                    .split(" ")
                    .join(" at ")
                    .split(":")
                    .slice(0, 2)
                    .join(":")
                    .split("-")
                    .join("/")
                }}</b>
              </p>
            </md-card-content>
          </md-card>
        </li>
      </ul>

      <nav aria-label="Page navigation example">
        <ul class="pagination">
          <li @click="getComments(currentPage - 1)" class="page-item">
            <span class="page-link">Previous</span>
          </li>
          <li
            v-for="n in pager.total"
            v-bind:key="n"
            class="page-item"
            v-bind:class="{ active: n === currentPage }"
            @click="getComments(n)"
          >
            <span class="page-link">{{ n }}</span>
          </li>
          <li @click="getComments(currentPage + 1)" class="page-item">
            <span class="page-link">Next</span>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</template>

<script>
import moment from "moment";
import tz from "moment-timezone";
import Header from "../../components/Header.vue";
export default {
  components: { Header },
  data() {
    return {
      loading: true,
      comments: [],
      pager: {},
      video: "",
      comment: "",
    };
  },
  created() {
    tz("Europe/Paris");
    this.getComments();
    this.getVideo();
  },
  methods: {
    moment: function() {
      return moment();
    },
    async sendComment() {
      let data = {
        body: this.comment,
      };
      let token = this.$getCookie("token");
      await this.$axios
        .$post(`video/${this.$route.params.videoID}/comment`, data, {
          headers: { Authorization: "Bearer " + token },
        })
        .then((rep) => {
          this.getComments();
        });
    },
    async getComments(page) {
      this.loading = true;

      let data = {};
      if (page) {
        this.currentPage = page;
        const pageValue = { page: page };
        Object.assign(data, pageValue);
      }

      let token = this.$getCookie("token");
      await this.$axios
        .$get(`video/${this.$route.params.videoID}/comments`, {
          params: data,
          headers: { Authorization: "Bearer " + token },
        })
        .then((rep) => {
          this.comments = rep.data;
          this.loading = false;
          this.pager = rep.pager;
        });
    },
    async getVideo() {
      this.loading = true;
      let token = this.$getCookie("token");
      await this.$axios
        .$get(`video/${this.$route.params.videoID}`, {
          headers: { Authorization: "Bearer " + token },
        })
        .then((rep) => {
          this.video = rep.data;
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
