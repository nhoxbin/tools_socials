<template>
<div>
  <page-title-bar></page-title-bar>
  <app-section-loader :status="loader"></app-section-loader>

  <div v-if="loader == false">
    <v-container fluid grid-list-xl py-0>
      <app-card v-if="!has_account || !isMember"
        :heading="$t('message.infoToLoginFB')"
        customClasses="mb-30">
        <v-form v-model="account_form.valid" ref="form" lazy-validation>
          <v-text-field
            label="Username"
            v-model="account_form.username"
            :rules="account_form.usernameRules">
          </v-text-field>
          <v-text-field
            label="Password"
            v-model="account_form.password"
            :rules="account_form.passwordRules"
            type="password">
          </v-text-field>
          <v-btn
            @click="submit"
            color="success">
            {{ $t("message.login") }}
          </v-btn>
          <v-btn @click="clear" color="primary">{{$t("message.clear")}}</v-btn>
        </v-form>
      </app-card>
      <app-card
        v-if="has_account"
        :heading="$t('message.yourInfoAccountFB')"
        :fullBlock="true"
        colClasses="xl12 lg12 md12 sm12 xs12">
          <div class="pa-3">
              <p class="mb-0">ID Facebook, Tên và trạng thái hoạt động của bạn</p>
          </div>
          <v-data-table :headers="headers" :items="account" hide-actions>
              <template slot="items" slot-scope="props">
                  <td>{{ props.item.name }}</td>
                  <td>{{ props.item.provider_uid }}</td>
                  <td>{{ props.item.is_active === 1 ? 'true':'false' }}</td>
                  <td>{{ props.item.status || 'Hoạt động' }}</td>
              </template>
          </v-data-table>
          <v-btn @click="updateAccountFacebook"
            :disabled="loading"
            :loading="loading"
            color="primary">
              Cập nhật tài khoản Facebook
          </v-btn>
      </app-card>
    </v-container>
  </div>
</div>
</template>
<script>
import { mapGetters } from 'vuex'

export default {
  data: function() {
    return {
      loader: false,
      loading: false,
      account_form: {
        valid: true,
        username: "",
        usernameRules: [
          v => !!v || "Name is required"
        ],
        password: "",
        passwordRules: [
          v => !!v || "E-mail is required"
        ]
      },
      headers: [
        { text: "Tên", align: "left", sortable: false, value: "name" },
        { text: "ID Facebook", sortable: false, value: "provider_uid" },
        { text: "Hoạt động", sortable: false, value: "is_active" },
        { text: "Trạng thái", sortable: false, value: "status" }
      ]
    }
  },
  computed: {
    ...mapGetters({
      account: 'facebookAccount',
      has_account: 'checkFacebookAccount'
    }),
    isMember() {
      if (this.$auth.user().role.name === 'Member') {
        return true;
      } else {
        return false;
      }
    }
  },
  methods: {
    VueNotify(type, message) {
      Vue.notify({
        group: 'app',
        type: type,
        text: message
      });
    },
    submit() {
      if (this.$refs.form.validate()) {
        this.loader = true;
        this.loginFacebook();
      }
    },
    clear() {
      this.$refs.form.reset();
    },
    loginFacebook: function() {
      const account = {
        username: this.account_form.username,
        password: this.account_form.password
      }
      
      Vue.http.post(route('facebook.login'), account)
        .then(response => {
          this.$store.dispatch('getAccountFB');
          this.VueNotify('success', response.body)
        }, error => {
          this.VueNotify('error', error.body)
        }).then(() => {
          this.loader = false;
        });
    },
    updateAccountFacebook: function() {
      this.loading = true;
      Vue.http.get(route('facebook.account.update'))
        .then(resp => {
          this.$store.dispatch('getAccountFB');
          this.loading = false;
          this.VueNotify('success', resp.body)
        });
    }
  }
}
</script>