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
            :href="'#'+item.value">
            {{ item.text }}
          </v-tab>
        </v-tabs>
        <v-tabs-items v-model="tab">
          <v-tab-item v-for="(item, index) in items"
            :key="index"
            :value="item.value">
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
                      v-model="comment_fields.limit"/>
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
                      v-model.lazy="comment_fields.message"
                      :disabled="is_start"
                      label="Nhập bình luận..."></v-textarea>
                  </v-flex>
                  <v-flex md12 sm12 xs12>
                    <v-text-field outline
                      v-model.lazy="comment_fields.url_picture"
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
                  @click="getData(selectedId, comment_fields.limit, uids)">Lấy bài viết
                </v-btn>
                <v-btn v-else
                  color="success"
                  :loading="loading"
                  :disabled="loading"
                  @click="startComment(selectedId, tab, data, comment_fields.message, comment_fields.url_picture)">Bắt đầu Comment
                </v-btn>
                <v-btn v-if="is_start"
                  color="warning"
                  @click="is_start = false">Dừng Auto!
                </v-btn>
              </v-flex>

                <v-spacer></v-spacer>
                
              <v-flex md4 sm6 xs12>
                <v-btn v-if="postsHasCommented.length === 0"
                  color="warning"
                  :loading="loading"
                  :disabled="loading"
                  @click="getPostsHasCommented(selectedId, tab)">Lấy các bài viết đã Comment
                </v-btn>
                <v-btn v-else
                  color="error"
                  :loading="loading"
                  :disabled="loading"
                  @click="deleteComment(selectedId, tab,  postsHasCommented)">Xóa
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
      tab: 'home',
      items: [
        { text: 'Home (NewFeed)', value: 'home' },
        { text: 'Feed (Wall)', value: 'feed' },
      ],
      is_start: false,
      loading: false,
      data: [],
      uids: '100003912253555,100039723903639,100039846377478',
      postsHasCommented: [],
      comment_fields: {
        message: 'hello google',
        url_picture: 'https://vnreview.vn/image/18/17/65/1817650.jpg',
        limit: 10
      }
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
      let url;
      if (this.tab === 'home') {
        url = route('facebook.home.getPosts', {
          p_uid: p_uid,
          type: 'page',
          limit: limit
        });
      } else if (this.tab === 'feed') {
        url = route('facebook.feed.getPosts', {
          p_uid: p_uid,
          uids: uids,
          limit: limit
        });
      }
      Vue.http.get(url).then(response => response.json())
        .then(data => {
          this.data = data;
          this.loading = false;
          this.VueNotify('success', 'Lấy bài viết thành công!');
        }, error => {
          this.loading = false;
          this.VueNotify('error', error.body);
        });
    },
    startComment(p_uid, tab, data, message, url_picture) {
      this.is_start = true;
      this.loading = true;
      sleep_loop(data, [3, 6], async(value, index) => {
        if (this.is_start === false) {
          this.data = [];
          this.loading = false;
          this.is_start = false;
          alert('Đã dừng Auto!');
          return 'break';
        }

        await Vue.http.post(route('facebook.comment.store', {
          p_uid: p_uid,
        }), {
          type: tab,
          comment: message,
          url_picture: url_picture,
          posts_id: value
        }).then((message) => {
            this.VueNotify('success', message.body);
          }, (error) => {
            this.VueNotify('error', error.body);
          });

        if (index === data.length-1) {
          this.data = [];
          this.loading = false;
          alert('Xong!!!');
        }
      });
    },
    getPostsHasCommented(p_uid, type) {
      this.loading = true;
      Vue.http.get(route('facebook.comment.show', {
        p_uid: p_uid,
        type: type
      })).then(response => {
          this.postsHasCommented = response.body;
          this.loading = false;
          this.VueNotify('success', 'Lấy thành công.');
        }, error => {
          this.loading = false;
          this.VueNotify('error', error.body);
        });
    },
    deleteComment(p_uid, type, postsHasCommented) {
      this.loading = true;
      sleep_loop(postsHasCommented, 1, async(val, index) => {
        await Vue.http.delete(route('facebook.comment.delete', {
          p_uid: p_uid,
          type: type,
          commented_id: val
        })).then(status => {
            this.VueNotify('success', status.body);
          }, error => {
            this.VueNotify('error', error.body);
          });

        if (index === postsHasCommented.length-1) {
          this.postsHasCommented = [];
          this.loading = false;
          alert('Xong!!!');
        }
      });
    }
  }
}
</script>