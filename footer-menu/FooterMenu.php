<?php
// Licence: MIT

if (!defined('SMF'))
	die('Hacking attempt...');

function footer_menu_load_theme()
{
	global $context, $modSettings;

	if (empty($modSettings['footer_menu']))
		return;

	if (!isset($_REQUEST['xml']))
		$context['template_layers'][] = 'footer_menu';

	get_footer_menu_filtered();
	loadTemplate('FooterMenu', 'footermenu');
}

function footer_menu_admin_areas(&$admin_areas)
{
	global $txt;

	loadLanguage('FooterMenu');
	$admin_areas['layout']['areas']['footermenu'] = array(
		'label' => $txt['footer_menu'],
		'icon' => 'settings.gif',
		'function' => 'FooterMenu',
		'subsections' => array(
			'index' => array($txt['footer_menu_menu_index']),
			'edit' => array($txt['footer_menu_menu_edit']),
			'categories' => array($txt['footer_menu_categories']),
			'editcategory' => array($txt['footer_menu_editcategory']),
		),
	);
}

function FooterMenu()
{
	global $context, $txt;

	// Load up all the tabs...
	$context[$context['admin_menu_name']]['tab_data'] = array(
		'title' => $txt['footer_menu'],
		'description' => $txt['footer_menu_desc'],
	);

	// Format: 'sub-action' => array('function', 'permission')
	$sub_actions = array(
		'index' => 'ListFooterMenu',
		'edit' => 'EditFooterMenu',
		'categories' => 'ListCategories',
		'editcategory' => 'EditFooterMenuCategory',
	);

	// Default to sub action 'index'
	if (!isset($_GET['sa']) || !isset($sub_actions[$_GET['sa']]))
		$_GET['sa'] = 'index';

	$context['sub_template'] = $_GET['sa'];

	// This area is reserved for admins only - do this here since the menu code does not.
	isAllowedTo('admin_forum');

	// Calls a function based on the sub-action
	$sub_actions[$_GET['sa']]();
}

function ListCategories()
{
	global $txt, $context, $sourcedir, $smcFunc, $scripturl;

	// Deleting?
	if (isset($_POST['delete'], $_POST['remove']))
	{
		checkSession();

		$smcFunc['db_query']('', '
			DELETE FROM {db_prefix}footer_menu_categories
			WHERE id_item IN ({array_int:items})',
			array(
				'items' => $_POST['remove'],
			)
		);
		redirectexit('action=admin;area=footermenu;sa=categories');
	}

	// New item?
	if (isset($_POST['new']))
		redirectexit('action=admin;area=footermenu;sa=editcategory');

	$listOptions = array(
		'id' => 'footer_menu_categories',
		'base_href' => $scripturl . '?action=action=admin;area=footermenu;sa=categories',
		'default_sort_col' => 'name',
		'no_items_label' => $txt['footer_menu_none'],
		'items_per_page' => 25,
		'get_items' => array(
			'function' => 'list_getCategories',
		),
		'get_count' => array(
			'function' => 'list_getNumCategories',
		),
		'columns' => array(
			'name' => array(
				'header' => array(
					'value' => $txt['footer_menu_itemname'],
					'style' => 'text-align: left;',
				),
				'data' => array(
					'function' => create_function('$rowData', '
						global $scripturl;

						return sprintf(\'<a href="%1$s?action=admin;area=footermenu;sa=editcategory;in=%2$d">%3$s</a>\', $scripturl, $rowData[\'id_item\'], $rowData[\'name\']);
					'),
					'style' => 'width: 40%;',
				),
				'sort' => array(
					'default' => 'name',
					'reverse' => 'name DESC',
				),
			),
			'modify' => array(
				'header' => array(
					'value' => $txt['modify'],
				),
				'data' => array(
					'sprintf' => array(
						'format' => '<a href="' . $scripturl . '?action=admin;area=footermenu;sa=editcategory;in=%1$s">' . $txt['modify'] . '</a>',
						'params' => array(
							'id_item' => false,
						),
					),
					'style' => 'width: 10%; text-align: center;',
				),
			),
			'remove' => array(
				'header' => array(
					'value' => $txt['remove'],
				),
				'data' => array(
					'function' => create_function('$rowData', '
						global $txt;
						return sprintf(\'<span id="remove_%1$s" class="color_no">%2$s</span>&nbsp;<input type="checkbox" name="remove[%1$s]" id="remove_%1$s" value="%1$s">\', $rowData[\'id_item\'], $txt[\'no\']);
					'),
					'style' => 'width: 10%; text-align: center;',
				),
			),
		),
		'form' => array(
			'href' => $scripturl . '?action=admin;area=footermenu;sa=categories',
			'name' => 'customProfileFields',
		),
		'additional_rows' => array(
			array(
				'position' => 'below_table_data',
				'value' => '<input type="submit" name="delete" value="' . $txt['delete'] . '" onclick="return confirm(' . JavaScriptEscape($txt['footer_menu_delete_sure']) . ');" class="button_submit">&nbsp;&nbsp;<input type="submit" name="new" value="' . $txt['footer_menu_make_new'] . '" class="button_submit">',
				'style' => 'text-align: right;',
			),
		),
	);
	require_once($sourcedir . '/Subs-List.php');
	createList($listOptions);
	$context['sub_template'] = 'show_list';
	$context['default_list'] = 'footer_menu_categories';
}

function list_getCategories($start, $items_per_page, $sort)
{
	global $smcFunc;

	$list = array();
	$request = $smcFunc['db_query']('', '
		SELECT id_item, name
		FROM {db_prefix}footer_menu_categories
		ORDER BY {raw:sort}
		LIMIT {int:start}, {int:items_per_page}',
		array(
			'sort' => $sort,
			'start' => $start,
			'items_per_page' => $items_per_page,
		)
	);
	while ($row = $smcFunc['db_fetch_assoc']($request))
		$list[] = $row;
	$smcFunc['db_free_result']($request);

	return $list;
}

function list_getNumCategories()
{
	global $smcFunc;

	$request = $smcFunc['db_query']('', '
		SELECT COUNT(*)
		FROM {db_prefix}footer_menu_categories');

	list ($numCategories) = $smcFunc['db_fetch_row']($request);
	$smcFunc['db_free_result']($request);

	return $numCategories;
}

function EditFooterMenuCategory()
{
	global $txt, $scripturl, $context, $settings, $smcFunc;

	$context['in'] = isset($_REQUEST['in']) ? (int) $_REQUEST['in'] : 0;
	$context['page_title'] = $txt['footer_menu'] . ' - ' . ($context['in'] ? $txt['footer_menu_title'] : $txt['footer_menu_add']);
	loadTemplate('FooterMenu');

	if ($context['in'])
	{
		$request = $smcFunc['db_query']('', '
			SELECT *
			FROM {db_prefix}footer_menu_categories
			WHERE id_item = {int:current_item}',
			array(
				'current_item' => $context['in'],
			)
		);
		$context['item'] = array();
		while ($row = $smcFunc['db_fetch_assoc']($request))
			$context['item'] = array(
				'name' => $row['name'],
			);
		$smcFunc['db_free_result']($request);
	}

	// Setup the default values as needed.
	if (empty($context['item']))
		$context['item'] = array(
			'name' => '',
		);

	// Are we saving?
	if (isset($_POST['save']))
	{
		checkSession();

		if (trim($_POST['name']) == '')
			fatal_lang_error('custom_option_need_name');
		$_POST['name'] = $smcFunc['htmlspecialchars']($_POST['name']);

		$up_col = array(
			'name = {string:name}',
			' id_item = {int:current_item}',
		);
		$up_data = array(
			'current_item' => $context['in'],
			'name' => $_POST['name'],
		);
		$in_col = array(
			'name' => 'string',
		);
		$in_data = array(
			$_POST['name'],
		);

		if ($context['in'])
			$smcFunc['db_query']('', '
				UPDATE {db_prefix}footer_menu_categories
				SET
					' . implode(',
					', $up_col) . '
				WHERE id_item = {int:current_item}',
				$up_data
			);
		else
			$smcFunc['db_insert']('',
				'{db_prefix}footer_menu_categories',
				$in_col,
				$in_data,
				array('id_item')
			);

		rebuild_footer_menu();
		redirectexit('action=admin;area=footermenu;sa=categories');
	}
	elseif (isset($_POST['delete']) && $context['item']['colname'])
	{
		checkSession();

		$smcFunc['db_query']('', '
			DELETE FROM {db_prefix}footer_menu_categories
			WHERE id_item = {int:current_item}',
			array(
				'current_item' => $context['in'],
			)
		);
		rebuild_footer_menu();
		redirectexit('action=admin;area=footermenu;sa=categories');
	}
}

function ListFooterMenu()
{
	global $txt, $context, $sourcedir, $smcFunc, $scripturl;

	// Deleting?
	if (isset($_POST['delete'], $_POST['remove']))
	{
		checkSession();

		$smcFunc['db_query']('', '
			DELETE FROM {db_prefix}footer_menu
			WHERE id_item IN ({array_int:items})',
			array(
				'items' => $_POST['remove'],
			)
		);
		rebuild_footer_menu();
		redirectexit('action=admin;area=footermenu');
	}

	// Changing the status?
	if (isset($_POST['save']))
	{
		checkSession();
		foreach (total_getFooterMenu() as $item)
		{
			$category = !empty($_POST['category'][$item['id_item']]) && in_array($_POST['category'][$item['id_item']], array_keys(total_getCategories())) ? $_POST['category'][$item['id_item']] : 1;
			if ($category != $item['category'])
				$smcFunc['db_query']('', '
					UPDATE {db_prefix}footer_menu
					SET category = {string:category}
					WHERE id_item = {int:item}',
					array(
						'category' => $category,
						'item' => $item['id_item'],
					)
				);
			$active = !empty($_POST['active'][$item['id_item']]) ? 'yes' : 'no';
			if ($active != $item['active'])
				$smcFunc['db_query']('', '
					UPDATE {db_prefix}footer_menu
					SET active = {string:active}
					WHERE id_item = {int:item}',
					array(
						'active' => $active,
						'item' => $item['id_item'],
					)
				);
		}
		rebuild_footer_menu();
		redirectexit('action=admin;area=footermenu');
	}

	// New item?
	if (isset($_POST['new']))
		redirectexit('action=admin;area=footermenu;sa=edit');

	$listOptions = array(
		'id' => 'footer_menu_items',
		'base_href' => $scripturl . '?action=action=admin;area=footermenu',
		'default_sort_col' => 'name',
		'no_items_label' => $txt['footer_menu_none'],
		'items_per_page' => 25,
		'get_items' => array(
			'function' => 'list_getFooterMenu',
		),
		'get_count' => array(
			'function' => 'list_getFooterMenuSize',
		),
		'columns' => array(
			'name' => array(
				'header' => array(
					'value' => $txt['footer_menu_itemname'],
					'style' => 'text-align: left;',
				),
				'data' => array(
					'function' => create_function('$rowData', '
						global $scripturl;

						return sprintf(\'<a href="%1$s?action=admin;area=footermenu;sa=edit;in=%2$d">%3$s</a><div class="smalltext">%4$s</div>\', $scripturl, $rowData[\'id_item\'], $rowData[\'name\'], $rowData[\'url\']);
					'),
					'style' => 'width: 40%;',
				),
				'sort' => array(
					'default' => 'name',
					'reverse' => 'name DESC',
				),
			),
			'category' => array(
				'header' => array(
					'value' => $txt['footer_menu_category'],
				),
				'data' => array(
					'function' => create_function('$rowData', '
						$ret = sprintf(\'<select name="category[%1$s]">\', $rowData[\'id_item\']);

						foreach (total_getCategories() as $id => $category)
							$ret .= \'
								<option value="\' . $id . \'"\' . ($id == $rowData[\'category\'] ? \' selected="selected"\' : \'\') . \'>\' . $category . \'</option>\';

						return $ret . \'
							</select>\';
					'),
					'style' => 'width: 10%; text-align: center;',
				),
				'sort' => array(
					'default' => 'category DESC',
					'reverse' => 'category',
				),
			),
			'active' => array(
				'header' => array(
					'value' => $txt['footer_menu_active'],
				),
				'data' => array(
					'function' => create_function('$rowData', '
						global $txt;
						$isChecked = $rowData[\'active\'] == \'no\' ? \'\' : \' checked\';
						return sprintf(\'<span id="active_%1$s" class="color_%4$s">%3$s</span>&nbsp;<input type="checkbox" name="active[%1$s]" id="active_%1$s" value="%1$s"%2$s>\', $rowData[\'id_item\'], $isChecked, $txt[$rowData[\'active\']], $rowData[\'active\']);
					'),
					'style' => 'width: 10%; text-align: center;',
				),
				'sort' => array(
					'default' => 'active DESC',
					'reverse' => 'active',
				),
			),
			'modify' => array(
				'header' => array(
					'value' => $txt['modify'],
				),
				'data' => array(
					'sprintf' => array(
						'format' => '<a href="' . $scripturl . '?action=admin;area=footermenu;sa=edit;in=%1$s">' . $txt['modify'] . '</a>',
						'params' => array(
							'id_item' => false,
						),
					),
					'style' => 'width: 10%; text-align: center;',
				),
			),
			'remove' => array(
				'header' => array(
					'value' => $txt['remove'],
				),
				'data' => array(
					'function' => create_function('$rowData', '
						global $txt;
						return sprintf(\'<span id="remove_%1$s" class="color_no">%2$s</span>&nbsp;<input type="checkbox" name="remove[%1$s]" id="remove_%1$s" value="%1$s">\', $rowData[\'id_item\'], $txt[\'no\']);
					'),
					'style' => 'width: 10%; text-align: center;',
				),
			),
		),
		'form' => array(
			'href' => $scripturl . '?action=admin;area=footermenu',
			'name' => 'customProfileFields',
		),
		'additional_rows' => array(
			array(
				'position' => 'below_table_data',
				'value' => '<input type="submit" name="save" value="' . $txt['save'] . '" class="button_submit">&nbsp;&nbsp;<input type="submit" name="delete" value="' . $txt['delete'] . '" onclick="return confirm(' . JavaScriptEscape($txt['footer_menu_delete_sure']) . ');" class="button_submit">&nbsp;&nbsp;<input type="submit" name="new" value="' . $txt['footer_menu_make_new'] . '" class="button_submit">',
				'style' => 'text-align: right;',
			),
		),
	);
	require_once($sourcedir . '/Subs-List.php');
	createList($listOptions);
	$context['sub_template'] = 'show_list';
	$context['default_list'] = 'footer_menu_items';
}

function list_getFooterMenu($start, $items_per_page, $sort)
{
	global $smcFunc;

	$list = array();
	$request = $smcFunc['db_query']('', '
		SELECT id_item, name, url, category, active
		FROM {db_prefix}footer_menu
		ORDER BY {raw:sort}
		LIMIT {int:start}, {int:items_per_page}',
		array(
			'sort' => $sort,
			'start' => $start,
			'items_per_page' => $items_per_page,
		)
	);
	while ($row = $smcFunc['db_fetch_assoc']($request))
		$list[] = $row;
	$smcFunc['db_free_result']($request);

	return $list;
}

function total_getFooterMenu()
{
	global $smcFunc;

	$list = array();
	$request = $smcFunc['db_query']('', '
		SELECT *
		FROM {db_prefix}footer_menu');
	while ($row = $smcFunc['db_fetch_assoc']($request))
		$list[$row['id_item']] = $row;
	$smcFunc['db_free_result']($request);
	return $list;
}

function total_getCategories()
{
	global $smcFunc;

	$list = array();
	$request = $smcFunc['db_query']('', '
		SELECT *
		FROM {db_prefix}footer_menu_categories');
	while ($row = $smcFunc['db_fetch_assoc']($request))
		$list[$row['id_item']] = $row['name'];
	$smcFunc['db_free_result']($request);
	return $list;
}

function get_footer_menu_filtered()
{
	global $context, $modSettings, $user_info;

	$modSettings['footer_menu'] = @unserialize($modSettings['footer_menu']);
	$context['footer_menu'] = &$modSettings['footer_menu'];
	foreach ($modSettings['footer_menu'] as $id_cat => $cat)
		foreach ($cat['items'] as $id => $item)
		{
			$group_list = explode(',', $item['groups']);
			$is_allowed = array_intersect($user_info['groups'], $group_list);
			if (empty($is_allowed))
				unset($context['footer_menu'][$id_cat]['items'][$id]);
			else
				$context['footer_menu'][$id_cat]['items'][$id] = array(
					'name' => $item['name'],
					'url' => $item['url'],
				);
		}
}

function rebuild_footer_menu()
{
	global $smcFunc;

	$list = array();
	$request = $smcFunc['db_query']('', '
		SELECT i.*, c.name AS cName
		FROM {db_prefix}footer_menu AS i
			JOIN {db_prefix}footer_menu_categories AS c ON (c.id_item = i.category)
		WHERE active = {string:active}',
		array(
			'active' => 'yes',
		)
	);
	while ($row = $smcFunc['db_fetch_assoc']($request))
	{
		if (!isset($list[$row['category']]))
			$list[$row['category']] = array(
				'name' => $row['cName'],
				'items' => array(),
			);
		$list[$row['category']]['items'][$row['id_item']] = $row;
	}
	$smcFunc['db_free_result']($request);
	updateSettings(
		array(
			'footer_menu' => serialize($list),
		)
	);
}

function list_getFooterMenuSize()
{
	global $smcFunc;

	$request = $smcFunc['db_query']('', '
		SELECT COUNT(*)
		FROM {db_prefix}footer_menu');

	list ($numProfileFields) = $smcFunc['db_fetch_row']($request);
	$smcFunc['db_free_result']($request);

	return $numProfileFields;
}

function EditFooterMenu()
{
	global $txt, $scripturl, $context, $settings, $smcFunc;

	$context['in'] = isset($_REQUEST['in']) ? (int) $_REQUEST['in'] : 0;
	$context['page_title'] = $txt['footer_menu'] . ' - ' . ($context['in'] ? $txt['footer_menu_title'] : $txt['footer_menu_add']);
	loadTemplate('FooterMenu');
	loadLanguage('ManageBoards');

	$request = $smcFunc['db_query']('', '
		SELECT id_group, group_name, online_color
		FROM {db_prefix}membergroups
		WHERE min_posts = {int:min_posts}
			AND id_group != {int:mod_group}
		ORDER BY group_name',
		array(
			'min_posts' => -1,
			'mod_group' => 3,
		)
	);
	$context['groups'] = array(
		-1 => $txt['parent_guests_only'],
		0 => $txt['parent_members_only'],
	);
	while ($row = $smcFunc['db_fetch_assoc']($request))
		$context['groups'][$row['id_group']] = '<span' . ($row['online_color'] ? ' style="color: ' . $row['online_color'] . '"' : '') . '>' . $row['group_name'] . '</span>';
	$smcFunc['db_free_result']($request);

	$context['categories'] = total_getCategories();

	loadLanguage('Profile');

	if ($context['in'])
	{
		$request = $smcFunc['db_query']('', '
			SELECT *
			FROM {db_prefix}footer_menu
			WHERE id_item = {int:current_item}',
			array(
				'current_item' => $context['in'],
			)
		);
		$context['item'] = array();
		while ($row = $smcFunc['db_fetch_assoc']($request))
			$context['item'] = array(
				'name' => $row['name'],
				'url' => $row['url'],
				'category' => $row['category'],
				'active' => $row['active'],
				'groups' => !empty($row['groups']) ? explode(',', $row['groups']) : array(),
			);
		$smcFunc['db_free_result']($request);
	}

	// Setup the default values as needed.
	if (empty($context['item']))
		$context['item'] = array(
			'name' => '',
			'url' => '',
			'category' => 1,
			'active' => true,
			'groups' => array(),
		);

	$all_groups_checked = array_intersect($context['item']['groups'], array_keys($context['groups']));
	if (!empty($all_groups_checked))
		$context['all_groups_checked'] = true;
	else
		$context['all_groups_checked'] = false;

	// Are we saving?
	if (isset($_POST['save']))
	{
		checkSession();

		if (trim($_POST['name']) == '')
			fatal_lang_error('custom_option_need_name');
		$_POST['name'] = $smcFunc['htmlspecialchars']($_POST['name']);
		$_POST['url'] = $smcFunc['htmlspecialchars']($_POST['url']);
		$category = $smcFunc['htmlspecialchars']($_POST['category']);

		$active = !empty($_POST['active']) ? 'yes' : 'no';
		$groups = !empty($_POST['groups']) ? implode(',', $_POST['groups']) : '';

		$up_col = array(
			'name = {string:name}',
			' url = {string:url}',
			'active = {string:active}',
			' id_item = {int:current_item}',
			' category = {string:category}',
			'groups = {string:groups}',
		);
		$up_data = array(
			'active' => $active,
			'category' => $category,
			'current_item' => $context['in'],
			'name' => $_POST['name'],
			'url' => $_POST['url'],
			'groups' => $groups,
		);
		$in_col = array(
			'name' => 'string',
			'url' => 'string',
			'active' => 'string',
			'category' => 'string',
			'groups' => 'string',
		);
		$in_data = array(
			$_POST['name'],
			$_POST['url'],
			$active,
			$category,
			$groups,
		);
		call_integration_hook('integrate_save_post_item', array(&$up_col, &$up_data, &$in_col, &$in_data));

		if ($context['in'])
		{
			$smcFunc['db_query']('', '
				UPDATE {db_prefix}footer_menu
				SET
					' . implode(',
					', $up_col) . '
				WHERE id_item = {int:current_item}',
				$up_data
			);
		}
		else
		{
			$smcFunc['db_insert']('',
				'{db_prefix}footer_menu',
				$in_col,
				$in_data,
				array('id_item')
			);
		}

		rebuild_footer_menu();
		redirectexit('action=admin;area=footermenu');
	}
	elseif (isset($_POST['delete']) && $context['item']['colname'])
	{
		checkSession();

		$smcFunc['db_query']('', '
			DELETE FROM {db_prefix}footer_menu
			WHERE id_item = {int:current_item}',
			array(
				'current_item' => $context['in'],
			)
		);
		rebuild_footer_menu();
		redirectexit('action=admin;area=footermenu');
	}
}

?>