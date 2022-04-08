<style type="text/css">
/* ------------------------------------------------------------------------------
 *
 *  # Core layout
 *
 *  Content area, sidebar, page header and boxed layout styles
 *
 * ---------------------------------------------------------------------------- */
/* ------------------------------------------------------------------------------
 *
 *  # Global configuration
 *
 *  Here you can change main theme, enable or disable certain components and
 *  optional styles. This allows you to include only components that you need.
 *
 *  'true'  - enables component and includes it to main CSS file.
 *  'false' - disables component and excludes it from main CSS file.
 *
 *  Layout helper: @if $layout == 'base' {...}
 *  Theme helper: @if $theme == 'material' {...}
 *  Component helper: @if $enable-* {...}
 *
 * ---------------------------------------------------------------------------- */
/* ------------------------------------------------------------------------------
 *
 *  # Custom Limitless functions
 *
 *  Utility mixins and functions for evalutating source code across our variables, maps, and mixins.
 *
 * ---------------------------------------------------------------------------- */
/* ------------------------------------------------------------------------------
 *
 *  # Custom template mixins
 *
 *  All custom mixins are prefixed with "ll-" to avoid conflicts
 *
 * ---------------------------------------------------------------------------- */
/* ------------------------------------------------------------------------------
 *
 *  # Main colors
 *
 *  List of the template main color palettes
 *
 * ---------------------------------------------------------------------------- */
/* ------------------------------------------------------------------------------
 *
 *  # Default Bootstrap variable overrides
 *
 *  Variables should follow the `$component-state-property-size` formula for
 *  consistent naming. Ex: $nav-link-disabled-color and $modal-content-box-shadow-xs.
 *  Also includes custom variables, all marked with "!default" flag.
 *
 * ---------------------------------------------------------------------------- */
/* ------------------------------------------------------------------------------
 *
 *  # Additional variables
 *
 *  Mainly 3rd party libraries and additional variables for default
 *  Bootstrap components.
 *
 * ---------------------------------------------------------------------------- */
/* ------------------------------------------------------------------------------
 *
 *  # Mixins
 *
 *  Import Bootstrap mixins with overrides
 *
 * ---------------------------------------------------------------------------- */
/* ------------------------------------------------------------------------------
 *
 *  # Badge mixin
 *
 *  Override and extend default badge mixin.
 *
 * ---------------------------------------------------------------------------- */
/* ------------------------------------------------------------------------------
 *
 *  # Buttons mixin
 *
 *  Override and extend default buttons mixin.
 *
 * ---------------------------------------------------------------------------- */
/* ------------------------------------------------------------------------------
 *
 *  # Caret mixin
 *
 *  Override and extend default cared mixin.
 *
 * ---------------------------------------------------------------------------- */
/* ------------------------------------------------------------------------------
 *
 *  # Forms mixin
 *
 *  Override and extend default forms mixin.
 *
 * ---------------------------------------------------------------------------- */
/* ------------------------------------------------------------------------------
 *
 *  # Background mixin
 *
 *  Override and extend default background mixin.
 *
 * ---------------------------------------------------------------------------- */
/* ------------------------------------------------------------------------------
 *
 *  # Main content layout
 *
 *  Styles for main structure of content area
 *
 * ---------------------------------------------------------------------------- */
/*
html {
  display: -ms-flexbox;
  display: flex;
  -ms-flex-direction: column;
      flex-direction: column; }

body {
  min-height: 100vh;
  display: -ms-flexbox;
  display: flex;
  -ms-flex-direction: column;
      flex-direction: column;
  -ms-flex: 1;
      flex: 1; }

.page-content {
  display: -ms-flexbox;
  display: flex;
  -ms-flex-positive: 1;
      flex-grow: 1; }

.content-wrapper {
  display: -ms-flexbox;
  display: flex;
  -ms-flex-direction: column;
      flex-direction: column;
  -ms-flex: 1;
      flex: 1;
  overflow: auto; }
*/
.content {
  padding: 1.25rem 1.25rem;
  -ms-flex-positive: 1;
      flex-grow: 1; }
  .content::after {
    display: block;
    clear: both;
    content: ""; }

/* ------------------------------------------------------------------------------
 *
 *  # Page header
 *
 *  Page header components and color options
 *
 * ---------------------------------------------------------------------------- */
.page-title {
  padding: 2rem 0;
  position: relative; }
  .page-title small {
    display: inline-block;
    margin-left: 0.625rem; }
    .page-title small:before {
      content: '/';
      margin-right: 0.875rem; }
    .page-title small.d-block {
      margin-left: 0; }
      .page-title small.d-block:before {
        content: none; }
  .page-title small.d-block,
  .page-title .breadcrumb {
    margin-left: 1.875rem; }
  .page-title h1, .page-title h2, .page-title h3, .page-title h4, .page-title h5, .page-title h6 {
    margin: 0; }

.page-header-content {
  position: relative;
  padding: 0 1.25rem; }
  .page-header-content[class*=border-bottom-] + .breadcrumb-line {
    border-top: 0; }

.page-header-light {
  background-color: #fff;
  border-bottom: 1px solid #ddd; }
  .page-header-light.has-cover {
    background: url(<?php print $this->RES_ROOT;?>images/backgrounds/seamless.png); }

.page-header-dark {
  background-color: #273246;
  color: #fff;
  margin-bottom: 1.25rem; }
  .page-header-dark > .breadcrumb > li > a,
  .page-header-dark > .breadcrumb > li + li:before,
  .page-header-dark .page-header-content .breadcrumb > li > a,
  .page-header-dark .page-header-content .breadcrumb > li + li:before {
    color: rgba(255, 255, 255, 0.9); }
  .page-header-dark > .breadcrumb > li > a:hover, .page-header-dark > .breadcrumb > li > a:focus,
  .page-header-dark .page-header-content .breadcrumb > li > a:hover,
  .page-header-dark .page-header-content .breadcrumb > li > a:focus {
    color: #fff;
    opacity: 1; }
  .page-header-dark > .breadcrumb > .active,
  .page-header-dark .page-header-content .breadcrumb > .active {
    color: rgba(255, 255, 255, 0.5); }
  .page-header-dark.has-cover {
    background: url(<?php print $this->RES_ROOT;?>images/login_cover.jpg);
    background-size: cover; }

/* ------------------------------------------------------------------------------
 *
 *  # Sidebar layouts
 *
 *  Sidebar components, main navigation and sidebar itself
 *
 * ---------------------------------------------------------------------------- */
.sidebar {
  -ms-flex: 0 0 auto;
      flex: 0 0 auto;
  width: 16.875rem;
  z-index: 1040; }
  .sidebar:not(.sidebar-component) {
    position: fixed;
    top: 0;
    bottom: 0;
    box-sizing: content-box;
    transition: all ease-in-out 0.15s; }
    @media screen and (prefers-reduced-motion: reduce) {
      .sidebar:not(.sidebar-component) {
        transition: none; } }
  .sidebar-main,
  .sidebar-main .sidebar-content, .sidebar-secondary,
  .sidebar-secondary .sidebar-content {
    left: -18.5625rem; }
  .sidebar-right,
  .sidebar-right .sidebar-content {
    right: -18.5625rem; }
  .sidebar.sidebar-fullscreen {
    width: 100%; }

.sidebar:not(.sidebar-component) .sidebar-content {
  position: fixed;
  top: 3.12503rem;
  bottom: 0;
  width: inherit;
  overflow-y: scroll;
  -webkit-overflow-scrolling: touch;
  transition: left ease-in-out 0.15s, right ease-in-out 0.15s; }
  @media screen and (prefers-reduced-motion: reduce) {
    .sidebar:not(.sidebar-component) .sidebar-content {
      transition: none; } }
  .navbar-lg:first-child ~ .page-content .sidebar:not(.sidebar-component) .sidebar-content {
    top: 3.37503rem; }
  .navbar-sm:first-child ~ .page-content .sidebar:not(.sidebar-component) .sidebar-content {
    top: 2.87503rem; }

.sidebar-mobile-main .sidebar-main {
  box-shadow: 0.25rem 0 1rem rgba(0, 0, 0, 0.35); }
  .sidebar-mobile-main .sidebar-main,
  .sidebar-mobile-main .sidebar-main .sidebar-content {
    left: 0; }

.sidebar-mobile-secondary .sidebar-secondary {
  box-shadow: 0.25rem 0 1rem rgba(0, 0, 0, 0.35); }
  .sidebar-mobile-secondary .sidebar-secondary,
  .sidebar-mobile-secondary .sidebar-secondary .sidebar-content {
    left: 0; }

.sidebar-mobile-right .sidebar-right {
  display: block;
  box-shadow: -0.25rem 0 1rem rgba(0, 0, 0, 0.35); }
  .sidebar-mobile-right .sidebar-right,
  .sidebar-mobile-right .sidebar-right .sidebar-content {
    right: 0; }

.sidebar-mobile-component .sidebar-component {
  display: block; }

.nav-sidebar {
  -ms-flex-direction: column;
      flex-direction: column; }
  .nav-sidebar .nav-item:not(.nav-item-header):first-child {
    padding-top: 0.5rem; }
  .nav-sidebar .nav-item:not(.nav-item-header):last-child {
    padding-bottom: 0.5rem; }
  .nav-sidebar .nav-item:not(.nav-item-divider) {
    margin-bottom: 1px; }
    .nav-sidebar .nav-item:not(.nav-item-divider):last-child {
      margin-bottom: 0; }
  .nav-sidebar > .nav-item > .nav-link {
    font-weight: 500; }
  .nav-sidebar .nav-link {
    position: relative;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-align: start;
        align-items: flex-start;
    padding: 0.75rem 1.25rem;
    transition: background-color ease-in-out 0.15s, color ease-in-out 0.15s; }
    @media screen and (prefers-reduced-motion: reduce) {
      .nav-sidebar .nav-link {
        transition: none; } }
    .nav-sidebar .nav-link i {
      margin-right: 1.25rem;
      margin-top: 0.12502rem;
      margin-bottom: 0.12502rem;
      top: 0; }
    .nav-sidebar .nav-link .badge {
      transition: background-color ease-in-out 0.15s, border-color ease-in-out 0.15s; }
      @media screen and (prefers-reduced-motion: reduce) {
        .nav-sidebar .nav-link .badge {
          transition: none; } }
    .nav-sidebar .nav-link.disabled, .nav-sidebar .nav-link.disabled:hover, .nav-sidebar .nav-link.disabled:focus {
      background-color: transparent;
      opacity: 0.5; }
  .nav-sidebar .nav-item-header {
    padding: 0.75rem 1.25rem;
    margin-top: 0.5rem; }
    .nav-sidebar .nav-item-header > i {
      display: none; }
  .nav-sidebar .nav-item-divider {
    margin: 0.5rem 0;
    height: 1px; }

.nav-item-submenu > .nav-link {
  padding-right: 2.75rem; }
  .nav-item-submenu > .nav-link:after {
    content: '\e9c7';
    font-family: "icomoon";
    display: inline-block;
    font-size: 1rem;
    vertical-align: middle;
    line-height: 1;
    position: absolute;
    top: 0.75rem;
    margin-top: 0.12502rem;
    right: 1.25rem;
    transition: -webkit-transform 0.25s ease-in-out;
    transition: transform 0.25s ease-in-out;
    transition: transform 0.25s ease-in-out, -webkit-transform 0.25s ease-in-out;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale; }
    @media screen and (prefers-reduced-motion: reduce) {
      .nav-item-submenu > .nav-link:after {
        transition: none; } }

.nav-item-submenu.nav-item-open > .nav-link:after {
  -webkit-transform: rotate(90deg);
          transform: rotate(90deg); }

.nav-group-sub {
  display: none; }
  .nav-group-sub .nav-link {
    padding: 0.625rem 1.25rem 0.625rem 3.5rem; }
  .nav-group-sub .nav-group-sub .nav-link {
    padding-left: 4.75rem; }
  .nav-group-sub .nav-group-sub .nav-group-sub .nav-link {
    padding-left: 6rem; }
  .nav-group-sub .nav-item-submenu > .nav-link:after {
    top: 0.625rem; }
  .nav-item-expanded > .nav-group-sub {
    display: block; }

.nav-scrollspy .nav-link.active + .nav,
.nav-scrollspy .nav-item-open .nav-link:not(.active) + .nav {
  display: block; }

.nav-scrollspy .nav-link.active:after {
  -webkit-transform: rotate(90deg);
          transform: rotate(90deg); }

.nav-sidebar-icons-reverse .nav-link {
  padding-right: 3.5rem; }
  .nav-sidebar-icons-reverse .nav-link i {
    position: absolute;
    top: 0.75rem;
    right: 1.25rem;
    margin-right: 0; }

.nav-sidebar-icons-reverse .nav-item-submenu .nav-link {
  padding-right: 4.5rem; }
  .nav-sidebar-icons-reverse .nav-item-submenu .nav-link:after {
    right: 3.25rem; }

.nav-sidebar-icons-reverse .nav-group-sub .nav-link {
  padding-left: 2.5rem; }
  .nav-sidebar-icons-reverse .nav-group-sub .nav-link i {
    top: 0.625rem; }

.nav-sidebar-icons-reverse .nav-group-sub .nav-group-sub .nav-link {
  padding-left: 3.75rem; }

.nav-sidebar-icons-reverse .nav-group-sub .nav-group-sub .nav-group-sub .nav-link {
  padding-left: 5rem; }

.nav-sidebar-bordered > .nav-item + .nav-item:not(.nav-item-divider) {
  margin-bottom: 0; }

.sidebar .card:first-child .nav-sidebar-bordered {
  padding-top: 0; }
  .sidebar .card:first-child .nav-sidebar-bordered > .nav-item:first-child,
  .sidebar .card:first-child .nav-sidebar-bordered > .nav-item-header:first-child {
    border-top: 0; }

.sidebar .card:last-child .nav-sidebar-bordered {
  padding-bottom: 0; }
  .sidebar .card:last-child .nav-sidebar-bordered > .nav-item:last-child,
  .sidebar .card:last-child .nav-sidebar-bordered > .nav-item-header:last-child {
    border-bottom: 0; }

.sidebar-dark .nav-sidebar-bordered > .nav-item {
  border-top: 1px solid rgba(255, 255, 255, 0.1); }
  .sidebar-dark .nav-sidebar-bordered > .nav-item:last-child {
    border-bottom: 1px solid rgba(255, 255, 255, 0.1); }

.sidebar-dark .nav-sidebar-bordered > .nav-item-header {
  background-color: rgba(0, 0, 0, 0.1);
  border-top: 1px solid rgba(255, 255, 255, 0.1);
  margin: 0; }

.sidebar-light .nav-sidebar-bordered > .nav-item {
  border-top: 1px solid #eee; }
  .sidebar-light .nav-sidebar-bordered > .nav-item:last-child {
    border-bottom: 1px solid #eee; }

.sidebar-light .nav-sidebar-bordered > .nav-item-header {
  background-color: #fafafa;
  border-top: 1px solid #eee;
  margin: 0; }

.sidebar-mobile-toggler {
  display: -ms-flexbox;
  display: flex;
  -ms-flex-pack: justify;
      justify-content: space-between;
  -ms-flex-align: center;
      align-items: center;
  border-top: 1px solid transparent;
  border-bottom: 1px solid transparent; }
  .sidebar-mobile-toggler a {
    padding: 0.875rem 1.25rem;
    transition: all ease-in-out 0.15s; }
    @media screen and (prefers-reduced-motion: reduce) {
      .sidebar-mobile-toggler a {
        transition: none; } }
    .navbar-lg:first-child ~ .page-content .sidebar-mobile-toggler a {
      padding: 1rem 1.25rem; }
    .navbar-sm:first-child ~ .page-content .sidebar-mobile-toggler a {
      padding: 0.75rem 1.25rem; }

.sidebar-mobile-expand i:last-child:not(:first-child) {
  display: none; }

.sidebar-fullscreen .sidebar-mobile-expand i:first-child {
  display: none; }

.sidebar-fullscreen .sidebar-mobile-expand i:last-child {
  display: inline-block; }

.sidebar-dark {
  background-color: #263238;
  color: #fff; }
  .sidebar-dark .sidebar-mobile-toggler {
    color: rgba(255, 255, 255, 0.9);
    border-bottom-color: rgba(255, 255, 255, 0.1); }
    .sidebar-dark .sidebar-mobile-toggler:not([class*=bg-]) {
      background-color: #1e272c; }
    .sidebar-dark .sidebar-mobile-toggler a {
      color: rgba(255, 255, 255, 0.9); }
      .sidebar-dark .sidebar-mobile-toggler a:hover {
        color: #fff;
        background-color: transparent; }
  .sidebar-dark .card-header {
    border-color: rgba(255, 255, 255, 0.1); }

.sidebar-dark .nav-sidebar .nav-link,
.sidebar-light .card[class*=bg-]:not(.bg-light):not(.bg-white):not(.bg-transparent) .nav-sidebar .nav-link {
  color: rgba(255, 255, 255, 0.9); }
  .sidebar-dark .nav-sidebar .nav-link:not(.disabled):hover,
  .sidebar-light .card[class*=bg-]:not(.bg-light):not(.bg-white):not(.bg-transparent) .nav-sidebar .nav-link:not(.disabled):hover {
    color: #fff;
    background-color: rgba(0, 0, 0, 0.15); }

.sidebar-dark .nav-sidebar .nav-item > .nav-link.active,
.sidebar-light .card[class*=bg-]:not(.bg-light):not(.bg-white):not(.bg-transparent) .nav-sidebar .nav-item > .nav-link.active {
  background-color: rgba(0, 0, 0, 0.15);
  color: #fff; }

.sidebar-dark .nav-sidebar .nav-item-open > .nav-link:not(.disabled),
.sidebar-dark .nav-sidebar > .nav-item-expanded:not(.nav-item-open) > .nav-link,
.sidebar-light .card[class*=bg-]:not(.bg-light):not(.bg-white):not(.bg-transparent) .nav-sidebar .nav-item-open > .nav-link:not(.disabled),
.sidebar-light .card[class*=bg-]:not(.bg-light):not(.bg-white):not(.bg-transparent) .nav-sidebar > .nav-item-expanded:not(.nav-item-open) > .nav-link {
  background-color: rgba(0, 0, 0, 0.15);
  color: #fff; }

.sidebar-dark .nav-sidebar > .nav-item-open > .nav-link:not(.disabled),
.sidebar-dark .nav-sidebar > .nav-item > .nav-link.active,
.sidebar-light .card[class*=bg-]:not(.bg-light):not(.bg-white):not(.bg-transparent) .nav-sidebar > .nav-item-open > .nav-link:not(.disabled),
.sidebar-light .card[class*=bg-]:not(.bg-light):not(.bg-white):not(.bg-transparent) .nav-sidebar > .nav-item > .nav-link.active {
  background-color: #26A69A;
  color: #fff; }

.sidebar-dark .nav-sidebar .nav-item-header,
.sidebar-light .card[class*=bg-]:not(.bg-light):not(.bg-white):not(.bg-transparent) .nav-sidebar .nav-item-header {
  color: rgba(255, 255, 255, 0.5); }

.sidebar-dark .nav-sidebar .nav-item-divider,
.sidebar-light .card[class*=bg-]:not(.bg-light):not(.bg-white):not(.bg-transparent) .nav-sidebar .nav-item-divider {
  background-color: rgba(255, 255, 255, 0.1); }

.sidebar-dark .nav-sidebar > .nav-item-submenu > .nav-group-sub,
.sidebar-light .card[class*=bg-]:not(.bg-light):not(.bg-white):not(.bg-transparent) .nav-sidebar > .nav-item-submenu > .nav-group-sub {
  background-color: rgba(0, 0, 0, 0.15); }

.sidebar-dark[class*=bg-] .nav-sidebar > .nav-item-open > .nav-link:not(.disabled),
.sidebar-dark[class*=bg-] .nav-sidebar > .nav-item > .nav-link.active,
.sidebar .card[class*=bg-]:not(.bg-light):not(.bg-white):not(.bg-transparent) .nav-sidebar > .nav-item-open > .nav-link:not(.disabled),
.sidebar .card[class*=bg-]:not(.bg-light):not(.bg-white):not(.bg-transparent) .nav-sidebar > .nav-item > .nav-link.active {
  background-color: rgba(0, 0, 0, 0.3); }

.sidebar-light {
  background-color: #fff;
  color: #333;
  border-right: 1px solid rgba(0, 0, 0, 0.125);
  background-clip: content-box; }
  .sidebar-light.sidebar-right {
    border-right: 0;
    border-left: 1px solid rgba(0, 0, 0, 0.125); }
  .sidebar-light .nav-sidebar .nav-link {
    color: rgba(51, 51, 51, 0.85); }
    .sidebar-light .nav-sidebar .nav-link:not(.disabled):hover {
      color: #333;
      background-color: #f5f5f5; }
  .sidebar-light .nav-sidebar .nav-item > .nav-link.active {
    background-color: #f5f5f5;
    color: #333; }
  .sidebar-light .nav-sidebar .nav-item-open > .nav-link:not(.disabled),
  .sidebar-light .nav-sidebar > .nav-item-expanded:not(.nav-item-open) > .nav-link {
    background-color: #f5f5f5;
    color: #333; }
  .sidebar-light .nav-sidebar > .nav-item-open > .nav-link:not(.disabled),
  .sidebar-light .nav-sidebar > .nav-item > .nav-link.active {
    background-color: #f5f5f5;
    color: #333; }
  .sidebar-light .nav-sidebar .nav-item-header {
    color: rgba(51, 51, 51, 0.5); }
  .sidebar-light .nav-sidebar .nav-item-divider {
    background-color: rgba(0, 0, 0, 0.125); }
  .sidebar-light .nav-sidebar > .nav-item-submenu > .nav-group-sub {
    background-color: transparent; }
  .sidebar-light .sidebar-mobile-toggler {
    color: rgba(51, 51, 51, 0.8);
    border-bottom-color: rgba(0, 0, 0, 0.125); }
    .sidebar-light .sidebar-mobile-toggler:not([class*=bg-]) {
      background-color: whitesmoke; }
    .sidebar-light .sidebar-mobile-toggler a {
      color: rgba(51, 51, 51, 0.8); }
      .sidebar-light .sidebar-mobile-toggler a:hover {
        color: #333;
        background-color: transparent; }
    .sidebar-light .sidebar-mobile-toggler[class*=bg-]:not(.bg-white):not(.bg-light):not(.bg-transparent) {
      color: rgba(255, 255, 255, 0.9);
      border-bottom-color: rgba(255, 255, 255, 0.1); }
      .sidebar-light .sidebar-mobile-toggler[class*=bg-]:not(.bg-white):not(.bg-light):not(.bg-transparent) a {
        color: rgba(255, 255, 255, 0.9); }
        .sidebar-light .sidebar-mobile-toggler[class*=bg-]:not(.bg-white):not(.bg-light):not(.bg-transparent) a:hover {
          color: #fff;
          background-color: transparent; }

.sidebar-component {
  display: none;
  width: 100%;
  border: 1px solid transparent;
  border-radius: 0.1875rem;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05); }
  .sidebar-component.sidebar-dark {
    border-color: rgba(255, 255, 255, 0.1); }
  .sidebar-component.sidebar-light {
    border-color: rgba(0, 0, 0, 0.125); }

@media (min-width: 768px) {
  .sidebar-xs .sidebar-main {
    width: 3.5rem; }
    .sidebar-xs .sidebar-main .sidebar-content::-webkit-scrollbar {
      width: 0 !important; }
    .sidebar-xs .sidebar-main .card:not(.card-sidebar-mobile),
    .sidebar-xs .sidebar-main .card-title {
      display: none; }
    .sidebar-xs .sidebar-main .card-header h6 + .header-elements {
      padding-top: 0.22117rem;
      padding-bottom: 0.22117rem; }
    .sidebar-xs .sidebar-main .card-header h5 + .header-elements {
      padding-top: 0.31733rem;
      padding-bottom: 0.31733rem; }
    .sidebar-xs .sidebar-main .nav-sidebar > .nav-item {
      position: relative;
      margin: 0; }
      .sidebar-xs .sidebar-main .nav-sidebar > .nav-item > .nav-link {
        -ms-flex-pack: center;
            justify-content: center;
        padding-left: 0;
        padding-right: 0; }
        .sidebar-xs .sidebar-main .nav-sidebar > .nav-item > .nav-link > i {
          position: static;
          margin-left: 0;
          margin-right: 0;
          display: block;
          padding-bottom: 1px; }
        .sidebar-xs .sidebar-main .nav-sidebar > .nav-item > .nav-link > span {
          display: none; }
    .sidebar-xs .sidebar-main .nav-sidebar > .nav-item-submenu:hover > .nav-group-sub, .sidebar-xs .sidebar-main .nav-sidebar > .nav-item-submenu:focus > .nav-group-sub {
      display: block !important; }
    .sidebar-xs .sidebar-main .nav-sidebar > .nav-item-submenu > .nav-group-sub {
      position: absolute;
      top: -0.5rem;
      right: -16.875rem;
      width: 16.875rem;
      display: none;
      z-index: 1000;
      box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
      border-top-right-radius: 0.1875rem;
      border-bottom-right-radius: 0.1875rem; }
      .sidebar-xs .sidebar-main .nav-sidebar > .nav-item-submenu > .nav-group-sub[data-submenu-title]:before {
        content: attr(data-submenu-title);
        display: block;
        padding: 0.75rem 1.25rem;
        padding-bottom: 0;
        margin-top: 0.5rem;
        opacity: 0.5; }
    .sidebar-xs .sidebar-main .nav-sidebar > .nav-item-submenu > .nav-link:after {
      content: none; }
    .sidebar-xs .sidebar-main .nav-sidebar .nav-group-sub .nav-link {
      padding-left: 1.25rem; }
    .sidebar-xs .sidebar-main .nav-sidebar .nav-group-sub .nav-group-sub .nav-link {
      padding-left: 2.25rem; }
    .sidebar-xs .sidebar-main .nav-sidebar .nav-group-sub .nav-group-sub .nav-group-sub .nav-link {
      padding-left: 3.5rem; }
    .sidebar-xs .sidebar-main .nav-sidebar > .nav-item-header {
      padding: 0;
      text-align: center; }
      .sidebar-xs .sidebar-main .nav-sidebar > .nav-item-header > i {
        display: block;
        top: 0;
        padding: 0.75rem 1.25rem;
        margin-top: 0.12502rem;
        margin-bottom: 0.12502rem; }
      .sidebar-xs .sidebar-main .nav-sidebar > .nav-item-header > div {
        display: none; }
    .sidebar-xs .sidebar-main .nav-sidebar > .nav-item-open > .nav-group-sub {
      display: none !important; }
    .sidebar-xs .sidebar-main .nav-sidebar > .nav-item:hover > .nav-link.disabled + .nav-group-sub,
    .sidebar-xs .sidebar-main .nav-sidebar > .nav-item:hover > .nav-link.disabled > span, .sidebar-xs .sidebar-main .nav-sidebar > .nav-item:focus > .nav-link.disabled + .nav-group-sub,
    .sidebar-xs .sidebar-main .nav-sidebar > .nav-item:focus > .nav-link.disabled > span {
      display: none !important; }
    .sidebar-xs .sidebar-main .sidebar-user .card-body {
      padding-left: 0;
      padding-right: 0; }
    .sidebar-xs .sidebar-main .sidebar-user .media {
      -ms-flex-pack: center;
          justify-content: center; }
      .sidebar-xs .sidebar-main .sidebar-user .media > div:not(:first-child) {
        display: none !important; }
      .sidebar-xs .sidebar-main .sidebar-user .media > div:first-child {
        margin: 0 !important; }
    .sidebar-xs .sidebar-main .nav-item-submenu-reversed .nav-group-sub {
      top: auto !important;
      bottom: 0; }
    .sidebar-xs .sidebar-main.sidebar-dark .nav-sidebar > .nav-item:not(.nav-item-open):hover > .nav-link:not(.active):not(.disabled) {
      color: #fff;
      background-color: rgba(0, 0, 0, 0.15); }
    .sidebar-xs .sidebar-main.sidebar-dark .nav-sidebar > .nav-item-submenu > .nav-group-sub {
      background-color: #304047;
      border-left: 1px solid rgba(255, 255, 255, 0.1); }
    .sidebar-xs .sidebar-main.sidebar-light .nav-sidebar > .nav-item:not(.nav-item-open):hover > .nav-link:not(.active):not(.disabled) {
      color: #333;
      background-color: #f5f5f5; }
    .sidebar-xs .sidebar-main.sidebar-light .nav-sidebar > .nav-item-submenu > .nav-group-sub {
      background-color: #fcfcfc;
      border: 1px solid rgba(0, 0, 0, 0.125); }
  .sidebar-xs .sidebar-main.sidebar-fixed {
    z-index: 1029; }
    .sidebar-xs .sidebar-main.sidebar-fixed .nav-sidebar > .nav-item-submenu:hover > .nav-group-sub, .sidebar-xs .sidebar-main.sidebar-fixed .nav-sidebar > .nav-item-submenu:focus > .nav-group-sub {
      position: fixed;
      left: 3.5rem;
      top: 3.12503rem;
      bottom: 0;
      width: 16.875rem;
      overflow-y: auto;
      border-radius: 0; }
  .sidebar-xs .navbar-lg:first-child ~ .page-content .sidebar-fixed.sidebar-main .nav-sidebar > .nav-item-submenu:hover > .nav-group-sub, .sidebar-xs .navbar-lg:first-child ~ .page-content .sidebar-fixed.sidebar-main .nav-sidebar > .nav-item-submenu:focus > .nav-group-sub {
    top: 3.37503rem; }
  .sidebar-xs .navbar-sm:first-child ~ .page-content .sidebar-fixed.sidebar-main .nav-sidebar > .nav-item-submenu:hover > .nav-group-sub, .sidebar-xs .navbar-sm:first-child ~ .page-content .sidebar-fixed.sidebar-main .nav-sidebar > .nav-item-submenu:focus > .nav-group-sub {
    top: 2.87503rem; } }

@media (min-width: 576px) {
  .sidebar-expand-sm.sidebar-main {
    z-index: 99;
    box-shadow: none; }
    .sidebar-expand-sm.sidebar-main .sidebar-content {
      left: 0; }
  .sidebar-expand-sm.sidebar-secondary {
    z-index: 98;
    box-shadow: none; }
    .sidebar-expand-sm.sidebar-secondary .sidebar-content {
      left: 0; }
  .sidebar-expand-sm.sidebar-right {
    z-index: 97;
    box-shadow: none; }
    .sidebar-expand-sm.sidebar-right .sidebar-content {
      right: 0; }
  .sidebar-expand-sm.sidebar-component {
    z-index: 96; }
  .sidebar-expand-sm:not(.sidebar-component) {
    position: static;
    transition: none; } }
  @media screen and (min-width: 576px) and (prefers-reduced-motion: reduce) {
    .sidebar-expand-sm:not(.sidebar-component) {
      transition: none; } }

@media (min-width: 576px) {
    .sidebar-expand-sm:not(.sidebar-component):not(.sidebar-fixed) .sidebar-content {
      position: static;
      overflow: visible;
      width: auto; }
  .sidebar-expand-sm.sidebar-dark:not(.sidebar-component) + .sidebar-dark:not(.sidebar-component) {
    border-left: 1px solid rgba(255, 255, 255, 0.1); }
  .sidebar-expand-sm .sidebar-mobile-toggler {
    display: none; }
  .sidebar-expand-sm.sidebar-fullscreen {
    width: 16.875rem; }
  .sidebar-main-hidden .sidebar-expand-sm.sidebar-main,
  .sidebar-component-hidden .sidebar-expand-sm.sidebar-component,
  .sidebar-secondary-hidden .sidebar-expand-sm.sidebar-secondary,
  .sidebar-mobile-right .sidebar-expand-sm.sidebar-right {
    display: none; }
  .sidebar-expand-sm.sidebar-right {
    display: none; }
    .sidebar-right-visible .sidebar-expand-sm.sidebar-right {
      display: block; }
  .sidebar-expand-sm.sidebar-component {
    display: block;
    width: 16.875rem; }
    .sidebar-expand-sm.sidebar-component-left {
      margin-right: 1.25rem; }
    .sidebar-expand-sm.sidebar-component-right {
      margin-left: 1.25rem; } }

@media (max-width: 575.98px) {
  .sidebar-expand-sm:not(.sidebar-component) {
    border: 0; } }

@media (min-width: 768px) {
  .sidebar-expand-md.sidebar-main {
    z-index: 99;
    box-shadow: none; }
    .sidebar-expand-md.sidebar-main .sidebar-content {
      left: 0; }
  .sidebar-expand-md.sidebar-secondary {
    z-index: 98;
    box-shadow: none; }
    .sidebar-expand-md.sidebar-secondary .sidebar-content {
      left: 0; }
  .sidebar-expand-md.sidebar-right {
    z-index: 97;
    box-shadow: none; }
    .sidebar-expand-md.sidebar-right .sidebar-content {
      right: 0; }
  .sidebar-expand-md.sidebar-component {
    z-index: 96; }
  .sidebar-expand-md:not(.sidebar-component) {
    position: static;
    transition: none; } }
  @media screen and (min-width: 768px) and (prefers-reduced-motion: reduce) {
    .sidebar-expand-md:not(.sidebar-component) {
      transition: none; } }

@media (min-width: 768px) {
    .sidebar-expand-md:not(.sidebar-component):not(.sidebar-fixed) .sidebar-content {
      position: static;
      overflow: visible;
      width: auto; }
  .sidebar-expand-md.sidebar-dark:not(.sidebar-component) + .sidebar-dark:not(.sidebar-component) {
    border-left: 1px solid rgba(255, 255, 255, 0.1); }
  .sidebar-expand-md .sidebar-mobile-toggler {
    display: none; }
  .sidebar-expand-md.sidebar-fullscreen {
    width: 16.875rem; }
  .sidebar-main-hidden .sidebar-expand-md.sidebar-main,
  .sidebar-component-hidden .sidebar-expand-md.sidebar-component,
  .sidebar-secondary-hidden .sidebar-expand-md.sidebar-secondary,
  .sidebar-mobile-right .sidebar-expand-md.sidebar-right {
    display: none; }
  .sidebar-expand-md.sidebar-right {
    display: none; }
    .sidebar-right-visible .sidebar-expand-md.sidebar-right {
      display: block; }
  .sidebar-expand-md.sidebar-component {
    display: block;
    width: 16.875rem; }
    .sidebar-expand-md.sidebar-component-left {
      margin-right: 1.25rem; }
    .sidebar-expand-md.sidebar-component-right {
      margin-left: 1.25rem; } }

@media (max-width: 767.98px) {
  .sidebar-expand-md:not(.sidebar-component) {
    border: 0; } }

@media (min-width: 992px) {
  .sidebar-expand-lg.sidebar-main {
    z-index: 99;
    box-shadow: none; }
    .sidebar-expand-lg.sidebar-main .sidebar-content {
      left: 0; }
  .sidebar-expand-lg.sidebar-secondary {
    z-index: 98;
    box-shadow: none; }
    .sidebar-expand-lg.sidebar-secondary .sidebar-content {
      left: 0; }
  .sidebar-expand-lg.sidebar-right {
    z-index: 97;
    box-shadow: none; }
    .sidebar-expand-lg.sidebar-right .sidebar-content {
      right: 0; }
  .sidebar-expand-lg.sidebar-component {
    z-index: 96; }
  .sidebar-expand-lg:not(.sidebar-component) {
    position: static;
    transition: none; } }
  @media screen and (min-width: 992px) and (prefers-reduced-motion: reduce) {
    .sidebar-expand-lg:not(.sidebar-component) {
      transition: none; } }

@media (min-width: 992px) {
    .sidebar-expand-lg:not(.sidebar-component):not(.sidebar-fixed) .sidebar-content {
      position: static;
      overflow: visible;
      width: auto; }
  .sidebar-expand-lg.sidebar-dark:not(.sidebar-component) + .sidebar-dark:not(.sidebar-component) {
    border-left: 1px solid rgba(255, 255, 255, 0.1); }
  .sidebar-expand-lg .sidebar-mobile-toggler {
    display: none; }
  .sidebar-expand-lg.sidebar-fullscreen {
    width: 16.875rem; }
  .sidebar-main-hidden .sidebar-expand-lg.sidebar-main,
  .sidebar-component-hidden .sidebar-expand-lg.sidebar-component,
  .sidebar-secondary-hidden .sidebar-expand-lg.sidebar-secondary,
  .sidebar-mobile-right .sidebar-expand-lg.sidebar-right {
    display: none; }
  .sidebar-expand-lg.sidebar-right {
    display: none; }
    .sidebar-right-visible .sidebar-expand-lg.sidebar-right {
      display: block; }
  .sidebar-expand-lg.sidebar-component {
    display: block;
    width: 16.875rem; }
    .sidebar-expand-lg.sidebar-component-left {
      margin-right: 1.25rem; }
    .sidebar-expand-lg.sidebar-component-right {
      margin-left: 1.25rem; } }

@media (max-width: 991.98px) {
  .sidebar-expand-lg:not(.sidebar-component) {
    border: 0; } }

@media (min-width: 1200px) {
  .sidebar-expand-xl.sidebar-main {
    z-index: 99;
    box-shadow: none; }
    .sidebar-expand-xl.sidebar-main .sidebar-content {
      left: 0; }
  .sidebar-expand-xl.sidebar-secondary {
    z-index: 98;
    box-shadow: none; }
    .sidebar-expand-xl.sidebar-secondary .sidebar-content {
      left: 0; }
  .sidebar-expand-xl.sidebar-right {
    z-index: 97;
    box-shadow: none; }
    .sidebar-expand-xl.sidebar-right .sidebar-content {
      right: 0; }
  .sidebar-expand-xl.sidebar-component {
    z-index: 96; }
  .sidebar-expand-xl:not(.sidebar-component) {
    position: static;
    transition: none; } }
  @media screen and (min-width: 1200px) and (prefers-reduced-motion: reduce) {
    .sidebar-expand-xl:not(.sidebar-component) {
      transition: none; } }

@media (min-width: 1200px) {
    .sidebar-expand-xl:not(.sidebar-component):not(.sidebar-fixed) .sidebar-content {
      position: static;
      overflow: visible;
      width: auto; }
  .sidebar-expand-xl.sidebar-dark:not(.sidebar-component) + .sidebar-dark:not(.sidebar-component) {
    border-left: 1px solid rgba(255, 255, 255, 0.1); }
  .sidebar-expand-xl .sidebar-mobile-toggler {
    display: none; }
  .sidebar-expand-xl.sidebar-fullscreen {
    width: 16.875rem; }
  .sidebar-main-hidden .sidebar-expand-xl.sidebar-main,
  .sidebar-component-hidden .sidebar-expand-xl.sidebar-component,
  .sidebar-secondary-hidden .sidebar-expand-xl.sidebar-secondary,
  .sidebar-mobile-right .sidebar-expand-xl.sidebar-right {
    display: none; }
  .sidebar-expand-xl.sidebar-right {
    display: none; }
    .sidebar-right-visible .sidebar-expand-xl.sidebar-right {
      display: block; }
  .sidebar-expand-xl.sidebar-component {
    display: block;
    width: 16.875rem; }
    .sidebar-expand-xl.sidebar-component-left {
      margin-right: 1.25rem; }
    .sidebar-expand-xl.sidebar-component-right {
      margin-left: 1.25rem; } }

@media (max-width: 1199.98px) {
  .sidebar-expand-xl:not(.sidebar-component) {
    border: 0; } }

.sidebar-expand.sidebar-main {
  z-index: 99;
  box-shadow: none; }
  .sidebar-expand.sidebar-main .sidebar-content {
    left: 0; }

.sidebar-expand.sidebar-secondary {
  z-index: 98;
  box-shadow: none; }
  .sidebar-expand.sidebar-secondary .sidebar-content {
    left: 0; }

.sidebar-expand.sidebar-right {
  z-index: 97;
  box-shadow: none; }
  .sidebar-expand.sidebar-right .sidebar-content {
    right: 0; }

.sidebar-expand.sidebar-component {
  z-index: 96; }

.sidebar-expand:not(.sidebar-component) {
  position: static;
  transition: none; }
  @media screen and (prefers-reduced-motion: reduce) {
    .sidebar-expand:not(.sidebar-component) {
      transition: none; } }
  .sidebar-expand:not(.sidebar-component):not(.sidebar-fixed) .sidebar-content {
    position: static;
    overflow: visible;
    width: auto; }

.sidebar-expand.sidebar-dark:not(.sidebar-component) + .sidebar-dark:not(.sidebar-component) {
  border-left: 1px solid rgba(255, 255, 255, 0.1); }

.sidebar-expand .sidebar-mobile-toggler {
  display: none; }

.sidebar-expand.sidebar-fullscreen {
  width: 16.875rem; }

.sidebar-main-hidden .sidebar-expand.sidebar-main,
.sidebar-component-hidden .sidebar-expand.sidebar-component,
.sidebar-secondary-hidden .sidebar-expand.sidebar-secondary,
.sidebar-mobile-right .sidebar-expand.sidebar-right {
  display: none; }

.sidebar-expand.sidebar-right {
  display: none; }
  .sidebar-right-visible .sidebar-expand.sidebar-right {
    display: block; }

.sidebar-expand.sidebar-component {
  display: block;
  width: 16.875rem; }
  .sidebar-expand.sidebar-component-left {
    margin-right: 1.25rem; }
  .sidebar-expand.sidebar-component-right {
    margin-left: 1.25rem; }

.sidebar-expand:not(.sidebar-component) {
  border: 0; }

.sidebar:not(.bg-transparent) .card {
  border-width: 0;
  margin-bottom: 0;
  border-radius: 0;
  box-shadow: none; }
  .sidebar:not(.bg-transparent) .card .card {
    border-width: 1px; }
  .sidebar:not(.bg-transparent) .card:not([class*=bg-]):not(.fixed-top) {
    background-color: transparent; }

.sidebar .card-footer {
  border-bottom: 1px solid rgba(0, 0, 0, 0.125); }

.sidebar .row:not(.no-gutters) {
  margin-left: -0.3125rem;
  margin-right: -0.3125rem; }
  .sidebar .row:not(.no-gutters) [class*=col] {
    padding-left: 0.3125rem;
    padding-right: 0.3125rem; }

.sidebar .form-group:last-child {
  margin-bottom: 0; }

.sidebar .nav-tabs .nav-item:first-child .nav-link {
  border-left: 0; }

.sidebar .nav-tabs .nav-item:last-child .nav-link {
  border-right: 0; }

.sidebar .nav-tabs .nav-link {
  border-top: 0;
  border-bottom-width: 0; }
  .sidebar .nav-tabs .nav-link.active {
    border-bottom-color: transparent; }

.sidebar-dark .nav-tabs {
  background-color: #1e272c;
  border-bottom-color: rgba(255, 255, 255, 0.1); }
  .sidebar-dark .nav-tabs .nav-link {
    color: rgba(255, 255, 255, 0.9); }
    .sidebar-dark .nav-tabs .nav-link:hover, .sidebar-dark .nav-tabs .nav-link:focus {
      color: #fff; }
    .sidebar-dark .nav-tabs .nav-link.active {
      color: #fff; }
  .sidebar-dark .nav-tabs:not(.nav-tabs-bottom) .nav-link.active {
    background-color: #263238;
    border-color: rgba(255, 255, 255, 0.1); }
  .sidebar-dark .nav-tabs .nav-item.show .nav-link:not(.active) {
    color: #fff; }

.sidebar-light .nav-tabs {
  background-color: whitesmoke;
  border-bottom-color: rgba(0, 0, 0, 0.125); }
  .sidebar-light .nav-tabs .nav-link:hover, .sidebar-light .nav-tabs .nav-link:focus {
    color: #333; }
  .sidebar-light .nav-tabs .nav-link.active {
    color: #333; }
  .sidebar-light .nav-tabs:not(.nav-tabs-bottom) .nav-link.active {
    background-color: #fff; }

.row-tile div[class*=col] .btn {
  border-radius: 0; }

.row-tile div[class*=col]:first-child .btn:first-child {
  border-top-left-radius: 0.1875rem; }

.row-tile div[class*=col]:first-child .btn:last-child {
  border-bottom-left-radius: 0.1875rem; }

.row-tile div[class*=col]:last-child .btn:first-child {
  border-top-right-radius: 0.1875rem; }

.row-tile div[class*=col]:last-child .btn:last-child {
  border-bottom-right-radius: 0.1875rem; }

.row-tile div[class*=col] .btn + .btn {
  border-top: 0; }

.row-tile div[class*=col] + div[class*=col] .btn {
  border-left: 0; }

/* ------------------------------------------------------------------------------
 *
 *  # Boxed layout
 *
 *  Styles for main structure of content area in boxed layout
 *
 * ---------------------------------------------------------------------------- */
.layout-boxed-bg {
  background: url(<?php print $this->RES_ROOT;?>images/backgrounds/boxed_bg.png) repeat; }

.layout-boxed {
  box-shadow: -4px 2px 4px rgba(0, 0, 0, 0.15), 4px 2px 4px rgba(0, 0, 0, 0.15); }
  @media (min-width: 992px) {
    .layout-boxed,
    .layout-boxed > .navbar {
      width: 992px;
      margin-left: auto;
      margin-right: auto; } }
  @media (min-width: 1200px) {
    .layout-boxed,
    .layout-boxed > .navbar {
      width: 1200px; } }
  .layout-boxed .page-content {
    background-color: #f5f5f5; }

@media (min-width: 992px) {
  .content-boxed:not(.navbar-collapse) {
    margin-left: 6%;
    margin-right: 6%; }
  .navbar > .content-boxed > *:first-child {
    margin-left: 6%; }
  .navbar > .content-boxed > *:last-child {
    margin-right: 6%; }
  .breadcrumb-line > .content-boxed > *:first-child {
    margin-left: 1.25rem; }
  .breadcrumb-line > .content-boxed > *:last-child {
    margin-right: 1.25rem; } }

</style>