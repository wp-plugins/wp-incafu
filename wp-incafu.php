<?php

/*
Plugin Name: WP-Incafu
Plugin URI: https://github.com/tazorax/wp-incafu/
Description: Incafu web module
Version: 1.0
Author: Mathieu Cabaret
Author URI: https://github.com/tazorax/
License: MIT
*/

if (!class_exists('WPIncafu')) :
	// DEFINE PLUGIN ID
	define('INCAFUPLUGIN_ID', 'wp-incafu');
	// DEFINE PLUGIN NICK
	define('INCAFUPLUGIN_NICK', 'WP-Incafu');

	class WPIncafu {
		/** function/method
		 * Usage: return absolute file path
		 * Arg(1): string
		 * Return: string
		 */
		public static function file_path($file) {
			return ABSPATH . 'wp-content/plugins/' . str_replace(basename(__FILE__), '', plugin_basename(__FILE__)) . $file;
		}

		/** function/method
		 * Usage: hooking the plugin options/settings
		 * Arg(0): null
		 * Return: void
		 */
		public static function register() {
			register_setting(INCAFUPLUGIN_ID . '_options', 'incafu_url');
			register_setting(INCAFUPLUGIN_ID . '_options', 'incafu_checkout');
			register_setting(INCAFUPLUGIN_ID . '_options', 'incafu_css');
			register_setting(INCAFUPLUGIN_ID . '_options', 'incafu_js');
		}

		/** function/method
		 * Usage: hooking (registering) the plugin menu
		 * Arg(0): null
		 * Return: void
		 */
		public static function menu() {
			// Create menu tab
			add_options_page(INCAFUPLUGIN_NICK . ' Options', INCAFUPLUGIN_NICK, 'manage_options', INCAFUPLUGIN_ID . '_options', array('WPIncafu', 'options_page'));
		}

		/** function/method
		 * Usage: show options/settings form page
		 * Arg(0): null
		 * Return: void
		 */
		public static function options_page() {
			if (!current_user_can('manage_options')) {
				wp_die(__('You do not have sufficient permissions to access this page.'));
			}

			$plugin_id = INCAFUPLUGIN_ID;
			// display options page
			include(self::file_path('views/options.php'));
		}

		/** function/method
		 * Usage: filtering the content
		 * Arg(1): string
		 * Return: string
		 */
		public static function content($content) {
			if (!isset($_SESSION['incafu'])) {
				$_SESSION['incafu'] = session_id();
			}

			$m = array();
			if (preg_match_all("/\[Incafu(.*)\]/i", $content, $m)) {
				$options = array();
				$value = $m[0][0];
				parse_str(trim($m[1][0]), $options);

				$keys = array_keys($options);
				$parameter_name = $keys[0];

				$param_url = '';

				if (in_array($parameter_name, array('c', 'sc', 'fp'))) {
					$param_url = '&t=' . $parameter_name . '&' . $parameter_name . '=' . $options[$parameter_name];
				}

				$incafu_url = get_option('incafu_url');

				if ($incafu_url) {
					$incafu_content = @file_get_contents(
						$incafu_url .
						'&session=' . $_SESSION['incafu'] .
						'&ipclient=' . $_SERVER['REMOTE_ADDR'] .
						$param_url
					) or $incafu_content = 'Error while connecting Incafu';
				} else {
					$incafu_content = 'Error: Incafu Store URL is not set';
				}

				$content_replacement = (get_option('incafu_checkout') ? '<div id="panier_boutique_web_incafu"></div>' : '') .
					'<div class="boutique">' . $incafu_content . '</div>';

				$css = get_option('incafu_css');

				if (!empty($css)) {
					$content_replacement .= '<style type="text/css">' . $css . '</style>';
				}

				$js = get_option('incafu_js');

				if (!empty($js)) {
					$content_replacement .= '<script type="text/javascript">' . $js . '</script>';
				}

				$content = str_replace($value, $content_replacement, $content);
			}

			return $content;
		}
	}

	if (is_admin()) {
		add_action('admin_init', array('WPIncafu', 'register'));
		add_action('admin_menu', array('WPIncafu', 'menu'));
	}

	add_filter('the_content', array('WPIncafu', 'content'));

	wp_register_style('wp-incafu-style', plugins_url('assets/css/incafu.css', __FILE__));
	wp_enqueue_style('wp-incafu-style');
endif;

