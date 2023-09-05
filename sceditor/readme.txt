[size=x-large][b]SCEditor Integration for SMF 2.0.x[/b][/size]

Integrates [url=http://www.sceditor.com/]SCEditor[/url] into your SMF 2.0 forum, retaining the same button layout and familiar icons.
[list]
[li]Full reply integration
[list]
[li]It works with third party scripts that use the WYSIWYG editor[/li]
[/list]
[/li]
[li]Quick reply integration[/li]
[li]Quick edit integration
[list]
[li]Only works if quick reply is enabled; falls back to the simple textarea otherwise[/li]
[/list]
[/li]
[/list]

Any mods that add toolbar buttons will still work as expected. Raw bbcodes will be shown in the WYSIWYG editor for them.

Also adds [tt][b]integrate_sceditor_options[/b]][/tt] to allow mods to change $sce_options and possibly enable compatibility with SMF  2.1 mods that extend SCEditor. See https://www.simplemachines.org/community/index.php?topic=581766.msg4119305#msg4119305 for a tentative list.


[size=large][b]Settings[/b][/size]

There are no settings. Install to enable, uninstall to disable.


[size=large][b]License[/b][/size]

[list]
	[li]This modification is licensed under the unlicense. A full copy of this license is included in the package file.[/li]
	[li]SCEditor is licensed under the MIT license.[/li]
[/list]


[size=large][b]Changelog[/b][/size]

Version 1.1.0:
[list]
	[li]Add integrate_sceditor_options[/li]
[/list]

Version 1.0.1:
[list]
	[li]Don't show the smiley popup if no smileys are in the popup[/li]
[/list]

Version 1.0.0:
[list]
	[li]Initial release[/li]
[/list]
