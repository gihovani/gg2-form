<?php
define('DB_FILE_PATH', 'base.db');
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_BASE', 'form');
define('DB_TYPE_MYSQL', 'MYSQL');
define('DB_TYPE_SQLITE', 'SQLITE');
define('DB_TYPE_ACTIVE', DB_TYPE_SQLITE);
define('NEEDS_UTF8_ENCODE', false);
define('EMAIL_FROM', 'contato@gg2.com.br');
define('EMAIL_TO', 'contato@gg2.com.br');
define('EMAIL_SUBJECT', 'Resposta Pesquisa');

$tableTextAnswer = (object)[
    'pk'      => 'id',
    'filter'  => 'survey_id',
    'sortBy' => '',
    'table'   => 'answers_text',
    'columns' => [
        'id',
        'survey_id',
        'user_id',
        'question',
        'answer',
        'question_id',
    ]
];
$tableAnswer = (object)[
    'pk'      => 'id',
    'filter'  => 'question_id',
    'sortBy' => 'priority',
    'table'   => 'answers',
    'columns' => [
        'id',
        'description',
        'type',
        'priority',
        'question_id'
    ]
];
$tableQuestion = (object)[
    'pk'      => 'id',
    'filter'  => 'survey_id',
    'sortBy' => 'priority',
    'table'   => 'questions',
    'columns' => [
        'id',
        'title',
        'image',
        'type',
        'priority',
        'survey_id'
    ]
];
