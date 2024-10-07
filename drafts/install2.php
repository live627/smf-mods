<?php
/*
	Drafts Modification for SMF 2.0/1.1

	Created by:		Charles Hill
	Website:		http://www.degreesofzero.com/

	Copyright 2008 - 2010.  All Rights Reserved.


	This script is meant to be run either from the package manager or directly by URL.

	ATTENTION: If you are MANUALLY installing or upgrading with this package, please access
	it directly, with a URL like the following:
		http://www.yourdomain.com/forum/upgrade1-1-201.php (or similar)
*/

// If SSI.php is in the same place as this file, and SMF isn't defined, this is being run standalone.
if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
	require_once(dirname(__FILE__) . '/SSI.php');
// Hmm... no SSI.php and no SMF?
elseif (!defined('SMF'))
	die('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');

global $smcFunc, $db_prefix;

$drafts = array('insert' => array(), 'values' => array(), 'prev_version' => '');

// old version of the drafts mod previously installed... let's do some stuff
if ($smcFunc['db_num_rows']($smcFunc['db_query']('', 'SHOW TABLES LIKE {string:table}', array('table' => $db_prefix . 'drafts'))) > 0)
{
	if ($smcFunc['db_num_rows']($smcFunc['db_query']('', 'SHOW COLUMNS FROM {db_prefix}drafts LIKE {string:column}', array('column' => 'draftID'))) > 0)
		$drafts['prev_version'] = '1.0x';
	// Spuds had attached his own version (for SMF 2.0) in the drafts mod's support topic
	elseif ($smcFunc['db_num_rows']($smcFunc['db_query']('', 'SHOW COLUMNS FROM {db_prefix}drafts LIKE {string:column}', array('column' => 'poll'))) == 0)
		$drafts['prev_version'] = 'Spuds';

	if (!empty($drafts['prev_version']))
	{
		if ($drafts['prev_version'] == '1.0x')
		{
			$add_columns = array();

			// for backwards compatibility... make sure these columns are in the existing table
			foreach (array(
				'locked' => 'tinyint(4) unsigned NOT NULL default {int:zero}',
				'isSticky' => 'tinyint(4) unsigned NOT NULL default {int:zero}',
				'smileysEnabled' => 'tinyint(4) unsigned NOT NULL default {int:zero}',
				'topicID' => 'int(10) unsigned NOT NULL default {int:zero}',
				'icon' => 'varchar(16) NOT NULL default {string:icon_default}'
			) as $k => $sql)
				if ($smcFunc['db_num_rows']($smcFunc['db_query']('', 'SHOW COLUMNS FROM {db_prefix}drafts LIKE {string:column}', array('column' => $k))) == 0)
					$add_columns[] = '`' . $k . '` ' . $sql;

			unset($k, $sql);

			// add the columns that don't already exist
			if (!empty($add_columns))
				$smcFunc['db_query']('', '
					ALTER TABLE {db_prefix}drafts
						' . implode(',
						ADD ', $add_columns),
					array(
						'zero' => 0,
						'icon_default' => 'xx'
					)
				);

			unset($add_columns);

			$request = $smcFunc['db_query']('', '
				SELECT draftID, memberID, boardID, topicID, timestamp, locked, isSticky, smileysEnabled, icon, body, subject
				FROM {db_prefix}drafts'
			);
		}
		else
			$request = $smcFunc['db_query']('', '
				SELECT draft_id, member_id, board_id, topic_id, timestamp, locked, is_sticky, smileys_enabled, icon, body, subject
				FROM {db_prefix}drafts'
			);

		// let's us figure out which function to use for escaping a string that we want to insert into the db
		$dbresf = array(
			'MySQL' => 'mysql_real_escape_string',
			'PostgreSQL' => 'pg_escape_string',
			'SQLite' => 'sqlite_escape_string'
		);

		while ($row = $smcFunc['db_fetch_row']($request))
		{
			$drafts['insert'][] = '({int:draft_id' . $row[0] . '}, {int:member_id' . $row[0] . '}, {int:board_id' . $row[0] . '}, {int:topic_id' . $row[0] . '}, {int:timestamp' . $row[0] . '}, {int:locked' . $row[0] . '}, {int:is_sticky' . $row[0] . '}, {int:smileys_enabled' . $row[0] . '}, {string:icon' . $row[0] . '}, {string:body' . $row[0] . '}, {string:subject' . $row[0] . '})';

			$drafts['values'] += array(
				'draft_id' . $row[0] => (int) $row[0],
				'member_id' . $row[0] => (int) $row[1],
				'board_id' . $row[0] => (int) $row[2],
				'topic_id' . $row[0] => (int) $row[3],
				'timestamp' . $row[0] => (int) $row[4],
				'locked' . $row[0] => (int) $row[5],
				'is_sticky' . $row[0] => (int) $row[6],
				'smileys_enabled' . $row[0] => (int) $row[7],
				'icon' . $row[0] => substr($row[8], 0, 16),
				'body' . $row[0] => substr($dbresf[$smcFunc['db_title']](str_replace('\'', '&#39;', un_htmlspecialchars(stripslashes($row[9])))), 0, 65535),
				'subject' . $row[0] => substr($dbresf[$smcFunc['db_title']](str_replace('\'', '&#39;', un_htmlspecialchars(stripslashes($row[10])))), 0, 255)
			);
		}

		$smcFunc['db_free_result']($request);

		// just so we're sure we set up the table properly
		$smcFunc['db_query']('', '
			DROP TABLE IF EXISTS {db_prefix}drafts'
		);
	}
}

// create the drafts table
$smcFunc['db_query']('', '
	CREATE TABLE IF NOT EXISTS {db_prefix}drafts(
		draft_id mediumint(8) NOT NULL auto_increment,
		member_id mediumint(8) NOT NULL default {int:zero},
		board_id smallint(5) NOT NULL default {int:zero},
		topic_id mediumint(8) NOT NULL default {int:zero},
		timestamp int(10) NOT NULL default {int:zero},
		locked tinyint(1) NOT NULL default {int:zero},
		is_sticky tinyint(1) NOT NULL default {int:zero},
		smileys_enabled tinyint(1) NOT NULL default {int:zero},
		icon varchar(16) NOT NULL default {string:icon_default},
		body text NOT NULL,
		subject tinytext NOT NULL,
		poll text NOT NULL,
		PRIMARY KEY (draft_id))',
	array(
		'zero' => 0,
		'icon_default' => 'xx'
	)
);

// put the drafts back into the db
if (!empty($drafts['insert']))
	$smcFunc['db_query']('', '
		INSERT INTO {db_prefix}drafts
			(`draft_id`, `member_id`, `board_id`, `topic_id`, `timestamp`, `locked`, `is_sticky`, `smileys_enabled`, `icon`, `body`, `subject`)
		VALUES
			' . implode(',
			', $drafts['insert']),
		$drafts['values']
	);

unset($drafts);

// All Done
if (SMF == 'SSI')
   echo 'Congratulations! You have successfully installed this mod!';

?>