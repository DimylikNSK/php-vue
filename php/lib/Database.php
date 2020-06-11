<?php

class Database
{
    private $config;
    public $pdo = null;

    protected $select = '*';
    protected $from = null;
    protected $where = null;
    protected $query = null;
    protected $result = [];

    protected $op = ['=', '<', '>'];

    public function __construct()
    {
        $this->config = require('./config/db.config.php');
        $dsn = "mysql:host=". $this->config['host'] .";dbname=".$this->config['db'].";charset=".$this->config['charset'];
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
//            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $this->pdo = new PDO($dsn, $this->config['user'], $this->config['password'], $opt);

        return $this->pdo;
    }


    public function table($table)
    {
        $this->from = $table;
        return $this;
    }


    public function select($fields)
    {
        $select = (is_array($fields) ? implode(', ', $fields) : $fields);
        $this->select = ($this->select == '*' ? $select : $this->select . ', ' . $select);

        return $this;
    }

    public function where($where, $op = null, $val = null)
    {
        if (is_null($where) || empty($where)) {
            return $this;
        }

        if (! in_array($op, $this->op) || $op == false) {
            $where = $where . ' = ' . $this->escape($op);
        } else {
            $where = $where . ' ' . $op . ' ' . $this->escape($val);
        }

        $this->where = $where;

        return $this;
    }


    public function like($field, $data, $type = '', $andOr = 'AND')
    {
        $like = '%' . trim($data) . '%';
        $like = $this->escape($like);

        $where = $field . ' ' . $type . 'LIKE ' . $like;

        if ($this->grouped) {
            $where = '(' . $where;
            $this->grouped = false;
        }

        $this->where = (is_null($this->where))
            ? $where
            : $this->where . ' ' . $andOr . ' ' . $where;

        return $this;
    }

    public function getAll()
    {
        $query = 'SELECT ' . $this->select . ' FROM ' . $this->from;

        if (! is_null($this->where)) {
            $query .= ' WHERE ' . $this->where;
        }

        return $this->query($query, true);
    }

    public function query($query, $all = true)
    {
        $this->reset();
        $this->query = trim($query);
        $sql = $this->pdo->query($this->query);
        $this->result = $all ? $sql->fetchAll() : $sql->fetch();

        return $this->result;
    }


    public function escape($data)
    {
        if ($data === null) return 'NULL';
        if (is_numeric($data)) return $data;

        return $this->pdo->quote($data);
    }

    public function __destruct()
    {
        $this->pdo = null;
    }


    protected function reset()
    {
        $this->select = '*';
        $this->from = null;
        $this->where = null;
        $this->query = null;
    }

}