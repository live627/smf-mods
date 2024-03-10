window.addEventListener("DOMContentLoaded", () => {
	const tooltip = document.createElement('div');
	tooltip.classList.add('glossarytooltip');
	document.body.appendChild(tooltip);

	for (const el of document.querySelectorAll('.glossary')) {
		if (el.title) {
			el.dataset.title = el.title;
			el.removeAttribute('title');
			el.onpointerover = e => {
				tooltip.classList.add('over');
				tooltip.innerHTML = el.dataset.title;
				tooltip.style.left =
					(e.pageX + tooltip.clientWidth + 10 < document.body.clientWidth)
						? (e.pageX + 10 + "px")
						: (document.body.clientWidth - 15 - tooltip.clientWidth + "px");
				tooltip.style.top =
					(e.pageY + tooltip.clientHeight + 10 < document.body.clientHeight)
						? (e.pageY + 10 + "px")
						: (document.body.clientHeight + 5 - tooltip.clientHeight + "px");
			}
			el.onpointerout = e => {
				tooltip.classList.remove('over');
			}
		}
	}
});
