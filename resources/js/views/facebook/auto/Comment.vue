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
                  <v-flex md2 sm4 xs6>
                    <span class="small pt-4 d-block">Số lượng bài viết</span>
                  </v-flex>
                  <v-flex md2 sm4 xs6>
                    <v-text-field v-model.lazy="homeLimitPosts"></v-text-field>
                  </v-flex>
                </v-layout>
	                <v-flex md12 sm12 xs12>
	                  <v-textarea outline v-model.lazy="comment" label="Nhập bình luận..."></v-textarea>
	                </v-flex>
              </v-card-title>
            </v-card>
          </v-tab-item>
        </v-tabs-items>
      </app-card>
    </v-layout>
  </v-container>
</div>
</template>

<script>
export default {
  data () {
    return {
      homeLimitPosts: 10,
      tab: 'auto-comment-home',
      items: ['home'],
      comment: null
    }
  },
  methods: {
    startComment(comment) {
    	Vue.http.post(route('facebook.auto.comment'), {homeLimitPosts: homeLimitPosts, comment: comment})
    		.then((response) => response.json())
    		.then((response) => {
    			console.log(response);
    		}, function(error) {
    			Vue.notify({
    				group: 'app',
    				type: 'error',
    				text: error.body
    			})
    		});
    }
  }
}
</script>

<style lang="css" scoped>
</style>