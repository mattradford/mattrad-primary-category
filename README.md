![Screenshot of Mattrad Primary Category plugin on a post edit screen](https://user-images.githubusercontent.com/2804540/130497404-17e4cc75-6a83-4c75-aa1f-a4a94e0ff907.png)

# Mattrad Primary Categories
This is a WordPress plugin to assign primary categories to posts and custom post types, for the default _category_ taxonomy only.

Assigned Primary Categories are shown on the WordPress Dashboard, in the post and custom post type indices, via an admin column.

* Show a post's Primary Category with the shortcode `[mr-primary-category]`.
* Get posts with a specified Primary Category using the function `mattrad_get_posts_in_primary_category()`.
* Or for the latest 10 posts with a specified Primary Category use the speedy function `mattrad_get_latest_posts_in_primary_category()`.

## Testing

This plugin has been tested with WordPress [Theme Test Data](https://github.com/WPTT/theme-test-data) and the following themes:

* Twenty Nineteen
* Twenty Twenty
* Twenty Twenty-One

Both Classic Editor and Block Editor are supported.

This plugin has *not* been tested with WordPress Multisite.

## Pre-requisites

* WordPress 5.8
* PHP 7.3

The plugin is likely to work on earlier versions of WordPress and PHP, but these have not been tested.

## Installation

* Download the [latest release](https://github.com/mattradford/mattrad-primary-category/releases/)
* Upload the zip file via _Plugins_ -> _Add new_
* Activate
* Assign Primary Categories via the post edit screen

Or you may install via [Composer](https://getcomposer.org/).

## Uninstallation

* When the plugin is deleted, it will remove any Primary Category metadata that has been set.

## Functions

`mattrad_get_posts_in_primary_category()` accepts two parameters:

* `$term`
  * This should be a valid Primary Category term name.
* `$args`
  * This allows the use of [WP_Query parameters](https://developer.wordpress.org/reference/classes/wp_query/#parameters).

`mattrad_get_latest_posts_in_primary_category()` accepts one parameter:

* `$term`
  * This should be a valid Primary Category term name.

## Shortcode options

`[mr-primary-category]` accepts two options:

* `link`
  * This defaults to `true` and outputs the post's Primary Category as a link to the Category Archive.
  * Use `link="false"` if you just want the name of the Primary Category to show.
* `text`
  * This defaults to `Category: ` and is prepended to the post's Primary Category.
  * Use `text=""` to remove or `text="Your text here "` to set custom text.

## To-Do

* Internationalisation
* Support for Tags (_post_tags_)
* Options page to allow admins to choose post types that primary categories can be applied to
* REST API route
* Activation and Deactivation functions
* Support for custom taxonomies
* Possible: Re-factor to store Primary Categories by ID rather than name
* Possible: Primary Category Archives (could use _pre_get_posts_ to alter existing archives)
