<?php
require_once('functions.php');
$tableObject = new TableObject($tableTextAnswer->table, $tableTextAnswer->columns, $tableTextAnswer->pk, $tableTextAnswer->filter, $tableTextAnswer->sortBy);
if ($method === 'get') {
    $surveyId = isset($_GET['id']) ? $_GET['id'] : 0;
    $sql = $tableObject->get($surveyId, true);
    $questions = runSQL($sql);
    print showJson(['data' => $questions]);
} else {
    print executeSQL();
}
