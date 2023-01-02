(function() {
	tinymce.PluginManager.add('et_mce_button', function( editor, url ) {
		editor.addButton( 'et_mce_button', {
            icon: ' et-shortcodes-icon ',
			tooltip: 'Etheme Shortcodes',
			type: 'menubutton',
			minWidth: 210,
			menu: [
				{
					text: 'Button',
					onclick: function() {
						editor.windowManager.open( {
							title: 'Button',
							body: [
								{
									type: 'listbox',
									name: 'style',
									label: 'Style',
									'values': [
										{text: 'Small', value: 'small'},
										{text: 'Medium', value: 'medium'},
										{text: 'Big', value: 'big'},
										{text: 'Small dark', value: 'small black'},
										{text: 'Medium dark', value: 'medium black'},
										{text: 'Big dark', value: 'big black'},
										{text: 'Small active', value: 'small active'},
										{text: 'Medium active', value: 'medium active'},
										{text: 'Big active', value: 'big active'},
										{text: 'Small light', value: 'small white'},
										{text: 'Medium light', value: 'medium white'},
										{text: 'Big light', value: 'big white'},
										{text: 'Small bordered', value: 'small bordered'},
										{text: 'Medium bordered', value: 'medium bordered'},
										{text: 'Big bordered', value: 'big bordered'},
									]
								},
								{
									type: 'textbox',
									name: 'url',
									label: 'URL',
									value: ''
								},
								{
									type: 'listbox',
									name: 'target',
									label: 'Target',
									'values': [
										{text: 'Current window', value: '_self'},
										{text: 'Blank', value: '_blank'},
									]
								},
								{
									type: 'textbox',
									name: 'icon',
									label: 'Icon',
									value: ''
								},
								{
									type: 'textbox',
									name: 'title',
									label: 'Title',
									value: 'BUTTON TEXT'
								},
								{
									type: 'textbox',
									name: 'class',
									label: 'Custom class',
									value: ''
								}
							],
							onsubmit: function( e ) {
								editor.insertContent( '[button et_class="' + e.data.class + '" target="' + e.data.target + '" style="' + e.data.style + '" url="' + e.data.url + '" icon="' + e.data.icon + '" title="' + e.data.title + '"]');
							}
						});
					}
				},
				{
					text: 'Blockquote',
					menu: [
						{
							text: 'Style 1',
							onclick: function() {
								editor.insertContent( '[blockquote][/blockquote]');
							}
						},
						{
							text: 'Style 2',
							onclick: function() {
								editor.insertContent( '[blockquote class="style2"][/blockquote]');
							}
						},
					]
				},
				{
					text: 'Divider',
					menu: [
						{
							text: 'Short',
							onclick: function() {
								editor.insertContent( '<hr>');
							}
						},
						{
							text: 'Wide',
							onclick: function() {
								editor.insertContent( '<hr class="wide">');
							}
						},
						{
							text: 'Full width',
							onclick: function() {
								editor.insertContent( '<hr class="full-width">');
							}
						},
					]
				},
				{
					text: 'Dropcaps',
					menu: [
						{
							text: 'Light',
							onclick: function() {
								editor.insertContent( '[dropcap style="light" color=""][/dropcap]');
							}
						},
						{
							text: 'Dark',
							onclick: function() {
								editor.insertContent( '[dropcap style="dark" color=""][/dropcap]');
							}
						},
						{
							text: 'Bordered',
							onclick: function() {
								editor.insertContent( '[dropcap style="bordered" color=""][/dropcap]');
							}
						},
					]
				},
				{
					text: 'Highlight',
					menu: [
						{
							text: 'Text',
							onclick: function() {
								editor.insertContent( '[mark style="text" color=""][/mark]');
							}
						},
						{
							text: 'Paragraph',
							onclick: function() {
								editor.insertContent( '[mark style="paragraph" color=""][/mark]');
							}
						},
						{
							text: 'Paragraph boxed',
							onclick: function() {
								editor.insertContent( '[mark style="paragraph-boxed" color=""][/mark]');
							}
						},
					]
				},
				{
					text: 'Ordered List',
					onclick: function() {
						editor.windowManager.open( {
							title: 'List params',
							body: [
								{
									type: 'listbox',
									name: 'style',
									label: 'Style',
									'values': [
										{text: 'Simple', value: 'simple'},
										{text: 'Active', value: 'active'},
										{text: 'Squared', value: 'squared'}
									]
								}

							],
							onsubmit: function( e ) {

								var html = [
									'<ol class="' + e.data.style + '">',
										'<li>List item 1</li>',
										'<li>List item 2</li>',
										'<li>List item 3</li>',
									'</ol>',
								].join('\n');

								editor.insertContent( html );
							}
						});
					}
				},
				{
					text: 'Unordered List',
					onclick: function() {
						editor.windowManager.open( {
							title: 'List params',
							body: [
								{
									type: 'listbox',
									name: 'style',
									label: 'Style',
									'values': [
										{text: 'Square', value: 'square'},
										{text: 'Circle', value: 'circle'},
										{text: 'Arrow', value: 'arrow'},
									]
								}

							],
							onsubmit: function( e ) {

								var html = [
									'<ul class="' + e.data.style + '">',
										'<li>List item 1</li>',
										'<li>List item 2</li>',
										'<li>List item 3</li>',
									'</ul>',
								].join('\n');

								editor.insertContent( html );
							}
						});
					}
				},
				{
					text: 'Post Meta',
					onclick: function() {
						editor.windowManager.open( {
							title: 'Meta params',
							body: [
								{
									type: 'checkbox',
									name: 'time',
									label: 'Time',
								    checked: true,
								},
								{
									type: 'checkbox',
									name: 'time_details',
									label: 'Time Details',
								    checked: true,
								},
								{
									type: 'checkbox',
									name: 'author',
									label: 'Author',
								    checked: true,
								},
								{
									type: 'checkbox',
									name: 'comments',
									label: 'Comments',
								    checked: true,
								},
								{
									type: 'checkbox',
									name: 'count',
									label: 'Count',
								    checked: true,
								},
								{
									type: 'textbox',
									name: 'class',
									label: 'Custom class',
									value: ''
								}
							],
							onsubmit: function( e ) {
								var args = '';

								args += 'time="' + e.data.time + '" ';
								args += 'time_details="' + e.data.time_details + '" ';
								args += 'author="' + e.data.author + '" ';
								args += 'comments="' + e.data.comments + '" ';
								args += 'count="' + e.data.count + '" ';
								args += 'class="' + e.data.class + '"';

								editor.insertContent( '[etheme_post_meta ' + args + ' ]');
							}
						});
					}
				},
			]
		});
	});
})();