<?php
// File: views/layouts/header.php
?><!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Автоподбор</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<!-- views/layouts/header.php (фрагмент) -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="/">Автоподбор</a>
        <!-- ... кнопка-toggler ... -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="/">Главная</a></li>
                <li class="nav-item"><a class="nav-link" href="/catalog">Каталог</a></li>
                <?php if (!empty($_SESSION['is_admin'])): ?>
                    <li class="nav-item"><a class="nav-link" href="/admin">Админ-панель</a></li>
                    <li class="nav-item"><a class="nav-link" href="/logout">Выйти</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="/login">Админ</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<main>
