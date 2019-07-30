require('./bootstrap');

window.Vue = require('vue');

import Vue from 'vue'
import Vuetify from 'vuetify'
import VueFullscreen from 'vue-fullscreen'
import VueBreadcrumbs from 'vue2-breadcrumbs'
import Notifications from 'vue-notification'
import DatePicker from 'vue2-datepicker'
import velocity from 'velocity-animate'
import Nprogress from 'nprogress'
import VueAuth from '@websanova/vue-auth'
import VueI18n from 'vue-i18n'
import {
	AUTH_CONFIG
} from './auth/config.js'


// global components
import GlobalComponents from './components/globalComponents'

// app.vue
import App from './App'

// router
import router from './router'

// themes
import primaryTheme from './themes/primaryTheme';

// messages
import messages from './lang';

// store
import {
	store
} from './store/store';

// include all css files
import './lib/VuelyCss'

router.beforeEach((to, from, next) => {
	Nprogress.start()
	next();
})

router.afterEach((from, next) => {
	Nprogress.done()
	setTimeout(() => {
		const contentWrapper = document.querySelector(".v-content__wrap");
		if (contentWrapper !== null) {
			contentWrapper.scrollTop = 0;
		}
	}, 200)
})

/* @websanova extension for using json web token auth */
// Vue.router and Vue.http is important
Vue.router = router;
Vue.use(VueAuth, AUTH_CONFIG);
// ./@websanova

Vue.use(Vuetify, {
	theme: store.getters.selectedTheme.theme
})
Vue.use(VueI18n)
Vue.use(Notifications, {
	velocity
})
Vue.use(VueBreadcrumbs)
Vue.use(VueFullscreen)
Vue.use(DatePicker)
Vue.use(GlobalComponents)

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));
// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

const i18n = new VueI18n({
	locale: store.getters.selectedLocale.locale,
	messages
})

const app = new Vue({
	store,
	i18n,
	router: Vue.router,
	render: h => h(App),
	components: {
		App
	}
}).$mount('#app');