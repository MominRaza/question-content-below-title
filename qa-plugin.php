<?php

if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
	header('Location: ../../');
	exit;
}

qa_register_plugin_layer('question-content-layer.php', 'Question Content Layer');
qa_register_plugin_module('module', 'question-content-admin.php', 'question_content_admin', 'Question Content Admin');

// Omit PHP closing tag to help avoid accidental output