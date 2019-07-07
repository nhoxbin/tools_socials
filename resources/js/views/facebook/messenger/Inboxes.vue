<template>
	<div>
	    <page-title-bar></page-title-bar>
		
		<v-container grid-list-xl fluid pt-0>
			<app-card
				:heading="$t('message.getListFriendsWithID')"
				contentCustomClass="input-label"
			>
				<v-layout row wrap>
					<v-flex xs6 sm2>
						<v-text-field
							name="uid"
							label="ID Facebook"
							:disabled="getWithType === 'me'"
							v-model="provider_uid">
						</v-text-field>
					</v-flex>
					<v-flex xs4 sm2>
						<v-select
							hide-details
							:items="items"
							item-text="text"
							item-value="name"
							v-model="getWithType"
							label="Select"
							single-line
							menu-props="bottom">
						</v-select>
					</v-flex>
					<v-flex xs2 sm2>
						<v-btn color="primary" raised>Lấy</v-btn>
					</v-flex>

						<!-- 6/6 -->

					<v-flex xs4 sm4>
						<v-range-slider
							v-model="time"
							hide-details
							thumb-size
							:max="42"
							:min="6"
							:step="3"
						></v-range-slider>
					</v-flex>
					<v-flex xs4 sm2>
						<v-card-text>
							{{ time[0] }} ~ {{ time[1] }} Giây
						</v-card-text>
					</v-flex>
				</v-layout>
				<v-layout row wrap>
					<v-flex xs6 sm3>
						<v-card color="primary" class="theme--dark">
							<v-card-text>3</v-card-text>
						</v-card>
					</v-flex>
					<v-flex xs6 sm3>
						<v-card color="primary" class="theme--dark">
							<v-card-text>1</v-card-text>
						</v-card>
					</v-flex>
				</v-layout>
			</app-card>
		</v-container>
	</div>
</template>
<script>
import { mapGetters } from 'vuex'

export default {
	data: function() {
		return {
			getWithType: 'me',
			provider_uid: '',
			time: [10, 25],
			items: [
		        { text: "ID của tôi", name: 'me' },
		        { text: "ID tùy chọn", name: 'custom' }
		    ],
		}
	},
	computed: {
		...mapGetters({account: 'facebookAccount'})
	},
	mounted() {
		this.provider_uid = JSON.parse(this.account).provider_uid;
	},
	watch: {
		getWithType(value) {
			if (value === 'me') {
				// this.myId();
			}
		}
	},
	methods: {
		
	}
}
	
</script>