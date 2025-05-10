<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <?php foreach ($cars as $car): ?>
            <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="card h-100">
                    <?php if (!empty($car['image'])): ?>
                        <img src="/img/<?= htmlspecialchars($car['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($car['make'].' '.$car['model']) ?>">
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($car['make'].' '.$car['model']) ?></h5>
                        <p class="card-text mb-1">
                            <i class="bi bi-calendar-event me-1 text-body-secondary"></i>
                            <?= htmlspecialchars($car['year']) ?>
                        </p>
                        <p class="card-text mb-1">
                            <i class="bi bi-speedometer2 me-1 text-body-secondary"></i>
                            <?= number_format($car['mileage'], 0, '.', ' ') ?> км
                        </p>
                        <p class="card-text text-success fw-semibold mt-auto">
                            <i class="bi bi-cash-stack me-1"></i>
                            <?= number_format($car['price'], 0, '.', ' ') ?> ₽
                        </p>
                        <div class="mt-3 d-flex flex-wrap gap-2">
                            <a href="/catalog/view/<?= $car['id'] ?>"
                               class="btn btn-outline-primary btn-sm flex-shrink-0">
                                Подробнее
                            </a>

                            <?php if (!empty($_SESSION['is_admin'])): ?>
                                <a href="/admin/cars/edit/<?= $car['id'] ?>"
                                   class="btn btn-outline-secondary btn-sm flex-shrink-0">
                                    <i class="bi bi-pencil-square me-1"></i> Редактировать
                                </a>

                                <form action="/admin/cars/delete/<?= $car['id'] ?>"
                                      method="post"
                                      class="flex-shrink-0"
                                      onsubmit="return confirm('Точно удалить этот автомобиль?');"
                                      style="display:inline-block;">
                                    <input type="hidden"
                                           name="csrf_token"
                                           value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash me-1"></i> Удалить
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
