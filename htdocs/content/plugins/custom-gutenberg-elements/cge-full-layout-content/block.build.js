!function(e){function t(l){if(n[l])return n[l].exports;var a=n[l]={i:l,l:!1,exports:{}};return e[l].call(a.exports,a,a.exports,t),a.l=!0,a.exports}var n={};t.m=e,t.c=n,t.d=function(e,n,l){t.o(e,n)||Object.defineProperty(e,n,{configurable:!1,enumerable:!0,get:l})},t.n=function(e){var n=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(n,"a",n),n},t.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},t.p="",t(t.s=0)}([function(e,t){function n(e){if(null==e)throw new TypeError("Cannot destructure undefined")}var l=wp.i18n,a=l.__,r=(l.setLocaleData,wp.blocks.registerBlockType),c=wp.editor.InnerBlocks,o=[["custom-gutenberg-elements/cge-layout-content",{}]];r("custom-gutenberg-elements/cge-full-layout-content",{title:a("CGE: Full - Layout Content","custom-gutenberg-elements"),icon:"index-card",category:"layout",attributes:{},edit:function(e){var t=e.className;n(e.attributes);e.setAttributes;return wp.element.createElement("div",{className:t+" cge-bloc-container"},wp.element.createElement("div",{className:"alert alert-info alert-orange-light"},wp.element.createElement("div",{className:"cge-bloc-title"},wp.element.createElement("span",{className:"title"},"CGE: Full Layout Content")),wp.element.createElement("div",{className:"row"},wp.element.createElement("div",{className:"col-sm-12"},wp.element.createElement(c,{template:o,templateLock:!1})))))},save:function(e){var t=e.className;return n(e.attributes),wp.element.createElement("div",{id:"layout",className:"layout container-fluid "+t},wp.element.createElement("div",{className:"layout-body inner"},wp.element.createElement("div",{className:"row"},wp.element.createElement(c.Content,null))))}})}]);