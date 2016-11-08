# Polylang Add-on: Smart Language Select Disabler

**Contributors:** [Teemu Suoranta](https://github.com/TeemuSuoranta)

**Tags:** Polylang, Admin, Language Select, WordPress

**License:** GPLv2 or later

![polylang-smart-disable-language-select](https://cloud.githubusercontent.com/assets/9577084/20032689/c792fba8-a398-11e6-8c49-1bfbbff0a3c9.jpg)


## Why this plugin exists?

### Changing post's or term's language when it has translations messes things up

Basically the translations are unlinked and there is no warning for this. You may need to change post's language for example when you start to write a new post and notice that it's in wrong language. When translations are involved, there's really no use for changing the language.

### Users confuse adding translations and changing post's language

I've seen that users have multiple times changed post's language when they meant to navigate to translation. Smartly disabling the select enhances the UI.

### Changing the posts language is risky business anyway

Changing the language of post when it has content is prone to errors. Even though the language can be changed, the images added to content may still be in wrong language. Custom fields, relations etc are not automatically changed. Language should be changed right away before adding content.

## What it does?

 * Checks if currently edited post or term has translations
 * If it has, prints one line of JS that disables the select


## Installation

Download and activate. That's it. You will need Polylang, too (d'oh).

**Composer:**
```
$ composer require aucor/polylang-smart-language-select-disabler
```
**With composer.json:**
```
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
```

## Filters

You can disable select always or build some fancy custom logic:

```
function my_polylang_disable_language_select($disable_select, $current_screen) {
	return true;
}
add_filter('polylang-disable-language-select', 'my_polylang_disable_language_select', 10, 2);
```


## Issues and feature whishlist

**Issues:**

 * There is a tiny delay for disable to happen. Maybe another JS hook would help?

 
 **Feature whishlist:**

 * You tell me.
