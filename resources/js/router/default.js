import Full from 'Container/Full'

const Admin = () =>
	import ('Views/pages/Blank');
const FacebookAccount = () =>
	import ('Views/facebook/Account');
const ListFriends = () =>
	import ('Views/facebook/friends/List');
const Reactions = () =>
	import ('Views/facebook/feed/Reactions');
const InboxKount = () =>
	import ('Views/facebook/messenger/Ranking');
const ListGroup = () =>
	import ('Views/facebook/groups/List');
const PostGroup = () =>
	import ('Views/facebook/groups/Post');

export default {
	path: '/',
	component: Full,
	redirect: '/facebook/account',
	children: [{
		path: '/admin',
		name: 'admin',
		component: Admin,
		meta: {
			auth: {
				roles: 2,
				redirect: {
					name: 'admin'
				},
				forbiddenRedirect: '/dashboard'
			}
		}
	}, {
		path: '/dashboard',
		component: Admin,
		name: 'dashboard',
		meta: {
			auth: true,
			requiresFacebookAccount: true,
			title: 'message.blank',
			breadcrumb: 'Dashboard'
		}
	}, {
		path: '/facebook/account',
		component: FacebookAccount,
		name: 'facebook.account',
		meta: {
			auth: true,
			title: 'message.account',
			breadcrumb: [{
				breadcrumbInactive: 'Facebook /'
			}, {
				breadcrumbActive: 'Account'
			}]
		}
	}, {
		path: '/facebook/friends/list',
		component: ListFriends,
		meta: {
			auth: true,
			requiresFacebookAccount: true,
			title: 'message.friendsList',
			breadcrumb: [{
				breadcrumbInactive: 'Facebook / Friends /'
			}, {
				breadcrumbActive: 'List'
			}]
		}
	}, {
		path: '/facebook/friends/reactions',
		component: Reactions,
		meta: {
			auth: true,
			requiresFacebookAccount: true,
			title: 'message.reactions',
			breadcrumb: [{
				breadcrumbInactive: 'Facebook / Wall /'
			}, {
				breadcrumbActive: 'Reactions'
			}]
		}
	}, {
		path: '/facebook/messenger/kount',
		component: InboxKount,
		meta: {
			auth: true,
			requiresFacebookAccount: true,
			title: 'message.rankInbox',
			breadcrumb: [{
				breadcrumbInactive: 'Facebook / Messenger /'
			}, {
				breadcrumbActive: 'Ranking'
			}]
		}
	}, {
		path: '/facebook/group/list',
		component: ListGroup,
		meta: {
			auth: true,
			requiresFacebookAccount: true,
			title: 'message.groupsList',
			breadcrumb: [{
				breadcrumbInactive: 'Facebook / Group /'
			}, {
				breadcrumbActive: 'List'
			}]
		}
	}, {
		path: '/facebook/group/post',
		component: PostGroup,
		meta: {
			auth: true,
			requiresFacebookAccount: true,
			title: 'message.groupsList',
			breadcrumb: [{
				breadcrumbInactive: 'Facebook / Group /'
			}, {
				breadcrumbActive: 'List'
			}]
		}
	}]
}