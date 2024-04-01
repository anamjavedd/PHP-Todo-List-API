<?php

class TodoController
{

    public function read()
    {

        $todos = App::get('database')->selectAll('todos');

        if ($todos) {
            // Serialize data into JSON format
            $responseData = json_encode($todos);
            header('Content-Type: application/json');
            http_response_code(200);
            echo $responseData;
        } else {
            http_response_code(404);
            echo json_encode(array('error' => 'No Todo items found.'));
        }

        //var_dump($todos);

        //return view('todos', compact('todos')); //associative array 'todos' is key
    }

    public function create()
    {

        $requestBody = file_get_contents('php://input');
        $data = json_decode($requestBody, true);

        if ($data !== null) {
            App::get('database')->insert('todos', [
                'description' => $data['description'],
                'completed' => $data['completed']

            ]);
        } else {
            // Handle invalid JSON data
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Invalid JSON data']);
        }

    }

    public function update($id)
    {
        $id = intval($id);
        $requestBody = file_get_contents('php://input');
        $data = json_decode($requestBody, true); // Decode JSON data into associative array

        if ($data === null) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid JSON data']);
            return;
        }

        if (!isset($data['description']) || !isset($data['completed'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }

        $updateSuccessful = App::get('database')->update(
            'todos',
            [
                'description' => $data['description'],
                'completed' => $data['completed']
            ], // Parameters to update
            "id = $id", // WHERE clause
            //['id' => $id] // Parameters for WHERE clause
        );

        if ($updateSuccessful) {
            http_response_code(200);
            echo json_encode(['message' => 'Todo item updated successfully']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Todo item not found or data was unchanged']);
        }
    }

    public function delete($id)
    {
        $id = intval($id);

        $deleteSuccessful = App::get('database')->delete(
            'todos', // Table name
            'id = :id', // WHERE clause
            ['id' => $id] // Parameters for WHERE clause
        );

        if ($deleteSuccessful) {
            http_response_code(200);
            echo json_encode(['message' => 'Todo item deleted successfully']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Todo item not found or could not be deleted']);
        }
    }



}