function postNotifier ()
{
	window.setInterval(function ()
	{
		var iTopicId = decodeURI(window.location.search.replace(new RegExp("^(?:.*[&\\?]" + encodeURI('topic').replace(/[\.\+\*]/g, "\\$&") + "(?:\\=([^&]*))?)?.*$", "i"), "$1"));

		getXMLDocument(smf_prepareScriptUrl(smf_scripturl) + 'action=postnotifier;topic=' + iTopicId + ';last_msg=' + iLastMsg + ';xml', function (oXMLDoc)
		{
			var
				oDiv = document.getElementById('topbar'),
				sRet = oXMLDoc.getElementsByTagName('message')[0];

			if (sRet.getAttribute("num") > 0)
			{
				oDiv.innerHTML = sRet.childNodes[0].nodeValue;
				oDiv.style.position = 'fixed';
				oDiv.style.display = 'block';
				oDiv.onclick = function ()
				{
					window.location.assign(smf_prepareScriptUrl(smf_scripturl) + 'topic=' + iTopicId + '.new#new');
				};
			}
			else
				oDiv.style.display = 'none';
		});
	}, 60000);
}
addLoadEvent(postNotifier);