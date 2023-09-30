<?php

function topic_descriptions_load_theme(): void
{
	loadLanguage('TopicDescriptions');
}

function topic_descriptions_admin_areas(array &$admin_areas): void
{
	global $txt;

	$admin_areas['config']['areas']['modsettings']['subsections']['topicdescriptions'] = array($txt['topic_descriptions']);
}

function topic_descriptions_modify_modifications(array &$sub_actions): void
{
	$sub_actions['topicdescriptions'] = 'ModifyTopicDescriptionsSettings';
}

function topic_descriptions_buffer(string $buffer): string
{
	return preg_replace_callback(
		'/<form\K[^?]+\?action=admin;area=modsettings;sa=topicdescriptions/',
		function($m)
		{
			return '<form id="admin_form_wrapper"' . $m[0];
		},
		$buffer
	);
}
