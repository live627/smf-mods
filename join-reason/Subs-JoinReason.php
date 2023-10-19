<?php

namespace JoinReason;

/**
 * Called by:
 *        integrate_member_context
 */
function member_context(array &$memberContext, int $user): void
{
	global $user_profile;

	$memberContext['join_reason'] = $user_profile[$user]['join_reason'] ?? '';
}

/**
 * Called by:
 *        integrate_load_member_data
 */
function load_member_data(string &$select_columns, string &$select_tables, string $set): void
{
	if ($set == 'profile')
		$select_columns .= ', mem.join_reason';
}

/**
 * Called by:
 *        integrate_profile_areas
 */
function profile_areas(array &$profile_areas): void
{
	if (allowedTo('moderate_forum'))
		$profile_areas['info']['areas']['summary']['function'] = function (int $memID) use ($profile_areas)
		{
			global $context, $modSettings, $txt, $user_profile;

			$profile_areas['info']['areas']['summary']['function']($memID);
			loadLanguage('JoinReason');
			$context['sub_template'] = 'summary';

			$context['print_custom_fields']['standard'][] = [
				'name' => $txt['join_reason'],
				'output_html' => $context['member']['join_reason'],
			];
		};
}

/**
 * @todo Add a hook to Register.php
 *
 * Called by:
 *        integrate_register
 */
function register(array &$regOptions): void
{
	if ($regOptions['interface'] == 'admin')
		return;

	$regOptions['register_vars']['join_reason'] = $regOptions['join_reason'] ?? '';
}

/**
 * Called by:
 *        integrate_manage_members
 */
function manage_members(): void
{
	if (isset($_REQUEST['sa']) && $_REQUEST['sa'] == 'search')
		loadLanguage('JoinReason');
}

/**
 * Called by:
 *        integrate_view_members_params
 */
function view_members_params(array &$params): void
{
	loadLanguage('JoinReason');

	$counter = 0;
	foreach ($params as $name => $array)
	{
		$counter++;
		if ($name == 'membername')
			break;
	}

	$params = array_merge(
		array_slice($params, 0, $counter, true),
		[
			'join_reason' => [
				'db_fields' => ['join_reason'],
				'type' => 'string',
			],
		],
		array_slice($params, $counter, null, true)
	);
}

/**
 * Called by:
 *        integrate_member_list
 */
function member_list(array &$listOptions): void
{
	global $txt;
	loadLanguage('JoinReason');

	$counter = 0;
	foreach ($listOptions['columns'] as $name => $array)
	{
		$counter++;
		if ($name == 'display_name')
			break;
	}

	$listOptions['columns'] = array_merge(
		array_slice($listOptions['columns'], 0, $counter, true),
		[
			'join_reason' => [
				'header' => [
					'value' => $txt['admin_browse_join_reason'],
				],
				'data' => [
					'db' => 'join_reason',
				],
				'sort' => [
					'default' => 'join_reason DESC',
					'reverse' => 'join_reason',
				],
			],
		],
		array_slice($listOptions['columns'], $counter, null, true)
	);
}

/**
 * Called by:
 *        integrate_approve_list
 */
function approve_list(array &$listOptions): void
{
	global $txt;
	loadLanguage('JoinReason');

	$counter = 0;
	foreach ($listOptions['columns'] as $name => $array)
	{
		$counter++;
		if ($name == 'date_registered')
			break;
	}

	$listOptions['columns'] = array_merge(
		array_slice($listOptions['columns'], 0, $counter, true),
		[
			'join_reason' => [
				'header' => [
					'value' => $txt['admin_browse_join_reason'],
				],
				'data' => [
					'db' => 'join_reason',
				],
				'sort' => [
					'default' => 'join_reason DESC',
					'reverse' => 'join_reason',
				],
			],
		],
		array_slice($listOptions['columns'], $counter, null, true)
	);
}

/**
 * Called by:
 *        integrate_load_profile_fields
 */
function load_profile_fields(array &$profile_fields): void
{
	global $txt;
	loadLanguage('JoinReason');

	$profile_fields['join_reason'] = [
		'type' => 'text',
		'label' => $txt['standard_profile_field_join_reason'],
		'size' => 50,
		'log_change' => true,
		'permission' => 'moderate_forum',
	];
}

/**
 * Called by:
 *        integrate_setup_profile_context
 */
function setup_profile_context(array &$fields): void
{
	$counter = array_search('date_registered', $fields);
	if ($counter === false)
		return;

	loadLanguage('JoinReason');
	array_splice($fields, $counter + 1, 0, 'join_reason');
}