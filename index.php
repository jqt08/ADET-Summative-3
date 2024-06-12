<?php
session_start();

if (!isset($_SESSION["todoList"])) {
    $_SESSION["todoList"] = new SplStack();
}

function appendData($task, $dueDate, $list)
{
    $list->push(["task" => $task, "dueDate" => $dueDate]);
    return $list;
}

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["task"])) {
        $task = $_POST["task"];
        $dueDate = $_POST["due_date"];

        if (!empty($task) && !empty($dueDate)) {
            $_SESSION["todoList"] = appendData($task, $dueDate, $_SESSION["todoList"]);
        } else {
            echo '<script>alert("Error: Task and Due Date are required")</script>';
        }
    } else {
        echo '<script>alert("Error: There is no data to add to the list")</script>';
    }
}

if (isset($_GET['task'])) {
    $_SESSION["todoList"] = deleteData($_GET['task'], $_SESSION["todoList"]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced To-Do List</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="min-h-screen bg-cover bg-center" style="background-image: url('https://c4.wallpaperflare.com/wallpaper/443/823/638/dark-hand-cross-dirt-wallpaper-preview.jpg');">
        <div class="flex flex-col items-center justify-center min-h-screen bg-black bg-opacity-20 p-4">
        <h1 class="text-3xl text-red-500 font-bold mb-6 flex items-center">
    <img src="https://pzwiki.net/w/images/3/3e/Moodle_Icon_Zombie.png" alt="Zombie Head" class="w-16 h-16 mr-2">
    Enhanced To-Do List
</h1>
            <div class="bg-gray-800 rounded-lg shadow-md w-full max-w-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-red-500 flex items-center mb-4">
    <img src="https://pzwiki.net/w/images/b/bb/Moodle_Icon_Bored.png" alt="Zombie Head" class="w-16 h-16 mr-2">
    Add a new task
</h2>
                <form method="post" action="">
                    <div class="mb-4">
                        <label class="block text-red-500 mb-2" for="task">Task</label>
                        <input class="w-full px-3 py-2 border rounded-lg" type="text" name="task" id="task" placeholder="Enter your task here">
                    </div>
                    <div class="mb-4">
                        <label class="block text-red-500 mb-2" for="due-date">Due Date</label>
                        <input class="w-full px-3 py-2 border rounded-lg" type="date" name="due_date" id="due-date" placeholder="Select due date">
                    </div>
                    <button class="bg-red-500 text-white px-4 py-2 rounded-lg">Add Task</button>
                </form>
            </div>
            <div class="bg-gray-800 rounded-lg shadow-md w-full max-w-md p-6">
                <h2 class="text-xl text-red-500 font-semibold flex items-center mb-4"><img src="https://cdn.cloudflare.steamstatic.com/steamcommunity/public/images/apps/108600/b8036444b2a8889663bc232c142ac888e8353415.ico" alt="Zombie Head" class="w-16 h-16 mr-2">Tasks</h2>
                <table class="w-full table-auto">
                    <thead>
                        <tr>
                            <th class="text-red-500 px-4 py-2 text-left">Task</th>
                            <th class="text-red-500 px-4 py-2 text-left">Due Date</th>
                            <th class="text-red-500 px-4 py-2 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!$_SESSION["todoList"]->isEmpty()) {
                            foreach ($_SESSION["todoList"] as $task) {
                                echo '<tr>';
                                echo '<td class=" text-red-500 border-t px-4 py-2">' . $task["task"] . '</td>';
                                echo '<td class="text-red-500 border-t px-4 py-2">' . $task["dueDate"] . '</td>';
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
</body>
</html>
