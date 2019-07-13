(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[6],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/facebook/auto/Comment.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/views/facebook/auto/Comment.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var Helpers_helpers__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! Helpers/helpers */ "./resources/js/helpers/helpers.js");
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
      tab: 'auto-comment-home',
      is_start: true,
      items: ['home'],
      comment: '',
      loading: false,
      posts: []
    };
  },
  computed: {
    ids: function ids() {
      var arr = [];

      _.forEach(this.$auth.user().facebook, function (value, index) {
        if (value.is_active) {
          arr[index] = {
            text: value.name,
            value: value.provider_uid
          };
        }
      });

      if (arr.length > 1) {
        arr[arr.length] = {
          text: 'Ngẫu nhiên',
          value: 'random'
        };
      }

      return arr;
    },
    selectedId: {
      get: function get() {
        if (this.ids[this.ids.length - 1].text === 'random') {
          return this.ids[this.ids.length - 1].value;
        } else {
          return this.ids[0].value;
        }
      },
      set: function set(val) {
        return val;
      }
    }
  },
  methods: {
    getPosts: function getPosts(uid) {
      var _this = this;

      this.loading = true;
      Vue.http.post(route('facebook.auto.comment'), {
        uid: uid
      }).then(function (response) {
        return response.json();
      }).then(function (posts) {
        _this.posts = posts;
        _this.loading = false;
      }, function (error) {
        Vue.notify({
          group: 'app',
          type: 'error',
          text: error.body
        });
      });
    },
    startComment: function startComment(uid, posts, comment) {
      var _this2 = this;

      Object(Helpers_helpers__WEBPACK_IMPORTED_MODULE_0__["sleep_loop"])(posts, [7, 15], function (val, index) {
        Vue.http.post(route('facebook.auto.comment'), {
          uid: uid,
          id_post: val,
          comment: comment
        }).then(function (response) {
          return response.json();
        }).then(function (response) {
          console.log(response);
        }, function (error) {
          Vue.notify({
            group: 'app',
            type: 'error',
            text: error.body
          });
        }).then(function () {
          if (_this2.is_start === false) {
            return 'break';
          }
        });
      });
    },
    stopComment: function stopComment() {}
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/facebook/auto/Comment.vue?vue&type=template&id=d00289ce&scoped=true&":
/*!*******************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/views/facebook/auto/Comment.vue?vue&type=template&id=d00289ce&scoped=true& ***!
  \*******************************************************************************************************************************************************************************************************************************/
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
        { attrs: { fluid: "", "grid-list-xl": "", "pt-0": "" } },
        [
          _c(
            "v-layout",
            { attrs: { row: "", wrap: "" } },
            [
              _c(
                "app-card",
                {
                  attrs: {
                    heading: "Chức năng",
                    fullBlock: "true",
                    withTabs: true,
                    tabs: ["Filter", "Filter"],
                    colClasses: "md12 sm12 xs12"
                  }
                },
                [
                  _c(
                    "v-tabs",
                    {
                      model: {
                        value: _vm.tab,
                        callback: function($$v) {
                          _vm.tab = $$v
                        },
                        expression: "tab"
                      }
                    },
                    _vm._l(_vm.items, function(item, index) {
                      return _c(
                        "v-tab",
                        {
                          key: index,
                          attrs: { href: "#auto-comment-" + item }
                        },
                        [
                          _vm._v(
                            "\r\n            " + _vm._s(item) + "\r\n          "
                          )
                        ]
                      )
                    }),
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "v-tabs-items",
                    {
                      model: {
                        value: _vm.tab,
                        callback: function($$v) {
                          _vm.tab = $$v
                        },
                        expression: "tab"
                      }
                    },
                    _vm._l(_vm.items, function(item, index) {
                      return _c(
                        "v-tab-item",
                        {
                          key: index,
                          attrs: { value: "auto-comment-" + item }
                        },
                        [
                          _c(
                            "v-card",
                            { attrs: { flat: "" } },
                            [
                              _c(
                                "v-card-title",
                                { attrs: { "primary-title": "" } },
                                [
                                  _c(
                                    "v-layout",
                                    { attrs: { row: "", wrap: "" } },
                                    [
                                      _c(
                                        "v-flex",
                                        {
                                          attrs: { md4: "", sm6: "", xs12: "" }
                                        },
                                        [
                                          _c(
                                            "span",
                                            {
                                              staticClass: "small pt-4 d-block"
                                            },
                                            [_vm._v("Chọn tk muốn comment")]
                                          )
                                        ]
                                      ),
                                      _vm._v(" "),
                                      _c(
                                        "v-flex",
                                        {
                                          attrs: { md4: "", sm6: "", xs12: "" }
                                        },
                                        [
                                          _c("v-select", {
                                            attrs: {
                                              items: _vm.ids,
                                              disabled: _vm.posts.length > 0
                                            },
                                            model: {
                                              value: _vm.selectedId,
                                              callback: function($$v) {
                                                _vm.selectedId = $$v
                                              },
                                              expression: "selectedId"
                                            }
                                          })
                                        ],
                                        1
                                      )
                                    ],
                                    1
                                  ),
                                  _vm._v(" "),
                                  _c(
                                    "v-flex",
                                    { attrs: { md12: "", sm12: "", xs12: "" } },
                                    [
                                      _c("v-textarea", {
                                        attrs: {
                                          outline: "",
                                          label: "Nhập bình luận..."
                                        },
                                        model: {
                                          value: _vm.comment,
                                          callback: function($$v) {
                                            _vm.comment = $$v
                                          },
                                          expression: "comment"
                                        }
                                      })
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
                      )
                    }),
                    1
                  ),
                  _vm._v(" "),
                  _vm.posts.length === 0
                    ? _c(
                        "v-btn",
                        {
                          attrs: {
                            color: "info",
                            loading: _vm.loading,
                            disabled: _vm.loading
                          },
                          on: {
                            click: function($event) {
                              return _vm.getPosts(_vm.selectedId)
                            }
                          }
                        },
                        [_vm._v("Lấy bài viết\r\n        ")]
                      )
                    : _c(
                        "v-btn",
                        {
                          attrs: {
                            color: "success",
                            loading: _vm.loading,
                            disabled: _vm.loading
                          },
                          on: {
                            click: function($event) {
                              return _vm.startComment(
                                _vm.uid,
                                _vm.posts,
                                _vm.comment
                              )
                            }
                          }
                        },
                        [_vm._v("Bắt đầu Comment\r\n        ")]
                      ),
                  _vm._v(" "),
                  _vm.is_start
                    ? _c(
                        "v-btn",
                        {
                          attrs: {
                            color: "success",
                            loading: _vm.loading,
                            disabled: _vm.loading
                          },
                          on: {
                            click: function($event) {
                              !_vm.is_start
                            }
                          }
                        },
                        [_vm._v("Dừng Auto!\r\n        ")]
                      )
                    : _vm._e()
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
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/views/facebook/auto/Comment.vue":
/*!******************************************************!*\
  !*** ./resources/js/views/facebook/auto/Comment.vue ***!
  \******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Comment_vue_vue_type_template_id_d00289ce_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Comment.vue?vue&type=template&id=d00289ce&scoped=true& */ "./resources/js/views/facebook/auto/Comment.vue?vue&type=template&id=d00289ce&scoped=true&");
/* harmony import */ var _Comment_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Comment.vue?vue&type=script&lang=js& */ "./resources/js/views/facebook/auto/Comment.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Comment_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Comment_vue_vue_type_template_id_d00289ce_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Comment_vue_vue_type_template_id_d00289ce_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "d00289ce",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/views/facebook/auto/Comment.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/views/facebook/auto/Comment.vue?vue&type=script&lang=js&":
/*!*******************************************************************************!*\
  !*** ./resources/js/views/facebook/auto/Comment.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Comment_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Comment.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/facebook/auto/Comment.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Comment_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/views/facebook/auto/Comment.vue?vue&type=template&id=d00289ce&scoped=true&":
/*!*************************************************************************************************!*\
  !*** ./resources/js/views/facebook/auto/Comment.vue?vue&type=template&id=d00289ce&scoped=true& ***!
  \*************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Comment_vue_vue_type_template_id_d00289ce_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Comment.vue?vue&type=template&id=d00289ce&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/facebook/auto/Comment.vue?vue&type=template&id=d00289ce&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Comment_vue_vue_type_template_id_d00289ce_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Comment_vue_vue_type_template_id_d00289ce_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);