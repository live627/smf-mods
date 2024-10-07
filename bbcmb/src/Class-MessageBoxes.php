<?php

/**
 * @package BBC Message Boxes
 * @version 1.0
 * @author John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2014, John Rayes
 * @license http://opensource.org/licenses/MIT MITl
 */

if (!defined('SMF'))
	die('No direct access...');

class MessageBoxesIntegration
{
	public static function bbc_codes(&$codes)
	{
		$codes = array_merge(
			$codes,
			array(
				array(
					'tag' => 'error',
					'before' => '<div class="error_bbc">',
					'after' => '</div>',
					'block_level' => true,
				),
				array(
					'tag' => 'warning',
					'before' => '<div class="warning_bbc">',
					'after' => '</div>',
					'block_level' => true,
				),
				array(
					'tag' => 'okay',
					'before' => '<div class="okay_bbc">',
					'after' => '</div>',
					'block_level' => true,
				),
				array(
					'tag' => 'info',
					'before' => '<div class="info_bbc">',
					'after' => '</div>',
					'block_level' => true,
				)
			)
		);

		loadCSSFile('mbox.css');
	}

	public static function sce_options(&$sce_options)
	{
		$sce_options['plugins'] = ($sce_options['plugins'] != '' ? $sce_options['plugins'] . ',' : '') . 'mbox';
	}

	public static function bbc_buttons(&$buttons)
	{
		global $settings, $txt;

		loadCSSFile('mbox.css');
		addJavaScriptVar(
			'mboxCss',
			str_replace(
				['..', "\n", "\t"],
				[$settings['default_theme_url'], '', ''],
				file_get_contents($settings['default_theme_dir'] . '/css/mbox.css')
			),
			true
		);
		loadJavaScriptFile('mbox.js', array('minimize' => true));
		loadLanguage('MessageBoxes');
		$temp = array();
		foreach ($buttons[0] as $tag)
		{
			$temp[] = $tag;

			if (isset($tag['code']) && $tag['code'] == 'justify')
			{
				$temp = array_merge(
					$temp,
					array(
						array(),
						array(
							'code' => 'error',
							'description' => $txt['error_bbc'],
						),
						array(
							'code' => 'warning',
							'description' => $txt['warning_bbc'],
						),
						array(
							'code' => 'okay',
							'description' => $txt['okay_bbc'],
						),
						array(
							'code' => 'info',
							'description' => $txt['info_bbc'],
						),
					)
				);
			}
		}

		$buttons[0] = $temp;
	}
}