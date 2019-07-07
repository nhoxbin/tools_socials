<template>
	<div>
	  <page-title-bar></page-title-bar>

		<v-container grid-list-xl fluid py-0>
			<v-layout row wrap>
				<app-card
					:heading="$t('message.infoToGetListFriends')"
					colClasses="xl12 lg12 md12 sm12 xs12">
					<v-layout row wrap>
						<v-flex xs6 sm2>
							<v-text-field
								name="uid"
								label="ID Facebook"
								:disabled="getWithType === 'me'"
								v-model.lazy="provider_uid">
							</v-text-field>
						</v-flex>
						<v-flex xs4 sm2>
							<v-select
								hide-details
								:items="types"
								item-text="text"
								item-value="name"
								v-model="getWithType"
								label="Select"
								single-line
								menu-props="bottom">
							</v-select>
						</v-flex>
						<v-flex xs2 sm2>
							<v-btn class="gradient-success"
								@click.native="getListFriends(provider_uid)"
								:loading="loading"
								:disabled="loading">
								Lấy
							</v-btn>
						</v-flex>
					</v-layout>
				</app-card>
			</v-layout>

			<template v-if="friends != null">
				<v-layout row wrap>
		      <app-card
		        :heading="$t('message.infoReactions')"
		        colClasses="xl12 lg12 md12 sm12 xs12">
		        <v-chip text-color="white" color="light-blue">
		          Có {{ friends.data.length }} bạn trong danh sách
		        </v-chip>
		        <v-chip text-color="white" color="orange">
							0 Người trong danh sách unfriend
						</v-chip>
		      </app-card>
		    </v-layout>

				<v-layout row wrap>
		      <app-card
		        :heading="$t('message.friendsList')"
		        :fullBlock="true"
		        colClasses="xl12 lg12 md12 sm12 d-xs-full">
		        <v-card-title>Filter
		          <v-spacer></v-spacer>
		          <v-text-field
		            append-icon="search"
		            label="Search"
		            single-line
		            hide-details
		            v-model="search">
		          </v-text-field>
		        </v-card-title>
		        <v-data-table
		          :headers="headers"
		          :items="friends.data"
		          :search="search">
		    			<template v-slot:items="props">
		    				<td class="d-custom-flex align-items-center" :style="{minWidth: '200px'}">
		              <img width="30" height="30" :src="props.item.picture" class="img-responsive rounded-circle mr-3">
		              <span class="fs-14">{{ props.item.name }}</span>
		            </td>
		            <td>{{ props.item.id }}</td>
		            <td>{{ props.item.mobile_phone }}</td>
		            <td>{{ props.item.birthday }}</td>
		            <td>{{ props.item.hometown ? props.item.hometown.name : '' }}</td>
		            <td>{{ props.item.location ? props.item.location.name : '' }}</td>
		            <td class="justify-center layout px-0">
		              <v-btn small class="info"
		                :href="props.item.link" target="_blank">
		                Trang cá nhân
		              </v-btn>
		              <v-btn small class="error"
		                @click="onDeleteFriend(props.item)">
		                Hủy kết bạn
		              </v-btn>
		            </td>
		    			</template>
		          <template slot="pageText" slot-scope="{ pageStart, pageStop }">
		            Từ {{ pageStart }} tới {{ pageStop }} bạn
		          </template>
		        </v-data-table>
		      </app-card>
		    </v-layout>
			</template>

			<v-snackbar
         :top="y === 'top'"
         :timeout = "timeout"
         v-model="snackbar">
         {{ snackbarMessage }}
      </v-snackbar>

      <delete-confirmation-dialog
  			ref="deleteConfirmationDialog"
  			heading="Hủy kết bạn?"
  			message="Một đi không trở lại nha! Chắc chưa?"
  			@onConfirm="unfriend">
   		</delete-confirmation-dialog>
		</v-container>
	</div>
</template>
<script>
import { mapGetters } from 'vuex'

export default {
	data: function() {
		return {
			loading: false,
			search: '',
			provider_uid: '',
			snackbar: false,
			snackbarMessage: "",
			y: "top",
			timeout: 2000,
			friends: null,
			getWithType: 'me',
			headers: [
				{
					text: "Name",
					align: "left",
					sortable: false
				},
				{ text: "ID", sortable: false },
				{ text: "Phone", value: 'mobile_phone' },
				{ text: "Birthday", value: 'birthday' },
				{ text: "Quê Quán", sortable: false },
				{ text: "Vị trí hiện tại", sortable: false },
				{ text: "Actions", sortable: false }
			],
			types: [
		        { text: "ID của tôi", name: 'me' },
		        { text: "ID tùy chọn", name: 'custom' }
			]
		}
	},
	computed: {
		...mapGetters({account: 'facebookAccount'})
	},
	mounted() {
		this.provider_uid = this.account.provider_uid;
	},
	watch: {
		getWithType(value) {
			if (value === 'me') {
				this.provider_uid = this.account.provider_uid;
			}
		}
	},
	methods: {
		onDeleteFriend(friend) {
			this.$refs.deleteConfirmationDialog.openDialog();
			this.selectFriend = friend;
	    },
	    unfriend() {
			this.$refs.deleteConfirmationDialog.close();
			let index = this.friends.data.indexOf(this.selectFriend);

			Vue.http.get(route('facebook.friends.unfriend', id))
	        .then((response) => {
	          if (response.status == 200) {
	            this.selectFriend = null;
	            this.$delete(this.friends.data, index);
	            this.snackbar = true;
	            this.snackbarMessage = 'Đã tiêu diệt ' + this.friends[index].name + '!';
	          }
	        });
	    },
		getListFriends(id) {
			this.loading = true;
			Vue.http.get(route('facebook.friends.getList', id))
				.then((response) => {
					if (response.status == 200) {
						this.friends = response.body;
					} else {
						Vue.notify({
							group: 'app',
							type: 'error',
							text: 'Không lấy được danh sách.'
						});
					}
					this.loading = false;
				});
		}
	}
}
	
</script>