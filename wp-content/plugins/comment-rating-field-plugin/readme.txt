=== Comment Rating Field Plugin ===
Contributors: n7studios,wpzinc
Donate link: https://www.wpzinc.com/plugins/comment-rating-field-pro-plugin
Tags: comment,field,rating,ratings,star,stars,gd,comments,review,reviews,stars,feedback
Requires at least: 3.6
Tested up to: 4.9.2
Requires PHP: 5.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Turn your WordPress Comments into a Review System.

== Description ==

Comment Rating Field adds a 5 star rating field to the end of a comment form in WordPress, allowing the site visitor to optionally submit a rating along with their comment. 

Ratings are displayed as stars for Posts that have ratings:
- below the Post Content, as an average rating,
- for each comment, below the comment text.

> #### Comment Rating Field Pro
> <a href="https://www.wpzinc.com/plugins/comment-rating-field-pro-plugin/" rel="friend" title="Allow site visitors to leave star ratings on Pages, Posts and Custom Post Types when posting comments on WordPress">Comment Rating Field Pro</a> provides additional functionality:<br />
>
> - **Multiple Field Groups:**<br />Each rating field group can be targeted to a specific Post Type and/or Taxonomy, allowing different fields for different sections of your web site.<br /><br />
> - **Multiple Rating Fields:** Add more than one rating field to your comment forms for Pages, Posts, Custom Post Types and/or Taxonomy Terms.<br /><br />
> - **Google Rich Snippet Schema Support:** Choose a schema (e.g. Review, Product, Place, Person) for your Ratings. Visitors can see the average rating on your Google search results.<br /><br />
> - **Rating Field Options:** Define the maximum rating, precision and position of rating fields.  Also limit visitors to one rating per Post, plus define which WordPress User Role(s) can add ratings.<br /><br />
> - **Display Ratings:** Define Positioning, styling, display field rating breakdowns, total number of ratings and more.<br /><br />
> - **Star or Bar Output:** Choose to output a Post's rating using stars or bars.<br /><br />
> - **Advanced Shortcodes:** All of the advanced display options are available on the Shortcode.<br /><br />
> - **Widget:** Display the Top Rated Posts in your Sidebars.<br /><br />
> - **Filter and Sort Ratings:** Click a rating number to view all reviews matching that rating, as well as sort reviews by date or rating.<br /><br />
> - **Sort Post Lists and Taxonomies by Rating:** Change your Post and Taxonomy Archives to display Posts ordered by rating instead of date.<br /><br />
> - **Developer Friendly:** Enable support on bespoke comment systems, and use our functions to easily display the average rating, get rating data and more.<br /><br />
> - **Jetpack, WooCommerce and SCE:** Full support for Jetpack, WooCommerce and Simple Comment Editing.<br /><br />
>
> [Upgrade to Comment Rating Field Pro](https://www.wpzinc.com/plugins/comment-rating-field-pro-plugin/)

= Support =

We will do our best to provide support through the WordPress forums. However, please understand that this is a free plugin, 
so support will be limited. Please read this article on <a href="http://www.wpbeginner.com/beginners-guide/how-to-properly-ask-for-wordpress-support-and-get-it/">how to properly ask for WordPress support and get it</a>.

If you require one to one email support, please consider <a href="https://www.wpzinc.com/plugins/comment-rating-field-pro-plugin" rel="friend">upgrading to the Pro version</a>.

= WP Zinc =
We produce free and premium WordPress Plugins that supercharge your site, by increasing user engagement, boost site visitor numbers
and keep your WordPress web sites secure.

Find out more about us at <a href="https://www.wpzinc.com" rel="friend" title="Premium WordPress Plugins">wpzinc.com</a>

== Installation ==

1. Upload the `comment-rating-field-plugin` folder to the `/wp-content/plugins/` directory
2. Active the Comment Rating Field Plugin through the 'Plugins' menu in WordPress
3. Configure the plugin by going to the `Comment Rating Field Plugin` menu that appears in your admin menu

== Frequently Asked Questions ==



== Screenshots ==

1. Settings Screen
2. Rating Field Input on Comments Form
3. Ratings displayed on Comments 
4. Average Rating displayed on Post

== Changelog ==

= 3.5.2 =
* Fix: Installation: Ensure a Group and Field exist, if none are defined

= 3.5.1 =
* Added: Version bump to match Pro version, using same core codebase and UI for basic features. Fixes several oustanding bugs.
* Added: Retina SVG Stars
* Added: General: Empty, Filled and Selected Star Colors
* Added: General: Star Size (in pixels)
* Added: Rating Field: Make rating field required / not required, with options to specify required message
* Added: Rating Output: Content: Option to conditionally display when ratings exist
* Added: Rating Output: Comments: Option to conditionally display when ratings exist

= 2.2.0 =
* Added: Edit Ratings in WordPress Admin when editing a Comment
* Added: Improved graphics for star rating and cancel buttons, with Retina support
* Added: Shortcode to output average rating for a Post

= 2.1.3 =
* Fix: Undefined variable errors

= 2.1.2 =
* Fix: Only display Review Helper for Super Admin and Admin

= 2.1.1 =
* Added: Review Helper to check if the user needs help
* Added: Using Pro core codebase and UI for basic features. Fixes several oustanding bugs
* Updated: Dashboard Submodule
* Fix: On new installations, enable for Posts by default to avoid possible confusion that the plugin does not work

= 2.1.0 =
* Fix: Changed branding from WP Cube to WP Zinc

= 2.0.9 =
* Tested with WordPress 4.3
* Fix: plugin_dir_path() and plugin_dir_url() used for Multisite / symlink support

= 2.0.8 =
* Added: Spanish translation
* Fix: Only calculate average rating from approved comments (some comments awaiting moderation were wrongly included in calculations previously)

= 2.0.7 =
* Fix: Changed Menu Icon
* Fix: WordPress 4.0 compatibility
* Fix: Removed unused admin CSS

= 2.0.6 =
* Fix: PHP warning on uninitialized $totalRating variable (props: tim.samuelsson)

= 2.0.5 =
* Added translation support and .pot file

= 2.0.4 =
* Fix: Better jQuery rating integration

= 2.0.3 =
* Removed reference to unused admin.js file

= 2.0.2 =
* Committed frontend.js; removed unused files

= 2.0.1 =
* Dashboard CSS + JS enhancements

= 2.0 =
* Fix: UI enhancements

= 1.5 =
* Fix: Upgrade link
* Version number in line with Pro version

= 1.42 =
* Fix: Enabled on Page option (ratings now display on Pages)
* Fix: CSS for some installations where rating numbers would display in front of stars

= 1.41 =
* Fix for WordPress 3.4 compatibility
* jQuery Rating Javascript updated to 3.14

= 1.4 =
* Removal of Donate Button
* On Activation, plugin no longer enables ratings on Pages and Posts by default
* Change: Average Rating displayed below content for better formatting and output on themes
* Fix: Language / localisation support
* Fix: Rating only shows on selected categories where specified in the plugin
* Fix: Recalculation of rating when comment removed
* Fix: Multisite Compatibility
* Fix: W3 Total Cache compatibility
* Pro Version Only: Support: Access to support ticket system and knowledgebase
* Pro Version Only: Custom Post Types: Support for rating display and functionality on ANY Custom Post Types and their Taxonomies
* Pro Version Only: Widgets: List the Highest Average Rating Posts within your sidebars
* Pro Version Only: Shortcodes: Use a shortcode to display the Average Rating anywhere within your content
* Pro Version Only: Rating Field: Make rating field a required field
* Pro Version Only: Display Average Rating: Choose to display average rating above or below the content
* Pro Version Only: Seamless Upgrade: Retain all current settings and ratings when upgrading to Pro

= 1.3 =
* Javascript changes to fix comment rating field not appearing below comment field on some themes.

= 1.2 =
* Enable on Pages Option Added
* Enable on Post Categories Option Added
* Display Average Option Added - will display the average of all ratings at the top of the comments list.
* Donate Button Added to Settings Panel
* Change to readme.txt file for required ID on comment form.

= 1.01 =
* Fixed paths for CSS and Javascript.

= 1.0 =
* First release.

== Upgrade Notice ==
