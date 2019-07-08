// Sidebar Routers
export const menus = {
	'message.general': [{
		action: 'zmdi-view-dashboard',
		title: 'message.dashboard',
		active: false,
		items: [{
			title: 'message.ecommerce',
			path: '/dashboard',
			exact: true,
		}]
	}],
	'message.facebook': [{
		action: 'zmdi-account',
		path: '/account',
		title: 'message.account',
		active: true,
		items: null
	}, {
		action: 'zmdi-accounts',
		title: 'message.friends',
		active: false,
		items: [{
			title: 'message.list',
			path: '/friends/list'
		}, {
			title: 'message.reactions',
			path: '/friends/reactions'
		}]
	}, {
		action: 'zmdi-comments',
		title: 'message.messenger',
		active: false,
		items: [{
			title: 'message.rank',
			path: '/messenger/kount'
		}, {
			title: 'message.inbox',
			path: '/messenger/inboxes'
		}]
	}, {
		action: 'zmdi-comments',
		title: 'message.group',
		active: false,
		items: [{
			title: 'message.list',
			path: '/groups/list'
		}, {
			title: 'message.post',
			path: '/groups/post'
		}]
	}]
}