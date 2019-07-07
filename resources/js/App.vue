<template>
  <v-app
	:dark="darkMode"
	id="inspire"
	:class="[{
		'collapse-sidebar': collapseSidebar
	}]"
  >
    	<router-view></router-view>
      <notifications 
        group="loggedIn" 
        animation-type="velocity"
        :animation="animation"
      />
      <notifications 
        group="app"
        animation-type="velocity"
        :animation="animation"
      />
  </v-app>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
  data() {
    return {
      animation: {
        enter: {
          opacity: [1, 0],
          translateX: [0, -300],
          scale: [1, 0.2]
        },
        leave: {
          opacity: 0,
          height: 0
        }
     }
    };
  },
  computed: {
    ...mapGetters([
      "darkMode",
      "collapseSidebar"
    ])
  }
};
</script>