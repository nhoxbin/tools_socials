(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[5],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/facebook/Account.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/views/facebook/Account.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vuex__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vuex */ "./node_modules/vuex/dist/vuex.esm.js");
function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; var ownKeys = Object.keys(source); if (typeof Object.getOwnPropertySymbols === 'function') { ownKeys = ownKeys.concat(Object.getOwnPropertySymbols(source).filter(function (sym) { return Object.getOwnPropertyDescriptor(source, sym).enumerable; })); } ownKeys.forEach(function (key) { _defineProperty(target, key, source[key]); }); } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {
      loader: false,
      loading: false,
      account_form: {
        valid: true,
        username: "",
        usernameRules: [function (v) {
          return !!v || "Name is required";
        }],
        password: "",
        passwordRules: [function (v) {
          return !!v || "E-mail is required";
        }]
      },
      headers: [{
        text: "Tên",
        align: "left",
        sortable: false,
        value: "name"
      }, {
        text: "ID Facebook",
        sortable: false,
        value: "provider_uid"
      }, {
        text: "Hoạt động",
        sortable: false,
        value: "is_active"
      }, {
        text: "Trạng thái",
        sortable: false,
        value: "status"
      }]
    };
  },
  computed: _objectSpread({}, Object(vuex__WEBPACK_IMPORTED_MODULE_0__["mapGetters"])({
    account: 'facebookAccount',
    has_account: 'checkFacebookAccount'
  }), {
    isMember: function isMember() {
      if (this.$auth.user().role.name === 'Member') {
        return true;
      } else {
        return false;
      }
    }
  }),
  methods: {
    VueNotify: function VueNotify(type, message) {
      Vue.notify({
        group: 'app',
        type: type,
        text: message
      });
    },
    submit: function submit() {
      if (this.$refs.form.validate()) {
        this.loader = true;
        this.loginFacebook();
      }
    },
    clear: function clear() {
      this.$refs.form.reset();
    },
    loginFacebook: function loginFacebook() {
      var _this = this;

      var account = {
        username: this.account_form.username,
        password: this.account_form.password
      };
      Vue.http.post(route('facebook.login'), account).then(function (response) {
        Vue.http.get(route('facebook.account.index')).then(function (resp) {
          _this.$auth.user().facebook = resp.body;

          _this.VueNotify('success', response.body);
        });
      }, function (error) {
        _this.VueNotify('error', error.body);
      }).then(function () {
        _this.loader = false;
      });
    },
    updateAccountFacebook: function updateAccountFacebook() {
      var _this2 = this;

      this.loading = true;
      Vue.http.get(route('facebook.account.update')).then(function (resp) {
        Vue.http.get(route('facebook.account.index')).then(function (response) {
          _this2.$auth.user().facebook = response.body;
          _this2.loading = false;

          _this2.VueNotify('success', resp.body);
        });
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/facebook/Account.vue?vue&type=template&id=44dcaa7a&":
/*!**************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/views/facebook/Account.vue?vue&type=template&id=44dcaa7a& ***!
  \**************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    [
      _c("page-title-bar"),
      _vm._v(" "),
      _c("app-section-loader", { attrs: { status: _vm.loader } }),
      _vm._v(" "),
      _vm.loader == false
        ? _c(
            "div",
            [
              _c(
                "v-container",
                { attrs: { fluid: "", "grid-list-xl": "", "py-0": "" } },
                [
                  !_vm.has_account || !_vm.isMember
                    ? _c(
                        "app-card",
                        {
                          attrs: {
                            heading: _vm.$t("message.infoToLoginFB"),
                            customClasses: "mb-30"
                          }
                        },
                        [
                          _c(
                            "v-form",
                            {
                              ref: "form",
                              attrs: { "lazy-validation": "" },
                              model: {
                                value: _vm.account_form.valid,
                                callback: function($$v) {
                                  _vm.$set(_vm.account_form, "valid", $$v)
                                },
                                expression: "account_form.valid"
                              }
                            },
                            [
                              _c("v-text-field", {
                                attrs: {
                                  label: "Username",
                                  rules: _vm.account_form.usernameRules
                                },
                                model: {
                                  value: _vm.account_form.username,
                                  callback: function($$v) {
                                    _vm.$set(_vm.account_form, "username", $$v)
                                  },
                                  expression: "account_form.username"
                                }
                              }),
                              _vm._v(" "),
                              _c("v-text-field", {
                                attrs: {
                                  label: "Password",
                                  rules: _vm.account_form.passwordRules,
                                  type: "password"
                                },
                                model: {
                                  value: _vm.account_form.password,
                                  callback: function($$v) {
                                    _vm.$set(_vm.account_form, "password", $$v)
                                  },
                                  expression: "account_form.password"
                                }
                              }),
                              _vm._v(" "),
                              _c(
                                "v-btn",
                                {
                                  attrs: { color: "success" },
                                  on: { click: _vm.submit }
                                },
                                [
                                  _vm._v(
                                    "\r\n            " +
                                      _vm._s(_vm.$t("message.login")) +
                                      "\r\n          "
                                  )
                                ]
                              ),
                              _vm._v(" "),
                              _c(
                                "v-btn",
                                {
                                  attrs: { color: "primary" },
                                  on: { click: _vm.clear }
                                },
                                [_vm._v(_vm._s(_vm.$t("message.clear")))]
                              )
                            ],
                            1
                          )
                        ],
                        1
                      )
                    : _vm._e(),
                  _vm._v(" "),
                  _vm.has_account
                    ? _c(
                        "app-card",
                        {
                          attrs: {
                            heading: _vm.$t("message.yourInfoAccountFB"),
                            fullBlock: true,
                            colClasses: "xl12 lg12 md12 sm12 xs12"
                          }
                        },
                        [
                          _c("div", { staticClass: "pa-3" }, [
                            _c("p", { staticClass: "mb-0" }, [
                              _vm._v(
                                "ID Facebook, Tên và trạng thái hoạt động của bạn"
                              )
                            ])
                          ]),
                          _vm._v(" "),
                          _c("v-data-table", {
                            attrs: {
                              headers: _vm.headers,
                              items: _vm.account,
                              "hide-actions": ""
                            },
                            scopedSlots: _vm._u(
                              [
                                {
                                  key: "items",
                                  fn: function(props) {
                                    return [
                                      _c("td", [
                                        _vm._v(_vm._s(props.item.name))
                                      ]),
                                      _vm._v(" "),
                                      _c("td", [
                                        _vm._v(_vm._s(props.item.provider_uid))
                                      ]),
                                      _vm._v(" "),
                                      _c("td", [
                                        _vm._v(
                                          _vm._s(
                                            props.item.is_active === 1
                                              ? "true"
                                              : "false"
                                          )
                                        )
                                      ]),
                                      _vm._v(" "),
                                      _c("td", [
                                        _vm._v(
                                          _vm._s(
                                            props.item.status || "Hoạt động"
                                          )
                                        )
                                      ])
                                    ]
                                  }
                                }
                              ],
                              null,
                              false,
                              3791190932
                            )
                          }),
                          _vm._v(" "),
                          _c(
                            "v-btn",
                            {
                              attrs: {
                                disabled: _vm.loading,
                                loading: _vm.loading,
                                color: "primary"
                              },
                              on: { click: _vm.updateAccountFacebook }
                            },
                            [
                              _vm._v(
                                "\r\n              Cập nhật tài khoản Facebook\r\n          "
                              )
                            ]
                          )
                        ],
                        1
                      )
                    : _vm._e()
                ],
                1
              )
            ],
            1
          )
        : _vm._e()
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/views/facebook/Account.vue":
/*!*************************************************!*\
  !*** ./resources/js/views/facebook/Account.vue ***!
  \*************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Account_vue_vue_type_template_id_44dcaa7a___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Account.vue?vue&type=template&id=44dcaa7a& */ "./resources/js/views/facebook/Account.vue?vue&type=template&id=44dcaa7a&");
/* harmony import */ var _Account_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Account.vue?vue&type=script&lang=js& */ "./resources/js/views/facebook/Account.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Account_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Account_vue_vue_type_template_id_44dcaa7a___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Account_vue_vue_type_template_id_44dcaa7a___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/views/facebook/Account.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/views/facebook/Account.vue?vue&type=script&lang=js&":
/*!**************************************************************************!*\
  !*** ./resources/js/views/facebook/Account.vue?vue&type=script&lang=js& ***!
  \**************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Account_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./Account.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/facebook/Account.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Account_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/views/facebook/Account.vue?vue&type=template&id=44dcaa7a&":
/*!********************************************************************************!*\
  !*** ./resources/js/views/facebook/Account.vue?vue&type=template&id=44dcaa7a& ***!
  \********************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Account_vue_vue_type_template_id_44dcaa7a___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./Account.vue?vue&type=template&id=44dcaa7a& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/facebook/Account.vue?vue&type=template&id=44dcaa7a&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Account_vue_vue_type_template_id_44dcaa7a___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Account_vue_vue_type_template_id_44dcaa7a___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);