<?php

class Connection

{

    public static function make($config)

    {
        try{

                // To interact with our database, we're going to use an instance of the PDO class
                //PHP Data Objects
                //In the constructor of PDO class, DSN is being provided, DSN is basically a connection string
                //DSN: What kind of database?---MYSQL And what host is it? It is a localhost Port number-- 127.0.0.1 What is the DB name?that we want to work on?
                //Constructor(DSN String, Username, Password)

            // return new PDO('mysql:host=127.0.0.1; dbname=mytodo', 'root', '');

            return new PDO(
                
                $config['connection'].';dbname='.$config['name'],
                $config['username'],
                $config['password'],
                $config['options']
            );
         
         } catch(PDOException $e) {
         
             die('Could not connect.');
         
         }
    }
}


// $connection = new Connection();
// $connection->make();

// ::denotes that you're calling a static function

// Connection::make();