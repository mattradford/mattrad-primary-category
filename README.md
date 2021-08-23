# Mattrad Primary Categories
This is a WordPress plugin to assign primary categories to posts and custom post types, for the default _category_ taxonomy only.

You can show primary categories on the front end via a query and a shortcode (`[mr-primary-category]`).

Assigned Primary Categories are shown on the WordPress Dashboard, in the post and custom post type indices, via an admin column.

This plugin has been tested with WordPress [Theme Test Data](https://github.com/WPTT/theme-test-data) and the following themes:

* Twenty Nineteen
* TwentyTwenty
* TwentyTwentyOne

Both Classic Editor and Block Editor are supported.

## Installation

* Download the [latest release](https://github.com/mattradford/mattrad-primary-categories/releases)
* Upload the zip file via Plugins -> Add new
* Activate
* Assign Primary Categories via the post edit screen

## Uninstallation

* When the plugin is deleted, it will remove any Primary Category metadata that has been set.

## Pre-requisites

* WordPress 5.8
* PHP 7.3

## Shortcode options

`[mr-primary-category]` accepts two options:

* `link`
 - This defaults to `true` and outputs the post's Primary Category as a link to the Category Archive.
 - Use `link="false"` if you just want the name of the Primary Category to show.
* `text`
 - This defaults to `Category: ` and is prepended to the post's Primary Category..
 - Use `text=""` to remove or `text="Your text here "`.

## To-Do

* Internationalisation
* Support for Tags (_post_tags_)
* REST API route
* Activation and Deactivation functions
* Support for custom taxonomies