<?php
/*
Plugin Name: Polylang Add-on: Smart Language Select Disabler
Plugin URI:
Version: 1.0.3
Author: Aucor Oy
Author URI: https://github.com/aucor
Description: Disable content language select when there's translations in Polylang
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: polylang-smart-language-select-disabler
*/

class PolylangSmartLanguageSelectDisabler {

	/**
	 * Constructor
	 */
	public function __construct() {

		// Check that Polylang is active
		global $polylang;

		if (isset($polylang)) {
			add_action('current_screen', array(&$this, 'maybe_disable_language_select'));
			add_action('pll_default_lang_row_action', array(&$this, 'maybe_disable_default_language_select'), 10, 2);
		}
	}

	/**
	 * Maybe disable language select on edit posts and terms
	 *
	 * Check switcher when there's translations. Switching language then will mess things up.
	 *
	 * @param $current_screen WP_Screen Object
	 */
	function maybe_disable_language_select($current_screen) {

		$disable_translations = false;

		if( $current_screen->base == 'post' ) {

			// $_GET['post'] has ID for all post types
			if( isset( $_GET['post'] ) ) {
				$post_id = intval( $_GET['post'] );
				$disable_translations = PLL()->model->post->get_translations( $post_id );

			// $_GET['new_lang'] is used when creating a translation so there's always translations
			} elseif( isset( $_GET['new_lang'] ) ) {
				$disable_translations = true;
			}

		} elseif ($current_screen->base == 'term' ) {

			// $_GET['tag_ID'] has ID for all taxonomies
			if( isset( $_GET['tag_ID'] ) ) {
				$term_id = intval( $_GET['tag_ID'] );
				$disable_translations = PLL()->model->term->get_translations( $term_id );

			// $_GET['new_lang'] is used when creating a translation so there's always translations
			} elseif( isset( $_GET['new_lang'] ) ) {
				$disable_translations = true;
			}

		}

		// Posts bulk edit
		if( $current_screen->base === 'edit' ) {
			$disable_translations = true;
		}

		// Terms bulk edit
		if( $current_screen->base === 'edit-tags' ) {
			$disable_translations = true;
		}

		// Give filter to add custom logic for disabler
		$disable_translations = apply_filters( 'polylang-disable-language-select', $disable_translations, $current_screen );

		if ($disable_translations) {

			add_action('in_admin_header', function() {
				?>

					<style>
						/* Bulk edit */
						.wp-admin .inline-edit-row select[name="inline_lang_choice"] {
							display: none !important;
						}

						/* Edit screens */
						.wp-admin #post_lang_choice,
						.wp-admin #term_lang_choice {
							display: none !important;
						}

						.wp-admin #select-post-language .pll-select-flag,
						.wp-admin #select-term-language, .pll-select-flag {
							margin-right: 6px;
						}

						.wp-admin #select-edit-term-language .description {
							display: none; /* Description doesn't apply */
						}
					</style>

					<script>

						function polylang_addon_disable_language_select(el, label) {

							if(el == null) {
								return false;
							}

							// get current language, if not already set
							if(label == null) {
								var label = el.options[el.selectedIndex].innerHTML;
							}

							// create <p>
							var p = document.createElement('p');
							p.style.display = 'inline-block';
							p.style.margin = '0';
							p.textContent = label;

							// insert <p>
							el.parentNode.insertBefore(p, el.nextSibling);

						}

						if(typeof addEventListener === 'function') {
							// Bulk edit
							document.addEventListener( 'DOMNodeInserted', function( e ) {
								var t = e.target;

								// WP inserts the quick edit from
								if ( '[object HTMLTableRowElement]' == Object.prototype.toString.call(t) && 'inline-edit' == t.getAttribute( 'id' ) ) {
									// timeout so that inline lang choice gets real value
									setTimeout(function() {
										var lang_choice = document.getElementsByName('inline_lang_choice');
										var lang_choice = lang_choice[0];
										var label =  lang_choice.options[ lang_choice.selectedIndex ].innerHTML
										polylang_addon_disable_language_select(lang_choice, label);
									}, 5);
								}
							} );

							// Edit screens
							document.addEventListener('DOMContentLoaded', function() {

								// posts
								var post_lang_choice = document.getElementById('post_lang_choice');
								polylang_addon_disable_language_select(post_lang_choice);

								// terms
								var term_lang_choice = document.getElementById('term_lang_choice');
								polylang_addon_disable_language_select(term_lang_choice);

							});
						}

					</script>

				<?php
			});
		}

	}

	/**
	 *  Maybe disable Polylang default language change select
	 *
	 *  @param  string $s    html markup of the action
	 *  @param  object $item
	 *  @return string
	 */
	function maybe_disable_default_language_select( $s, $item ) {
		$disable = true;

		// Give get parameter to allow chaning the default language
		if ( isset( $_GET['iknowwhatimdoing'] ) ) {
			$disable = false;
		}

		// Give filter to add custom logic for disabler
		$disable = apply_filters( 'polylang-disable-default-language-select', $disable, $item );

		// Return empty string if disabled
		if ( $disable ) {
			return '';
		}

		// Return original string containing link to change
		return $s;
	}

}

add_action( 'plugins_loaded', function() {
	global $polylang_smart_language_select_disabler;

	$polylang_smart_language_select_disabler = new PolylangSmartLanguageSelectDisabler();
} );
