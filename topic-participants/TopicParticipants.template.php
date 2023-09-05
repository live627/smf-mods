<?php

function template_main()
{
	global $context, $txt, $memberContext, $settings;

	echo '
	<div class="cat_bar">
		<h3 class="catbg">
			', $txt['topic_participants'], '
		</h3>
	</div>
	<span class="upperframe"><span></span></span>
	<div class="roundframe noup">
	<table>
		<tbody class="content">';

	$other = allowedTo('profile_view_any');
	foreach ($context['topic_participants'] as $member => $num)
	{
		loadMemberContext($member);

		echo '
			<tr>
				<td width="40%" align="center">', $memberContext[$member]['avatar']['image'], '</td>
				<td>
					<table width="100%" cellspacing="8">
						<tr><td><h4>', $other ? $memberContext[$member]['link'] : $memberContext[$member]['name'], '</h4></td>';

		if ($memberContext[$member]['group'] != '')
			echo '
						<tr><td>', $memberContext[$member]['group'], '</td></tr>';
		elseif (!empty($settings['hide_post_group']) && $memberContext[$member]['post_group'] != '')
			echo '
						<tr><td>', $memberContext[$member]['post_group'], '</td></tr>';

		if (!empty($settings['show_blurb']) && $memberContext[$member]['title'] != '')
			echo '
						<tr><td>', $memberContext[$member]['title'], '</td></tr>';

		echo '
						<tr><td>', $txt['member_postcount'], ': ', $num, '</td></tr>';

		echo '
					</table>
				</td>
			</tr>';
	}
	echo '
		</tbody>
	</table>
	</div>
	<span class="lowerframe"><span></span></span>';
}
