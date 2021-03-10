<?php

class question_content_admin
{
	public function option_default($option)
	{
		switch($option) {

			case 'question_content_max_len':
				return 260;

			case 'question_content_css':
                return '.question-content-text{
	font-size: 14px;
	font-weight: normal;
	margin-top: 8px;
	letter-spacing: 0.5px;
	opacity: 0.8;
	text-align: justify;
}
.question-content-image{
	width: 100%;
	aspect-ratio: 16 / 9;
	object-fit: cover;
	/* margin: -16px -16px 10px -16px; */
	margin-bottom: -10px;
	/* border-radius: 4px 4px 0 0; */
}';
			default:
				return null;				
		}

	}

	public function admin_form(&$qa_content)
	{
		$ok = null;

		if (qa_clicked('question_content_save_button')) {
			qa_opt('question_content_on', (int) qa_post_text('question_content_on_field'));
			qa_opt('question_content_max_len', (int) qa_post_text('question_content_max_len_field'));
			qa_opt('question_image_on', (int) qa_post_text('question_image_on_field'));
			qa_opt('question_content_css', (string) qa_post_text('question_content_css_field'));

			$ok = qa_lang('admin/options_saved');
		}
		else if (qa_clicked('question_content_reset_button')) {
			qa_opt('question_content_max_len', (int) $this->option_default('question_content_max_len'));
			qa_opt('question_content_css', (string) $this->option_default('question_content_css'));

			$ok = qa_lang('admin/options_reset');
		}

		qa_set_display_rules($qa_content, array(
			'question_content_max_len_display' => 'question_content_on_field',
		));

		return array(
			'ok' => ($ok && !isset($error)) ? $ok : null,

			'fields' => array(
				array(
					'label' => 'Shows question content below title in question lists',
					'type' => 'checkbox',
					'value' => qa_opt('question_content_on'),
					'tags' => 'name="question_content_on_field" id="question_content_on_field"',
				),
				array(
					'id' => 'question_content_max_len_display',
					'label' => 'Maximum length of question content:',
					'suffix' => 'characters',
					'type' => 'number',
					'value' => (int) qa_opt('question_content_max_len'),
					'tags' => 'name="question_content_max_len_field"',
				),
				array(
					'label' => 'Shows question\'s first image in question lists',
					'type' => 'checkbox',
					'value' => qa_opt('question_image_on'),
					'tags' => 'name="question_image_on_field" id="question_image_on_field"',
				),
				array(
					'rows' => 8,
					'label' => 'Question content CSS',
					'type' => 'textarea',
					'value' => qa_opt('question_content_css'),
					'tags' => 'name="question_content_css_field"',
				),
			),

			'buttons' => array(
				array(
					'label' => qa_lang_html('main/save_button'),
					'tags' => 'name="question_content_save_button"',
				),
				array(
					'label' => qa_lang_html('admin/reset_options_button'),
					'tags' => 'name="question_content_reset_button"',
				),
			),
		);
	}
}
