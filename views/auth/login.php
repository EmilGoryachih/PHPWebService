<!-- views/auth/login.php -->
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-5" style="max-width: 400px;">
    <h2 class="mb-4 text-center">Вход в админ-панель</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger text-center">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="/login" method="post">
        <div class="mb-3">
            <label class="form-label">Логин</label>
            <input type="text" name="login" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Пароль</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Войти</button>
    </form>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
