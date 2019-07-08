<template>
<div>
	<page-title-bar></page-title-bar>

	<v-container grid-list-xl fluid py-0>
		<v-layout row wrap>
			<app-card
				:fullBlock="true"
				colClasses="xl12 lg12 md12 sm12 xs12">
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
					select-all
					class="elevation-1"
					v-model="selected"
					:headers="headers"
					:items="groups"
					:loading="loading"
					:search="search">
					<template v-slot:items="props">
						<td>
							<v-checkbox primary hide-details
								v-model="props.selected">
							</v-checkbox>
						</td>
						<td>{{ props.item.name }}</td>
						<td>{{ props.item.member_count }}</td>
						<td>{{ props.item.privacy }}</td>
						<td>{{ props.item.administrator }}</td>
						<td class="justify-center">
							<v-btn small color="info"
								target="_blank"
								:href="'https://fb.com/'+props.item.id">Link
							</v-btn>
						</td>
					</template>
					<template slot="pageText" slot-scope="{ pageStart, pageStop, itemsLength }">
						Đang xem {{ pageStart }} - {{ pageStop }} trong {{ itemsLength }} nhóm
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
			search: null,
			loading: false,
			selected: [],
			groups: [],
			headers: [
				{ text: 'Tên', value: 'name' },
				{ text: 'Số lượng thành viên', value: 'member_count' },
				{ text: 'Riêng tư', value: 'privacy' },
				{ text: 'Quyền hạn', value: 'administrator' },
				{ text: 'Hành động', value: 'actions', sortable: false }
			]
		}
	},
	mounted() {
		this.getListGroups();
	},
	methods: {
		getListGroups() {
			this.loading = true;
			Vue.http.get(route('facebook.groups.list'))
				.then((response) => {
					this.groups = response.body;
					this.loading = false;
				});
		}
	}
}
</script>