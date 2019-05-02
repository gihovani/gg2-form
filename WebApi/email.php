<?php
require_once('functions.php');
$post = TableObject::postData();
if (!isset($post['answers'])) {
    header("HTTP/1.0 404 Not Found");
    exit;
}
//$answers = [['user_id' => 1, 'survey_id' => 1, 'question' => '1', 'answer' => 10]];
define('TD_OPEN', '<td>');
define('TD_CLOSE', '</td>' . PHP_EOL);
$answers = $post['answers'];
$body = '<table border="1">';
$body .= '<tr>';
$body .= '<th>Data</th>';
$body .= '<th>Cod. Cliente</th>';
$body .= '<th>Cod. Pesquisa</th>';
$body .= '<th>Pergunta</th>';
$body .= '<th>Resposta</th>';
$body .= '</tr>' . PHP_EOL;
foreach ($answers as $answer) {
    $body .= '<tr>';
    $body .= TD_OPEN . date('d/m/Y') . TD_CLOSE;
    $body .= TD_OPEN . $answer['user_id'] . TD_CLOSE;
    $body .= TD_OPEN . $answer['survey_id'] . TD_CLOSE;
    $body .= TD_OPEN . $answer['question'] . TD_CLOSE;
    $body .= TD_OPEN . $answer['answer'] . TD_CLOSE;
    $body .= '</tr>' . PHP_EOL;
}
$body .= '</table>';
sendMail($body);
print showJson($answers);
