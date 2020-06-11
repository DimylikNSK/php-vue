<?php


class RequestHandler
{
    private $body = [];
    private $db;
    private $table;

    public function __construct($db, $table)
    {
        $this->db = $db;
        $this->table = $table;

        if (!empty($_GET)) {
            $this->body['column'] = $this->filterInput('column');
            $this->body['op'] = $this->filterInput('filterType');
            $this->body['value'] = $this->filterInput('value');
        }
        
    }

    public function response()
    {
        
        if (!$this->body || empty($this->body)) {
            return $this->getRowsAll();
        }

        return $this->handleOperations();
    }

    private function handleOperations()
    {
        switch ($this->body['op']) {
            case 'eq':
                return $this->getRowsWhere('=');
                break;
            case 'gt':
                return $this->getRowsWhere('>');
                break;
            case 'lt':
                return $this->getRowsWhere('<');
                break;
            case 'like':
                return $this->getRowsLike();
                break;
            default:
                return json_encode([]);
                break;
        }
    }

    private function getRowsWhere($op)
    {
        return json_encode(
            $this->db->table($this->table)
                ->where($this->body['column'], $op, $this->body['value'])
                ->getAll()
        );
    }

    private function getRowsLike()
    {
        return json_encode(
            $this->db->table($this->table)
                ->like($this->body['column'], $this->body['value'])
                ->getAll()
        );
    }

    private function getRowsAll()
    {
        return json_encode($this->db->table($this->table)->getAll());
    }

    private function filterInput($value)
    {
        return htmlspecialchars(filter_input(INPUT_GET, $value));
    }

}