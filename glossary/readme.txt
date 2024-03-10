[hr]
[center][color=red][size=16pt][b]GLOSSARY v0.3.1[/b][/size][/color]
[url=http://custom.simplemachines.org/mods/index.php?action=search;author=68142][b]By Slinouille[/b][/url]
[/center]
[hr]

[color=blue][b][size=12pt][u]Introduction[/u][/size][/b][/color]
The mod adds a glossary to your forum. You can manage the words and associated definition and display a tooltip containing the definition in the messages.
Glossary mod uses:
- jQuery javascript library : http://jquery.com/
- jQuery Tooltip pluggin for tooltip activity from http://bassistance.de/jquery-plugins/jquery-plugin-tooltip.
- jQuery SimpleModal pluggin for modal forms from http://www.ericmmartin.com/projects/simplemodal/.
- famfam Silk icon set from http://www.famfamfam.com/

[color=blue][b][size=12pt][u]Features[/u][/size][/b][/color]
o Display a full list with words in your glossary
o Classification and selection by alphabetic order
o Can define one level of groups for your words in Glossary
o Manage rights for seeing and administrating the glossary, and for authorizing suggestion by membergroup
o Display a bubble tooltip on all words in the forms messages
o Activation in Admin Panel
o Enable or not the member keywords suggestion functionnality
o Keywords administration possibility for adding, updating and deleting
o Keywords detection can be optionaly case insensitive in messages
o Display title and body in tooltips
o Can activate or not bbccode in tooltips
o Define for each word if shown or not in messages
o bbc code for glossary word (in order to force tooltip for "not shown" words)
o If wanted, only display each word once per message
o Specific glossary administrators menu
o Enable a "right-click" administration menu (usefull for big glossaries)
o Languages : French, English, German

[color=blue][b][size=12pt][u]Installation[/u][/size][/b][/color]
Simply install the package to install this modification on the SMF Default Core theme.
Manual edits will be required for other themes.

Glossary mod is totaly compliant with any other javascript library.

[b]If you have a previous install, after update don't forget to save the glossary settings in admin panel so that all variables are reinitialised[/b]

[color=blue][b][size=12pt][u]Customize the Tooltips[/u][/size][/b][/color]
Please customize the file "jquery.tooltip.css" to fit Tootip Effect with your Forum template.

This mod is compatible with SMF 2.0 Beta 4 Public and above only.

[color=blue][b][size=12pt][u]Support[/u][/b][/color]
Please use the modification thread for support with this modification.

[color=blue][b][size=12pt][u]Changelog[/u][/size][/b][/color]
[b]0.3.1 - 25th April 2009[/b]
- bug correction: some .JS files and images were not associated to default theme directory (thanks to Kindred)
- bug correction: when keyword was ending a line, the tooltip description was badly interpreted (thanks to dekay)
- bug correction: url tag was visible in links when bbc was enabled in tooltips
- bug correction: in german language, a quote was missing (thanks to dekay)

[b]0.3.0 - 04th April 2009[/b]
o New specific Glossary page in admin panel
o New drop menu for Glossary administrators
o Enable a "right-click" administration menu (usefull for big glossaries)
o Add words selection for specific activity
o New option "By default, show definition in post"
o Installation and upgrading database has been entierely recoded
o Nicer dialog boxes
o Correcting errors in log
o Correcting bug "if a glossary word is also in the definition of another one"

[b]0.2.0 - 03th February 2009[/b]
o New admin actions directly in main page
o Categories management in one unique form

[b]0.1.9 - 03th February 2009[/b]
o Added option "Show glossary words only once per message"
o Added value "Show in message" for each glossary word. This permits to authorize or not a word to be shown in messages
o Added BBC code glossary in order to force a glossary word to be shown in message
o Added one level of category
o Correction of bug in tooltip for special caracters

[b]0.1.8 - 29th January 2009[/b]
o Correction of a (stupid) bug

[b]0.1.7 - 26th January 2009[/b]
o Correction of a bug if database in UTF-8 (reported by PerryM)

[b]0.1.6 - 24th January 2009[/b]
o Code rewritting for better management of non conflict mode. 
  jQuery library is now loaded independently of other libraries.

[b]0.1.5 - 17th January 2009[/b]
o Solved conflict mode with other than jQuery javascript librairies
o Added GERMAN translation (by CvH)

[b]0.1.4 - 2d January 2009[/b]
o Add possibility to have title and body in keywords definition. Administrator can choose a special character to delimit title and body.
o Administrator can activate bbccode in tooltip.

[b]0.1.3 - 1st December 2008[/b]
o Add members keywords suggestion functionnality

[b]0.1.2 - 1st December 2008[/b]
o Add Thickobx pluggin for better management keywords administration

[b]0.1.1 - 30th November 2008[/b]
o Glossary analyzer improvment
o Corrections after code review

[b]0.1.0 - 21th November 2008[/b]
o Initial release
