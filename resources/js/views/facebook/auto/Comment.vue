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
            - Nếu số lượng comment muốn reply là 0 thì sẽ chỉ comment lên bài viết và nếu muốn reply comment thì sẽ chỉ reply comment (không comment lên bài viết)
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
                      v-model="comment_fields.limit_posts"/>
                  </v-flex>
                  <v-flex md6 sm6 xs12 v-if="item.value === 'feed'">
                    <span class="small pt-4 d-block">số comment muốn reply trong 1 bài viết</span>
                  </v-flex>
                  <v-flex md6 sm6 xs12 v-if="item.value === 'feed'">
                    <v-text-field
                      :disabled="data.length > 0"
                      v-model.lazy="comment_fields.limit_comments">
                    </v-text-field>
                  </v-flex>
                  <v-flex md6 sm6 xs12 v-if="item.value === 'feed'">
                    <span class="small pt-4 d-block">ID muốn comment</span>
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
                    <upload-btn
                      accept="image/*"
                      @file-update="onImageChange"
                      :disabled="is_start"
                      title="Chọn hình ảnh" />
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
                  @click="getData()">Lấy bài viết
                </v-btn>
                <v-btn v-else
                  color="success"
                  :loading="loading"
                  :disabled="loading"
                  @click="startComment()">Bắt đầu Comment
                </v-btn>
                <v-btn v-if="is_start"
                  color="warning"
                  @click="is_start = false">Dừng Auto!
                </v-btn>
              </v-flex>

                <v-spacer></v-spacer>
                
              <v-flex md4 sm6 xs12>
                <v-btn v-if="commented_id.length === 0"
                  color="warning"
                  :loading="loading"
                  :disabled="loading"
                  @click="getCommentedId()">Lấy các bài viết đã Comment
                </v-btn>
                <v-btn v-else
                  color="error"
                  :loading="loading"
                  :disabled="loading"
                  @click="deleteComment(selectedId, tab, commented_id)">Xóa
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
      data: ['118296656171131_118306016170195', '118296656171131_118313009502829', '117552879578842_117649856235811'],
      uids: '',
      commented_id: [],
      comment_fields: {
        message: '',
        file: undefined,
        url_picture: '',
        limit_posts: 10,
        limit_comments: 0
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
    onImageChange(file) {
      this.comment_fields.file = file;
    },
    getData() {
      this.loading = true;
      let url;
      if (this.tab === 'home') {
        url = route('facebook.home.getPosts', {
          p_uid: this.selectedId,
          type: 'page',
          limit_posts: this.comment_fields.limit_posts
        });
      } else if (this.tab === 'feed') {
        url = route('facebook.feed.getPosts', {
          p_uid: this.selectedId,
          uids: this.uids,
          limit_posts: this.comment_fields.limit_posts,
          limit_comments: this.comment_fields.limit_comments
        });
      }
      Vue.http.get(url).then(res => res.json())
        .then(data => {
          this.data = data;
          this.loading = false;
          this.VueNotify('success', 'Lấy bài viết thành công!');
        }, error => {
          this.loading = false;
          this.VueNotify('error', error.body);
        });
    },
    async startComment() {
      this.is_start = true;
      this.loading = true;
      
      if (typeof this.comment_fields.file === 'object') {
        var formData = new FormData();
        formData.append('file', this.comment_fields.file);

        await Vue.http.post(route('facebook.feed.uploadFile', this.selectedId), formData)
          .then(res => {
            this.comment_fields.url_picture = res.body;
            this.VueNotify('success', 'Lấy url hình ảnh thành công!');
          })
      }

      sleep_loop(this.data, 2, async(value, index) => {
        if (this.is_start === false) {
          this.data = [];
          this.loading = false;
          this.is_start = false;
          alert('Đã dừng Auto!');
          return 'break';
        }

        await Vue.http.post(route('facebook.feed.comment', {
          p_uid: this.selectedId,
          object_id: value
        }), {
          comment: this.comment_fields.message,
          url_picture: this.comment_fields.url_picture
        }).then(message => {
            this.VueNotify('success', message.body);
          }, error => {
            this.VueNotify('error', error.body);
          });

        if (index === this.data.length-1) {
          this.data = [];
          this.loading = false;
          this.is_start = false;
          alert('Xong!!!');
        }
      });
    },
    /*getCommentedId() {
      this.loading = true;
      Vue.http.get(route('facebook.comment.show', {
        p_uid: this.selectedId,
        type: this.tab
      })).then(response => {
          this.commented_id = response.body;
          this.loading = false;
          this.VueNotify('success', 'Lấy thành công ' + response.body.length + ' bình luận.');
        }, error => {
          this.loading = false;
          this.VueNotify('error', error.body);
        });
    },
    deleteComment(p_uid, type, commented_id) {
      this.loading = true;
      sleep_loop(commented_id, 1, async(val, index) => {
        await Vue.http.delete(route('facebook.comment.delete', {
          p_uid: this,
          type: type,
          commented_id: val
        })).then(status => {
            this.VueNotify('success', status.body);
          }, error => {
            this.VueNotify('error', error.body);
          });

        if (index === commented_id.length-1) {
          this.commented_id = [];
          this.loading = false;
          alert('Xong!!!');
        }
      });
    }*/
  }
}
</script>