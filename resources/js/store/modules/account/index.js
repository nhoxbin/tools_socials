const state = {
	account: localStorage.getItem('FacebookAccount') !== '' ?
		JSON.parse(localStorage.getItem('FacebookAccount')) : null
}

const getters = {
	facebookAccount(state) {
		return state.account;
	},
	checkFacebookAccount(state) {
		return state.account === null ? false : true;
	}
}

const actions = {
	getAccountFB(context) {
		Vue.http.get(route('facebook.account.show'))
			.then((response) => {
				if (response.body.length > 0) {
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
		state.account = null;
		localStorage.removeItem('FacebookAccount');
	}
}

export default {
	state,
	getters,
	actions,
	mutations
}