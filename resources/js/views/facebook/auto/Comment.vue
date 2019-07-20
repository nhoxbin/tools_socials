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
            - Ấn "Dừng Auto" sẽ trở lại trạng thái ban đầu!
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
            :href="`#auto-comment-${item.value}`">
            {{ item.text }}
          </v-tab>
        </v-tabs>
        <v-tabs-items v-model="tab">
          <v-tab-item v-for="(item, index) in items"
            :key="index"
            :value="`auto-comment-${item.value}`">
            <v-card flat>
              <v-card-title primary-title>
                <v-layout row wrap>
                  <v-flex md6 sm6 xs12>
                    <span class="small pt-4 d-block">Chọn tk muốn comment</span>
                  </v-flex>
                  <v-flex md6 sm6 xs12>
                    <v-select
                      :items="p_uids"
                      :disabled="data.length > 0"
                      v-model="selectedId"
                    ></v-select>
                  </v-flex>
                  <v-flex md6 sm6 xs12>
                    <span class="small pt-4 d-block">Số lượng bài viết muốn comment</span>
                  </v-flex>
                  <v-flex md6 sm6 xs12>
                    <v-text-field
                      type="number"
                      :disabled="data.length > 0"
                      v-model="limit"/>
                  </v-flex>
                  <v-flex md6 sm6 xs12 v-if="item.value === 'feed'">
                    <span class="small pt-4 d-block">ID người dùng</span>
                  </v-flex>
                  <v-flex md6 sm6 xs12 v-if="item.value === 'feed'">
                    <v-text-field
                      :disabled="data.length > 0"
                      v-model.lazy="uids">
                    </v-text-field>
                  </v-flex>
                  <v-flex md12 sm12 xs12>
                    <v-textarea outline
                      v-model.lazy="comment"
                      :disabled="is_start"
                      label="Nhập bình luận..."></v-textarea>
                  </v-flex>
                  <v-flex md12 sm12 xs12>
                    <v-text-field outline
                      v-model.lazy="url_picture"
                      :disabled="is_start"
                      label="URL hình ảnh..."/>
                  </v-flex>
                </v-layout>
              </v-card-title>
            </v-card>

            <v-layout row wrap>
              <v-flex md4 sm6 xs12>
                <v-btn v-if="data.length === 0"
                  color="info"
                  :loading="loading"
                  :disabled="loading"
                  @click="getData(selectedId, limit, uids)">Lấy bài viết
                </v-btn>
                <v-btn v-else
                  color="success"
                  :loading="loading"
                  :disabled="loading"
                  @click="startComment(selectedId, data, comment, url_picture)">Bắt đầu Comment
                </v-btn>
                <v-btn v-if="is_start"
                  color="warning"
                  @click="is_start = false">Dừng Auto!
                </v-btn>
              </v-flex>

                <v-spacer></v-spacer>
                
              <v-flex md4 sm6 xs12>
                <v-btn v-if="postHasCommented.length === 0"
                  color="warning"
                  :loading="loading"
                  :disabled="loading"
                  @click="getPostHasCommented(selectedId)">Lấy các bài viết đã Comment
                </v-btn>
                <v-btn v-else
                  color="error"
                  :loading="loading"
                  :disabled="loading"
                  @click="deleteComment(selectedId, postHasCommented)">Xóa
                </v-btn>
              </v-flex>
            </v-layout>
          </v-tab-item>
        </v-tabs-items>
      </app-card>
    </v-layout>
  </v-container>
</div>
</template>
<script>
import { sleep_loop } from "Helpers/helpers"

export default {
  data () {
    return {
      tab: 'auto-comment-feed',
      items: [
        { text: 'Home (NewFeed)', value: 'home' },
        { text: 'Feed (Wall)', value: 'feed' },
      ],
      is_start: false,
      loading: false,
      limit: 10,
      comment: '',
      data: [],
      uids: '100006508931079,100025602870873,100004931401259,100008978522629,100024151687853',
      postHasCommented: [],
      url_picture: ''
    }
  },
  computed: {
    p_uids() {
      let arr = [];
      _.forEach(this.$auth.user().facebook, (value, index) => {
        arr[index] = { text: value.name, value: value.provider_uid };
      });
      return arr;
    },
    selectedId: {
      get() {
        return this.p_uids[0].value;
      },
      set(val) {
        return val;
      }
    }
  },
  methods: {
    VueNotify(type, message) {
      Vue.notify({
        group: 'app',
        type: type,
        text: message
      });
    },
    getData(p_uid, limit, uids) {
      this.loading = true;
      if (this.tab === 'auto-comment-home') {
        Vue.http.post(route('facebook.auto.comment-home'), {
          p_uid: p_uid,
          limit: limit
        }).then((response) => response.json())
          .then((data) => {
            this.data = data;
            this.loading = false;
            this.VueNotify('success', 'Lấy bài viết thành công!');
          }, (error) => {
            this.VueNotify('error', error.body);
            this.loading = false;
          });
      } else if (this.tab === 'auto-comment-feed') {
        Vue.http.get(route('facebook.multi-threads.getMultiPostsOfMultiUser', {
          p_uid: p_uid,
          uids: uids,
          limit: limit
        })).then((response) => response.json())
          .then((data) => {
            this.data = data;
            this.loading = false;
            this.VueNotify('success', 'Lấy bài viết thành công!');
          }, (error) => {
            this.VueNotify('error', error.body);
            this.loading = false;
          });
      }
    },
    async startComment(p_uid, data, comment, url_picture) {
      this.is_start = true;
      this.loading = true;
      var self = this;
      await sleep_loop(data, [4, 7], async function(value, index) {
        if (self.is_start === false) {
          self.data = [];
          self.loading = false;
          alert('Đã dừng Auto!');
          return 'break';
        }
        await Vue.http.post(route('facebook.auto.comment', {
          p_uid: p_uid,
          id_post: value
        }), {
          comment: comment,
          url_picture: url_picture
        }).then((message) => {
            self.VueNotify('success', message.body);
          }, function(error) {
            self.VueNotify('error', error.body);
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