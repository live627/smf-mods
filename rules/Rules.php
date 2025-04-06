<?php
// Version 1.0: Rules.php

if (!defined('SMF'))
	die('Hacking attempt...');

function Rules()
{
	global $context, $settings, $options, $scripturl, $boarddir, $sourcedir, $modSettings, $user_info, $txt;

	isAllowedTo('view_rules');

	$context['all_pages']['rules'] = 'rules';
	if (!empty($modSettings['the_rules_enable_agreement'])) $context['all_pages']['agreement'] = 'agreement';
	if (!empty($modSettings['the_rules_enable_additional']))  $context['all_pages']['additional'] = 'additional';

	$context['sub_action'] = isset($_REQUEST['area']) ? $_REQUEST['area'] : '';
	$context['current_page'] = $context['sub_action'] ? $context['sub_action'] : 'rules';

	switch ($context['current_page'])
	{
	case 'agreement':
		$link_name = !empty($modSettings['the_rules_agreement_display_name']) ? $modSettings['the_rules_agreement_display_name'] : $txt['agreement'];
		$link_url = $scripturl . '?action=rules;area=agreement';
		$context['page_contents'] = file_exists($boarddir . '/agreement.txt') ? parse_bbc(file_get_contents($boarddir . '/agreement.txt')) : $txt['the_rules_not_configured'];
		break;

	case 'additional':
		$link_name = !empty($modSettings['the_rules_additional_display_name']) ? $modSettings['the_rules_additional_display_name'] : $txt['additional'];
		$link_url = $scripturl . '?action=rules;area=additional';
		$context['page_contents'] = !empty($modSettings['the_rules_additional_text']) ? parse_bbc($modSettings['the_rules_additional_text']) : $txt['the_rules_not_configured'];
		break;

	default:
		$link_name = !empty($modSettings['the_rules_display_name']) ? $modSettings['the_rules_display_name'] : $txt['rules'];
		$link_url = $scripturl . '?action=rules';
		$context['page_contents'] = !empty($modSettings['the_rules_text']) ? parse_bbc($modSettings['the_rules_text']) : $txt['the_rules_not_configured'];
	}

	$context['page_title'] = $link_name;

	require_once($sourcedir . '/Subs-Menu.php');

	// Define all the menu structure - see Subs-Menu.php for details!
	$rules_areas = array(
		'forum' => array(
			'title' => !empty($modSettings['the_rules_display_name']) ? $modSettings['the_rules_display_name'] : $txt['rules'],
			'areas' => array(
				'rules' => array(
					'label' => !empty($modSettings['the_rules_display_name']) ? $modSettings['the_rules_display_name'] : $txt['rules'],
				),
				'agreement' => array(
					'label' => !empty($modSettings['the_rules_agreement_display_name']) ? $modSettings['the_rules_agreement_display_name'] : $txt['agreement'],
					'enabled' => isset($context['all_pages']['agreement']),
				),
				'additional' => array(
					'label' => !empty($modSettings['the_rules_additional_display_name']) ? $modSettings['the_rules_additional_display_name'] : $txt['additional'],
					'enabled' => isset($context['all_pages']['additional']),
				),
			),
		),
	);

	// Any files to include?
	if (!empty($modSettings['integrate_rules_include']))
	{
		$rules_includes = explode(',', $modSettings['integrate_rules_include']);
		foreach ($rules_includes as $include)
		{
			$include = strtr(trim($include), array('$boarddir' => $boarddir, '$sourcedir' => $sourcedir, '$themedir' => $settings['theme_dir']));
			if (file_exists($include))
				require_once($include);
		}
	}

	// Let them modify rules areas easily.
	call_integration_hook('integrate_rules_areas', array(&$rules_areas));

	// Make sure the rulesistrator has a valid session...
	validateSession();

	// Actually create the menu!
	$rules_include_data = createMenu($rules_areas);
	unset($rules_areas);

	// Nothing valid?
	if ($rules_include_data == false)
		fatal_lang_error('no_access', false);

	// Build the link tree.
	$context['linktree'][] = array(
		'url' => $scripturl . '?action=rules',
		'name' => !empty($modSettings['the_rules_display_name']) ? $modSettings['the_rules_display_name'] : $txt['rules'],
	);
	if (isset($rules_include_data['current_area']) && $rules_include_data['current_area'] != 'index')
		$context['linktree'][] = array(
			'url' => $scripturl . '?action=rules;area=' . $rules_include_data['current_area'] . ';' . $context['session_var'] . '=' . $context['session_id'],
			'name' => $rules_include_data['label'],
		);

	loadTemplate('Rules');
}

?>