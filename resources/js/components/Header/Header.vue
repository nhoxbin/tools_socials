<!-- Header Structure -->
<template>
  <div>
    <v-navigation-drawer
      v-if="!horizontal"
      app
      fixed
      v-model="drawer"
      :mini-variant.sync="collapseSidebar"
      mini-variant-width="70"
      :width="250"
      class="Vuely-sidebar"
      :style="{backgroundImage: 'url(' + selectedSidebarBgImage.url + ')'}"
      :class="{'background-none': !backgroundImage}">
      <!-- App Sidebar -->
      <app-sidebar></app-sidebar>
    </v-navigation-drawer>
    <v-toolbar 
      class="Vuely-toolbar"
      app
      :color="activeHeaderFilter.class"
      fixed>
      <div class="d-custom-flex align-items-center navbar-left">
        <div v-if="!horizontal">
          <v-toolbar-side-icon icon large @click.stop="drawer = !drawer" class="v-step-0"></v-toolbar-side-icon>
        </div>
        <div class="site-logo-wrap d-custom-flex ml-0 align-items-center" v-else>
          <router-link to="/" class="grayish-blue site-logo-img">
            <img src="/static/img/site-logo.png" alt="site logo" width="100" height="30">
          </router-link>
        </div>
      </div>
      <div class="navbar-right">
        <user></user>
      </div>
    </v-toolbar>
  </div>
</template>

<script>
import Sidebar from "../Sidebar/Sidebar.vue";
import LanguageProvider from "./LanguageProvider";
import User from "./User";
import { getCurrentAppLayout } from "Helpers/helpers";
import { mapGetters } from "vuex";

export default {
  props: {
    horizontal: {
      default: false,
      type: Boolean
    }
  },
  data() {
    return {
      collapsed: false, // collapse sidebar
      drawer: null, // sidebar drawer default true
      sidebarImages: "", // sidebar background images
      enableDefaultSidebar: false
    };
  },
  computed: {
    ...mapGetters([
      "backgroundImage",
      "selectedSidebarBgImage",
      "darkMode",
      "collapseSidebar",
      "activeHeaderFilter"
    ])
  },
  methods: {
    sidebarPath(link){
      this.$store.dispatch('setActiveMenuGroup',{ pathURL: link });
      this.$router.push(this.getMenuLink(link));
    },
    getMenuLink(link) {
      return "/" + getCurrentAppLayout(this.$router) + link;
    }
  },
  components: {
    appSidebar: Sidebar,
    LanguageProvider,
    User
  }
};
</script>
