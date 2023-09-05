<?php

/**
 * @package   Login Code
 * @version   1.0.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2022, John Rayes
 * @license   http://opensource.org/licenses/0BSD Zero-Clause BSD
 */

function template_main(): void
{
	global $context, $scripturl, $txt;

	echo '
		<form action="', $scripturl, '?action=logincode2" method="post" accept-charset="', $context['character_set'], '" id="postmodify">
			<div class="cat_bar">
				<h3 class="catbg">
					', $context['page_title'], '
				</h3>
			</div>
			<span class="upperframe"><span></span></span>
			<div class="roundframe noup">';

	if (isset($context['errors']))
	{
		echo '
					<div class="errorbox" id="errors">
						<strong>', $txt['logincode_error_title'], '</strong>
						<ul>';

		foreach ($context['errors'] as $error)
			echo '
							<li>', $txt[$error], '</li>';

		echo '
						</ul>
					</div>';
	}

	echo '
					<dl class="settings">
						<dt>
							<strong>', $txt['logincode_input'], ':</strong>
						</dt>
						<dd>
							<input type="text" name="code" value="" autocomplete="off" />
						</dd>
					</dl>
				<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
				<input name="u" value="', $context['u'], '" type="hidden" />
				<div class="righttext padding">
					<input name="submit" value="', $txt['logincode_submit'], '" class="button_submit" type="submit" />
				</div>
			</div>
			<span class="lowerframe"><span></span></span>
		</form>';
}
