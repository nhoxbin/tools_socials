import Vue from 'vue'
import VueRouter from 'vue-router'

//routes
import defaultRoutes from './default';

// session components
const SignUpOne = () =>
	import ('Views/session/SignUpOne');
const LoginOne = () =>
	import ('Views/session/LoginOne');
const LockScreen = () =>
	import ('Views/session/LockScreen');
const ForgotPassword = () =>
	import ('Views/session/ForgotPassword');
const ResetPassword = () =>
	import ('Views/session/ResetPassword');

Vue.use(VueRouter)

export default new VueRouter({
	mode: 'history',
	routes: [
		defaultRoutes, {
			path: '/session/sign-up',
			component: SignUpOne,
			meta: {
				auth: false,
				title: 'message.signUp',
				breadcrumb: null
			}
		}, {
			path: '/session/login',
			component: LoginOne,
			meta: {
				auth: false,
				title: 'message.login',
				breadcrumb: null
			}
		}, {
			path: '/session/forgot-password',
			component: ForgotPassword,
			meta: {
				title: 'message.forgotPassword',
				breadcrumb: null
			}
		}, {
			path: '/session/reset-password',
			component: ResetPassword,
			meta: {
				title: 'message.resetPassword',
				breadcrumb: null
			}
		}
	]
})