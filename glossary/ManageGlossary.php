<?php

function ModifyGlossarySettings()
{
	global $txt, $scripturl, $context, $settings, $sc;

	$config_vars = array(
			array('check', 'enable_glossary_mod'),
			array('check', 'enable_tooltips'),
			'',
			array('check', 'enable_numeric_glossary'),
			array('check', 'enable_groups_in_glossary'),
			'',
			array('check', 'glossary_none_sensitive'),
            array('text', 'glossary_separator', 1),
            array('check', 'enable_bbc_tooltip_glossary'),
            array('check', 'glossary_unique_word'),
            array('check', 'glossary_show_in_message_default'),
            array('check', 'glossary_tooltip_in_simpleportal'),
            array('check', 'glossary_admin_context_menu'),
			'',
            array('int', 'glossary_definition_width',4),
            array('int', 'glossary_word_width',4),
	);

	// Saving?
	if (isset($_GET['save']))
	{
		checkSession();

		saveDBSettings($config_vars);
		redirectexit('action=admin;area=modsettings;sa=glossary');
	}

	$context['post_url'] = $scripturl . '?action=admin;area=modsettings;save;sa=glossary';
	$context['settings_title'] = $txt['glossary'];

	prepareDBSettingContext($config_vars);
}