<template>
<div>
	<page-title-bar></page-title-bar>

	<v-container grid-list-xl fluid py-0>
		<v-layout row wrap>
			<app-card
				:heading="$t('message.chooseDate')"
				colClasses="xl12 lg12 md12 sm12 xs12">
				<v-layout row wrap>
					<v-flex xs12 sm6>
						<v-select
						  :items="date"
						  item-text="text"
						  item-name="value"
						  v-model="selectDateType"
						  label="Ngày"
						></v-select>
					</v-flex>
					<v-flex xs2 sm2>
						<v-btn class="gradient-success"
							@click.native="getReactions(selectDateType)"
							:loading="loading"
							:disabled="loading">
							Lấy
						</v-btn>
					</v-flex>
				</v-layout>
			</app-card>
		</v-layout>

		<template v-if="reactions != null">
			<v-layout row wrap>
				<app-card
			        :heading="$t('message.friendsList')"
			        :fullBlock="true"
			        :withTabs="true"
			        :tabs="[$t('message.myFriends'), $t('message.notMyFriends'), $t('message.all')]"
			        @onChangeTabCallback="optionsGet = $event"
			        colClasses="xl12 lg12 md12 sm12 d-xs-full">
			        <v-card-title>
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
						:items="filteredReactions"
						:search="search">
						<template v-slot:items="props">
							<td class="d-custom-flex align-items-center" style="min-width:300px">
								<img width="30" height="30" :src="props.item.picture"
									class="img-responsive rounded-circle mr-3">
								<span class="fs-14">{{ props.item.name }}</span>
							</td>
							<td>{{ props.item.id }}</td>
							<td>{{ props.item.reactions.like }}</td>
							<td>{{ props.item.reactions.love }}</td>
							<td>{{ props.item.reactions.haha }}</td>
							<td>{{ props.item.reactions.wow }}</td>
							<td>{{ props.item.reactions.sad }}</td>
							<td>{{ props.item.reactions.angry }}</td>
							<td class="justify-center layout" style="margin-bottom: 0">
								<v-btn small class="info" :href="props.item.link" target="_blank">
									Trang cá nhân
								</v-btn>
								<v-btn small class="error" @click="onDeleteFriend(props.item)" v-if="props.item.is_friend === 1">
									Hủy kết bạn
								</v-btn>
							</td>
						</template>
						<template slot="pageText" slot-scope="{ pageStart, pageStop, itemsLength }">
							Đang xem {{ pageStart }} - {{ pageStop }} trong {{ itemsLength }} người thả cảm xúc
						</template>
			        </v-data-table>
				</app-card>
	    	</v-layout>
		</template>
	</v-container>
</div>
</template>
<script>
export default {
	data: function() {
		return {
			loading: false,
			selectDateType: '1',
			optionsGet: 0,
			headers: [
				{ text: "Name", sortable: false },
				{ text: "ID", value: 'id', sortable: false },
				{ text: "Like", value: 'reactions.like' },
				{ text: "Love", value: 'reactions.love' },
				{ text: "Haha", value: 'reactions.haha' },
				{ text: "Wow", value: 'reactions.wow' },
				{ text: "Sad", value: 'reactions.sad' },
				{ text: "Angry", value: 'reactions.angry' },
				{
					text: "Actions",
					align: "center",
					sortable: false
				}
			],
			date: [
				{ text: '3 tháng', value: '1' },
				{ text: '6 tháng', value: '2' }
			],
			reactions: null,
			search: ''
		}
	},
	computed: {
		filteredReactions: function() {
			return this.reactions.filter(reaction => {
				let filter;
				switch(this.optionsGet) {
					case 0:
						filter = (reaction.is_friend == 1);
						break;
					case 1:
						filter = (reaction.is_friend == 0);
						break;
					case 2:
						filter = (reaction.is_friend == 0 || (reaction.is_friend == 1));
						break;
				}
				return filter;
			});
		}
	},
	methods: {
		getReactions(type) {
			this.loading = true;
			Vue.http.post(route('facebook.feed.reactions'), { selectDateType: type })
				.then((response) => {
					return response.json();
				})
				.then((data) => {
					this.reactions = data;
					this.loading = false;
				})
				.catch(function(error) {
					Vue.notify({
						group: 'app',
						type: 'error',
						text: error.body
					})
				});
		},
		onDeleteFriend(friend) {
			alert('Cumming Soon');
		}
	}
}
	
</script>