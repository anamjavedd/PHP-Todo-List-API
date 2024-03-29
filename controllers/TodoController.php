<?php

class TodoController
{

    public function read()
    {

        $todos = App::get('database')->selectAll('todos');

        if ($todos) {
            // Serialize data into JSON format
            $responseData = json_encode($todos);

            // Set HTTP header to indicate JSON content
            header('Content-Type: application/json');

            // Set HTTP status code to 200 (Success)
            http_response_code(200);

            // Output JSON-encoded data
            echo $responseData;
        } else {
            // Set HTTP status code to 404 (Not Found)
            http_response_code(404);

            // Output an error message or appropriate response
            echo json_encode(array('error' => 'No Todo items found.'));
        }

        //var_dump($todos);

        //return view('todos', compact('todos')); //associative array 'users' is key
    }

    public function create()
    {

        // Assuming you're receiving JSON data in the request body
        $requestBody = file_get_contents('php://input');
        $data = json_decode($requestBody, true); // Decode JSON data into associative array

        // Check if JSON data is valid
        if ($data !== null) {
            // Assuming $data contains the todo data in JSON format
            App::get('database')->insert('todos', [
                'description' => $data['description'], // Assuming 'description' is a field in the JSON data
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
        // Sanitize the ID parameter
        $id = intval($id);

        // Retrieve request body JSON data
        $requestBody = file_get_contents('php://input');
        $data = json_decode($requestBody, true); // Decode JSON data into associative array

        // Check if JSON data is valid
        if ($data === null) {
            // Handle invalid JSON data
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Invalid JSON data']);
            return;
        }

        // Validate required fields
        if (!isset($data['description']) || !isset($data['completed'])) {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }

        // Perform database update
        $updateSuccessful = App::get('database')->update(
            'todos', // Table name
            [
                'description' => $data['description'],
                'completed' => $data['completed']
            ], // Parameters to update
            "id = :id", // WHERE clause
            ['id' => $id] // Parameters for WHERE clause
        );

        // Check if update was successful
        if ($updateSuccessful) {
            // Return success response
            http_response_code(200);
            echo json_encode(['message' => 'Todo item updated successfully']);
        } else {
            // If no rows were updated, it could mean the item doesn't exist or the data was the same
            http_response_code(404);
            echo json_encode(['error' => 'Todo item not found or data was unchanged']);
        }
    }

    public function delete($id)
    {
        // Sanitize the ID parameter
        $id = intval($id);

        // Perform the delete operation
        $deleteSuccessful = App::get('database')->delete(
            'todos', // Table name
            'id = :id', // WHERE clause
            ['id' => $id] // Parameters for WHERE clause
        );

        // Check if delete was successful
        if ($deleteSuccessful) {
            // Return success response
            http_response_code(200);
            echo json_encode(['message' => 'Todo item deleted successfully']);
        } else {
            // Return error response if delete failed (e.g., item not found)
            http_response_code(404);
            echo json_encode(['error' => 'Todo item not found or could not be deleted']);
        }
    }



}