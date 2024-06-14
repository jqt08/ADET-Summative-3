<?php
session_start();

// Initialize session variable if not already set
if (!isset($_SESSION["todoList"])) {
    $_SESSION["todoList"] = new SplStack();
}

// Function to append task data to the session list
function appendData($task, $dueDate, $dueTime, $list)
{
    $dueDateTime = $dueDate . ' ' . $dueTime;
    $list->push(["task" => $task, "dueDateTime" => $dueDateTime]);
    return $list;
}

// Function to delete a task from the session list
function deleteData($toDelete, $list)
{
    $tempList = new SplStack();
    while (!$list->isEmpty()) {
        $task = $list->pop();
        if ($task["task"] !== $toDelete) {
            $tempList->push($task);
        }
    }
    return $tempList;
}

// Handling form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["task"]) && !empty($_POST["due_date"]) && !empty($_POST["due_time"])) {
        $task = $_POST["task"];
        $dueDate = $_POST["due_date"];
        $dueTime = $_POST["due_time"];

        // Append task to session list
        $_SESSION["todoList"] = appendData($task, $dueDate, $dueTime, $_SESSION["todoList"]);
    } else {
        echo '<script>alert("Error: Task, Due Date, and Due Time are required fields.")</script>';
    }
}

// Handling task deletion
if (isset($_GET['task'])) {
    $_SESSION["todoList"] = deleteData($_GET['task'], $_SESSION["todoList"]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Almendra:ital,wght@0,400;0,700;1,400;1,700&family=Unna:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Butcherman&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Almendra:ital,wght@0,400;0,700;1,400;1,700&family=Libre+Caslon+Display&family=Unna:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <style>
        body{
            font-family: 'Libre Caslon Display', sans-serif;
        }
        .title-font{
            color: #e60000;
            font-family: 'Butcherman', sans-serif;
            text-shadow: 
                -1px -1px 0 #000,  
                 1px -1px 0 #000,
                -1px  1px 0 #000,
                 1px  1px 0 #000;
        }
        .title-font1{
            color: #e60000;
            font-family: 'Almendra', sans-serif;
            text-shadow: 
                -1px -1px 0 #000,  
                 1px -1px 0 #000,
                -1px  1px 0 #000,
                 1px  1px 0 #000;
        }
    </style>
    <title>Enhanced To-Do List</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="min-h-screen bg-cover bg-center" style="background-image: url('https://static1.thegamerimages.com/wordpress/wp-content/uploads/2019/04/Project-Zomboid-Cover.jpg');">
        <div class="flex flex-col items-center justify-center min-h-screen bg-black bg-opacity-20 p-4">
            <h1 class="text-7xl font-bold mb-6 flex items-center title-font">
                <img src="https://pzwiki.net/w/images/3/3e/Moodle_Icon_Zombie.png" alt="Zombie Head" class="w-16 h-16 mr-2">
                Enhanced To-Do List
            </h1>
            <div class="bg-gray-800 rounded-lg shadow-md w-full max-w-md p-6 mb-6 flex items-center justify-between">
                <h2 class="text-3xl font-semibold text-red-500 flex items-center mb-4 title-font1">
                    <img src="https://pzwiki.net/w/images/b/bb/Moodle_Icon_Bored.png" alt="Zombie Head" class="w-16 h-16 mr-2">
                    Add a new task
                </h2>
                <p id="clock" class="text-red-500"></p>
            </div>
            <div class="bg-gray-800 rounded-lg shadow-md w-full max-w-md p-6">
                <form method="post" action="">
                    <div class="mb-4">
                        <label class="text-2xl block text-red-500 mb-2" for="task">Task</label>
                        <input class="w-full px-3 py-2 border rounded-lg" type="text" name="task" id="task" placeholder="Enter your task here">
                    </div>
                    <div class="mb-4">
                        <label class="text-2xl block text-red-500 mb-2" for="due-date">Due Date</label>
                        <input class="w-full px-3 py-2 border rounded-lg" type="date" name="due_date" id="due-date">
                    </div>
                    <div class="mb-4">
                        <label class="text-2xl block text-red-500 mb-2" for="due-time">Time Due</label>
                        <input class="w-full px-3 py-2 border rounded-lg" type="time" name="due_time" id="due-time">
                    </div>
                    <button class="text-2xl bg-red-500 text-white px-4 py-2 rounded-lg">Add Task</button>
                </form>
            </div>
            <div class="bg-gray-800 rounded-lg shadow-md w-full max-w-md p-6">
                <h2 class="text-3xl text-red-500 font-semibold flex items-center mb-4 title-font1">
                    <img src="https://cdn.cloudflare.steamstatic.com/steamcommunity/public/images/apps/108600/b8036444b2a8889663bc232c142ac888e8353415.ico" alt="Zombie Head" class="w-16 h-16 mr-2">
                    Tasks
                </h2>
                <table class="w-full table-auto">
                    <thead>
                        <tr>
                            <th class="text-xl text-red-500 px-4 py-2 text-left">Task</th>
                            <th class="text-xl text-red-500 px-4 py-2 text-left">Due Date</th>
                            <th class="text-xl text-red-500 px-4 py-2 text-left">Time Due</th>
                            <th class="text-xl text-red-500 px-4 py-2 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!$_SESSION["todoList"]->isEmpty()) {
                            foreach ($_SESSION["todoList"] as $task) {
                                echo '<tr>';
                                echo '<td class=" text-red-500 border-t px-4 py-2">' . htmlspecialchars($task["task"]) . '</td>';
                                echo '<td class="text-red-500 border-t px-4 py-2">' . date('M d, Y', strtotime($task["dueDateTime"])) . '</td>';
                                echo '<td class="text-red-500 border-t px-4 py-2">' . date('h:i A', strtotime($task["dueDateTime"])) . '</td>';
                                echo '<td class="border-t px-4 py-2"><a href="index.php?task=' . urlencode($task["task"]) . '" class="text-white">Delete</a></td>';
                                echo '</tr>';
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Real-time clock in Manila, Philippines timezone
        function updateClock() {
            var now = new Date();
            var options = { timeZone: 'Asia/Manila', hour: 'numeric', minute: 'numeric', second: 'numeric' };
            var formattedTime = now.toLocaleTimeString('en-US', options);
            document.getElementById('clock').textContent = formattedTime;
        }

        // Update clock every second
        setInterval(updateClock, 1000);

        // Initial call to display clock immediately
        updateClock();
    </script>
</body>
</html>
