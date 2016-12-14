<?php
/*
Plugin Name: Polylang Add-on: Smart Language Select Disabler
Plugin URI: 
Version: 0.1.1
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

		$translations = false;

		if( $current_screen->base == 'post' ) {

			// $_GET['post'] has ID for all post types
			if( isset( $_GET['post'] ) ) {
				$post_id = intval( $_GET['post'] );
				$translations = PLL()->model->post->get_translations( $post_id );

			// $_GET['new_lang'] is used when creating a translation so there's always translations
			} elseif( isset( $_GET['new_lang'] ) ) {
				$translations = true;
			}

			// Give filter to add custom logic for disabler
			$translations = apply_filters( 'polylang-disable-language-select', $translations, $current_screen );

			if ($translations) {
				?>
				<script>
					if(typeof addEventListener === 'function') {
						document.addEventListener('DOMContentLoaded', function() {
							document.getElementById('post_lang_choice').setAttribute('disabled', 'disabled');
						});
					}
				</script>
				<?php
			}

		} elseif ($current_screen->base == 'term' ) {

			// $_GET['tag_ID'] has ID for all taxonomies
			if( isset( $_GET['tag_ID'] ) ) {
				$term_id = intval( $_GET['tag_ID'] );
				$translations = PLL()->model->term->get_translations( $term_id );

			// $_GET['new_lang'] is used when creating a translation so there's always translations
			} elseif( isset( $_GET['new_lang'] ) ) {
				$translations = true;
			}

			// Give filter to add custom logic for disabler
			$translations = apply_filters( 'polylang-disable-language-select', $translations, $current_screen );

			if ($translations) {
			?>
				<script>
					if(typeof addEventListener === 'function') {
						document.addEventListener('DOMContentLoaded', function() {
							document.getElementById('term_lang_choice').setAttribute('disabled', 'disabled');
						});
					}
				</script>
			<?php
			}
		}

	}

}

add_action( 'plugins_loaded', create_function('', 'global $polylang_smart_language_select_disabler; $polylang_smart_language_select_disabler = new PolylangSmartLanguageSelectDisabler();') );

