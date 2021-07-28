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
/* harmony import */ var _modules_ead_server_side_render__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./modules/ead-server-side-render */ "./blocks/document/modules/ead-server-side-render.js");
/* harmony import */ var _modules_icon__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./modules/icon */ "./blocks/document/modules/icon.js");
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

/**
 * BLOCK: document
 *
 * Registering a basic block with Gutenberg.
 */




var __ = wp.i18n.__; // Import __() from wp.i18n

var registerBlockType = wp.blocks.registerBlockType; // Import registerBlockType() from wp.blocks

var _wp$components = wp.components,
    Placeholder = _wp$components.Placeholder,
    Button = _wp$components.Button,
    withNotices = _wp$components.withNotices;
var Fragment = wp.element.Fragment;
var MediaPlaceholder = wp.editor.MediaPlaceholder;
var validTypes = ['text/plain', 'text/richtext', 'application/pdf', 'application/postscript', 'image/tiff', 'application/msword', 'application/vnd.ms-powerpoint', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.openxmlformats-officedocument.wordprocessingml.template', 'application/vnd.ms-word.template.macroEnabled.12', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel.sheet.macroEnabled.12', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/vnd.openxmlformats-officedocument.presentationml.slideshow', 'application/vnd.apple.pages', 'image/svg+xml'];
var validExts = ".pdf,.tif, .tiff, .doc, .txt,.pps, .ppt, .xla, .xls, .xlt, .xlw, .docx, .dotx, .dotm, .xlsx, .xlsm, .pptx, .pages, .svg, .ppsx";
var validExtension = ['.pdf', '.tif', '.tiff', '.doc', '.txt', '.xls', '.xlt', '.xlw', '.docx', '.dotx', '.dotm', '.xlsx', '.xlsm', '.pptx', '.pages', '.svg', '.ppsx'];
/**
 * Register: a Gutenberg Block.
 *
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */

registerBlockType('embed-any-document/document', {
  title: __('Document', 'embed-any-document'),
  // Block title.
  description: __('Upload and Embed your documents.', 'embed-any-document'),
  // Block description
  icon: _modules_icon__WEBPACK_IMPORTED_MODULE_3__["default"].block,
  // Block icon
  category: 'embed',
  // Block category,
  keywords: [__('add document', 'embed-any-document'), __('embed document', 'embed-any-document'), __('embed any document', 'embed-any-document')],
  // Access the block easily with keyword aliases

  /**
   * The edit function describes the structure of the block in the context of the editor.
   * This represents what the editor will render when the block is used.
   */
  edit: function edit(props) {
    var attributes = props.attributes,
        setAttributes = props.setAttributes,
        noticeUI = props.noticeUI,
        noticeOperations = props.noticeOperations;
    var shortcode = attributes.shortcode;
    var blockProps = null;
    var shortcodeText;
    var embedurl;

    var createNotice = function createNotice(type, msg, id) {
      wp.data.dispatch('core/notices').createNotice(type, // Can be one of: success, info, warning, error.
      __(msg), // Text string to display.
      {
        id: id,
        //assigning an ID prevents the notice from being added repeatedly
        isDismissible: true // Whether the user can dismiss the notice.

      });
    };

    var onSelectImage = function onSelectImage(media) {
      if (!media || !media.url) {
        return;
      }

      if (media.url) {
        embedurl = media.url;
      }

      eadShortcode(embedurl);
    };

    var onSelectURL = function onSelectURL(url) {
      var filename;
      var checkExtExist;

      if (!url) {
        return;
      }

      if (url) {
        embedurl = url;
        filename = url.split('/').pop();
        filename = filename.split('.').pop();
        filename = '.' + filename;
      }

      if (filename != '') {
        checkExtExist = validExtension.includes(filename);

        if (checkExtExist == true) {
          eadShortcode(embedurl);
        } else {
          createNotice('error', 'File type is not supported!!!', 'eadlinkerror');
          return;
        }
      } else {
        createNotice('warning', 'Some error occurred, click', 'eadunknownerror');
      }
    };

    var onUploadError = function onUploadError(message) {
      noticeOperations.removeAllNotices();
      noticeOperations.createErrorNotice(message);
    };

    var eadShortcode = function eadShortcode(embedurl) {
      blockProps = props;

      if (embedurl) {
        shortcodeText = '[embeddoc url="' + embedurl + '"]';
      }

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

      viewer = jQuery.inArray(viewer, emebeder.viewers) !== -1 ? viewer : 'google';
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
    };

    var providerLink = function providerLink() {
      var link = 'http://goo.gl/wJTQlc';
      window.open(link, '_blank');
    };

    var setBlockProps = function setBlockProps() {
      blockProps = props;
      blockProps.activeEadBlock = true;
    };

    jQuery('body').on('click', '#embed-popup #insert-doc', function () {
      var shortcodeText = jQuery('#embed-popup #shortcode').text();

      var _EadHelper$parseShort8 = _modules_helper__WEBPACK_IMPORTED_MODULE_0__["default"].parseShortcode(shortcodeText),
          url = _EadHelper$parseShort8.url,
          _EadHelper$parseShort9 = _EadHelper$parseShort8.width,
          width = _EadHelper$parseShort9 === void 0 ? emebeder.width : _EadHelper$parseShort9,
          _EadHelper$parseShort10 = _EadHelper$parseShort8.height,
          height = _EadHelper$parseShort10 === void 0 ? emebeder.height : _EadHelper$parseShort10,
          _EadHelper$parseShort11 = _EadHelper$parseShort8.download,
          download = _EadHelper$parseShort11 === void 0 ? emebeder.download : _EadHelper$parseShort11,
          _EadHelper$parseShort12 = _EadHelper$parseShort8.viewer,
          viewer = _EadHelper$parseShort12 === void 0 ? emebeder.provider : _EadHelper$parseShort12,
          _EadHelper$parseShort13 = _EadHelper$parseShort8.text,
          text = _EadHelper$parseShort13 === void 0 ? emebeder.text : _EadHelper$parseShort13,
          _EadHelper$parseShort14 = _EadHelper$parseShort8.cache,
          cache = _EadHelper$parseShort14 === void 0 ? true : _EadHelper$parseShort14;

      if (blockProps !== null) {
        if (blockProps.activeEadBlock === true) {
          blockProps.activeEadBlock = false;
          viewer = jQuery.inArray(viewer, emebeder.viewers) !== -1 ? viewer : 'google';
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
      }, props)), wp.element.createElement(_modules_ead_server_side_render__WEBPACK_IMPORTED_MODULE_2__["default"], {
        block: "embed-any-document/document",
        attributes: attributes
      })];
    } else {
      return wp.element.createElement(MediaPlaceholder, {
        className: "ead-media-placeholder",
        onSelect: onSelectImage,
        onSelectURL: onSelectURL,
        labels: {
          title: 'Embed Any Document',
          'instructions': 'Upload a document,pick from your media library,or add from external websites'
        },
        icon: "format-image",
        accept: validExts,
        allowedTypes: validTypes,
        OnError: onUploadError
      }, wp.element.createElement(Button, {
        className: "ead-button-dropbox disabled",
        onClick: providerLink,
        value: "click"
      }, "Add from dropbox"), wp.element.createElement(Button, {
        className: "ead-button-drive",
        onClick: providerLink,
        value: "click"
      }, "Add from drive"), wp.element.createElement(Button, {
        className: "ead-button-box",
        onClick: providerLink,
        value: "click"
      }, "Add from box"));
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

/***/ "./blocks/document/modules/ead-server-side-render.js":
/*!***********************************************************!*\
  !*** ./blocks/document/modules/ead-server-side-render.js ***!
  \***********************************************************/
/*! exports provided: rendererPath, default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "rendererPath", function() { return rendererPath; });
/* harmony import */ var _icon__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./icon */ "./blocks/document/modules/icon.js");
function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function () { var Super = _getPrototypeOf(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = _getPrototypeOf(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _possibleConstructorReturn(this, result); }; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Date.prototype.toString.call(Reflect.construct(Date, [], function () {})); return true; } catch (e) { return false; } }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }


var _wp$i18n = wp.i18n,
    __ = _wp$i18n.__,
    sprintf = _wp$i18n.sprintf;
var _wp$element = wp.element,
    Component = _wp$element.Component,
    createRef = _wp$element.createRef;
var apiFetch = wp.apiFetch;
var addQueryArgs = wp.url.addQueryArgs;
var _wp$components = wp.components,
    Placeholder = _wp$components.Placeholder,
    Spinner = _wp$components.Spinner;
var _lodash = lodash,
    isEqual = _lodash.isEqual,
    debounce = _lodash.debounce;
function rendererPath(block) {
  var attributes = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
  var urlQueryArgs = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
  return addQueryArgs("/wp/v2/block-renderer/".concat(block), _objectSpread(_objectSpread({
    context: 'edit'
  }, null !== attributes ? {
    attributes: attributes
  } : {}), urlQueryArgs));
}

var EadServerSideRender = /*#__PURE__*/function (_Component) {
  _inherits(EadServerSideRender, _Component);

  var _super = _createSuper(EadServerSideRender);

  function EadServerSideRender(props) {
    var _this;

    _classCallCheck(this, EadServerSideRender);

    _this = _super.call(this, props);
    _this.state = {
      response: null
    };
    _this.eadRef = createRef();
    return _this;
  }

  _createClass(EadServerSideRender, [{
    key: "componentDidMount",
    value: function componentDidMount() {
      this.isStillMounted = true;
      this.fetch(this.props); // Only debounce once the initial fetch occurs to ensure that the first
      // renders show data as soon as possible.

      this.fetch = debounce(this.fetch, 500);
    }
  }, {
    key: "componentWillUnmount",
    value: function componentWillUnmount() {
      this.isStillMounted = false;
    }
  }, {
    key: "componentDidUpdate",
    value: function componentDidUpdate(prevProps, prevState) {
      if (!isEqual(prevProps, this.props)) {
        this.fetch(this.props);
      }

      if (this.state.response !== prevState.response && null !== this.eadRef.current) {
        var _this$props$attribute = this.props.attributes,
            attributes = _this$props$attribute === void 0 ? null : _this$props$attribute;

        if (attributes !== null && attributes && (attributes.viewer === 'google' || attributes.viewer === 'browser' || attributes.viewer === 'built-in')) {
          var viewer = attributes.viewer;
          var currentRef = this.eadRef.current;
          var documentWrapper = jQuery(currentRef).find('.ead-document');
          var iframe = documentWrapper.find('.ead-iframe');

          if (viewer === 'google' || viewer === 'browser') {
            if (viewer === 'google') {
              iframe.css('visibility', 'visible');
            }

            iframe.on('load', function () {
              jQuery(this).parents('.ead-document').find('.ead-document-loading').css('display', 'none');
            });
          }

          if (viewer === 'browser' || viewer === 'built-in') {
            var src = documentWrapper.data('pdfSrc');
            viewer = typeof src !== 'undefined' && src.length > 0 && viewer.length > 0 ? viewer : false;
            var isBuiltInViewer = 'pdfjs' in eadPublic && eadPublic.pdfjs && eadPublic.pdfjs.length > 0 && viewer === 'built-in';

            if (viewer && (viewer === 'browser' || isBuiltInViewer)) {
              if (PDFObject.supportsPDFs || isBuiltInViewer) {
                var options = {};

                if (!isBuiltInViewer) {
                  options = {
                    width: iframe.css('width'),
                    height: iframe.css('height')
                  };
                } else {
                  options = {
                    forcePDFJS: true,
                    PDFJS_URL: eadPublic.pdfjs
                  };
                }

                PDFObject.embed(src, documentWrapper, options);
              } else {
                iframe.css('visibility', 'visible');
              }
            }
          }
        }
      }
    }
  }, {
    key: "fetch",
    value: function fetch(props) {
      var _this2 = this;

      if (!this.isStillMounted) {
        return;
      }

      if (null !== this.state.response) {
        this.setState({
          response: null
        });
      }

      var block = props.block,
          _props$attributes = props.attributes,
          attributes = _props$attributes === void 0 ? null : _props$attributes,
          _props$urlQueryArgs = props.urlQueryArgs,
          urlQueryArgs = _props$urlQueryArgs === void 0 ? {} : _props$urlQueryArgs;
      var path = rendererPath(block, attributes, urlQueryArgs); // Store the latest fetch request so that when we process it, we can
      // check if it is the current request, to avoid race conditions on slow networks.

      var fetchRequest = this.currentFetchRequest = apiFetch({
        path: path
      }).then(function (response) {
        if (_this2.isStillMounted && fetchRequest === _this2.currentFetchRequest && response) {
          _this2.setState({
            response: response.rendered
          });
        }
      }).catch(function (error) {
        if (_this2.isStillMounted && fetchRequest === _this2.currentFetchRequest) {
          _this2.setState({
            response: {
              error: true,
              errorMsg: error.message
            }
          });
        }
      });
      return fetchRequest;
    }
  }, {
    key: "render",
    value: function render() {
      var response = this.state.response;
      var _this$props = this.props,
          className = _this$props.className,
          EmptyResponsePlaceholder = _this$props.EmptyResponsePlaceholder,
          ErrorResponsePlaceholder = _this$props.ErrorResponsePlaceholder,
          LoadingResponsePlaceholder = _this$props.LoadingResponsePlaceholder;

      if (response === '') {
        return wp.element.createElement(EmptyResponsePlaceholder, _extends({
          response: response
        }, this.props));
      } else if (!response) {
        return wp.element.createElement(LoadingResponsePlaceholder, _extends({
          response: response
        }, this.props));
      } else if (response.error) {
        return wp.element.createElement(ErrorResponsePlaceholder, _extends({
          response: response
        }, this.props));
      }

      var wrapperClass = typeof className !== 'undefined' && className ? 'ead-block-content-wrapper ' + className : 'ead-block-content-wrapper';
      return wp.element.createElement("div", {
        ref: this.eadRef,
        className: wrapperClass,
        dangerouslySetInnerHTML: {
          __html: response
        }
      });
    }
  }]);

  return EadServerSideRender;
}(Component);

EadServerSideRender.defaultProps = {
  EmptyResponsePlaceholder: function EmptyResponsePlaceholder(_ref) {
    var className = _ref.className;
    return wp.element.createElement(Placeholder, {
      label: __('Document', 'embed-any-document'),
      icon: _icon__WEBPACK_IMPORTED_MODULE_0__["default"].block,
      className: className
    }, __('No document found!', 'embed-any-document'));
  },
  ErrorResponsePlaceholder: function ErrorResponsePlaceholder(_ref2) {
    var response = _ref2.response,
        className = _ref2.className;
    var errorMessage = sprintf( // translators: %s: error message describing the problem
    __('Error loading the document: %s', 'embed-any-document'), response.errorMsg);
    return wp.element.createElement(Placeholder, {
      label: __('Document', 'embed-any-document'),
      icon: _icon__WEBPACK_IMPORTED_MODULE_0__["default"].block,
      className: className
    }, errorMessage);
  },
  LoadingResponsePlaceholder: function LoadingResponsePlaceholder(_ref3) {
    var className = _ref3.className;
    return wp.element.createElement(Placeholder, {
      className: className
    }, wp.element.createElement(Spinner, null));
  }
};
/* harmony default export */ __webpack_exports__["default"] = (EadServerSideRender);

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

var EadHelper = /*#__PURE__*/function () {
  function EadHelper() {
    _classCallCheck(this, EadHelper);
  }

  _createClass(EadHelper, null, [{
    key: "getFileSource",
    value: function getFileSource(url) {
      var source = 'internal';
      var siteUrl = emebeder.site_url;

      if (url.indexOf(siteUrl) === -1) {
        if (url.indexOf('dropbox.com') !== -1) {
          source = 'dropbox';
        } else {
          source = 'external';
        }
      }

      return source;
    }
  }, {
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
    key: "getFileExtension",
    value: function getFileExtension(url) {
      var ext = '.' + url.split('.').pop();
      var extSplitted = ext.split('?');
      return extSplitted[0];
    }
  }, {
    key: "isValidMSExtension",
    value: function isValidMSExtension(url) {
      var validExt = emebeder.msextension.split(',');
      return jQuery.inArray(this.getFileExtension(url), validExt) !== -1;
    }
  }, {
    key: "isPDF",
    value: function isPDF(url) {
      if (this.getFileExtension(url) === '.pdf') {
        return true;
      } else {
        return false;
      }
    }
  }]);

  return EadHelper;
}();

/* harmony default export */ __webpack_exports__["default"] = (EadHelper);

/***/ }),

/***/ "./blocks/document/modules/icon.js":
/*!*****************************************!*\
  !*** ./blocks/document/modules/icon.js ***!
  \*****************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
var icon = {
  block: wp.element.createElement("svg", {
    "aria-hidden": "true",
    role: "img",
    focusable: "false",
    "class": "dashicon dashicons-media-document",
    xmlns: "http://www.w3.org/2000/svg",
    width: "20",
    height: "20",
    viewBox: "0 0 20 20"
  }, wp.element.createElement("path", {
    d: "M12 2l4 4v12H4V2h8zM5 3v1h6V3H5zm7 3h3l-3-3v3zM5 5v1h6V5H5zm10 3V7H5v1h10zM5 9v1h4V9H5zm10 3V9h-5v3h5zM5 11v1h4v-1H5zm10 3v-1H5v1h10zm-3 2v-1H5v1h7z"
  }))
};
/* harmony default export */ __webpack_exports__["default"] = (icon);

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
function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function () { var Super = _getPrototypeOf(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = _getPrototypeOf(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _possibleConstructorReturn(this, result); }; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Date.prototype.toString.call(Reflect.construct(Date, [], function () {})); return true; } catch (e) { return false; } }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }


var __ = wp.i18n.__;
var Component = wp.element.Component;
var InspectorControls = wp.editor.InspectorControls;
var _wp$components = wp.components,
    PanelBody = _wp$components.PanelBody,
    TextControl = _wp$components.TextControl,
    SelectControl = _wp$components.SelectControl,
    ToggleControl = _wp$components.ToggleControl,
    Disabled = _wp$components.Disabled;

var EadInspector = /*#__PURE__*/function (_Component) {
  _inherits(EadInspector, _Component);

  var _super = _createSuper(EadInspector);

  function EadInspector() {
    var _this;

    _classCallCheck(this, EadInspector);

    _this = _super.apply(this, arguments);
    _this.downloadControlhandle = _this.downloadControlhandle.bind(_assertThisInitialized(_this));
    _this.viewerControlHandle = _this.viewerControlHandle.bind(_assertThisInitialized(_this));
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
      var viewerOptions = [];
      var downloadTextControl = null;
      var enableViewerControl = viewer && jQuery.inArray(viewer, emebeder.viewers) !== -1;

      if (enableViewerControl) {
        viewerOptions = [{
          value: 'google',
          label: __('Google Docs Viewer', 'embed-any-document')
        }];

        if (_helper__WEBPACK_IMPORTED_MODULE_0__["default"].isValidMSExtension(url)) {
          viewerOptions.push({
            value: 'microsoft',
            label: __('Microsoft Office Online', 'embed-any-document')
          });
        }

        var fileSrc = _helper__WEBPACK_IMPORTED_MODULE_0__["default"].getFileSource(url);

        if (_helper__WEBPACK_IMPORTED_MODULE_0__["default"].isPDF(url) && fileSrc !== 'dropbox') {
          viewerOptions.push({
            value: 'browser',
            label: __('Browser Based', 'embed-any-document')
          });

          if (jQuery.inArray('built-in', emebeder.viewers) !== -1 && fileSrc === 'internal') {
            viewerOptions.push({
              value: 'built-in',
              label: __('Built-In Viewer', 'embed-any-document')
            });
          }
        }

        downloadTextControl = wp.element.createElement(TextControl, {
          label: __('Download Text', 'embed-any-document'),
          help: __('Default download button text', 'embed-any-document'),
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
      }

      return wp.element.createElement(InspectorControls, null, wp.element.createElement(PanelBody, null, wp.element.createElement(TextControl, {
        label: __('Width', 'embed-any-document'),
        help: __('Width of document either in px or in %', 'embed-any-document'),
        value: width,
        onChange: function onChange(width) {
          return setAttributes({
            width: width
          });
        }
      })), wp.element.createElement(PanelBody, null, wp.element.createElement(TextControl, {
        label: __('Height', 'embed-any-document'),
        help: __('Height of document either in px or in %', 'embed-any-document'),
        value: height,
        onChange: function onChange(height) {
          return setAttributes({
            height: height
          });
        }
      })), wp.element.createElement(PanelBody, null, wp.element.createElement(SelectControl, {
        label: __('Show Download Link', 'embed-any-document'),
        options: [{
          value: 'all',
          label: __('For all users', 'embed-any-document')
        }, {
          value: 'logged',
          label: __('For Logged-in users', 'embed-any-document')
        }, {
          value: 'none',
          label: __('No Download', 'embed-any-document')
        }],
        value: download,
        onChange: this.downloadControlhandle
      })), wp.element.createElement(PanelBody, null, downloadTextControl), wp.element.createElement(PanelBody, null, wp.element.createElement(SelectControl, {
        label: __('Viewer', 'embed-any-document'),
        options: viewerOptions,
        value: viewer,
        onChange: this.viewerControlHandle
      })), !this.state.cacheHidden && wp.element.createElement(PanelBody, null, wp.element.createElement(ToggleControl, {
        label: __('Cache', 'embed-any-document'),
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