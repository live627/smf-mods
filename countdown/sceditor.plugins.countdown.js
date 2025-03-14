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
					[editor._('Description')],
				];

				for (let i = 0; i < data.length; i++) {
					const div = document.createElement('div');

					for (let j = 0; j < data[i].length; j++) {
						const id = 'cd-inp-' + (i + j);
						const label = document.createElement('label');
						label.setAttribute('for', id);
						label.textContent = data[i][j];
						const input = document.createElement('input');
						input.type = 'text';
						input.dir = 'ltr';
						input.id = id;
						input.required = i === 0 || i === 2;

						if (i < 2) {
							const length = data[i][j].length;
							input.size = length;
							input.maxLength = length;
							input.pattern = '\\d{1,' + length + '}';
						}

						div.append(label, input);
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
						if (el.value && el.size != 20) {
							params.push(el.value);
						}
					}

					if (params.length && this.checkValidity()) {
						editor.insert(
							'[countdown=' + params.join(',') + ']' + this.elements[5].value,
							'[/countdown]'
						);
					}

					editor.closeDropDown(true);
				});

				editor.createDropDown(caller, 'insertcountdown', content);
			};

			this.commands.countdown.exec = commandHandler;
			this.commands.countdown.txtExec = commandHandler;
		};
	};
})(sceditor);
