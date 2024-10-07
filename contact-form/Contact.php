<?php

if (!defined('SMF'))
	die('Hacking attempt...');

function Contact()
{
	global $context, $mbname, $txt, $sourcedir, $modSettings, $webmaster_email, $user_info;

	if (!$user_info['is_guest'])
		redirectexit();

	loadTemplate('Contact');
	$context['robot_no_index'] = true;
	$context['linktree'][] = array(
		'url' => $scripturl . '?action=contact',
		'name' => $txt['contact_title'],
	);

	if (isset($_POST['send']))
	{
		checkSession('post');
		validateToken('contact');
		$context['errors'] = array();

		if (!empty($modSettings['reg_verification']) && $context['user']['is_guest'])
		{
			require_once $sourcedir . '/Subs-Editor.php';
			$verificationOptions = array(
				'id' => 'contact',
			);
			$context['visual_verification'] = create_control_verification($verificationOptions, true);

			if (is_array($context['visual_verification']))
			{
				loadLanguage('Errors');
				foreach ($context['visual_verification'] as $error)
					$context['errors'][] = $txt['error_' . $error];
			}
		}

		$fields = array('from', 'email', 'subject', 'message');
		foreach ($fields as $field)
			if (empty($_POST[$field]))
				$context['errors'][] = $txt['contact_' . $field . '_blank'];

		if (empty($context['errors']))
		{
			spamProtection('contact');
			$message = <<<EOD
Name: {$_POST['from']}
Email: {$_POST['email']}
Subject: {$_POST['subject']}

{$_POST['message']}
EOD;

			require_once $sourcedir . '/Subs-Post.php';
			sendmail($webmaster_email, $mbname . ' - ' . %_POST['subject'], $message, $_POST['email']);

			$context['sub_template'] = 'send';
			$context['page_title'] = $txt['contact_title_sent'];
			return;
		}
	}

	if (!empty($modSettings['reg_verification']))
	{
		require_once $sourcedir . '/Subs-Editor.php';
		$verificationOptions = array(
			'id' => 'contact',
		);
		$context['visual_verification'] = create_control_verification($verificationOptions);
		$context['visual_verification_id'] = $verificationOptions['id'];
	}
	else
		$context['visual_verification'] = false;

	$context['page_title'] = $txt['contact_title'];
	createToken('contact');
}
