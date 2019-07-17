<template>
<div>
  <page-title-bar></page-title-bar>

  <v-container fluid grid-list-xl pt-0>
    <v-layout row wrap>
      <app-card
        heading="Một số lưu ý"
        colClasses="md12 sm12 xs12">
        <v-list>
          <v-list-tile-title>
          - Đây là phần mềm tự động nên khi ấn "Lấy bài viết" sẽ tự động lấy các bài viết ở newfeed
          </v-list-tile-title>
          <v-list-tile-title>
            - Khi ấn "Bắt đầu comment" sẽ hiện ra nút "Dừng Auto". Phần mềm sẽ tự động chạy và comment vào những bài viết đã lấy được
          </v-list-tile-title>
          <v-list-tile-title>
            - Khi ấn vào nút "Dừng Auto" phần mềm sẽ dừng tự động bình luận và trở lại trạng thái ban đầu để lấy bài viết
          </v-list-tile-title>
        </v-list>
      </app-card>

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
                  <v-flex md6 sm6 xs12>
                    <span class="small pt-4 d-block">Nhập số lượng bài viết muốn comment</span>
                  </v-flex>
                  <v-flex md6 sm6 xs12>
                    <v-text-field
                      type="number"
                      :disabled="posts.length > 0"
                      v-model="limitPosts"
                    ></v-text-field>
                  </v-flex>
                  <v-flex md6 sm6 xs12>
                    <span class="small pt-4 d-block">Chọn tk muốn comment</span>
                  </v-flex>
                  <v-flex md6 sm6 xs12>
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

        <v-layout row wrap>
          <v-flex md3 sm3 xs3>
            <v-btn v-if="posts.length === 0"
              color="info"
              :loading="loading"
              :disabled="loading"
              @click="getPosts(selectedId, limitPosts)">Lấy bài viết
            </v-btn>
            <v-btn v-else
              color="success"
              :loading="loading"
              :disabled="loading"
              @click="startComment(selectedId, posts, comment)">Bắt đầu Comment
            </v-btn>
            <v-btn v-if="is_start"
              color="warning"
              @click="is_start = false">Dừng Auto!
            </v-btn>
          </v-flex>

            <v-spacer></v-spacer>
            
          <v-flex md4 sm4 xs4>
            <v-btn v-if="postHasCommented.length === 0"
              color="warning"
              :loading="loading"
              :disabled="loading"
              @click="getPostHasCommented(selectedId)">Lấy các bài viết đã Comment</v-btn>
            <v-btn v-else
              color="error"
              :loading="loading"
              :disabled="loading"
              @click="deleteComment(selectedId, postHasCommented)">Xóa tất cả Comment</v-btn>
          </v-flex>
        </v-layout>
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
      is_start: false,
      items: ['home'],
      comment: '',
      loading: false,
      posts: [],
      limitPosts: 50,
      postHasCommented: []
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
      return arr;
    },
    selectedId: {
      get() {
        return this.ids[0].value;
      },
      set(val) {
        return val;
      }
    }
  },
  methods: {
    getPosts(uid, limitPosts) {
      this.loading = true;
      Vue.http.post(route('facebook.auto.comment'), {
        uid: uid,
        limitCommentPosts: limitPosts
      }).then((response) => response.json())
        .then((posts) => {
          this.posts = posts;
          this.loading = false;
          Vue.notify({
            group: 'app',
            type: 'success',
            text: 'Lấy bài viết thành công!'
          });
        }, (error) => {
          Vue.notify({
            group: 'app',
            type: 'error',
            text: error.body
          });
          this.loading = false;
        });
    },
    startComment(uid, posts, comment) {
      this.is_start = true;
      this.loading = true;
      sleep_loop(posts, [5, 10], (val, index) => {
        if (this.is_start === false) {
          this.posts = [];
          this.loading = false;
          alert('Đã dừng Auto!');
          return 'break';
        }
        Vue.http.post(route('facebook.auto.comment'), {
          uid: uid,
          id_post: val.id,
          comment: comment
        }).then((status) => {
            Vue.notify({
              group: 'app',
              type: 'success',
              text: status.body + ' vào bài viết của ' + val.from.name
            });
          }, function(error) {
            Vue.notify({
              group: 'app',
              type: 'error',
              text: error.body
            });
          });
      });
    },
    getPostHasCommented(uid) {
      this.loading = true;
      Vue.http.get(route('facebook.auto.delete-comment', uid)).then(response => {
          this.postHasCommented = response.body;
          this.loading = false;
          Vue.notify({
            group: 'app',
            type: 'success',
            text: 'Lấy những bài đã bình luận thành công, sẵn sàng xóa!'
          });
        });
    },
    async deleteComment(uid, postHasCommented) {
      this.loading = true;
      await sleep_loop(postHasCommented, 2, async(val, index) => {
        await Vue.http.post(route('facebook.auto.delete-comment', {uid: uid}), {
          commented_id: val
        }).then(status => {
            Vue.notify({
              group: 'app',
              type: 'success',
              text: status.body
            });
          }, error => {
            Vue.notify({
              group: 'app',
              type: 'error',
              text: error.body
            });
          });

        if (index === postHasCommented.length-1) {
          this.postHasCommented = [];
          this.loading = false;
        }
      });
    }
  }
}
</script>

<style lang="css" scoped>
</style>