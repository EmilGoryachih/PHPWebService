<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-5 text-center">
    <h1 class="mb-3">Административная панель</h1>
    <p class="mb-4">Выберите необходимое действие:</p>
    <a href="/admin/cars/create" class="btn btn-primary btn-lg me-3">
        <i class="bi bi-plus-circle me-1"></i> Добавить авто в каталог
    </a>
    <a href="/admin/requests" class="btn btn-success btn-lg">
        <i class="bi bi-card-list me-1"></i> Просмотреть заявки
    </a>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
