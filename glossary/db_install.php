<?php

/*************************************************************************************************************
* db_install.php - Database installation script for Glossary for SMF 2.1 mod (v1.4)
**************************************************************************************************************
* This mod is licensed under the 2-Clause BSD License, which can be found here:
*	https://opensource.org/licenses/BSD-2-Clause
**************************************************************************************************************
* Copyright (c) 2008-2009 Slinouille, 2024 Kathy Leslie, 2024 John Rayes
* Redistribution and use in source and binary forms, with or without modification, are permitted provided
* that the following conditions are met:
*	1.	Redistributions of source code must retain the above copyright notice, this list of conditions
*		and the following disclaimer.
*	2.	Redistributions in binary form must reproduce the above copyright notice, this list of conditions and
*		the following disclaimer in the documentation and/or other materials provided with the distribution.
* This software is provided by the copyright holders and contributors "as is" and any express or implied
* warranties, including, but not limited to, the implied warranties of merchantability and fitness for a
* particular purpose are disclaimed. In no event shall the copyright holder or contributors be liable for
* any direct, indirect, incidental, special, exemplary, or consequential damages (including, but not
* limited to, procurement of substitute goods or services; loss of use, data, or profits; or business
* interruption) however caused and on any theory of liability, whether in contract, strict liability, or
* tort (including negligence or otherwise) arising in any way out of the use of this software, even if
* advised of the possibility of such damage.
*************************************************************************************************************/

//===========================================================================================================
// Original Glossary mod by Slinouille
// https://www.simplemachines.org/community/index.php?action=profile;u=68142
// https://custom.simplemachines.org/mods/index.php?mod=1525
//
// Updated and enhanced for SMF 2.1 by GL700Wing
// https://www.simplemachines.org/community/index.php?action=profile;u=112942
//
// Version 1.4: Amazing and incredibly fast parsing code in Glossary.php contributed by live627
// (https://www.simplemachines.org/community/index.php?action=profile;u=154736)
//===========================================================================================================

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

//===================================================================================
// Create the database table 'glossary' for the keywords and definitions.
//===================================================================================
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

//===================================================================================
// Create the database table 'glossary_groups' for the categories.
//===================================================================================
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

//===================================================================================
// Version 1.0: Add the 'case_sensitive' column to the 'glossary' table.
//===================================================================================
$smcFunc['db_add_column'](
	'{db_prefix}glossary',
	array(
		'name' => 'case_sensitive',
		'type' => 'INT',
		'size' => '1',
		'default' => 0,
		'null' => false,
	)
);

//===================================================================================
// Version 1.4: Add the 'tag_only' column to the 'glossary' table.
//===================================================================================
$smcFunc['db_add_column'](
	'{db_prefix}glossary',
	array(
		'name' => 'tag_only',
		'type' => 'INT',
		'size' => '1',
		'default' => 0,
		'null' => false,
	)
);

//===================================================================================
// Create the mod settings (but don't overwrite any existing settings).
//===================================================================================
// Version 1.2: Add settings for excluding boards from Glossary use.
// Version 1.4: Add setting to convert the separator character(s) to a line break.
$smcFunc['db_insert']('ignore',
	'{db_prefix}settings',
	array('variable' => 'string', 'value' => 'string',),
	array(
		array('glossary_mod_enable', '1'),
		array('glossary_enable_tooltips', '1'),
		array('glossary_tooltip_signature', '0'),
		array('glossary_tooltip_pm', '0'),
		array('glossary_tooltip_news', '0'),
		array('glossary_bbcode_only_mode', '0'),
		array('glossary_enable_boards_to_exclude', '0'),
		array('glossary_show_board_ids', '0'),
		array('glossary_tooltip_bbc', '0'),
		array('glossary_tooltip_once', '0'),
		array('glossary_case_sensitive', '0'),
		array('glossary_separator', ''),
		array('glossary_separator_convert', '0'),
		array('glossary_enable_numeric', '0'),
		array('glossary_enable_synonyms', '0'),
		array('glossary_show_synonyms', '0'),
		array('glossary_enable_groups', '0'),
		array('glossary_show_used_chars', '0'),
		array('glossary_show_author_admin', '0'),
		array('glossary_show_author_all', '0'),
		array('glossary_author_exclude', '0'),
		array('glossary_show_tooltips_default', '1'),
		array('glossary_approve_keyword_default', '1'),
		array('glossary_admin_context_menu', '0'),
		array('glossary_word_width', '150'),
		array('glossary_category_width', '100'),
	),
	array('variable')
);

?>
