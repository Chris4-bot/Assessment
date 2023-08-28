<?php
try {
    require_once("todo.controller.php");
    
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $path = explode( '/', $uri);
    $requestType = $_SERVER['REQUEST_METHOD'];
    $body = file_get_contents('php://input');
    $pathCount = count($path);

    $controller = new TodoController();
    
    switch($requestType) {
        case 'GET':
            //die(json_encode(array("message" => "Test")));
            if ($path[$pathCount - 2] == 'todo' && isset($path[$pathCount - 1]) && strlen($path[$pathCount - 1])) {
                $id = $path[$pathCount - 1];
                $todo = $controller->load($id);
                if ($todo) {
                    http_response_code(200);
                    die(json_encode($todo));
                }
                http_response_code(404);
                die();
            } else {
                http_response_code(200);
                die(json_encode($controller->loadAll()));
            }

            break;
        case 'POST':
            //implement your code here
            $data = json_decode($body);

            if ($data) {
                $newTodo = new Todo($data->id, $data->title, $data->description, $data->done);
                if ($controller->create($newTodo)) {
                    http_response_code(201);
                    die(json_encode(array("message" => "TODO item created successfully.")));
                } else {
                    http_response_code(500);
                    die(json_encode(array("message" => "Failed to create TODO item.")));
                }
            } else {
                http_response_code(400);
                die(json_encode(array("message" => "Invalid JSON data.")));
            }
            break;
        case 'PUT':
            //implement your code here
            if ($path[$pathCount - 2] == 'todo' && isset($path[$pathCount - 1]) && strlen($path[$pathCount - 1])) {
                $id = $path[$pathCount - 1];

                $data = json_decode($body);

                // Load the existing Todo item
                $existingTodo = $controller->load($id);

  
                


                if ($data && $existingTodo) {
                    // Update the existing Todo item's properties
                    $existingTodo->title = $data->title;
                    $existingTodo->description = $data->description;
                    $existingTodo->done = $data->done;

                    //die(json_encode(array("message" => "data", $data, "existingTodo", $existingTodo)));

                    if ($controller->update($id, $existingTodo)) {
                        http_response_code(200);
                        die(json_encode(array("message" => "TODO item updated successfully.")));
                    } else {
                        http_response_code(500);
                        die(json_encode(array("message" => "Failed to update TODO item.")));
                    }
                } else {
                    http_response_code(400);
                    die(json_encode(array("message" => "Invalid JSON data or TODO item not found.")));
                }
            } else {
                http_response_code(400);
                die(json_encode(array("message" => "Invalid request.")));
            }
            break;
        case 'DELETE':
            //implement your code here
            if ($path[$pathCount - 2] == 'todo' && isset($path[$pathCount - 1]) && strlen($path[$pathCount - 1])) {
                $id = $path[$pathCount - 1];
                if ($controller->delete($id)) {
                    http_response_code(200);
                    die(json_encode(array("message" => "TODO item deleted successfully.")));
                } else {
                    http_response_code(500);
                    die(json_encode(array("message" => "Failed to delete TODO item.")));
                }
            } else {
                http_response_code(400);
                die(json_encode(array("message" => "Invalid request.")));
            }
            break;
        default:
            http_response_code(501);
            die();
            break;
    }
} catch(Throwable $e) {
    error_log($e->getMessage());
    http_response_code(500);
    die();
}
