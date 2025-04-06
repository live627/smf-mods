<?php
// Version 1.0: Rules.template.php

function template_main()
{
	global $context;

	echo '
	<div class="cat_bar">
		<h3 class="catbg">
			', $context['page_title'], '
		</h3>
	</div>
	<span class="upperframe"><span></span></span>
	<div class="roundframe">
		', $context['page_contents'], '
	</div>
	<span class="lowerframe"><span></span></span>';
}

?>