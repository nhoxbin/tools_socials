import Vue from 'vue'
import EventEmitter from 'eventemitter3'
import {
	store
} from '../store/store'

class AuthService {
	constructor() {
		this.login = this.login.bind(this)
		this.setSession = this.setSession.bind(this)
		this.logout = this.logout.bind(this)
		this.isAuthenticated = this.isAuthenticated.bind(this)
		this.authenticated = this.isAuthenticated()
		this.authNotifier = new EventEmitter()
	}

	login() {

	}

	handleAuthentication() {
		//
		this.auth0.parseHash((err, authResult) => {
			if (authResult && authResult.accessToken && authResult.idToken) {
				this.setSession(authResult)
				router.replace('/default/dashboard/ecommerce')
			} else if (err) {
				router.replace('/')
				console.log(err)
				alert(`Error: ${err.error}. Check the console for further details.`)
			}
		})
	}

	isAuthenticated() {
		// Check whether the current time is past the
		// access token's expiry time
		let expiresAt = JSON.parse(localStorage.getItem('expires_at'))
		return new Date().getTime() < expiresAt
	}

	setSession(authResult) {
		store.dispatch('signInUserWithAuth0', authResult)
		localStorage.setItem('isUserSigninWithAuth0', true)
			// Set the time that the access token will expire at
		let expiresAt = JSON.stringify(
			authResult.expiresIn * 1000 + new Date().getTime()
		)
		localStorage.setItem('access_token', authResult.accessToken)
		localStorage.setItem('id_token', authResult.idToken)
		localStorage.setItem('expires_at', expiresAt)
		this.authNotifier.emit('authChange', {
			authenticated: true
		})
	}

	logout() {
		store.dispatch('logoutUser')
			// Clear access token and ID token from local storage
		localStorage.removeItem('access_token')
		localStorage.removeItem('id_token')
		localStorage.removeItem('expires_at')
		this.userProfile = null
		this.authNotifier.emit('authChange', false)
			// navigate to the home route
		router.push('/session/login')
	}
}

export default AuthService;