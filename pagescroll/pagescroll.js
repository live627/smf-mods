/**
 * @package Pagescroll
 * @version 1.0
 * @author John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2019, John Rayes
 * @license http://opensource.org/licenses/MIT MIT
 */

window.addEventListener('DOMContentLoaded', function()
{
	var oDiv = document.createElement("div"); 
	oDiv.id = 'pagescroll';
	document.body.appendChild(oDiv); 
	window.addEventListener('scroll', function()
	{
		var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
		var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
		var scrolled = (winScroll / height) * 100;
		oDiv.style.width = scrolled + "%";
	});
});