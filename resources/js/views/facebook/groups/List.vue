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
			
			<cards-user-profile v-if="friends != null"
				:friends="friends">
			</cards-user-profile>
		</v-container>
	</div>
</template>
<script>
import { mapGetters } from 'vuex'
// import UserProfile from "Components/UserProfile"

export default {
	components: {
		
	},
	data: function() {
		return {
			loading: false,
			provider_uid: '',
			friends: null,
			getWithType: 'me',
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