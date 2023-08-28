
function putTodo(todo) {
    // implement your code here
    const todoId = todo.id;
    fetch(`api/todo/${todoId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(todo),
    })
    .then(response => response.json())
    .then(updatedTodo => {
        console.log("Updated TODO item:", updatedTodo);

    })
    .catch(error => console.error('Error updating TODO item:', error));

}

function postTodo(todo) {
    // implement your code here
    fetch('api/todo', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(todo),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Failed to create todo');
        }
        return response.json();
    })
    .then(createdTodo => {
        console.log('Todo created:', createdTodo);
    })
    .catch(error => {
        console.error('Error creating todo:', error);
        showToastMessage('Failed to create todo...');
    });
    console.log("calling postTodo");
    console.log(todo);
}

function deleteTodo(todo) {
    // implement your code here
    const todoId = todo.id;

    // Send a DELETE request to the API
    fetch(`api/todo/${todoId}`, {
        method: 'DELETE',
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Failed to delete todo');
        }
        return response.json();
    })
    .then(deletedTodo => {
        console.log('Todo deleted:', deletedTodo);
    })
    .catch(error => showToastMessage('Failed to delete todo...'));
    console.log("calling deleteTodo");
    console.log(todo);


}


// example using the FETCH API to do a GET request
function getTodos() {
    fetch(window.location.href + 'api/todo')
    .then(response => response.json())
    .then(json => drawTodos(json))
    .catch(error => showToastMessage('Failed to retrieve todos...'));
    console.log("Refreash");
}
getTodos();
