<?php

class BannedMemberAvatar
{
	 private static array $banned_member_map = [];

	/**
	 * Called by:
	 *        integrate_member_context
	 */
	public static function member_context(array &$memberContext, int $user): void
	{
		if (isset($memberContext['is_bammed'] && $memberContext['is_bammed'] === true) {
			self::$banned_member_map[$memberContext['email']] = $memberContext['is_bammed'];
		}
	}

	/**
	 * Called by:
	 *        integrate_set_avatar_data
	 */
	public static function set_avatar_data(string &$image, array &$data): void
	{
		global $settings;

		if (isset(self::$banned_member_map[$data['email']]) && self::$banned_member_map[$data['email']] === true) {
			$image = $settings['default_images_url'] . '/banned.png';
		}
	}
}