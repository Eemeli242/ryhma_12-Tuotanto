<?php
require '../includes/header.php';
$id = intval($_GET['id'] ?? 0);
if ($id) {
    $stmt = $pdo->prepare('DELETE FROM cabins WHERE id = :id');
    $stmt->execute(['id'=>$id]);
}
header('Location: list.php');
exit;
?>
