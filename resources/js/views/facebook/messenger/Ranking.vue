<template>
	<div>
		<page-title-bar></page-title-bar>
		<app-section-loader :status="loader"></app-section-loader>

		<v-container grid-list-xl fluid py-0 v-if="kount !== null">
			<v-layout row wrap>
				<app-card
					:heading="$t('message.standard')"
					:fullBlock="true"
					colClasses="xl12 lg12 md12 sm12 xs12">
					<div class="pa-3">
						<p class="mb-0">Xếp hạng <code>2579</code> tin nhắn</p>
					</div>
					<v-data-table
						v-bind:headers="headers"
						:items="kount"
						hide-actions>
						<template slot="items" slot-scope="props">
							<td>{{ '1' }}</td>
							<td>{{ props.item.participants[0].name }}</td>
							<td>{{ props.item.message_count }}</td>
							<td>
								<v-btn color="primary"
									:href="'https://www.facebook.com/' + props.item.link"
									target="_blank">
									Inbox
								</v-btn>
								<v-btn color="accent"
									:href="'https://www.facebook.com/' + props.item.participants[0].id"
									target="_blank">
									History
								</v-btn>
							</td>
						</template>
					</v-data-table>
				</app-card>
			</v-layout>
		</v-container>
	</div>
</template>
<script>
	
export default {
	data: function() {
		return {
			loader: true,
			headers: [
				{ text: 'Hạng', value: 'rank' },
				{ text: 'Tên', value: 'name', sortable: false },
				{ text: 'Tin nhắn', value: 'message_count' },
				{ text: 'Hành động', value: 'actions', sortable: false }
			],
			kount: null
		}
	},
	mounted() {
		Vue.http.get(route('facebook.messenger.inbox-kount'))
			.then((response) => {
				if (response.status == 204) {
					Vue.notify({
						group: 'app',
						type: 'error',
						text: 'Lỗi ko lấy được tin nhắn.'
					});
					return;
				} else {
					return response.json();
				}
			})
			.then((data) => {
				if (data != undefined) {
					this.kount = data;
				}
				this.loader = false;
			});
	}
}
</script>