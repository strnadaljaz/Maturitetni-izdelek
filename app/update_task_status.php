<?php
session_start();
include_once("../login_register/connection.php");

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'error' => 'Uporabnik ni prijavljen.']);
    exit();
}

$username = $_SESSION['username'];
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['task']) && isset($data['task_done'])) {
    $task = $data['task'];
    $task_done = $data['task_done'];

    $stmt = $con->prepare("
        UPDATE tasks 
        SET task_done = ? 
        WHERE task = ? AND user_id = (SELECT user_id FROM users WHERE username = ?)
    ");
    $stmt->bind_param("iss", $task_done, $task, $username);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Napaka pri bazi podatkov.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Neveljavni podatki.']);
}
