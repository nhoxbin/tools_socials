(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[8],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/facebook/friends/List.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/views/facebook/friends/List.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************/
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
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {
      loading: false,
      search: '',
      provider_uid: '',
      snackbar: false,
      snackbarMessage: "",
      y: "top",
      timeout: 2000,
      friends: null,
      getWithType: 'me',
      headers: [{
        text: "Name",
        align: "left",
        sortable: false
      }, {
        text: "ID",
        sortable: false
      }, {
        text: "Phone",
        value: 'mobile_phone'
      }, {
        text: "Birthday",
        value: 'birthday'
      }, {
        text: "Quê Quán",
        sortable: false
      }, {
        text: "Vị trí hiện tại",
        sortable: false
      }, {
        text: "Actions",
        sortable: false
      }],
      types: [{
        text: "ID của tôi",
        name: 'me'
      }, {
        text: "ID tùy chọn",
        name: 'custom'
      }]
    };
  },
  computed: _objectSpread({}, Object(vuex__WEBPACK_IMPORTED_MODULE_0__["mapGetters"])({
    account: 'facebookAccount'
  })),
  mounted: function mounted() {
    this.provider_uid = this.account.provider_uid;
  },
  watch: {
    getWithType: function getWithType(value) {
      if (value === 'me') {
        this.provider_uid = this.account.provider_uid;
      }
    }
  },
  methods: {
    onDeleteFriend: function onDeleteFriend(friend) {
      this.$refs.deleteConfirmationDialog.openDialog();
      this.selectFriend = friend;
    },
    unfriend: function unfriend() {
      var _this = this;

      this.$refs.deleteConfirmationDialog.close();
      var index = this.friends.data.indexOf(this.selectFriend);
      Vue.http.get(route('facebook.friends.unfriend', id)).then(function (response) {
        if (response.status == 200) {
          _this.selectFriend = null;

          _this.$delete(_this.friends.data, index);

          _this.snackbar = true;
          _this.snackbarMessage = 'Đã tiêu diệt ' + _this.friends[index].name + '!';
        }
      });
    },
    getListFriends: function getListFriends(id) {
      var _this2 = this;

      this.loading = true;
      Vue.http.get(route('facebook.friends.getList', id)).then(function (response) {
        if (response.status == 200) {
          _this2.friends = response.body;
        } else {
          Vue.notify({
            group: 'app',
            type: 'error',
            text: 'Không lấy được danh sách.'
          });
        }

        _this2.loading = false;
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/facebook/friends/List.vue?vue&type=template&id=637fe5b2&":
/*!*******************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/views/facebook/friends/List.vue?vue&type=template&id=637fe5b2& ***!
  \*******************************************************************************************************************************************************************************************************************/
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
      _c(
        "v-container",
        { attrs: { "grid-list-xl": "", fluid: "", "py-0": "" } },
        [
          _c(
            "v-layout",
            { attrs: { row: "", wrap: "" } },
            [
              _c(
                "app-card",
                {
                  attrs: {
                    heading: _vm.$t("message.infoToGetListFriends"),
                    colClasses: "xl12 lg12 md12 sm12 xs12"
                  }
                },
                [
                  _c(
                    "v-layout",
                    { attrs: { row: "", wrap: "" } },
                    [
                      _c(
                        "v-flex",
                        { attrs: { xs6: "", sm2: "" } },
                        [
                          _c("v-text-field", {
                            attrs: {
                              name: "uid",
                              label: "ID Facebook",
                              disabled: _vm.getWithType === "me"
                            },
                            model: {
                              value: _vm.provider_uid,
                              callback: function($$v) {
                                _vm.provider_uid = $$v
                              },
                              expression: "provider_uid"
                            }
                          })
                        ],
                        1
                      ),
                      _vm._v(" "),
                      _c(
                        "v-flex",
                        { attrs: { xs4: "", sm2: "" } },
                        [
                          _c("v-select", {
                            attrs: {
                              "hide-details": "",
                              items: _vm.types,
                              "item-text": "text",
                              "item-value": "name",
                              label: "Select",
                              "single-line": "",
                              "menu-props": "bottom"
                            },
                            model: {
                              value: _vm.getWithType,
                              callback: function($$v) {
                                _vm.getWithType = $$v
                              },
                              expression: "getWithType"
                            }
                          })
                        ],
                        1
                      ),
                      _vm._v(" "),
                      _c(
                        "v-flex",
                        { attrs: { xs2: "", sm2: "" } },
                        [
                          _c(
                            "v-btn",
                            {
                              staticClass: "gradient-success",
                              attrs: {
                                loading: _vm.loading,
                                disabled: _vm.loading
                              },
                              nativeOn: {
                                click: function($event) {
                                  return _vm.getListFriends(_vm.provider_uid)
                                }
                              }
                            },
                            [_vm._v("\n\t\t\t\t\t\t\t\tLấy\n\t\t\t\t\t\t\t")]
                          )
                        ],
                        1
                      )
                    ],
                    1
                  )
                ],
                1
              )
            ],
            1
          ),
          _vm._v(" "),
          _vm.friends != null
            ? [
                _c(
                  "v-layout",
                  { attrs: { row: "", wrap: "" } },
                  [
                    _c(
                      "app-card",
                      {
                        attrs: {
                          heading: _vm.$t("message.infoReactions"),
                          colClasses: "xl12 lg12 md12 sm12 xs12"
                        }
                      },
                      [
                        _c(
                          "v-chip",
                          {
                            attrs: {
                              "text-color": "white",
                              color: "light-blue"
                            }
                          },
                          [
                            _vm._v(
                              "\n\t\t          Có " +
                                _vm._s(_vm.friends.data.length) +
                                " bạn trong danh sách\n\t\t        "
                            )
                          ]
                        ),
                        _vm._v(" "),
                        _c(
                          "v-chip",
                          { attrs: { "text-color": "white", color: "orange" } },
                          [
                            _vm._v(
                              "\n\t\t\t\t\t\t\t0 Người trong danh sách unfriend\n\t\t\t\t\t\t"
                            )
                          ]
                        )
                      ],
                      1
                    )
                  ],
                  1
                ),
                _vm._v(" "),
                _c(
                  "v-layout",
                  { attrs: { row: "", wrap: "" } },
                  [
                    _c(
                      "app-card",
                      {
                        attrs: {
                          heading: _vm.$t("message.friendsList"),
                          fullBlock: true,
                          colClasses: "xl12 lg12 md12 sm12 d-xs-full"
                        }
                      },
                      [
                        _c(
                          "v-card-title",
                          [
                            _c("v-spacer"),
                            _vm._v(" "),
                            _c("v-text-field", {
                              attrs: {
                                "append-icon": "search",
                                label: "Search",
                                "single-line": "",
                                "hide-details": ""
                              },
                              model: {
                                value: _vm.search,
                                callback: function($$v) {
                                  _vm.search = $$v
                                },
                                expression: "search"
                              }
                            })
                          ],
                          1
                        ),
                        _vm._v(" "),
                        _c("v-data-table", {
                          attrs: {
                            headers: _vm.headers,
                            items: _vm.friends.data,
                            search: _vm.search
                          },
                          scopedSlots: _vm._u(
                            [
                              {
                                key: "items",
                                fn: function(props) {
                                  return [
                                    _c(
                                      "td",
                                      {
                                        staticClass:
                                          "d-custom-flex align-items-center",
                                        style: { minWidth: "200px" }
                                      },
                                      [
                                        _c("img", {
                                          staticClass:
                                            "img-responsive rounded-circle mr-3",
                                          attrs: {
                                            width: "30",
                                            height: "30",
                                            src: props.item.picture
                                          }
                                        }),
                                        _vm._v(" "),
                                        _c("span", { staticClass: "fs-14" }, [
                                          _vm._v(_vm._s(props.item.name))
                                        ])
                                      ]
                                    ),
                                    _vm._v(" "),
                                    _c("td", [_vm._v(_vm._s(props.item.id))]),
                                    _vm._v(" "),
                                    _c("td", [
                                      _vm._v(_vm._s(props.item.mobile_phone))
                                    ]),
                                    _vm._v(" "),
                                    _c("td", [
                                      _vm._v(_vm._s(props.item.birthday))
                                    ]),
                                    _vm._v(" "),
                                    _c("td", [
                                      _vm._v(
                                        _vm._s(
                                          props.item.hometown
                                            ? props.item.hometown.name
                                            : ""
                                        )
                                      )
                                    ]),
                                    _vm._v(" "),
                                    _c("td", [
                                      _vm._v(
                                        _vm._s(
                                          props.item.location
                                            ? props.item.location.name
                                            : ""
                                        )
                                      )
                                    ]),
                                    _vm._v(" "),
                                    _c(
                                      "td",
                                      {
                                        staticClass:
                                          "justify-center layout px-0"
                                      },
                                      [
                                        _c(
                                          "v-btn",
                                          {
                                            staticClass: "info",
                                            attrs: {
                                              small: "",
                                              href: props.item.link,
                                              target: "_blank"
                                            }
                                          },
                                          [
                                            _vm._v(
                                              "\n\t\t                Trang cá nhân\n\t\t              "
                                            )
                                          ]
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "v-btn",
                                          {
                                            staticClass: "error",
                                            attrs: { small: "" },
                                            on: {
                                              click: function($event) {
                                                return _vm.onDeleteFriend(
                                                  props.item
                                                )
                                              }
                                            }
                                          },
                                          [
                                            _vm._v(
                                              "\n\t\t                Hủy kết bạn\n\t\t              "
                                            )
                                          ]
                                        )
                                      ],
                                      1
                                    )
                                  ]
                                }
                              },
                              {
                                key: "pageText",
                                fn: function(ref) {
                                  var pageStart = ref.pageStart
                                  var pageStop = ref.pageStop
                                  var itemsLength = ref.itemsLength
                                  return [
                                    _vm._v(
                                      "\n\t\t            Đang xem " +
                                        _vm._s(pageStart) +
                                        " - " +
                                        _vm._s(pageStop) +
                                        " trong " +
                                        _vm._s(itemsLength) +
                                        " bạn\n\t\t          "
                                    )
                                  ]
                                }
                              }
                            ],
                            null,
                            false,
                            1410290435
                          )
                        })
                      ],
                      1
                    )
                  ],
                  1
                )
              ]
            : _vm._e(),
          _vm._v(" "),
          _c(
            "v-snackbar",
            {
              attrs: { top: _vm.y === "top", timeout: _vm.timeout },
              model: {
                value: _vm.snackbar,
                callback: function($$v) {
                  _vm.snackbar = $$v
                },
                expression: "snackbar"
              }
            },
            [_vm._v("\n         " + _vm._s(_vm.snackbarMessage) + "\n      ")]
          ),
          _vm._v(" "),
          _c("delete-confirmation-dialog", {
            ref: "deleteConfirmationDialog",
            attrs: {
              heading: "Hủy kết bạn?",
              message: "Một đi không trở lại nha! Chắc chưa?"
            },
            on: { onConfirm: _vm.unfriend }
          })
        ],
        2
      )
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/views/facebook/friends/List.vue":
/*!******************************************************!*\
  !*** ./resources/js/views/facebook/friends/List.vue ***!
  \******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _List_vue_vue_type_template_id_637fe5b2___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./List.vue?vue&type=template&id=637fe5b2& */ "./resources/js/views/facebook/friends/List.vue?vue&type=template&id=637fe5b2&");
/* harmony import */ var _List_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./List.vue?vue&type=script&lang=js& */ "./resources/js/views/facebook/friends/List.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _List_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _List_vue_vue_type_template_id_637fe5b2___WEBPACK_IMPORTED_MODULE_0__["render"],
  _List_vue_vue_type_template_id_637fe5b2___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/views/facebook/friends/List.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/views/facebook/friends/List.vue?vue&type=script&lang=js&":
/*!*******************************************************************************!*\
  !*** ./resources/js/views/facebook/friends/List.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_List_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./List.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/facebook/friends/List.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_List_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/views/facebook/friends/List.vue?vue&type=template&id=637fe5b2&":
/*!*************************************************************************************!*\
  !*** ./resources/js/views/facebook/friends/List.vue?vue&type=template&id=637fe5b2& ***!
  \*************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_List_vue_vue_type_template_id_637fe5b2___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./List.vue?vue&type=template&id=637fe5b2& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/facebook/friends/List.vue?vue&type=template&id=637fe5b2&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_List_vue_vue_type_template_id_637fe5b2___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_List_vue_vue_type_template_id_637fe5b2___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);