[center][color=blue][size=16pt][b]Glossary for SMF 2.1
v1.4[/b][/size][/color]
Based on [url="https://custom.simplemachines.org/index.php?mod=1525"]Glossary mod[/url] by [url="http://custom.simplemachines.org/mods/index.php?action=search;author=68142"][b]Slinouille[/b][/url]
[/center]
[hr]

[color=blue][b][size=12pt][u]Introduction[/u][/size][/b][/color]
The mod adds a Glossary to your forum.  You can create Glossary items (ie, keywords and definitions) that are displayed in messages as a keyword tooltip containing the definition.

This mod uses the following JavaScript plugins (some with associated CSS).
- jQuery Tooltip plugin from [url="https://jqueryui.com"]jQuery UI[/url] (customised for this mod).
- jQuery SimpleModal plugin from [url="https://www.ericmmartin.com/projects/simplemodal"]SimpleModal 1.4.4[/url] (updated for this mod).
- jQuery ContextMenu plugin originally from [url="https://abeautifulsite.net"]A Beautiful Site[/url].
- famfamfam Silk icons available from [url="https://peacocksoftware.com/silk"]Peacock Software[/url].

[color=blue][b][size=12pt][u]Glossary for SMF 2.0[/u][/size][/b][/color]
[b]v0.3.1 - 25-Apr-09[/b]
o Create a Glossary consisting of keywords and keyword definitions for your forum community.
o Allow numeric keywords in addition to alphabetic keywords.
o Allow categories for grouping of keywords.
o Allow keywords to have synonyms.
o New BBCode to enable keywords to be inserted inline in messages.
o Option to allow keyword definitions to contain BBCodes.
o Tooltips:
  ~ Option to view keyword definition when hovering over keyword in a message.
  ~ Option for BBCode keywords to be not case-sensitive.
  ~ If a keyword is used more than once per message option to only display tooltip once.
  ~ Specify character to be used to insert line breaks in keywords definitions.
  ~ Support for tooltips with SimplePortal mod.
o Membergroup permissions to allow/disallow members to:
  ~ View the Glossary list.
  ~ Suggest new Glossary keywords with definitions.
  ~ Manage/administer the Glossary keywords, keyword definitions and categories.
o Glossary List Administration:
  ~ Keywords (with keyword definitions) can be:.
     * Added.
     * Updated (including being renamed and/or having synonyms added, updated, or deleted).
     * Deleted.
     * Approved.
     * Unapproved.
     * Have tooltip for keyword definition enabled.
     * Have tooltip for keyword definition disabled.
     * Assigned to a category (or no category).
   ~ Categories can be:
      * Added.
      * Updated (including being renamed).
      * Deleted.
o Enable a 'right-click' Glossary administration menu in Glossary ' Alphabetic/Alphanumeric' view (useful for large glossaries).
o Languages: French, English, and German.
o Mod setting to enable/disable mod.
o Mod setting to enable checkbox to enable tooltips for new/updated keywords by default.
o Mod settings to specify width of keyword/keyword definition columns in Glossary list.

[hr]

[color=blue][b][size=12pt][u]Glossary for SMF 2.1[/u][/size][/b][/color]
[b]v1.0 - 09-Sep-24[/b]
o Hooks Only!!
o SMF 2.1.4 compatibility.
o Tooltips:
  ~ Improved processing of keyword for tooltip to remove [i]'http(s)://'[/i] from keyword to ensure tooltip is displayed correctly.
  ~ Line breaks in definitions displayed correctly without the need to use a custom line break character.
  ~ Option to only display tooltips for keywords that are inside a Glossary BBCode tag (ie, [nobbc][glossary]Keyword[/glossary][/nobbc]).
  ~ Option to display tooltips in signatures.
  ~ Option to display tooltips in PMs.
  ~ Option to display tooltips in 'News' items.
  ~ Fixed bug where the case of whole/partial words used in definitions that contain keywords was changed to match the keyword case (eg, In the SMF 2.0 version of this mod if there was a 'RAM' keyword and the word 'mainframe' was in a definition it would be displayed as 'mainfRAMe' in a tooltip).
o Membergroup permissions to allow/disallow members to:
  ~ View Glossary tooltips in messages.
  ~ Insert Glossary BBCode in messages.
  ~ Not allow guests to manage/administer the Glossary.
o Glossary List:
  ~ Link to member profile for unapproved keywords (if guests are allowed to make keyword suggestions the word 'Guest' will be shown).
  ~ Mod settings to show keyword author to Glossary admins and/or all members who can view Glossary index.
  ~ Mod setting to only show alphanumeric characters with an associated keyword.
  ~ Mod setting to enable checkbox to approve tooltips for new/updated keywords by default.
  ~ Mod setting for definition column width removed.
  ~ Mod setting for category column width added.
  ~ Keyword synonyms shown below keywords.
     * If the synonyms mod setting is not enabled the word '[i]Disabled[/i] is shown at the top of the list of synonyms.
  ~ Synonym icon and tooltip removed.
  ~ Added 'right-click' context menu for Glossary administration to [i]'Categories'[/i] view.
o Option to make individual keywords case sensitive  Note: this will also make all the synonyms for the keyword case-sensitive.
o Synonyms:
  ~ Keyword checking to ensure keyword is not already in use as a synonym.
  ~ Synonym checking to ensure:
     * Synonym is not already in use as a keyword.
     * Synonym is not already assigned to another keyword.
  ~ Specific error messages for keyword/synonym conflicts.
  ~ Mod setting to display keyword and synonyms below keyword definition in tooltip.
o Improved disabling of mod features (eg, Glossary list [except for Glossary admins], membergroup permissions, BBCode, etc) when mod is not enabled.
o Updated error message reporting to provide SMF 2.1 compatibility.
o Updated jQuery Tooltip and SimpleModal scripts and/or CSS – customised to work with this mod.
o Updated installation process to use mod specific directories/folders for images, languages, CSS files, and scripts.
o Removal of support for SimplePortal mod.
o Removed unused strings from language file.
o Other bug fixes, performance improvements, and tweaks.
o Support for special characters in keywords and synonyms.
o Automatic Glossary integrity checking and alerting for keyword/synonym issues/conflicts.
o Languages: English.

[hr]
[b]Usage[/b]
This mod allows Glossary keywords to be displayed as tooltips (with their definition shown when hovering over/clicking on the keyword) in posts and other message types (ie, News items, PMs, and/or signatures).

[hr]
[b]Limitations when the use of the Glossary BBCode tag (ie, [nobbc][glossary]Keyword[/glossary][/nobbc]) is optional[/b]
1. Although the use of the Glossary BBCode tag is optional using the mod this way may result in a noticeable increase in the time it takes pages to load when a forum has a combination of a large glossary, a large number of verbose posts on displayed topic pages, and/or is hosted on a low spec server/shared hosting.  The reason for this is that using the mod this way results in the need to check [b]every[/b] word in [b]every[/b] post (and other messages types) on a displayed page for a possible Glossary keyword match and to create the tooltip if a keyword match is found.

2. If a Glossary keyword/synonym is not marked as case-sensitive and it matches a HTML tag or text in another BBCode tag (eg, [i]'BR'[/i] matches the [i]'<br>'[/i] tag, [i]'RED'[/i] matches the [i]'[nobbc][color=red][/nobbc]'[/i] tag) the matching string in the HTML tag/BBCode tag will be displayed as a Glossary keyword/tooltip.

3. The Glossary list is processed in alphanumeric order and, if a keyword has synonyms they are processed [b]before[/b] the next keyword, and as a result keywords/synonyms may not always have the expected definition shown in their tooltip.
[b]Scenario A.[/b] Keywords/synonyms that contain special characters and which are a substring of another keyword/synonym:
Keyword: [i]Ali'i[/i] with definition: [i]Chief or leader[/i]
Keyword: [i]Ali'i's Home[/i] with definition: [i]In the village ...[/i]
If the use of the Glossary BBCode tag is [b]optional[/b] the keyword [i]Ali'i's Home[/i] will [b]always[/b] have the definition of the keyword [i]Ali'i[/i] in a tooltip (ie, even if [i]Ali'i's Home[/i] is enclosed in the Glossary BBCode tag).

[b]Scenario B:[/b] Single/multi word keyword/synonym that is a substring of another single/multi word keyword/synonym: 
[b]First example:[/b] The keywords [i]'Submarine'[/i] and [i]'Yellow Submarine'[/i] have been added to the Glossary.
If the use of the Glossary BBCode tag is [b]optional[/b] the tooltip for the phrase [i]'Yellow Submarine'[/i] will [b]always[/b] contain the definition for the [i]'Submarine'[/i] keyword (ie, even if [i]'Yellow Submarine'[/i] is enclosed in the Glossary BBCode tag).
[b]Second example:[/b] The keywords [i]'Big Boat'[/i] (with a synonym of [i]'Yellow'[/i]), [i]'Submarine'[/i] and [i]'Yellow Submarine'[/i] have been added to the Glossary.
If the use of the Glossary BBCode tag is [b]optional[/b] the tooltip for the phrase [i]'Yellow Submarine'[/i] now will [b]always[/b] have [b]TWO[/b] tooltips: the definition for the [i]'Yellow'[/i] synonym of the [i]'Big Boat'[/i] keyword [b]AND[/b] the definition for the [i]'Submarine'[/i] keyword.

[hr]
[b]Use of special characters in keywords/synonyms[/b]
Most special characters (ie, except for <, >, =, /, \, ", and leading apostrophes) and trailing apostrophes are supported in keywords/synonyms.

'Smart' quotes are not supported in keywords/synonyms and the use of other typographic characters (see [url=https://www.w3.org/wiki/Common_HTML_entities_used_for_typography]Common HTML entities used for typography[/url] in keywords/synonyms may cause issues and/or make them difficult to use.

[hr]
[b]Other limitations/known issues[/b]
The use of the [i]http://[/i] or [i]https://[/i] strings is not supported in keywords, synonyms or definitions.

[hr]
[b]Fonts[/b]
The fonts used for displaying Glossary keywords and/or definitions are defined as follows:
 
Font family for displaying definitions shown in tooltips:
.ui-widget in ./Themes/default/css/glossary/glossary.ui.tooltip.css

Font family for displaying keywords/definitions in the Glossary list:
.glossary_keyword_def in ./Themes/default/css/glossary/glossary.css

To change between a [i]serif[/i] font and a [i]sans-serif[/i] font change the order of the [i]font-family[/i] lines and clear the forum cache (you may also need to disable the [i]Administration Center > Features and Options > General > Minimize CSS and JavaScript files[/i] option if this is enabled).

[hr]
[b]Release History:[/b]
1.0 : 09-Sep-24
o Initial release.

1.1 : 20-Sep-24
o Bug fix for [url=https://www.simplemachines.org/community/index.php?msg=4180227]issue with URLs in BBCode tags[/url].

1.2 : 26-Sep-24
o Added option to exclude messages in specific boards from Glossary use.
   ~ To make it easier to identify board IDs this mod can optionally show them before the board name in the [i]'Manage Boards and Categories > Modify Boards'[/i] list.
o Minor performance/reliability improvements.

1.3 : 28-Sep-24
o Fixed tooltip corruption issue reported by [url=https://www.simplemachines.org/community/index.php?msg=4180344]JRMBelgium[/url] that occurred in a very specific of circumstances if the [i]'Show keyword and synonyms below definition in tooltip'[/i] option was enabled.
o Minor performance/reliability improvements and layout changes.

1.4 : 15-Oct-24
o Parsing code for processing Glossary keywords in messages completely rewritten by [url=https://www.simplemachines.org/community/index.php?action=profile;u=154736]live627[/url] - it's now amazingly fast!
o Added mod setting to give option to convert the line separator character(s) in the description to line breaks when editing the keyword.
o Glossary list:
   ~ Added option to enable tooltips to [b]only[/b] be shown when individual keywords (and their synonyms) are inside the Glossary BBCode tag - previously this was only available as a global setting.
   ~ Convert the line separator character(s) in the description to line breaks when displaying keywords if the associated mod setting is enabled.
   ~ Show the total number of keywords and synonyms (plus numbers of keywords/synonyms with non-default settings).
   ~ Show non-default settings for keywords (ie, not approved, not shown as tooltips, case sensitive, and tag only) below settings icons.

[hr]
[color=blue][i][size=10pt]License[/size][/i][/color]
Copyright 2024 Kathy Leslie, 2024 John Rayes.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:
1. Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
2. Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.

This software is provided by the copyright holders and contributors "as is" and any express or implied warranties, including, but not limited to, the implied warranties of merchantability and fitness for a particular purpose are disclaimed. In no event shall the copyright holder or contributors be liable for any direct, indirect, incidental, special, exemplary, or consequential damages (including, but not limited to, procurement of substitute goods or services; loss of use, data, or profits; or business interruption) however caused and on any theory of liability, whether in contract, strict liability, or tort (including negligence or otherwise) arising in any way out of the use of this software, even if advised of the possibility of such damage.
