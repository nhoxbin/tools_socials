import Vue from 'vue'
import VueResource from 'vue-resource'

/* settings to use vue-auth */
Vue.use(VueResource);
const URL = window.location.protocol + '//' + window.location.hostname + ':8000';
// Vue.http is the VueResource
Vue.http.options.root = URL;
// set headers to Vue.http request
Vue.http.interceptors.push((request, next) => {
	// request.headers.set('Accept', 'application/json');
	request.headers.set('Content-Type', 'application/json');
	request.headers.set('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
	next()
});
// ./ settings

let auth_path = '/api/auth/';
export const AUTH_CONFIG = {
	auth: require('@websanova/vue-auth/drivers/auth/bearer.js'),
	http: require('@websanova/vue-auth/drivers/http/vue-resource.1.x.js'),
	router: require('@websanova/vue-auth/drivers/router/vue-router.2.x.js'),
	tokenDefaultName: 'AuthToken',
	tokenStore: ['localStorage'],
	rolesVar: 'role_id',
	rememberMe: true,
	authRedirect: {
		path: '/session/login'
	},
	notFoundRedirect: {
		path: '/facebook/dashboard'
	},
	registerData: {
		url: auth_path + 'register',
		redirect: '/facebook/account',
		method: 'POST',
		autoLogin: true
	},
	loginData: {
		url: auth_path + 'login',
		method: 'POST',
		redirect: '/facebook/account',
		fetchUser: true,
	},
	logoutData: {
		url: auth_path + 'logout',
		method: 'POST',
		redirect: '/session/login',
		makeRequest: true
	},
	fetchData: {
		url: auth_path + 'user',
		method: 'GET',
		enabled: true
	},
	refreshData: {
		url: auth_path + 'refresh',
		method: 'GET',
		enabled: true,
		interval: 30
	},
	parseUserData(data) {
		return data || {}
	}
}