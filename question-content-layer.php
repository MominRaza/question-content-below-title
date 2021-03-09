<?php

class qa_html_theme_layer extends qa_html_theme_base
{
	public function head_custom()
	{
        qa_html_theme_base::head_custom();
		$this->output('<style type="text/css">
		.question-content-text{
			font-size: 14px;
			font-weight: normal;
			margin-top: 8px;
			letter-spacing: 0.5px;
			opacity: 0.8;
			text-align: justify;
		}
		.question-content-image{
			width: calc(100% + 32px);
			aspect-ratio: 16 / 9;
			object-fit: cover;
			margin: -16px -16px 10px -16px;
			border-radius: 4px 4px 0 0;
		}
		</style>');
	}

	public function q_list($q_list)
	{
		if ($this->template != 'admin' && !empty($q_list['qs']) && (qa_opt('question_content_on') || qa_opt('question_image_on'))) { // first check it is not an empty list and the feature is turned on
			// Collect the question ids of all items in the question list (so we can do this in one DB query)

			$postids = array();
			foreach ($q_list['qs'] as $question) {
				if (isset($question['raw']['postid']))
					$postids[] = $question['raw']['postid'];
			}

			if (!empty($postids)) {
				// Retrieve the content for these questions from the database
				$maxlength = qa_opt('question_content_max_len');
				$result = qa_db_query_sub('SELECT postid, content, format FROM ^posts WHERE postid IN (#)', $postids);
				$postinfo = qa_db_read_all_assoc($result, 'postid');

				// Get the regular expression fragment to use for blocked words and the maximum length of content to show

				$blockwordspreg = qa_get_block_words_preg();

				// Now add the popup to the title for each question

				foreach ($q_list['qs'] as $index => $question) {
					if (isset($postinfo[$question['raw']['postid']])) {
						$thispost = $postinfo[$question['raw']['postid']];
						$title = isset($question['title']) ? $question['title'] : '';
						if (qa_opt('question_content_on')) {
							$text = qa_viewer_text($thispost['content'], $thispost['format'], array('blockwordspreg' => $blockwordspreg));
							$text = preg_replace('/\s+/', ' ', $text);  // Remove duplicated blanks, new line characters, tabs, etc
							$text = $this->qam_shorten_string_line($text, $maxlength);
							$q_list['qs'][$index]['title'] = $title.'<p class="question-content-text">'.qa_html($text).'</p>';
						}
						if (qa_opt('question_image_on')) {
							$imageText = qa_viewer_html($thispost['content'], $thispost['format'], array('blockwordspreg' => $blockwordspreg));
							// Extract image source from content
							preg_match_all('/<img[^>]+src=[\'"]([^\'"]+)[\'"][^>]*>/i', $imageText, $matches);
							// If content has image than show it!
							if (!empty($matches[0])) {
								// assign image to the variable
								$image = '<img src="' . $matches[1][0] . '" alt="'.$title.'" class="question-content-image" />'; // change the size using attr or css
								$q_list['qs'][$index]['content'] = '<a href="'.qa_path_html($question['raw']['postid']).'" >'.$image.'</a>';
							}
						}
					}
				}
			}
		}

		parent::q_list($q_list); // call back through to the default function
	}

	public function q_item_main($q_item)
	{
		if ($this->template != 'admin' && qa_opt('question_image_on')) {
			$this->output('<div class="qa-q-item-main">');
				
			$this->q_item_content($q_item);
			$this->post_avatar_meta($q_item, 'qa-q-item');
			$this->q_item_title($q_item);
			$this->post_tags($q_item, 'qa-q-item');
			$this->q_item_buttons($q_item);

			$this->output('</div>');
		} else {
			parent::q_item_main($q_item);
		}	
	}
	
	private function qam_shorten_string_line($string, $length, $ellipsis = '...')
	{
		if (qa_strlen($string) > $length) {
			$remaining = $length - qa_strlen($ellipsis);

			$words = qa_string_to_words($string, false, true);
			$countwords = count($words);

			$prefix = '';

			for ($addword = 0; $addword < $countwords; $addword++) {
				$word = array_shift($words);

				$wordLength = qa_strlen($word);

				if ($wordLength > $remaining)
					break;

				$prefix .= $word;

				$remaining -= $wordLength;
			}

			$string = $prefix . $ellipsis;
		}

		return $string;
	}
}
