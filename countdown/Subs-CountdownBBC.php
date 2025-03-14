<?php

class CountdownBBC
{
	public static function bbc_codes(array &$codes): void
	{
		loadLanguage('CountdownBBC');

		$codes[] = [
			'tag' => 'countdown',
			'type' => 'unparsed_commas_content',
			'content' => '$1',
			'validate' => function(&$tag, &$data, $disabled): void
			{
				global $txt;

				if (isset($disabled['countdown']))
					return;

				$end_time = mktime(
					empty($data[4]) ? 0 : intval($data[4]), // Hour
					empty($data[5]) ? 0 : intval($data[5]), // Minute
					0, // Second (always 0)
					empty($data[1]) ? 0 : intval($data[1]), // Month
					empty($data[2]) ? 0 : intval($data[2]), // Day
					empty($data[3]) ? 0 : intval($data[3]), // Year
				);

				$remain_time = $end_time - time();
				$parts = [];

				// Time units with their corresponding translation keys
				$time_units = [
					31556926 => 'cd_year', // 1 year in seconds
					2628288 => 'cd_month', // 1 month in seconds
					86400 => 'cd_day',     // 1 day in seconds
					3600 => 'cd_hour',     // 1 hour in seconds
					60 => 'cd_minute',     // 1 minute in seconds
				];

				foreach ($time_units as $unit_seconds => $key) {
					if ($remain_time >= $unit_seconds) {
						$value = intval($remain_time / $unit_seconds);
						$remain_time -= $value * $unit_seconds;
						$parts[] = self::format_time_unit($value, $key);
					}
				}

				if ($data[0] == '' || $remain_time > 0) {
					$data = [strtr($txt['cd_remaining'], ['{0}' => sentence_list($parts)])];
				}
			},
		];
	}

	public static function format_time_unit(int $count, string $key): string
	{
		global $txt;

		$patterns = explode('|', $txt[$key]);

		// Select the appropriate singular/plural form
		$pattern = $count == 1 ? $patterns[0] : $patterns[1];

		return str_replace('#', $count, $pattern);
	}

	public static function sce_options(array &$sce_options): void
	{
		if (allowedTo('bbc_countdown'))
			$sce_options['plugins'] = ($sce_options['plugins'] != '' ? $sce_options['plugins'] . ',' : '') . 'countdown';
	}

	public static function bbc_buttons(array &$buttons): void
	{
		if (!allowedTo('bbc_countdown'))
			return;

		global $txt;

		loadCSSFile('countdown.css');
		loadJavaScriptFile('sceditor.plugins.countdown.js', array('minimize' => true));
		loadLanguage('CountdownBBC');
		addInlineJavaScript(
			'
			sceditor.locale["' . $txt['lang_dictionary'] . '"] = {
				"Description": "' . $txt['cd_description'] . '",
			};',
			true,
		);

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
							'image' => 'countdown',
							'code' => 'countdown',
							'description' => $txt['countdown'],
						),
					)
				);
			}
		}

		$buttons[0] = $temp;
	}
}
