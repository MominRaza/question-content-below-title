<?php

class question_content_admin
{
	public function option_default($option)
	{
		if ($option === 'question_content_max_len')
			return 260;
	}


	public function admin_form(&$qa_content)
	{
		$saved = qa_clicked('question_content_save_button');

		if ($saved) {
			qa_opt('question_content_on', (int) qa_post_text('question_content_on_field'));
			qa_opt('question_content_max_len', (int) qa_post_text('question_content_max_len_field'));
			qa_opt('question_image_on', (int) qa_post_text('question_image_on_field'));
		}

		qa_set_display_rules($qa_content, array(
			'question_content_max_len_display' => 'question_content_on_field',
		));

		return array(
			'ok' => $saved ? 'Question Content settings saved' : null,

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
			),

			'buttons' => array(
				array(
					'label' => 'Save Changes',
					'tags' => 'name="question_content_save_button"',
				),
			),
		);
	}
}
