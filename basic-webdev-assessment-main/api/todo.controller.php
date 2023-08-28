<?php
require_once("todo.class.php");

class TodoController {
    private const PATH = __DIR__."/todo.json";
    private array $todos = [];

    public function __construct() {
        $content = file_get_contents(self::PATH);
        if ($content === false) {
            throw new Exception(self::PATH . " does not exist");
        }  
        $dataArray = json_decode($content);
        if (!json_last_error()) {
            foreach($dataArray as $data) {
                if (isset($data->id) && isset($data->title))
                $this->todos[] = new Todo($data->id, $data->title, $data->description, $data->done);
            }
        }
    }

    public function loadAll() : array {
        return $this->todos;
    }

    public function load(string $id) : Todo | bool {
        foreach($this->todos as $todo) {
            if ($todo->id == $id) {
                return $todo;
            }
        }
        return false;
    }

    public function create(Todo $todo) : bool {
        // implement your code here
        $this->todos[] = $todo;
        return $this->saveToJSON();

        return true;
    }

    public function update(string $id, Todo $updatedtodo) : bool {
        // implement your code here
        foreach ($this->todos as &$todo) {
            if ($todo->id == $id) {

                $todo = $updatedtodo;
                return $this->saveToJSON();
            }
        }
        return true;
    }

    public function delete(string $id) : bool {
        // implement your code here
        foreach ($this->todos as $index => $todo) {
            if ($todo->id == $id) {
                unset($this->todos[$index]);
                return $this->saveToJSON();
            }
        }
        return true;
    }

    // add any additional functions you need below

    private function saveToJSON() : bool {
        $jsonArray = [];
        foreach ($this->todos as $todo) {
            $jsonArray[] = [
                "id" => $todo->id,
                "title" => $todo->title,
                "description" => $todo->description,
                "done" => $todo->done,
            ];
        }
        return file_put_contents(self::PATH, json_encode($jsonArray, JSON_PRETTY_PRINT)) !== false;
    }
}