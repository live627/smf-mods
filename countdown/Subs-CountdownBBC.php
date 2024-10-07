<?php

class CountdownBBC
{
	public static function bbc_codes(&$codes)
	{
		global $txt;

		loadLanguage('CountdownBBC');
		$codes[] = 
			array(
				'tag' => 'countdown',
				'type' => 'unparsed_commas_content',
				'content' => '$1',
				'validate' => function(&$tag, &$data, $disabled) use ($txt)
				{
				$endTime = mktime ( empty($data[4]) ? 0 : intval($data[4]), empty($data[5]) ? 0 : intval($data[5]), 0, empty($data[1]) ? 0 : intval($data[1]), empty($data[2]) ? 0 : intval($data[2]), empty($data[3]) ? 0 : intval($data[3]) );
				$remainTime = $endTime - time();
				$message = "";
				if ( $remainTime > 0 )
	  			{
					if ( $remainTime >= 31556926 ){
						$year = intval ( $remainTime / 31556926 );
	  				$remainTime = $remainTime - ( $year * 31556926 );
	  				if ($year > 1)
	  					$message = $year . $txt['cd_years'];
	  				else
	  					$message = $year . $txt['cd_year'];
					}
					if ($remainTime >= 2629743.83){
						$month = intval ( $remainTime / 2629743.83 );
						$remainTime = $remainTime - ( $month * 2629743.83 );
	  				if (!$message == "")
	  					$message = $message . " ";
	  				if ($month > 1)
	  					$message .= $month . $txt['cd_months'];
	  				else
	  					$message .= $month . $txt['cd_month'];
					}
					if ($remainTime >= 86400){
						$day = intval ( $remainTime / 86400 );
						$remainTime = $remainTime - ( $day * 86400 );
						if (!$message == "")
	  					$message = $message . " ";
	  				if ($day > 1)
	  					$message .= $day . $txt['cd_days'];
	  				else
	  					$message .= $day . $txt['cd_day'];
					}
					if ($remainTime >= 3600){
						$hour = intval ( $remainTime / 3600 );
						$remainTime = $remainTime - ( $hour * 3600 );
						if (!$message == "")
	  					$message = $message . " ";
	  				if ($hour > 1)
	  					$message .= $hour . $txt['cd_hours'];
	  				else
	  					$message .= $hour . $txt['cd_hour'];
					}
	 				if ($remainTime >= 60){
						$minute = intval ( $remainTime / 60 );
						$remainTime = $remainTime - ( $minute * 60 );
						if (!$message == "")
	  					$message = $message . " ";
	  				if ($minute > 1)
	  					$message .= $minute . $txt['cd_minutes'];
	  				else
	  					$message .= $minute . $txt['cd_minute'];
	  				}
	  				$message .= $txt['cd_remaning'];
	  			}else
	  				$message = $data[0];
	  			
	  			$data[0] = $message;
			}
			);
	}

	public static function bbc_buttons(&$buttons)
	{
		global $txt;

		loadLanguage('CountdownBBC');
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
							'before' => '[countdown]',
							'after' => '[/countdown]',
							'description' => $txt['countdown'],
						),
					)
				);
			}
		}

		$buttons[0] = $temp;
	}
}
