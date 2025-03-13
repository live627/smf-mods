<?php

class BannedMemberAvatar
{
	 private static array $banned_member_map = [];

	/**
	 * Called by:
	 *        integrate_set_avatar_data
	 */
	public static function set_avatar_data(string &$image, array &$data): void
	{
		global $settings, $user_profile;

		if (!isset(self::$banned_member_map[$data['email']])) {
			if (isset($user_profile)) {
				foreach ($user_profile as $profile) {
					if (isset($profile['is_activated'])) {
						self::$banned_member_map[$profile['email_address']] = $profile['is_activated'] >= 10;
					}
				}
			}
		}

		if (isset(self::$banned_member_map[$data['email']]) && self::$banned_member_map[$data['email']] === true) {
			$image = $settings['default_images_url'] . '/banned.png';
		}
	}
}
