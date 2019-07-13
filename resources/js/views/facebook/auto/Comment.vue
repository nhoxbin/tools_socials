<template>
<div>
  <page-title-bar></page-title-bar>

  <v-container fluid grid-list-xl pt-0>
    <v-layout row wrap>
      <app-card
        heading="Chức năng"
        fullBlock="true"
        :withTabs="true"
        :tabs="['Filter', 'Filter']"
        colClasses="md12 sm12 xs12">
        <v-tabs v-model="tab">
          <v-tab v-for="(item, index) in items"
            :key="index"
            :href="`#auto-comment-${item}`">
            {{ item }}
          </v-tab>
        </v-tabs>
        <v-tabs-items v-model="tab">
          <v-tab-item v-for="(item, index) in items"
            :key="index"
            :value="`auto-comment-${item}`">
            <v-card flat>
              <v-card-title primary-title>
                <v-layout row wrap>
                  <v-flex md4 sm6 xs12>
                    <span class="small pt-4 d-block">Chọn tk muốn comment</span>
                  </v-flex>
                  <v-flex md4 sm6 xs12>
                    <v-select
                      :items="ids"
                      :disabled="posts.length > 0"
                      v-model="selectedId"
                    ></v-select>
                  </v-flex>
                </v-layout>
	                <v-flex md12 sm12 xs12>
	                  <v-textarea outline v-model.lazy="comment" label="Nhập bình luận..."></v-textarea>
	                </v-flex>
              </v-card-title>
            </v-card>
          </v-tab-item>
        </v-tabs-items>
        <v-btn v-if="posts.length === 0"
          color="info"
          :loading="loading"
          :disabled="loading"
          @click="getPosts(selectedId)">Lấy bài viết
        </v-btn>
        <v-btn v-else
          color="success"
          :loading="loading"
          :disabled="loading"
          @click="startComment(uid, posts, comment)">Bắt đầu Comment
        </v-btn>
        <v-btn v-if="is_start"
          color="success"
          :loading="loading"
          :disabled="loading"
          @click="!is_start">Dừng Auto!
        </v-btn>
      </app-card>
    </v-layout>
  </v-container>
</div>
</template>
<script>
import { sleep_loop } from "Helpers/helpers";

export default {
  data () {
    return {
      tab: 'auto-comment-home',
      is_start: true,
      items: ['home'],
      comment: '',
      loading: false,
      posts: []
    }
  },
  computed: {
    ids() {
      var arr = [];
      _.forEach(this.$auth.user().facebook, (value, index) => {
        if (value.is_active) {
          arr[index] = { text: value.name, value: value.provider_uid };
        }
      });
      if (arr.length > 1) {
        arr[arr.length] = { text: 'Ngẫu nhiên', value: 'random' };
      }
      return arr;
    },
    selectedId: {
      get() {
        if (this.ids[this.ids.length-1].text === 'random') {
          return this.ids[this.ids.length-1].value;
        } else {
          return this.ids[0].value;
        }
      },
      set(val) {
        return val;
      }
    }
  },
  methods: {
    getPosts(uid) {
      this.loading = true;
      Vue.http.post(route('facebook.auto.comment'), {
        uid: uid,
      }).then((response) => response.json())
        .then((posts) => {
          this.posts = posts;
          this.loading = false;
        }, function(error) {
          Vue.notify({
            group: 'app',
            type: 'error',
            text: error.body
          });
        });
    },
    startComment(uid, posts, comment) {
      sleep_loop(posts, [7, 15], (val, index) => {
        Vue.http.post(route('facebook.auto.comment'), {
          uid: uid,
          id_post: val,
          comment: comment
        }).then((response) => response.json())
          .then((response) => {
            console.log(response);
          }, function(error) {
            Vue.notify({
              group: 'app',
              type: 'error',
              text: error.body
            });
          })
          .then(() => {
            if (this.is_start === false) {
              return 'break';
            }
          });
      });
    },
    stopComment() {

    }
  }
}
</script>

<style lang="css" scoped>
</style>