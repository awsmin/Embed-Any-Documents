/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./blocks/document/main.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./blocks/document/main.js":
/*!*********************************!*\
  !*** ./blocks/document/main.js ***!
  \*********************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _modules_helper__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./modules/helper */ "./blocks/document/modules/helper.js");
/* harmony import */ var _modules_inspector__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./modules/inspector */ "./blocks/document/modules/inspector.js");
function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; var ownKeys = Object.keys(source); if (typeof Object.getOwnPropertySymbols === 'function') { ownKeys = ownKeys.concat(Object.getOwnPropertySymbols(source).filter(function (sym) { return Object.getOwnPropertyDescriptor(source, sym).enumerable; })); } ownKeys.forEach(function (key) { _defineProperty(target, key, source[key]); }); } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

/**
 * BLOCK: document
 *
 * Registering a basic block with Gutenberg.
 */


var __ = wp.i18n.__; // Import __() from wp.i18n

var registerBlockType = wp.blocks.registerBlockType; // Import registerBlockType() from wp.blocks

var _wp$components = wp.components,
    ServerSideRender = _wp$components.ServerSideRender,
    IconButton = _wp$components.IconButton;
/**
 * Register: a Gutenberg Block.
 *
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */

registerBlockType('embed-any-document/document', {
  title: __('Document'),
  // Block title.
  description: __('Upload and Embed your documents.'),
  // Block description
  icon: 'media-document',
  // Block icon
  category: 'embed',
  // Block category,
  keywords: [__('add document'), __('embed document'), __('embed any document')],
  // Access the block easily with keyword aliases

  /**
   * The edit function describes the structure of the block in the context of the editor.
   * This represents what the editor will render when the block is used.
   */
  edit: function edit(props) {
    var attributes = props.attributes,
        setAttributes = props.setAttributes;
    var shortcode = attributes.shortcode;
    var blockProps = null;

    var setBlockProps = function setBlockProps() {
      blockProps = props;
      blockProps.activeEadBlock = true;
    };

    jQuery('body').on('click', '#embed-popup #insert-doc', function () {
      var shortcodeText = jQuery('#embed-popup #shortcode').text();

      var _EadHelper$parseShort = _modules_helper__WEBPACK_IMPORTED_MODULE_0__["default"].parseShortcode(shortcodeText),
          url = _EadHelper$parseShort.url,
          _EadHelper$parseShort2 = _EadHelper$parseShort.width,
          width = _EadHelper$parseShort2 === void 0 ? emebeder.width : _EadHelper$parseShort2,
          _EadHelper$parseShort3 = _EadHelper$parseShort.height,
          height = _EadHelper$parseShort3 === void 0 ? emebeder.height : _EadHelper$parseShort3,
          _EadHelper$parseShort4 = _EadHelper$parseShort.download,
          download = _EadHelper$parseShort4 === void 0 ? emebeder.download : _EadHelper$parseShort4,
          _EadHelper$parseShort5 = _EadHelper$parseShort.viewer,
          viewer = _EadHelper$parseShort5 === void 0 ? emebeder.provider : _EadHelper$parseShort5,
          _EadHelper$parseShort6 = _EadHelper$parseShort.text,
          text = _EadHelper$parseShort6 === void 0 ? emebeder.text : _EadHelper$parseShort6,
          _EadHelper$parseShort7 = _EadHelper$parseShort.cache,
          cache = _EadHelper$parseShort7 === void 0 ? true : _EadHelper$parseShort7;

      if (blockProps !== null) {
        if (blockProps.activeEadBlock === true) {
          blockProps.activeEadBlock = false;
          blockProps.setAttributes({
            shortcode: shortcodeText,
            url: url,
            width: width,
            height: height,
            download: download,
            text: text,
            viewer: viewer,
            cache: cache === 'off' ? false : true
          });
        }
      }
    });

    if (typeof shortcode !== 'undefined') {
      return [wp.element.createElement(_modules_inspector__WEBPACK_IMPORTED_MODULE_1__["default"], _objectSpread({
        setAttributes: setAttributes
      }, props)), wp.element.createElement(ServerSideRender, {
        block: "embed-any-document/document",
        attributes: attributes
      })];
    } else {
      return wp.element.createElement("div", {
        className: "components-placeholder ead-block-wrapper"
      }, wp.element.createElement(IconButton, {
        className: "awsm-embed",
        icon: "media-document",
        onClick: setBlockProps,
        isLarge: true
      }, __('Add Document')));
    }
  },

  /**
   * The save function defines the way in which the different attributes should be combined into the final markup, which is then serialized by Gutenberg into post_content.
   */
  save: function save(props) {
    var shortcode = props.attributes.shortcode;
    return shortcode;
  }
});

/***/ }),

/***/ "./blocks/document/modules/helper.js":
/*!*******************************************!*\
  !*** ./blocks/document/modules/helper.js ***!
  \*******************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var EadHelper =
/*#__PURE__*/
function () {
  function EadHelper() {
    _classCallCheck(this, EadHelper);
  }

  _createClass(EadHelper, null, [{
    key: "parseShortcode",
    value: function parseShortcode(shortcodeText) {
      var atts = {};
      shortcodeText.match(/[\w-]+=".+?"/g).forEach(function (attr) {
        attr = attr.match(/([\w-]+)="(.+?)"/);
        atts[attr[1]] = attr[2];
      });
      return atts;
    }
  }, {
    key: "isValidMSExtension",
    value: function isValidMSExtension(url) {
      var validExt = emebeder.msextension.split(',');
      var ext = '.' + url.split('.').pop();
      return jQuery.inArray(ext, validExt) !== -1;
    }
  }]);

  return EadHelper;
}();

/* harmony default export */ __webpack_exports__["default"] = (EadHelper);

/***/ }),

/***/ "./blocks/document/modules/inspector.js":
/*!**********************************************!*\
  !*** ./blocks/document/modules/inspector.js ***!
  \**********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _helper__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./helper */ "./blocks/document/modules/helper.js");
function _typeof(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } return _assertThisInitialized(self); }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }


var __ = wp.i18n.__;
var Component = wp.element.Component;
var InspectorControls = wp.editor.InspectorControls;
var _wp$components = wp.components,
    PanelBody = _wp$components.PanelBody,
    TextControl = _wp$components.TextControl,
    SelectControl = _wp$components.SelectControl,
    ToggleControl = _wp$components.ToggleControl,
    Disabled = _wp$components.Disabled;

var EadInspector =
/*#__PURE__*/
function (_Component) {
  _inherits(EadInspector, _Component);

  function EadInspector() {
    var _this;

    _classCallCheck(this, EadInspector);

    _this = _possibleConstructorReturn(this, _getPrototypeOf(EadInspector).apply(this, arguments));
    _this.downloadControlhandle = _this.downloadControlhandle.bind(_assertThisInitialized(_assertThisInitialized(_this)));
    _this.viewerControlHandle = _this.viewerControlHandle.bind(_assertThisInitialized(_assertThisInitialized(_this)));
    var _this$props$attribute = _this.props.attributes,
        download = _this$props$attribute.download,
        viewer = _this$props$attribute.viewer;
    _this.state = {
      downloadDisabled: download === 'none' ? true : false,
      cacheHidden: viewer === 'google' ? false : true
    };
    return _this;
  }

  _createClass(EadInspector, [{
    key: "downloadControlhandle",
    value: function downloadControlhandle(download) {
      this.setState({
        downloadDisabled: download === 'none' ? true : false
      });
      this.props.setAttributes({
        download: download
      });
    }
  }, {
    key: "viewerControlHandle",
    value: function viewerControlHandle(viewer) {
      this.setState({
        cacheHidden: viewer === 'google' ? false : true
      });
      this.props.setAttributes({
        viewer: viewer
      });
    }
  }, {
    key: "render",
    value: function render() {
      var _this$props = this.props,
          _this$props$attribute2 = _this$props.attributes,
          url = _this$props$attribute2.url,
          width = _this$props$attribute2.width,
          height = _this$props$attribute2.height,
          text = _this$props$attribute2.text,
          download = _this$props$attribute2.download,
          viewer = _this$props$attribute2.viewer,
          cache = _this$props$attribute2.cache,
          setAttributes = _this$props.setAttributes;
      var viewerOptions = [{
        value: 'google',
        label: __('Google Docs Viewer')
      }];

      if (_helper__WEBPACK_IMPORTED_MODULE_0__["default"].isValidMSExtension(url)) {
        viewerOptions.push({
          value: 'microsoft',
          label: __('Microsoft Office Online')
        });
      }

      var downloadTextControl = wp.element.createElement(TextControl, {
        label: __('Download Text'),
        help: __('Default download button text'),
        value: text,
        onChange: function onChange(text) {
          return setAttributes({
            text: text
          });
        }
      });

      if (this.state.downloadDisabled) {
        downloadTextControl = wp.element.createElement(Disabled, null, downloadTextControl);
      }

      return wp.element.createElement(InspectorControls, null, wp.element.createElement(PanelBody, null, wp.element.createElement(TextControl, {
        label: __('Width'),
        help: __('Width of document either in px or in %'),
        value: width,
        onChange: function onChange(width) {
          return setAttributes({
            width: width
          });
        }
      })), wp.element.createElement(PanelBody, null, wp.element.createElement(TextControl, {
        label: __('Height'),
        help: __('Height of document either in px or in %'),
        value: height,
        onChange: function onChange(height) {
          return setAttributes({
            height: height
          });
        }
      })), wp.element.createElement(PanelBody, null, wp.element.createElement(SelectControl, {
        label: __('Show Download Link'),
        options: [{
          value: 'all',
          label: __('For all users')
        }, {
          value: 'logged',
          label: __('For Logged-in users')
        }, {
          value: 'none',
          label: __('No Download')
        }],
        value: download,
        onChange: this.downloadControlhandle
      })), wp.element.createElement(PanelBody, null, downloadTextControl), wp.element.createElement(PanelBody, null, wp.element.createElement(SelectControl, {
        label: __('Viewer '),
        options: viewerOptions,
        value: viewer,
        onChange: this.viewerControlHandle
      })), !this.state.cacheHidden && wp.element.createElement(PanelBody, null, wp.element.createElement(ToggleControl, {
        label: __('Cache'),
        checked: cache,
        onChange: function onChange(cache) {
          return setAttributes({
            cache: cache
          });
        }
      })));
    }
  }]);

  return EadInspector;
}(Component);

/* harmony default export */ __webpack_exports__["default"] = (EadInspector);

/***/ })

/******/ });
//# sourceMappingURL=document-block.js.map