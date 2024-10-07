[center][color=blue][size=16pt][b]Glossary for SMF 2.1
v1.0[/b][/size][/color]
Based on [url="https://custom.simplemachines.org/index.php?mod=1525"]Glossary mod[/url] by [url="http://custom.simplemachines.org/mods/index.php?action=search;author=68142"][b]Slinouille[/b][/url]
[/center]
[hr]

[color=blue][b][size=12pt][u]Introduction[/u][/size][/b][/color]
The mod adds a Glossary to your forum.  You can manage the keywords and associated definition and optionally display a tooltip containing the keyword definition in the messages.

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
o Tooltips:.
   ~ Option to view keyword definition when hovering over keyword in a message.
   ~ Option for BBCode keywords to be not case-sensitive.
   ~ If a keyword is used more than once per message option to only display tooltip once.
   ~ Specify character to be used to insert line breaks in keywords definitions.
   ~ Support for tooltips with SimplePortal mod.
o Membergroup permissions to allow/disallow members to:
   ~ View the Glossary index.
   ~ Suggest new Glossary keywords with definitions.
   ~ Manage/administer the Glossary keywords, keyword definitions and categories.
o Glossary Index Administration:.
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
o Mod settings to specify width of keyword/keyword definition columns in Glossary index.

[hr]

[color=blue][b][size=12pt][u]Glossary for SMF 2.1[/u][/size][/b][/color]
[b]v1.0 - 25-Jan-24[/b]
o Hooks Only!!
o SMF 2.1.4 compatibility.
o Keyword checking to ensure keyword is not already in use as a synonym.
o Synonym checking to ensure:
   ~ Synonym is not already in use as a keyword.
   ~ Synonym is not already assigned to another keyword.
o Specific error messages for keyword/synonym conflicts.
o Link to member profile for unapproved keywords (if guests are allowed to make keyword suggestions the word 'Guest' will be shown).
o Improved processing of keyword for tooltip to remove [i]http(s)://[/i] from keyword to ensure tooltip is displayed correctly.
o Membergroup permissions to allow/disallow members to:
   ~ View Glossary tooltips in messages.
   ~ Insert Glossary BBCode in messages.
   ~ Not allow guests to manage/administer the Glossary.
o Glossary Index:
   ~ Keyword synonyms shown below keywords.
     * If the synonyms mod setting is not enabled the word '[i]Disabled[/i] is shown at the top of the list of synonyms.
   ~ Synonym icon and tooltip removed.
o Mod settings to show keyword author to Glossary admins and/or all members in the Glossary index.
o Mod setting to only show alphanumeric characters with an associated keyword in the Glossary index.
o Mod setting to enable checkbox to approve tooltips for new/updated keywords by default.
   ~ Added 'right-click' Glossary administration menu to [i]'Categories'[/i] view.
o Improved removal of mod features (eg, Glossary index, membergroup permissions, BBCode, etc) if mod is disabled.
o Updated error message reporting to provide SMF 2.1 compatibility.
o Replaced jQuery Tooltip and SimpleModal scripts – both customised/updated to work with this mod.
o Updated installation process to use mod specific directories/folders for images, languages, CSS files, and scripts.
o Removal of support for SimplePortal mod.
o Removed unused strings from language file.
o Other minor performance improvements, bug fixes and tweaks.
o Languages: English.

[hr]

[b]Release History:[/b]
1.0 - 26-Jan-24
o Initial release.

[hr]
[color=blue][i][size=10pt]License[/size][/i][/color]
Copyright 2024 Kathy Leslie.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:
1. Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
2. Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

