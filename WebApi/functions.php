<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization, No-Auth');
header('Content-type: application/json; charset=UTF-8');
require_once('config.php');
require_once('TableObject.php');
$method = strtolower($_SERVER['REQUEST_METHOD']);
if ($method === 'options') {
    die('');
}
if (!in_array($method, [
    'get',
    'put',
    'post',
    'delete'
])) {
    throw new Exception('Erro no método: ' . $method . ' - não encontrado');
}
function getSQL()
{
    global $method;
    global $tableObject;
    $arguments = (isset($_SERVER['argv'][0]) && !empty($_SERVER['argv'][0])) ? $_SERVER['argv'][0] : null;
    $sql = $tableObject->$method($arguments);
    if (empty($sql)) {
        throw new Exception('Erro no método: ' . $method . ' - ocorreu algum erro ao gerar a SQL!');
    }

    return $sql;
}

function runSQL($sql)
{
    return (DB_TYPE_ACTIVE === DB_TYPE_SQLITE) ? runSQLite($sql) : runMySQL($sql);
}

function runSQLite($sql)
{
    global $tableObject;
    global $method;
    $data = [];
    $db = new SQLite3(DB_FILE_PATH);
    $result = ($method === 'get') ? $db->query($sql) : $db->exec($sql);
    if (empty($result)) {
        throw new Exception('Erro no método: ' . $method . ' - SQL: ' . $sql . ' - ' . $db->lastErrorMsg());
    }
    if ($method === 'get') {
        while ($object = $result->fetchArray(SQLITE3_ASSOC)) {
            $data[] = transformDataObject($object);
        }
    } else if ($method === 'post') {
        $object = $tableObject->availableParams;
        $object['id'] = $db->lastInsertRowid();
        $data = $object;
    }
    return $data;
}

function runMySQL($sql)
{
    global $tableObject;
    global $method;
    $data = [];
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_BASE);
//    mysqli_set_charset($conn, 'utf8');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $result = $conn->query($sql);
    if (empty($result)) {
        throw new Exception('Erro no método: ' . $method . ' - SQL: ' . $sql . ' - ');
    }
    if ($method === 'get') {
        while ($object = $result->fetch_array(MYSQLI_ASSOC)) {
            $data[] = transformDataObject($object);
        }
    } else if ($method === 'post') {
        $object = $tableObject->availableParams;
        $object['id'] = $conn->insert_id;
        $data = $object;
    }
    $conn->close();

    return $data;
}

function transformDataObject($object)
{
    $tmp = [];
    foreach ($object as $key => $value) {
        $value = TableObject::decodeString($value);
        if (NEEDS_UTF8_ENCODE) {
            $tmp[utf8_encode($key)] = utf8_encode($value);
        } else {
            $tmp[$key] = $value;
        }
    }
    return $tmp;
}

function showJson($data)
{
    return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
}

function executeSQL($jsonEncode = true)
{
    $sql = getSQL();
    $response = [
        'msg'     => 'methodo executado com sucesso!',
        'success' => true,
        'sql'     => $sql,
        'data'    => runSQL($sql)
    ];

    return ($jsonEncode) ? showJson($response) : $response;
}

function sendMail($body, $subject = EMAIL_SUBJECT, $to = EMAIL_TO, $from = EMAIL_FROM)
{
    $message = '<!DOCTYPE html>
<html lang="pt">
<head>
	<meta charset="UTF-8">
  <title>' . $subject . '</title>
</head>
<body>' . $body . '</body>
</html>';
    $headers = [
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=utf-8',
        // Additional headers
        'To: ' . $to,
        'From: ' . $from
    ];

    // Mail it
    return mail($to, $subject, $message, implode(PHP_EOL, $headers));
}
