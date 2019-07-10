/**
 * Auth Module
 */
import Vue from 'vue'
import webServices from 'WebServices'
import Nprogress from 'nprogress';
import router from '../../../router';

const state = {
	authToken: localStorage.getItem('AuthToken') || null
}

// getters
const getters = {
	getToken: state => {
		return state.authToken;
	}
}

// actions
const actions = {
	signUp(context, payload) {
		const {
			userDetail,
			auth
		} = payload;

		Nprogress.start();
		// after sign up success, auto login
		auth.register({
			body: JSON.stringify(userDetail),
			error: function(error) {
				if (typeof error.body === 'object') {
					let errors = error.body.errors;
					for (let err in errors) {
						for (let e in errors[err])
							context.commit('authNotify', {
								type: 'error',
								message: errors[err][e]
							});
					}
				} else {
					context.commit('authNotify', {
						type: 'error',
						message: error.body
					});
				}
			}
		});
	},
	signIn(context, payload) {
		return new Promise(async(resolve, reject) => {
			const {
				user,
				auth
			} = payload;

			Nprogress.start();
			await auth.login({
				body: JSON.stringify(user),
				success: function(response) {
					context.commit('authNotify', {
						type: 'success',
						message: response.body
					});
					resolve();
				},
				error: function(error) {
					context.commit('authNotify', {
						type: 'error',
						message: error.body
					});
				}
			});
		})
	},
	logoutUser(context, auth) {
		return new Promise(async(resolve, reject) => {
			Nprogress.start();
			await auth.logout();
			context.commit('logoutUser');
			resolve();
			Nprogress.done();
		})
	},
	sendEmail(context) {
		context.commit('sendEmailSuccessfully');
	},
	invalidEmail(context) {
		context.commit('invalidEmailSent');
	}
}

// mutations
const mutations = {
	authNotify(state, payload) {
		// actions <=> login or register
		// type <=> success or failure
		Nprogress.done();
		Vue.notify({
			group: 'loggedIn',
			type: payload.type,
			text: payload.message
		});
	},
	logoutUser(state) {
		state.authToken = null
		localStorage.removeItem('AuthToken');
	},
	sendEmailSuccessfully(state) {
		router.push("/session/login");
		Vue.notify({
			group: 'loggedIn',
			type: 'success',
			text: 'Email Sent Successfully!'
		});
	},
	invalidEmailSent(state) {
		Vue.notify({
			group: 'loggedIn',
			type: 'error',
			text: 'Please Enter Valid Email Id!'
		});
	}
}

export default {
	state,
	getters,
	actions,
	mutations
}