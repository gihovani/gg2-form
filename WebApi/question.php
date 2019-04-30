<?php
require_once('functions.php');
$tableObject = new TableObject($tableQuestion->table, $tableQuestion->columns, $tableQuestion->pk, $tableQuestion->filter, $tableQuestion->sortBy);
if ($method === 'get') {
    $surveyId = isset($_GET['id']) ? $_GET['id'] : 0;
    $sql = $tableObject->get($surveyId, true);
    $questions = runSQL($sql);
    $answerObject = new TableObject($tableAnswer->table, $tableAnswer->columns, $tableAnswer->pk, $tableAnswer->filter, $tableAnswer->sortBy);
    foreach ($questions as $key => $question) {
        $sql = $answerObject->get($question['id'], true);
        $questions[$key]['answers'] = runSQL($sql);
    }
    $response['data'] = $questions;
    print showJson($response);
} else {
    print executeSQL();
}
