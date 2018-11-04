=== Apollo13 Framework Extensions ===
Contributors: air-1
Tags: custom post types, shortcodes, elementor widgets, wpbakery page builder support
Requires at least: 4.7
Tested up to: 4.9
Requires PHP: 5.4.0
Stable tag: trunk
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Adds custom post types, shortcodes and some non theme features that we use in themes build on our Apollo13 Framework

== Description ==

**Apollo13 Framework Extensions** adds few features to themes build on Apollo13 Framework. These are:

* Designs Importer,
* shortcodes based on Apollo13 Framework features: writtng effect, count down, socials, scroller, slider, galleries, post grid,
* support for WPBakery Page Builder elements added by Apollo13 Framework,
* custom post types: albums, works & people,
* Export/Import of theme options,
* Custom Sidebar,
* Custom CSS,
* Meta options that are creating content for posts, pages, albums and works,
* Responsive Image resizing ,
* Maintenance mode.

This plugin requires one of themes build on **Apollo13 Framework** theme to be installed.

It is mostly used for:

* [Rife Free](https://apollo13themes.com/rife/free/)
* [Rife Pro](https://apollo13themes.com/rife/)


== Installation ==

1. Upload **Apollo13 Framework Extensions** to the `/wp-content/plugins/` directory
2. Activate the plugin through the **Plugins** menu in WordPress
3. Done!

== Frequently Asked Questions ==

= I installed the plugin but it does not work =

This plugin will only work with themes build on **Apollo13 Framework**.
All themes compatible are listed above in description.



== Changelog ==

= 1.5.0(31.10.2018) =

Added:
-Apollo13 Framework shortcodes as Elementor widgets
-support for max_width & margin in a13fe-post-list shortcode
-filter to set number of custom fields in albums & works - apollo13framework_custom_fields_number
-filtering by taxonomies in admin for albums, works & people

Fixed:
-broken import process on servers that hides available PHP memory

----------------
= 1.4.4(03.10.2018) =

Added:
-support for max_width & margin in a13fe-post-list shortcode for people post type

Improved:
-Image generating when using with Elementor
-orderby for post-list list shortcode can now by affected by plugins
-Image resizing script now works for WPBakery Post grids when using Framework grid layouts
-Recreation of global.css in Elementor on design import

----------------
= 1.4.3(04.09.2018) =

Fixed:
-custom links for album & works when using WP Bakery(broken since 1.4.0)

----------------
= 1.4.2(04.09.2018) =

Fixed:
-Fatal error when custom menu widget is used with older version of theme

----------------

= 1.4.1(04.09.2018) =

Moved from theme framework to "Apollo13 Framework Extensions" plugin:
-constants for user css & importer
-custom menu widget menu walker

Improved:
-exporting new WooCommerce 3.3 options, instead of old ones

----------------
= 1.4.0(28.08.2018) =

Important: This update will be required for theme update to version 1.11.0

Moved from theme framework to "Apollo13 Framework Extensions" plugin:
-Design importer
-Exporter of theme options
-Custom Sidebars
-Custom CSS
-Some meta options that were connected to content creation
-Image resizing script
-Saving user changes to user.css file
-custom link & subtitle options in posts, albums and works
-Maintenance mode

Fixed:
-sending e-mails on photo proofing finish

