<?php

class ActivityinProfile
{
	/**
	 * Called by:
	 *        integrate_profile_areas
	 */
	public static function profile_areas(array &$profile_areas): void
	{
		$profile_areas['info']['areas']['summary']['function'] = function (int $memID) use ($profile_areas)
		{
			global $context, $modSettings, $txt, $user_profile;

			$profile_areas['info']['areas']['summary']['function']($memID);
			loadLanguage('ActivityInProfile');
			$context['sub_template'] = 'summary';

			if (isset($context['member']['action'])) {
				$context['print_custom_fields']['standard'][] = [
					'name' => $txt['current_activity'],
					'output_html' => $context['member']['action'],
				];
			};
		}
	}
}