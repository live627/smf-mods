<?php
// Version: 1.1: Subs-PackageSort.php

if (!defined('SMF'))
	die('Hacking attempt...');

function packagesort_helpadmin()
{
	loadLanguage('PackageSort');
}

function packagesort_modification_types()
{
	global $context, $txt;

	unset($context['modification_types'][array_search('modification', $context['modification_types'])]);
	unset($context['available_modification']);
	$context['modification_types'] = array_merge(
		array(
			'install',
			'upgrade',
			'uninstall',
			'cannot_install',
			'cannot_upgrade',
			'cannot_uninstall',
			'cannot_uninstall_2',
		),
		$context['modification_types']
	);

	loadLanguage('PackageSort');

	foreach (array(
		'install',
		'upgrade',
		'uninstall',
		'cannot_install',
		'cannot_upgrade',
		'cannot_uninstall',
		'cannot_uninstall_2',
	) as $state_txt)
	{
		$txt[$state_txt . '_package'] = sprintf(
			'%s &mdash; %s',
			$txt['modification_package'],
			$txt['pkgstate_' . $state_txt]
		);
		$context['available_' . $state_txt] = array();
		add_integration_function(
			'integrate_packages_lists_' . $state_txt,
			'packagesort_packages_list',
			false
		);
	}
}

function packagesort_packages_sort_id(&$sort_id)
{
	unset($sort_id['modification']);
	$sort_id = array_merge(
		array(
			'install' => 1,
			'upgrade' => 1,
			'uninstall' => 1,
			'cannot_install' => 1,
			'cannot_upgrade' => 1,
			'cannot_uninstall' => 1,
			'cannot_uninstall_2' => 1,
		),
		$sort_id
	);
}

function packagesort_packages_list(&$listOptions)
{
	global $txt;

	$listOptions['additional_rows'] = array(
		array(
			'position' => 'above_column_headers',
			'value' => $txt['pkgstatehelp_' . str_replace('packages_lists_', '', $listOptions['id'])],
			'class' => 'information smalltext',
		),
	);
}