=== Post Date Editor ===
Contributors: digitalbkk
Donate link: https://digitalbkk.com/
Tags: admin, posts, dates, editor, publish-date
Requires at least: 5.0
Tested up to: 6.8
Requires PHP: 7.0
Stable tag: 1.4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A quick and efficient way to edit post and page publication and modification dates through an admin interface.

== Description ==

Post Date Editor is a simple yet powerful WordPress plugin that allows administrators to easily view and modify the published date and last-modified date of any post or page. It provides a clean, user-friendly interface in the WordPress admin area with advanced search capabilities.

= Features =

* Search posts/pages by ID or title
* Live search with instant results
* Post preview card with detailed information
* Easy-to-use datetime picker for both dates
* Supports all post types (posts and pages)
* Clean and intuitive user interface
* AJAX-powered for smooth operation
* Proper error handling and success notifications

== Installation ==

1. Upload the `post-date-editor` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Tools > Post Date Editor to use the interface

== Usage ==

1. After activation, go to Tools > Post Date Editor in your WordPress admin panel
2. You can search for posts/pages in two ways:
   * Enter the post/page ID directly
   * Search by title using the search box
3. Once you find the desired post/page, you'll see a preview card with post details
4. Use the datetime pickers to modify the published and last-modified dates
5. Click "Save Dates" to apply the changes

== Screenshots ==

1. Post Date Editor main interface
2. Search results display
3. Post preview with date editing options

== Frequently Asked Questions ==

= Can I edit dates for custom post types? =

Currently, the plugin supports editing dates for posts and pages. Support for custom post types may be added in future versions.

= Is it possible to bulk edit dates for multiple posts? =

Not in the current version. This plugin is designed for editing dates of individual posts.

= Will this affect my post's permalinks? =

If your permalink structure includes the date, changing the published date may change the permalink for that post.

== Changelog ==

= 1.4.3 =
* Fixed security issue with nonce validation
* Added proper unslashing and sanitization of nonce values
* Improved input validation for better security

= 1.4.2 =
* Added proper nonce verification for form handling
* Fixed security issues with admin notice display
* Updated WordPress compatibility to 6.8
* Improved plugin security

= 1.4.1 =
* Fixed issue with admin page showing "link expired" error
* Removed incorrect nonce verification on page load

= 1.4.0 =
* Added advanced search functionality with title-based search
* Added post preview card with detailed information
* Implemented live search results with AJAX
* Integrated Select2 for enhanced search experience
* Improved UI with tabs for different search methods
* Added post preview with featured image, excerpt, and meta information
* Added better error handling and success notifications
* Enhanced security with proper data validation and sanitization

= 1.3.0 =
* Added support for page post type
* Added basic error handling
* Added admin notices for success/error feedback

= 1.2.0 =
* Added AJAX-based post fetching
* Added date validation
* Added GMT date conversion support

= 1.1.0 =
* Added basic CSS styling
* Added JavaScript enhancements
* Added admin menu integration

= 1.0.0 =
* Initial release
* Basic post date editing functionality
* Simple admin interface
* Post ID based search

== Upgrade Notice ==

= 1.4.3 =
Security fix: Improves nonce handling and input sanitization. Please update immediately.

= 1.4.2 =
Security update: Adds proper nonce verification for form handling. Please update immediately for improved security.

= 1.4.1 =
Fixes an issue where the admin page was showing a "link expired" error. Please update for improved stability.

= 1.4.0 =
Major update with improved search functionality, post preview card, and enhanced UI. Security improvements included.

== Privacy ==

This plugin does not collect or store any user data. It only interacts with your WordPress database to edit post dates. 