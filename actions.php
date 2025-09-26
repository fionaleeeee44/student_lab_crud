<?php
require_once __DIR__ . '/config.php';

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

function redirectWith($msg) {
    $params = [];
    if (isset($_GET['q'])) $params['q'] = $_GET['q'];
    if (isset($_GET['sort'])) $params['sort'] = $_GET['sort'];
    if (isset($_GET['order'])) $params['order'] = $_GET['order'];
    $params['msg'] = $msg;
    header('Location: index.php?' . http_build_query($params));
    exit;
}

try {
    if ($action === 'create') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $course = trim($_POST['course'] ?? '');
        if ($name === '' || $email === '' || $course === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            redirectWith('Please provide valid name, email, and course.');
        }
        $stmt = $pdo->prepare('INSERT INTO students(name,email,course) VALUES(:name,:email,:course)');
        $stmt->execute([':name'=>$name, ':email'=>$email, ':course'=>$course]);
        redirectWith('Student added.');
    }
    if ($action === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $course = trim($_POST['course'] ?? '');
        if ($id <= 0 || $name === '' || $email === '' || $course === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            redirectWith('Invalid input.');
        }
        $stmt = $pdo->prepare('UPDATE students SET name=:name, email=:email, course=:course WHERE id=:id');
        $stmt->execute([':name'=>$name, ':email'=>$email, ':course'=>$course, ':id'=>$id]);
        redirectWith('Student updated.');
    }
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            redirectWith('Invalid ID.');
        }
        $stmt = $pdo->prepare('DELETE FROM students WHERE id=:id');
        $stmt->execute([':id'=>$id]);
        redirectWith('Student deleted.');
    }
    redirectWith('Unknown action.');
} catch (PDOException $e) {
    $msg = 'Operation failed.';
    if ((int)$e->errorInfo[1] === 1062) {
        $msg = 'Email already exists.';
    }
    redirectWith($msg);
}


