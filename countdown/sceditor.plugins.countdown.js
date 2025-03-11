(function (sceditor) {
	'use strict';

	sceditor.plugins.countdown = function () {
		this.init = function () {
				const opts = this.opts;

			const commandHandler = function (caller) {
				const editor = this;
				const content = document.createElement('form');

				const data = [
					['MM', 'DD', 'YYYY'],
					['HH', 'MM'],
				];

				for (let i = 0; i < data.length; i++) {
					const div = document.createElement('div');

					for (let j = 0; j < data[i].length; j++) {
						const input = document.createElement('input');
						input.type = 'text';
						input.dir = 'ltr';
						input.size = data[i][j].length;
						input.required = i === 0;
						input.maxLength = data[i][j].length;
						input.pattern = '\\d{1,' + data[i][j].length + '}';
						div.append(input, data[i][j]);
					}

					content.appendChild(div);
				}

				const button = document.createElement('input');
				button.type = 'submit';
				button.className = 'button';
				button.value = editor._('Insert');
				content.appendChild(button);

				content.children[0].focus();

				content.addEventListener('submit', function (e) {
					e.preventDefault();
					let params = [];

					for (let el of this.elements) {
						if (el.value && el.type !== 'submit') {
							params.push(el.value);
						}
					}

					if (params.length && this.checkValidity()) {
						editor.insertText('countdown=' + params.join(',') + ']', '[/countdown]');
					}

					editor.closeDropDown(true);
				});

				editor.createDropDown(caller, 'insertcountdown', content);
			};

			this.commands.countdown.exec = commandHandler;
		};
	};
})(sceditor);