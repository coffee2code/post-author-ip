this.PostAuthorIP=function(t){var e={};function n(o){if(e[o])return e[o].exports;var r=e[o]={i:o,l:!1,exports:{}};return t[o].call(r.exports,r,r.exports,n),r.l=!0,r.exports}return n.m=t,n.c=e,n.d=function(t,e,o){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:o})},n.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},n.t=function(t,e){if(1&e&&(t=n(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var o=Object.create(null);if(n.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var r in t)n.d(o,r,function(e){return t[e]}.bind(null,r));return o},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="",n(n.s=15)}([function(t,e){!function(){t.exports=this.wp.element}()},function(t,e){!function(){t.exports=this.wp.components}()},function(t,e){!function(){t.exports=this.wp.compose}()},function(t,e){!function(){t.exports=this.wp.data}()},function(t,e){!function(){t.exports=this.wp.plugins}()},function(t,e){t.exports=function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}},function(t,e){function n(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}t.exports=function(t,e,o){return e&&n(t.prototype,e),o&&n(t,o),t}},function(t,e,n){var o=n(12),r=n(13);t.exports=function(t,e){return!e||"object"!==o(e)&&"function"!=typeof e?r(t):e}},function(t,e){function n(e){return t.exports=n=Object.setPrototypeOf?Object.getPrototypeOf:function(t){return t.__proto__||Object.getPrototypeOf(t)},n(e)}t.exports=n},function(t,e,n){var o=n(14);t.exports=function(t,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function");t.prototype=Object.create(e&&e.prototype,{constructor:{value:t,writable:!0,configurable:!0}}),e&&o(t,e)}},function(t,e){!function(){t.exports=this.wp.editPost}()},function(t,e){!function(){t.exports=this.wp.i18n}()},function(t,e){function n(t){return(n="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}function o(e){return"function"==typeof Symbol&&"symbol"===n(Symbol.iterator)?t.exports=o=function(t){return n(t)}:t.exports=o=function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":n(t)},o(e)}t.exports=o},function(t,e){t.exports=function(t){if(void 0===t)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return t}},function(t,e){function n(e,o){return t.exports=n=Object.setPrototypeOf||function(t,e){return t.__proto__=e,t},n(e,o)}t.exports=n},function(t,e,n){"use strict";n.r(e);var o=n(4),r=n(5),u=n.n(r),i=n(6),c=n.n(i),f=n(7),p=n.n(f),s=n(8),a=n.n(s),l=n(9),b=n.n(l),y=n(0),d=n(1),m=n(2),h=n(3),x=n(10),O=n(11),j=function(t){function e(){return u()(this,e),p()(this,a()(e).apply(this,arguments))}return b()(e,t),c()(e,[{key:"render",value:function(){var t=this.props,e=t.meta,n=(e=void 0===e?{}:e)["c2c-post-author-ip"];t.updateMeta;return Object(y.createElement)(x.PluginPostStatusInfo,null,Object(y.createElement)(d.Disabled,{className:"post-author-ip-disabled"},Object(y.createElement)(d.TextControl,{label:Object(O.__)("Author IP Address","post-author-ip"),className:"post-author-ip",value:n,onChange:function(){}})))}}]),e}(y.Component),v=Object(m.compose)([Object(h.withSelect)(function(t){return{meta:(0,t("core/editor").getEditedPostAttribute)("meta")}}),Object(h.withDispatch)(function(t,e){var n=e.meta,o=t("core/editor").editPost;return{updateMeta:function(t){delete n["c2c-post-author-ip"],o({meta:n})}}}),Object(m.ifCondition)(function(t){return""!=t.meta["c2c-post-author-ip"]})])(j);Object(o.registerPlugin)("post-author-ip",{render:v})}]);