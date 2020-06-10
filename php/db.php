<?php


class DB
{
    private static $config;
    private static $db;

    function __construct()
    {
        self::$config = require('db.options.php');
        $dsn = "mysql:host=". self::$config['host'] .";dbname=".self::$config['db'].";charset=".self::$config['charset'];
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        self::$db = new PDO($dsn, self::$config['user'], self::$config['password'], $opt);
    }

    public static function query($stmt)
    {
        return self::$db->query($stmt);
    }

    public static function prepare($stmt)
    {
        return self::$db->prepare($stmt);
    }

    public static function run($query, $args = [])
    {
        if (!$args) {
            return self::query($query);
        }
        $stmt = self::prepare($query);
        $stmt->execute($args);
        return $stmt;
    }

    public static function getRow($query, $args = [])
    {
        return self::run($query, $args)->fetch();
    }

    public static function getRows($query, $args = [])
    {
        return self::run($query, $args)->fetchAll();
    }
}