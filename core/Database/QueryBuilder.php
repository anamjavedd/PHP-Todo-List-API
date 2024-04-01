<?php

class QueryBuilder
{
    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;

    }

    public function selectAll($table)
    {
        //Prepare an SQL Query
        $statement = $this->pdo->prepare("select * from {$table}");

        $statement->execute();

        //Below line was giving both Associative Array and Indexed Array
        // var_dump($statement->fetchAll());

        //Below line will return an array of objects and only Associative Array
        //var_dump($statement->fetchAll(PDO::FETCH_OBJ));

        // $tasks = $statement->fetchAll(PDO::FETCH_OBJ);

        // $tasks = $statement->fetchAll(PDO::FETCH_CLASS, 'Task');

        return $statement->fetchAll(PDO::FETCH_CLASS);
    }

    public function insert($table, $parameters)
    {
        //insert into users (name, email) values (:name, :email)

        //$statement->execute(['name' => 'Joe', 'email' => 'joe@example.com']);

        //Make an sql statement

        $sql = sprintf(
            'insert into %s (%s) values (%s)',
            $table,
            implode(', ', array_keys($parameters)),
            ':' . implode(', :', array_keys($parameters))
        );

        try {
            $statement = $this->pdo->prepare($sql);

            $statement->execute($parameters);
        } catch (Exception $e) {
            die('Whoops, something went wrong.');
        }

    }
    public function update($table, $parameters, $whereClause)
    {
        $setParts = [];
        foreach ($parameters as $key => $value) {
            $setParts[] = "{$key} = :{$key}";
        }
        $setClause = implode(', ', $setParts);

        // Construct the update query
        $sql = sprintf(
            'UPDATE %s SET %s WHERE %s',
            $table,
            $setClause,
            $whereClause
        );

        // Merge parameters for set values and where clause
        // $allParameters = array_merge($parameters, $whereParameters);

        // Prepare and execute the query
        $statement = $this->pdo->prepare($sql);
        foreach ($parameters as $key => &$value) {
            $statement->bindParam(":{$key}", $value);
        }

        $statement->execute();
        return $statement->rowCount() > 0;
    }

    public function delete($table, $whereClause, $whereParameters)
    {
        // Construct the delete query
        $sql = sprintf(
            'DELETE FROM %s WHERE %s',
            $table,
            $whereClause
        );

        // Prepare and execute the query
        $statement = $this->pdo->prepare($sql);
        foreach ($whereParameters as $key => &$value) {
            $statement->bindParam(":{$key}", $value);
        }

        $statement->execute();
        return $statement->rowCount() > 0;
    }

}