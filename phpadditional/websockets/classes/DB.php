<?php


class DB
{
    private PDO $db;
    private string $host;
    private string $dbName;
    private string $username;
    private string $password;

    /**
     * DB constructor.
     * @param string $host
     * @param string $dbName
     * @param string $username
     * @param string $password
     */
    public function __construct(string $host='127.0.0.1', string $dbName='mod4',
                                string $username='root', string $password='root')
    {
        $this->host = $host;
        $this->dbName = $dbName;
        $this->username = $username;
        $this->password = $password;
        $this->db = new PDO('mysql:dbname='.$this->dbName.'host='.$this->host,
            $this->username,$this->password);
    }


}