<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-5">
    <?php if ($car): ?>
        <div class="row mb-4">
            <div class="col-md-6 mb-4">
                <?php if (!empty($car['image'])): ?>
                    <img src="/img/<?= htmlspecialchars($car['image']) ?>" class="img-fluid rounded mb-3" alt="<?= htmlspecialchars($car['make'].' '.$car['model']) ?>">
                <?php endif; ?>
                <?php if (!empty($images)): ?>
                    <div class="row">
                        <?php foreach ($images as $img): ?>
                            <div class="col-4 mb-3">
                                <img src="/img/<?= htmlspecialchars($img['filename']) ?>" class="img-fluid rounded" alt="Дополнительное фото">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-md-6">
                <h2 class="mb-4"><?= htmlspecialchars($car['make'].' '.$car['model']) ?></h2>
                <ul class="list-group list-group-flush mb-4">
                    <li class="list-group-item">
                        <i class="bi bi-calendar-event me-2"></i>
                        <?= htmlspecialchars($car['year']) ?>
                    </li>
                    <li class="list-group-item">
                        <i class="bi bi-speedometer2 me-2"></i>
                        <?= number_format($car['mileage'], 0, '.', ' ') ?> км
                    </li>
                    <li class="list-group-item">
                        <i class="bi bi-cash-stack me-2"></i>
                        <span class="text-success fw-semibold">
                            <?= number_format($car['price'], 0, '.', ' ') ?> ₽
                        </span>
                    </li>
                </ul>
                <div class="mb-4">
                    <h5>Описание</h5>
                    <p><?= nl2br(htmlspecialchars($car['description'])) ?></p>
                </div>
                <a href="/catalog" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Назад в каталог
                </a>
                <?php if (!empty($_SESSION['is_admin'])): ?>
                    <a href="/admin/cars/edit/<?= $car['id'] ?>" class="btn btn-outline-primary ms-2">
                        <i class="bi bi-pencil-square me-1"></i> Редактировать
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">Автомобиль не найден.</div>
        <a href="/catalog" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Назад в каталог
        </a>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
