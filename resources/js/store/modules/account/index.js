const state = {
	account: JSON.parse(localStorage.getItem('FacebookAccount'))
}

const getters = {
	facebookAccount(state) {
		return state.account;
	},
	checkFacebookAccount(state) {
		return state.account != null ? true : false;
	}
}

const actions = {
	loginFacebook(context, FacebookAccount) {
		return new Promise(async(resolve, reject) => {
			await Vue.http.post(route('facebook.login'), FacebookAccount).then(async(response) => {
				// login facebook success
				await context.dispatch('getFacebookAccount');
				context.commit('accountNotify', {
					type: 'success',
					message: response.body
				});
			}).catch(function(response) {
				context.commit('accountNotify', {
					type: 'error',
					message: response.body
				});
			});
			resolve();
		})
	},
	getFacebookAccount(context) {
		Vue.http.get(route('facebook.account.show'))
			.then((response) => {
				if (response.status == 200) {
					context.commit('storeAccount', response.body);
				} else {
					context.commit('accountNotify', {
						type: 'error',
						message: 'Bạn chưa có tài khoản Facebook, vui lòng đăng nhập!'
					});
				}
			});
	},
	deleteFacebookAccount(context) {
		context.commit('deleteAccount');
	}
}

const mutations = {
	accountNotify(state, payload) {
		Vue.notify({
			group: 'loggedIn',
			type: payload.type,
			text: payload.message
		});
	},
	storeAccount(state, account) {
		state.account = account;
		localStorage.setItem('FacebookAccount', JSON.stringify(account));
	},
	deleteAccount(state) {
		state.FacebookAccount = null;
		localStorage.removeItem('FacebookAccount');
	}
}

export default {
	state,
	getters,
	actions,
	mutations
}