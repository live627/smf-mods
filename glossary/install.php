<?php
/*****************************************************************
* SMF GLossary									             *
******************************************************************/

// OK First load the stuff
global $smcFunc, $db_prefix, $context, $boarddir, $db_name, $db_passwd, $db_user, $db_server, $modSettings, $scripturl, $boardurl;

$doing_manual_install = false;

if (!defined('SMF') && file_exists(dirname(__FILE__) . '/SSI.php'))
{
	require_once(dirname(__FILE__) . '/SSI.php');
	$doing_manual_install = true;
}
elseif (!defined('SMF'))
	die('The installer wasn\'t able to connect to SMF! Make sure that you are either installing this via the Package Manager or the SSI.php file is in the same directory.');

// The whole "delete" part was copied from the SMF code. Thanks, guys!
if (isset($_GET['delete']))
{
	@unlink(__FILE__);

	header('Location: http://' . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT']) . dirname($_SERVER['PHP_SELF']) . '/Themes/default/images/blank.gif');
	exit;
}

if ($doing_manual_install)
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
	<title>SMD Glossary mod Database Installer</title>
     <link rel="stylesheet" type="text/css" href="Themes/default/style.css" />
</head>
<body>
	<br /><br />';


// Start it!
// First load the SMF 2's Extra DB Functions
db_extend('packages');
db_extend('extra');

// Get the table list
	$tables = array();
	$tmp = $smcFunc['db_list_tables']();
	foreach ($tmp as $t)
		if (substr($db_prefix,0,strlen($db_name) + 3) != '`'.$db_name.'`.')
			$tables[] = $t;
		else
			$tables[] = '`'.$db_name . '`.'.$t;

// Create the tables
$smcFunc['db_create_table'](
	'{db_prefix}glossary',
	array(
		array('name' => 'id','type' => 'INT','size' => '12','default' => 0,'null' => false,'auto' => true),
		array('name' => 'word','type' => 'VARCHAR','size' => '50','default' => 0,'null' => false),
		array('name' => 'definition','type' => 'TEXT','null' => false),
		array('name' => 'member_id','type' => 'INT','size' => '13','default' => 0,'null' => false),
		array('name' => 'date','type' => 'VARCHAR','size' => '30','default' => 0,'null' => false),
		array('name' => 'valid','type' => 'INT','size' => '1','default' => 0,'null' => false),
		array('name' => 'synonyms','type' => 'TEXT','null' => false),
		array('name' => 'show_in_message','type' => 'INT','size' => '1','default' => 0,'null' => false),
		array('name' => 'group_id','type' => 'INT','size' => '8','default' => 0,'null' => false),
	),
	array(
		array(
			'type' => 'primary',
			'columns' => array('id')
		)
	),
	array(),
	'ignore'
);

$created_tables[] = 'glossary';

$smcFunc['db_create_table'](
	'{db_prefix}glossary_groups',
	array(
		array('name' => 'id','type' => 'INT','size' => '12','default' => 0,'null' => false,'auto' => true),
		array('name' => 'title','type' => 'VARCHAR','size' => '50','default' => 0,'null' => false),
	),
	array(
		array(
			'type' => 'primary',
			'columns' => array('id')
		)
	),
	array(),
	'ignore'
);
$created_tables[] = 'glossary_groups';

echo '
<table cellpadding="0" cellspacing="0" border="0" class="tborder" width="550" align="center"><tr><td>
<div class="titlebg" style="padding: 1ex">
	SMF Glossary mod Database Installer
</div>
<div class="windowbg2" style="padding: 2ex">';

// Tell them what has been done
echo '<b>Creating / Updating Tables</b>
<br /><br />
<ul class="normallist">';
foreach ($created_tables as $table_name)
	if (in_array($table_name, $tables))
		echo '
	<li>Table <i>'.preg_replace('/`[^`]+`\./', '', $table_name).'</i> already exists.</li>';
	else
		echo '
	<li>'.$table_name.' table created.</li>';

echo '
</ul>';
if ( count($addFields) > 0 ){
	echo '
	<br /><br />
	<b>Fields added :</b>
	<ul class="normallist">';
		foreach($addFields as $field)
			echo '<li>'.$field.'</li>';
	echo '
	</ul>';
}
if ( count($changeFields) > 0 ){
	echo '
	<br /><br />
	<b>Fields changed :</b>
	<ul class="normallist">';
		foreach($changeFields as $field)
			echo '<li>'.$field.'</li>';
	echo '
	</ul>';
}
echo '
	<br /><br /><b>Thank you for trying out SMF Glossary mod!</b>
</div>
</td></tr></table>
<br />';

if ($doing_manual_install)
	echo '
</body></html>';

?>
