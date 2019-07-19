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
const InboxEveryOne = () =>
	import ('Views/facebook/messenger/Inboxes');
const ListGroups = () =>
	import ('Views/facebook/groups/List');
const PostGroup = () =>
	import ('Views/facebook/groups/Post');
const AutoComment = () =>
	import ('Views/facebook/auto/Comment');

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
		path: '/facebook/dashboard',
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
		path: '/facebook/messenger/inboxes',
		component: InboxEveryOne,
		meta: {
			auth: true,
			requiresFacebookAccount: true,
			title: 'message.inbox',
			breadcrumb: [{
				breadcrumbInactive: 'Facebook / Messenger /'
			}, {
				breadcrumbActive: 'Inbox'
			}]
		}
	}, {
		path: '/facebook/groups/list',
		component: ListGroups,
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
	}, {
		path: '/facebook/auto/comment',
		component: AutoComment,
		meta: {
			auth: true,
			requiresFacebookAccount: true,
			title: 'message.autoComment',
			breadcrumb: [{
				breadcrumbInactive: 'Facebook / Auto /'
			}, {
				breadcrumbActive: 'Comment'
			}]
		}
	}]
}