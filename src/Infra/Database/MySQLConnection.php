<?php

class MySQLConnection
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $host = "localhost";
            $db   = "estacionamento"; // Altere para o nome do seu banco
            $user = "root";           // Usuário
            $pass = "mybabykooksi93";  // Senha, se houver
            $charset = "utf8mb4";

            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

            self::$connection = new PDO($dsn, $user, $pass);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return self::$connection;
    }
}
