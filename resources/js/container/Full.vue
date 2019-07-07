<!-- App Main Structure -->
<template>
	<div class="app-default-layout">
		<template v-if="!$auth.ready()">
			<rotate-square2></rotate-square2>
		</template>
		<template v-else>
			<!-- App Header -->
			<app-header></app-header>
			<!-- App Main Content -->
			<v-content>
				<!-- App Router -->
				<transition name="router-anim" :enter-active-class="`animated ${selectedRouterAnimation}`">
					<router-view v-if="$auth.check()"></router-view>
				</transition>
			</v-content>
		</template>
	</div>
</template>

<script>
import { mapGetters } from "vuex";
import Header from "Components/Header/Header.vue";
import AppConfig from "Constants/AppConfig";

export default {
  data() {
    return {
    };
  },
  components: {
    appHeader: Header,
  },
  computed: {
    ...mapGetters(['selectedRouterAnimation'])
  },
  mounted() {
  }
};
</script>

<style scoped>
.app-default-layout {
  height: 100vh;
}
</style>
