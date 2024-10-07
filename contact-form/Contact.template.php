<?php

function template_main()
{
	global $scripturl, $txt, $context;

	if (!empty($context['errors']))
		echo '
				<div class="errorbox"><ul><li>', implode('</li><li>', $context['errors']), '</li></ul></div>';

	echo '
		<div class="cat_bar">
			<h3 class="catbg">
				', $context['page_title'], '
			</h3>
		</div>
		<div class="information noup">
			', $txt['contact_all_fields_required'], '
		</div>
		<form action="', $scripturl, '?action=contact" method="post">
			<div class="windowbg noup">
				<span class="half_content lower_padding"><b>', $txt['contact_field_from'], ':</b></span>
				<span class="half_content lower_padding"><input type="text" name="from" size="50" /></span>
				<span class="half_content lower_padding"><b>', $txt['contact_field_email'], ':</b></span>
				<span class="half_content lower_padding"><input type="text" name="email" size="50" /></span>
				<span class="half_content lower_padding"><b>', $txt['contact_field_subject'], ':</b></span>
				<span class="half_content lower_padding"><input type="text" name="subject" size="50" /></span>
				<span class="half_content lower_padding"><b>', $txt['contact_field_message'], ':</b></span>
				<span class="half_content lower_padding"><textarea rows="6" name="message" cols="42"></textarea></span>';

	if ($context['visual_verification'])
	{
		echo '
				<span class="half_content lower_padding"><b>', $txt['verification'], ':</b></span>
				<span class="half_content lower_padding">', template_control_verification($context['visual_verification_id'], 'all'), '</span>';
	}

	echo '
				<div class="righttext">
					<input type="submit" value="', $txt['sendtopic_send'], '" name="send" tabindex="', $context['tabindex']++, '" class="button" />
					<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
					<input type="hidden" name="', $context['contact_token_var'], '" value="', $context['contact_token'], '" />
				</div>
			</div>
		</form>';
}

function template_send()
{
	global $scripturl, $txt, $context;

	echo '
		<div class="cat_bar">
			<h3 class="catbg">
				', $context['page_title'], '
			</h3>
		</div>
		<div class="information noup">
			', $txt['contact_success'], '
		</div>
		<div class="windowbg noup">
			<a href="', $scripturl, '">', $txt['contact_success_link'], '</a>
		</div>';
}