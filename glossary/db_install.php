<?php

//============================================================================
// Original Glossary mod by Slinouille
// https://www.simplemachines.org/community/index.php?action=profile;u=68142
// https://custom.simplemachines.org/mods/index.php?mod=1525
//
// Updated and enhanced for SMF 2.1 by GL700Wing
// https://www.simplemachines.org/community/index.php?action=profile;u=112942
//============================================================================

$SSI_INSTALL = false;
if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
{
	$SSI_INSTALL = true;
	require_once(dirname(__FILE__) . '/SSI.php');
}
elseif (!defined('SMF')) // If we are outside SMF and can't find SSI.php, then throw an error
	die('<b>Error:</b> Cannot install - please verify you put this file in the same place as SMF\'s SSI.php.');

require_once($sourcedir.'/Subs-Admin.php');
db_extend('packages');

// Create the database table for the keywords and definitions.
$columns = array(
	array(
		'name' => 'id',
		'type' => 'INT',
		'size' => '12',
		'default' => 0,
		'null' => false,
		'auto' => true,
	),
	array(
		'name' => 'word',
		'type' => 'VARCHAR',
		'size' => '50',
		'default' => 0,
		'null' => false,
	),
	array(
		'name' => 'definition',
		'type' => 'TEXT',
		'null' => false,
	),
	array(
		'name' => 'member_id',
		'type' => 'INT',
		'size' => '13',
		'default' => 0,
		'null' => false,
	),
	array(
		'name' => 'date',
		'type' => 'VARCHAR',
		'size' => '30',
		'default' => 0,
		'null' => false,
	),
	array(
		'name' => 'valid',
		'type' => 'INT',
		'size' => '1',
		'default' => 0,
		'null' => false,
	),
	array(
		'name' => 'synonyms',
		'type' => 'TEXT',
		'null' => false,
	),
	array(
		'name' => 'show_in_message',
		'type' => 'INT',
		'size' => '1',
		'default' => 0,
		'null' => false,
	),
	array(
		'name' => 'group_id',
		'type' => 'INT',
		'size' => '8',
		'default' => 0,
		'null' => false,
	),
);
$indexes = array(
	array(
		'type' => 'primary',
		'columns' => array('id'),
	),
);
$smcFunc['db_create_table']('{db_prefix}glossary', $columns, $indexes, array(), 'ignore');

// Create the database table for the categories.
$columns = array(
	array(
		'name' => 'id',
		'type' => 'INT',
		'size' => '12',
		'default' => 0,
		'null' => false,
		'auto' => true,
	),
	array(
		'name' => 'title',
		'type' => 'VARCHAR',
		'size' => '50',
		'default' => 0,
		'null' => false,
	),
);
$indexes = array(
	array(
		'type' => 'primary',
		'columns' => array('id'),
	),
);
$smcFunc['db_create_table']('{db_prefix}glossary_groups', $columns, $indexes, array(), 'ignore');

// Create the mod settings (but don't overwrite any existing settings).
$smcFunc['db_insert']('ignore',
	'{db_prefix}settings',
	array('variable' => 'string', 'value' => 'string',),
	array(
		array('glossary_mod_enable', '1'),
		array('glossary_enable_tooltips', '1'),
		array('glossary_tooltip_once', '0'),
		array('glossary_case_sensitive', '0'),
		array('glossary_enable_numeric', '0'),
		array('glossary_enable_synonyms', '0'),
		array('glossary_enable_groups', '0'),
		array('glossary_tooltip_bbc', '0'),
		array('glossary_separator', ''),
		array('glossary_show_used_chars', '0'),
		array('glossary_show_author_admin', '0'),
		array('glossary_show_author_all', '0'),
		array('glossary_show_tooltips_default', '1'),
		array('glossary_approve_keyword_default', '1'),
		array('glossary_admin_context_menu', '0'),
		array('glossary_word_width', '100'),
		array('glossary_definition_width', '800'),
	),
	array('variable')
);

?>
