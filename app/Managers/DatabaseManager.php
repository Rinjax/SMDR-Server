<?php

namespace App\Managers;

class DatabaseManager
{
    //username to access the database.
    protected $username;

    //password to access the database.
    protected $password;

    //name of the database to connect to.
    protected $database;

    //the host address of the database server.
    protected $host;

    //the port of the host/database wanting to connect to.
    protected $port;

    //total string of the host and port.
    protected $hostConnection;

    //this is the database connection object.
    protected $DB;

    //database connection options.
    protected $options = [
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC, //make the default fetch be an associative array.
    ];

    protected $logger;

    public function __construct()
    {
        $this->username = getenv('DB_USERNAME');

        $this->password = getenv('DB_PASSWORD');

        $this->database = getenv('DB_DATABASE');

        $this->host = getenv('DB_HOST');

        $this->port= getenv('DB_PORT');

        $this->hostConnection = 'mysql:host=' . $this->host . ':' . $this->port . ';dbname=' . $this->database;

        //turn on errors in the form of exceptions for local development.
        if(getenv('APP_ENV') == 'local') array_push($this->options, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);

        $this->logger = new LogManager('database-manager');

    }

    /**
     * Create the connection to the database.
     * @return \PDO
     */
    public function connectToDatabase()
    {
        try{
            $this->DB = new \PDO($this->hostConnection, $this->username, $this->password, $this->options);
            echo("connected \r\n");
        }catch(\Exception $e){
            die('bum connection to database' . $e->getMessage());
        }

        return $this->DB;
    }

    /**
     * Run insert query.
     * @param $table
     * @param $dataArray
     * @return mixed
     */
    public function insert($table, $dataArray)
    {
        $this->logger->debug('Database table: ' . $table . ' Data array: ', $dataArray);

        $columns = $this->dataArrayToColums($dataArray);
        $values = $this->dataArrayToValues($dataArray);

        $this->logger->debug('Data for SQL statement:', [
            'table' => $table,
            'columns' => $columns,
            'values' => $values
        ]);
        

        $statment = $this->DB->prepare('INSERT INTO ' . $table . '(' . $columns . ') VALUES (' . $values . ')');
        //$statment = $this->DB->prepare('INSERT INTO ' . $table . '(call_id,call_leg_id) VALUES (:call_id,:call_leg_id)');


        // $this->logger->debug('Prepare statement: ' . $statment->debugDumpParams() );

        try{
            echo implode('  ',$dataArray);
            return $statment->execute($dataArray);
            //return $statment->execute($data);

        }catch(\Exception $e){
            $this->logger->debug('FAILED INSERT >>> ' . $e->getMessage());
            die($e->getMessage());
        }
    }

    /**
     * Take array keys and return as comma separated string for use in SQL statements.
     * @param $dataArray
     * @return bool|string
     */
    protected function dataArrayToColums($dataArray)
    {
        $keys =  array_keys($dataArray);

        $string = '';

        foreach($keys as $key){
            $string .= $key . ',';
        }

        $this->logger->debug('converting dataArray to string: ' . rtrim($string, ','));

        return rtrim($string, ',');
    }

    /**
     * Take array values and return as comma separated string for use in SQL statements.
     * @param $dataArray
     * @return bool|string
     */
    protected function dataArrayToValues($dataArray)
    {
        $keys =  array_keys($dataArray);

        $string = '';

        foreach($keys as $key){
            $string .= ':'. $key . ',';
        }

        return rtrim($string, ',');
    }

    /**
     * Show all the tables for the database.
     * @return mixed
     */
    public function showTables()
    {
        $statement = $this->DB->query('SHOW TABLES');

        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }
}