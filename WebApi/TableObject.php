<?php

class TableObject
{
    private $filter = '';
    private $sortBy = '';
    private $primaryKey = '';
    private $columns = [];
    private $table = '';
    public $availableParams = [];

    public function __construct($table, $columns, $primaryKey = '', $filter = '', $sortBy = '')
    {
        $this->columns = $columns;
        $this->table = $table;
        $this->setPrimaryKey($primaryKey);
        $this->setFilter($filter);
        $this->setSortBy($sortBy);
        $this->setAvailableParams($this->columns);
    }

    public function setAvailableParams($aviableParams)
    {
        $this->availableParams = ($aviableParams) ? $aviableParams : $this->columns;
        return $this;
    }

    public function setPrimaryKey($primaryKey)
    {
        $this->primaryKey = ($primaryKey) ? $primaryKey : $this->columns[0];
        return $this;
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        return $this;
    }

    public function setSortBy($sortBy)
    {
        $this->sortBy = $sortBy;
        return $this;
    }

    private function _availableParams($params)
    {
        if (empty($params)) {
            return [];
        }
        $availableFields = $this->availableParams;
        $ret = [];
        foreach ($availableFields as $key) {
            if (isset($params[$key])) {
                $ret[$key] = $params[$key];
            }
        }
        return $ret;
    }

    public static function postData()
    {
        return (empty($_POST)) ? json_decode(file_get_contents('php://input'), true) : $_POST;
    }

    public static function clearString($value)
    {
        if (NEEDS_UTF8_ENCODE) {
            $value = utf8_encode($value);
        }
        return htmlentities($value);
    }

    private function _prepareSql($data, $format = '%s = "%s"')
    {
        $sql = [];
        foreach ($data as $key => $value) {
            $sql[] = sprintf($format, $key, self::clearString($value));
        }

        return $sql;
    }

    public function get($filter = 0, $returnAll = true)
    {
        $filters = [];
        $where = '';
        $extras = '';
        if ($filter) {
            if ($this->filter) {
                $filters[$this->filter] = $filter;
            } else {
                $filters[$this->primaryKey] = $filter;
            }
        } else {
            $requestFilter = isset($_REQUEST['filter']) ? $_REQUEST['filter'] : [];
            $filters = $this->_availableParams($requestFilter);
        }
        if (!empty($filters)) {
            $where = 'WHERE ' . implode(' and ', $this->_prepareSql($filters));
        } else if (!$returnAll) {
            return '';
        }
        if (!empty($this->sortBy)) {
            $extras = 'ORDER BY ' . $this->sortBy;
        }
        $columnsStr = implode(',', $this->columns);
        return sprintf('SELECT %s FROM %s %s %s', $columnsStr, $this->table, $where, $extras);
    }

    public function put($id = 0)
    {
        if (empty($id)) {
            return '';
        }
        $data = $this->_availableParams(self::postData());
        $columnsStr = implode(',', $this->_prepareSql($data));
        return sprintf('UPDATE %s SET %s WHERE %s = %d', $this->table, $columnsStr, $this->primaryKey, $id);
    }

    public function delete($id = 0)
    {
        if (empty($id)) {
            return '';
        }
        return sprintf('DELETE FROM %s WHERE %s = %d', $this->table, $this->primaryKey, $id);
    }

    public function post()
    {
        $data = $this->_availableParams(self::postData());
        if (empty($data)) {
            return '';
        }
        $columnsStr = implode(',', array_keys($data));
        $values = array_values($data);
        $values = array_map('TableObject::clearString', $values);
        $valuesStr = '"' . implode('","', $values) . '"';
        return sprintf('INSERT INTO %s (%s) VALUES (%s)', $this->table, $columnsStr, $valuesStr);
    }
}
