<?php
if (!session_id()) session_start();
require_once __DIR__ . '/../../config.php';
if (empty($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
    header('Location: /admin/auth/login.php');
    exit;
}
?><!doctype html>
<html lang="fi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin - Lomamökit</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>body{padding-top:56px}.sidebar{min-height:100vh;padding-top:1rem;background:#f8f9fa}</style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="/admin/index.php">Lomamökit – Admin</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="/admin/auth/logout.php">Kirjaudu ulos (<?php echo htmlspecialchars($_SESSION['user']['username']); ?>)</a></li>
      </ul>
    </div>
  </div>
</nav>
<div class="container-fluid">
  <div class="row">
    <div class="col-2 sidebar">
      <?php include __DIR__ . '/sidebar.php'; ?>
    </div>
    <div class="col-10 py-4">
