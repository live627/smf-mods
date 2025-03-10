<?php

class GroupsOnProfile
{
	/**
	 * Called by:
	 *        integrate_member_context
	 */
	public static function member_context(array &$memberContext, int $user): void
	{
		global $user_profile;

		$memberContext['additional_groups'] = $user_profile[$user]['additional_groups'] ?? '';
	}

	/**
	 * Called by:
	 *        integrate_profile_areas
	 */
	public static function profile_areas(array &$profile_areas): void
	{
		$profile_areas['info']['areas']['summary']['function'] = function (int $memID) use ($profile_areas) {
			global $context, $scripturl, $settings, $txt;

			$profile_areas['info']['areas']['summary']['function']($memID);
			$context['sub_template'] = 'summary';

			foreach (self::getMembergroupListForProfile($context['member']['additional_groups']) as $group_data) {
				$context['print_custom_fields']['standard'][] = [
					'name' => $txt['position'],
					'output_html' => strtr(
						'<a href="{scripturl}?action=groups;sa=members;group={id_group}" style="{online_color} width: 35%; display: inline-block;">{group_name}</a> {stars_img}',
						[
							'{scripturl}' => $scripturl,
							'{id_group}' => $group_data['id_group'],
							'{online_color}' => $group_data['online_color'] ? 'color: ' . $group_data['online_color'] . '; ' : '',
							'{group_name}' => $group_data['group_name'],
							'{stars_img}' => str_repeat(
								'<img src="' . str_replace('$language', $context['user']['language'], $settings['images_url'] . '/membericons/' . $group_data['icon']) . '" alt="*" style="vertical-align: middle;" />',
								empty($group_data['num_icons']) ? 0 : $group_data['num_icons'],
							),
						],
					),
				];
			}
		};
	}

	// Retrieve a list of (visible) membergroups used by the cache. (Modified)
	public static function getMembergroupListForProfile(string $additional_groups): array
	{
		global $smcFunc;

		$groups = [];

		if ($additional_groups !== '') {
			if ($smcFunc['db_title'] === POSTGRE_TITLE) {
				$query_part = '
					SPLIT_PART(icons, \'#\', 1) AS num_icons,
					SPLIT_PART(icons, \'#\', 2) AS icon';
			} else {
				$query_part = '
					SUBSTRING_INDEX(icons, \'#\', 1) AS num_icons,
					SUBSTRING_INDEX(icons, \'#\', -1) AS icon';
			}

			$request = $smcFunc['db_query']('', '
				SELECT id_group, group_name, online_color, ' . $query_part . '
				FROM {db_prefix}membergroups
				WHERE id_group IN ({array_int:groups})
					AND min_posts = {int:min_posts}
					AND hidden = {int:not_hidden}
					AND id_group != {int:mod_group}
				ORDER BY group_name',
				array(
					'groups' => explode(',', $additional_groups),
					'min_posts' => -1,
					'not_hidden' => 0,
					'mod_group' => 3,
				)
			);

			while ($row = $smcFunc['db_fetch_assoc']($request)) {
				$groups[] = $row;
			}
			$smcFunc['db_free_result']($request);
		}

		return $groups;
	}
}