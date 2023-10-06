<?php

class TCIP
{
	public static function profile_areas(&$profile_areas)
	{
		$profile_areas['info']['areas']['summary']['function'] = function(int $memID)
		{
			global $context, $modSettings, $txt, $user_profile;

			summary($memID);
			loadLanguage('TCIP');
			$context['sub_template'] = 'summary';

			// They haven't even been registered for a full day!?
			$days_registered = (int) ((time() - $user_profile[$memID]['date_registered']) / (3600 * 24));
			if (empty($user_profile[$memID]['date_registered']) || $days_registered < 1)
				$context['member']['topics_per_day'] = $txt['not_applicable'];
			else
				$context['member']['topics_per_day'] = comma_format($context['member']['topics'] / $days_registered, 3);

			$context['tcip_show_topic_count'] = !empty($modSettings['tcip_show_topic_count']);
			$context['tcip_show_participated_count'] = !empty($modSettings['tcip_show_participated_count']);
		};
	}

	public static function profile_stats(int $memID)
	{
		global $context, $modSettings, $smcFunc, $user_info;

		$result = $smcFunc['db_query']('user_activity_by_time', '
			SELECT
				HOUR(FROM_UNIXTIME(poster_time + {int:time_offset})) AS hour,
				COUNT(*)
			FROM (
				SELECT poster_time, id_msg
				FROM {db_prefix}messages AS m
					JOIN {db_prefix}topics AS t ON m.id_msg = t.id_first_msg
				WHERE id_member = {int:current_member}
				ORDER BY id_msg DESC
				LIMIT 1001
			) a
			GROUP BY hour',
			array(
				'current_member' => $memID,
				'time_offset' => $user_info['time_offset'] * 3600,
			)
		);
		$maxPosts = $realPosts = 0;
		$num_topics_by_time = array();
		$context['topics_by_time'] = array();
		while ([$hour, $num] = $smcFunc['db_fetch_row']($result))
		{
			$maxPosts = max($num, $maxPosts);
			$realPosts += $num;

			$num_topics_by_time[$hour] = $num;
		}
		$smcFunc['db_free_result']($result);

		if ($maxPosts > 0)
			for ($hour = 0; $hour < 24; $hour++)
			{
				$context['topics_by_time'][$hour] = array(
					'hour' => $hour,
					'hour_format' => stripos($user_info['time_format'], '%p') === false ? $hour : date('g a', mktime($hour)),
					'posts' => 0,
					'posts_percent' => 0,
					'relative_percent' => 0,
					'is_last' => $hour == 23,
				);

				if (isset($num_topics_by_time[$hour]))
				{
					$context['topics_by_time'][$hour]['posts'] = $num_topics_by_time[$hour];
					$context['topics_by_time'][$hour]['posts_percent'] = round(($num_topics_by_time[$hour] * 100) / $realPosts);
					$context['topics_by_time'][$hour]['relative_percent'] = round(($num_topics_by_time[$hour] * 100) / $maxPosts);
				}
			}
		loadLanguage('TCIP');
	}
}