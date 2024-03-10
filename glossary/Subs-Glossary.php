<?php
	
//Parse the message for highlighting the glossary key words
function parse_glossary($message)
{
	global $context, $modSettings, $smcFunc, $db_prefix;

	if ( empty($context['glossary_list']) ){
		$context['glossary_list'] = array();
		$data_glossary = $smcFunc['db_query']('', '
			SELECT *
			FROM {db_prefix}glossary
			WHERE valid = {int:valid} AND show_in_message = {int:show_in_message}
			ORDER BY word ASC',
			array(
				'valid' => 1,
				'show_in_message' => 1,
			)
		);   
		while ($res = $smcFunc['db_fetch_assoc']($data_glossary) ){
			$context['glossary_list'][strtolower($res['word'])] = $smcFunc['htmlspecialchars'](parse_bbc($res['definition']));
			if ( !empty($res['synonyms']) ){
				$synonyms = explode(',',$res['synonyms']);
				foreach ( $synonyms as $synonym ){
					$context['glossary_list'][strtolower($synonym)] = $smcFunc['htmlspecialchars'](parse_bbc($res['definition']));
				}
			}
		}
		$smcFunc['db_free_result']($data_glossary);
	$context['ep_time'] = 0;
	$context['words'] = build_regex(array_keys( $context['glossary_list']), '/');
	}

	// 1. The SKIP-FAIL block prevents any matches within code tags.
	// 2. The negative lookahead prevents a match if a word is a html tag.
	$message = preg_replace_callback(
		'/<code.*?<\/code>(*SKIP)(*FAIL)|\b'. $context['words'] . '\b(?![^<]*>)/'. (!empty($modSettings['glossary_none_sensitive']) ? 'i' : ''),
		function ($matches) use ($context) {
			return '<span class="glossary highlight" title="' . $context['glossary_list'][strtolower($matches[0])] . '">' . $matches[0] . '</span>';
		},
		$message,
		(isset($modSettings['glossary_unique_word']) && $modSettings['glossary_unique_word']==1) ? 1 : -1
	);
	return $message;
}

namespace Glossary;

/**
 * Called by:
 *        integrate_actions
 */
function actions(array &$actionArray): void
{
	$actionArray['glossary'] = array('Glossary.php', 'Glossary');
}

/**
 * Called by:
 *        integrate_menu_buttons
 */
function buttons(array &$buttons): void
{
	global $scripturl, $txt;

		$counter = 0;
		foreach ($buttons as $name => $array)
		{
			$counter++;
			if ($name == 'calendar')
				break;
		}

		$buttons = array_merge(
			array_slice($buttons, 0, $counter, true),
			[
			'glossary' => array(
				'title' => $txt['glossary'],
				'href' => $scripturl . '?action=glossary',
				'show' => allowedTo('view_glossary')
			),
			],
			array_slice($buttons, $counter, null, true)
		);
	}

function load_theme()
	{
		loadLanguage('Glossary');
		loadCSSFile('glossary.tooltip.css', ['minimize' => true]);
		loadJavaScriptFile('glossary.tooltip.js', ['minimize' => true]);
	}

function admin_areas(array &$admin_areas): void
{
	global $txt;

	$admin_areas['config']['areas']['modsettings']['subsections']['glossary'] = array($txt['glossary']);
}

function modify_modifications(array &$sub_actions): void
{
	require_once __DIR__ . '/ManageGlossary.php';
	$sub_actions['glossary'] = 'ModifyGlossarySettings';
}

function admin_search(array &$language_files, array &$include_files, array &$settings_search): void
{
	//~ $language_files[] = 'ManageGlossary';
	$include_files[] = 'ManageGlossary';
	$settings_search[] = array('ModifyGlossarySettings', 'area=modsettings;sa=glossary');
}

function load_permissions(&$permissionGroups, &$permissionList)
{
			$permissionList['membergroup'] += array(
			'view_glossary' => array(false, 'glossary', 'view_basic_info'),
			'admin_glossary' => array(false, 'glossary', 'administrate'),
			'suggest_glossary' => array(false, 'glossary', 'view_basic_info'),
	);
	}

function load_permission_levels(&$groupLevels)
	{
		$groupLevels['global']['restrict'][] = 'view_glossary';
	}

function illegal_guest_permissions()
	{
		global $context;

		$context['non_guest_permissions'][] = 'admin_glossary';
	}

function bbc_codes(&$codes)
	{
		$codes[] = array(
            'tag' => 'glossary',
            'type' => 'unparsed_content',
            'content' => '$1',
            'validate' => function(&$tag, &$data, $disabled) {
  global $smcFunc;

		$data_glossary = $smcFunc['db_query']('', '
			SELECT definition, word 
			FROM {db_prefix}glossary
			WHERE valid = {int:valid} AND (word) = ({string:word}) AND show_in_message = {string:show_in_message}',
			array(
				'valid' => 1,
				'show_in_message' => 0,
				'word' => $smcFunc['htmltrim']($data),
			)
		);   
			[$definition,$word] = $smcFunc['db_fetch_row']($data_glossary);
			if ($word!==null){
			$data = '<span class="glossary highlight" title="'. $smcFunc['htmlspecialchars']($definition).'">' . $data . '</span>';
		}
		static $once;
		if ($once != true) {
		$once = true;
		loadCSSFile('glossary.tooltip.css', ['minimize' => true]);
		loadJavaScriptFile('glossary.tooltip.js', ['minimize' => true]);
	}
}
			);
	}

function bbc_buttons(&$buttons)
	{
		global $settings, $txt;

		$temp = array();
		foreach ($buttons[0] as $tag)
		{
			$temp[] = $tag;

			if (isset($tag['code']) && $tag['code'] == 'justify')
			{
				$temp = array_merge(
					$temp,
					array(
						array(),
						array(
				'image' => 'glossary',
				'code' => 'glossary',
				'before' => '[glossary]',
				'after' => '[/glossary]',
				'description' => $txt['bbc_glossary']
						),
					)
				);
			}
		}

		$buttons[0] = $temp;
	}
