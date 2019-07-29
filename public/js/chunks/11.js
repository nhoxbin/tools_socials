(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[11],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/facebook/messenger/Ranking.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/views/facebook/messenger/Ranking.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
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
      loader: true,
      headers: [{
        text: 'Hạng',
        value: 'rank'
      }, {
        text: 'Tên',
        value: 'name',
        sortable: false
      }, {
        text: 'Tin nhắn',
        value: 'message_count'
      }, {
        text: 'Hành động',
        value: 'actions',
        sortable: false
      }],
      kount: null
    };
  },
  mounted: function mounted() {
    var _this = this;

    Vue.http.get(route('facebook.messenger.inbox-kount')).then(function (response) {
      if (response.status == 204) {
        Vue.notify({
          group: 'app',
          type: 'error',
          text: 'Lỗi ko lấy được tin nhắn.'
        });
        return;
      } else {
        return response.json();
      }
    }).then(function (data) {
      if (data != undefined) {
        _this.kount = data;
      }

      _this.loader = false;
    });
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/facebook/messenger/Ranking.vue?vue&type=template&id=3a3f25d0&":
/*!************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/views/facebook/messenger/Ranking.vue?vue&type=template&id=3a3f25d0& ***!
  \************************************************************************************************************************************************************************************************************************/
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
      _vm.kount !== null
        ? _c(
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
                        heading: _vm.$t("message.standard"),
                        fullBlock: true,
                        colClasses: "xl12 lg12 md12 sm12 xs12"
                      }
                    },
                    [
                      _c("div", { staticClass: "pa-3" }, [
                        _c("p", { staticClass: "mb-0" }, [
                          _vm._v("Xếp hạng "),
                          _c("code", [_vm._v("2579")]),
                          _vm._v(" tin nhắn")
                        ])
                      ]),
                      _vm._v(" "),
                      _c("v-data-table", {
                        attrs: {
                          headers: _vm.headers,
                          items: _vm.kount,
                          "hide-actions": ""
                        },
                        scopedSlots: _vm._u(
                          [
                            {
                              key: "items",
                              fn: function(props) {
                                return [
                                  _c("td", [_vm._v(_vm._s(props.index + 1))]),
                                  _vm._v(" "),
                                  _c("td", [
                                    _vm._v(
                                      _vm._s(props.item.participants[0].name)
                                    )
                                  ]),
                                  _vm._v(" "),
                                  _c("td", [
                                    _vm._v(_vm._s(props.item.message_count))
                                  ]),
                                  _vm._v(" "),
                                  _c(
                                    "td",
                                    [
                                      _c(
                                        "v-btn",
                                        {
                                          attrs: {
                                            small: "",
                                            color: "primary",
                                            href:
                                              "https://www.facebook.com/" +
                                              props.item.link,
                                            target: "_blank"
                                          }
                                        },
                                        [
                                          _vm._v(
                                            "\r\n\t\t\t\t\t\t\t\tInbox\r\n\t\t\t\t\t\t\t"
                                          )
                                        ]
                                      ),
                                      _vm._v(" "),
                                      _c(
                                        "v-btn",
                                        {
                                          attrs: {
                                            small: "",
                                            color: "accent",
                                            href:
                                              "https://www.facebook.com/" +
                                              props.item.participants[0].id,
                                            target: "_blank"
                                          }
                                        },
                                        [
                                          _vm._v(
                                            "\r\n\t\t\t\t\t\t\t\tHistory\r\n\t\t\t\t\t\t\t"
                                          )
                                        ]
                                      )
                                    ],
                                    1
                                  )
                                ]
                              }
                            }
                          ],
                          null,
                          false,
                          3062057522
                        )
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
        : _vm._e()
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/views/facebook/messenger/Ranking.vue":
/*!***********************************************************!*\
  !*** ./resources/js/views/facebook/messenger/Ranking.vue ***!
  \***********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Ranking_vue_vue_type_template_id_3a3f25d0___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Ranking.vue?vue&type=template&id=3a3f25d0& */ "./resources/js/views/facebook/messenger/Ranking.vue?vue&type=template&id=3a3f25d0&");
/* harmony import */ var _Ranking_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Ranking.vue?vue&type=script&lang=js& */ "./resources/js/views/facebook/messenger/Ranking.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Ranking_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Ranking_vue_vue_type_template_id_3a3f25d0___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Ranking_vue_vue_type_template_id_3a3f25d0___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/views/facebook/messenger/Ranking.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/views/facebook/messenger/Ranking.vue?vue&type=script&lang=js&":
/*!************************************************************************************!*\
  !*** ./resources/js/views/facebook/messenger/Ranking.vue?vue&type=script&lang=js& ***!
  \************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Ranking_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Ranking.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/facebook/messenger/Ranking.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Ranking_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/views/facebook/messenger/Ranking.vue?vue&type=template&id=3a3f25d0&":
/*!******************************************************************************************!*\
  !*** ./resources/js/views/facebook/messenger/Ranking.vue?vue&type=template&id=3a3f25d0& ***!
  \******************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Ranking_vue_vue_type_template_id_3a3f25d0___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Ranking.vue?vue&type=template&id=3a3f25d0& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/facebook/messenger/Ranking.vue?vue&type=template&id=3a3f25d0&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Ranking_vue_vue_type_template_id_3a3f25d0___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Ranking_vue_vue_type_template_id_3a3f25d0___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);