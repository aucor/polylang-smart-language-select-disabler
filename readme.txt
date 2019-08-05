=== Smart Language Select Disabler for Polylang ===
Contributors: TeemuSuoranta
Tags: olylang, Admin, Language Select, WordPress
Requires at least: 4.7.3
Tested up to: 5.2.2
Requires PHP: 7.0
License: GPLv2 or later

== Description ==
= Why this plugin exists? =

= Changing post's or term's language when it has translations messes things up =

Basically the translations are unlinked and there is no warning for this. You may need to change post's language for example when you start to write a new post and notice that it's in wrong language. When translations are involved, there's really no use for changing the language.

= Users confuse adding translations and changing post's language =

I've seen that users have multiple times changed post's language when they meant to navigate to translation. Smartly disabling the select enhances the UI.

= Changing the posts language is risky business anyway =

Changing the language of post when it has content is prone to errors. Even though the language can be changed, the images added to content may still be in wrong language. Custom fields, relations etc are not automatically changed. Language should be changed right away before adding content.

= Changing the site default language is risky business anyway =

Changing the site default language is prone to errors. Even though the language can be changed, links in content may still be in wrong language. Custom fields, relations etc are not automatically changed. Language should be changed only if user knows what they are doing.

= What it does? =

* Checks if currently edited post or term has translations
* If it has, hides select and shows the name of current language with CSS and vanilla JS
* Removes option to change default language unless the user does not provide `iknowwhatimdoing` GET-parameter or allows it via filter

= Composer: =

`$ composer require aucor/polylang-smart-language-select-disabler`

= With composer.json: = 
`
{
  "require": {
    "aucor/polylang-smart-language-select-disabler": "*"
  },
  "extra": {
    "installer-paths": {
      "htdocs/wp-content/plugins/{$name}/": ["type:wordpress-plugin"]
    }
  }
}
`

= Filters =

You can disable select always or build some fancy custom logic:

`
function my_polylang_disable_language_select($disable_select, $current_screen) {
	return true;
}
add_filter('polylang-disable-language-select', 'my_polylang_disable_language_select', 10, 2);
`

Allow default language change:
`
add_filter('polylang-disable-default-language-select', '__return_true');
`

= Issues=

* No disabling for media (to-do)
 

== Installation ==
Download and activate. That's it. You will need Polylang, too (d'oh).

== Changelog ==
= 1.1.0 = 
* Disable default language change select

* Changing site default language will probably cause some issues if you don't knows what you are doing. Disable the availability to do so, unless the user know what they are doing. Also, use WordPress coding standards to make contributing easier. Props to @timiwahalahti for changes in this release.

= 1.0.2 =
* Remove `create_function` usage and include license

= 1.0.1 =
* Add changes that dropped somewhere along the way

* Added some missing stuff because some changes dropped in 1.0.0 (facepalm)

= 1.0.0 =
* Disable functionality rewritten

*The HTML attribute "disabled" caused many problems. The disabling was changed so that the select is hidden with CSS and in its place, plain paragraph with current language is displayed.

= 0.1.1 =
* Fix bug when adding a new term translation

* Disabling the language select messes up creating new term translation. The translation wasn't linked to the original term because of the disabled attribute. Tried to remove the disabled attribute on submit but couldn't get it work for some reason with WordPress admin form. Removed the disabling for now on that view.

= 0.1.0 =
* Initial release