<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-5">
    <h1 class="mb-4">Заявки клиентов</h1>
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead class="table-light">
            <tr>
                <th>Имя</th>
                <th>Телефон</th>
                <th>Дата заявки</th>
                <th class="text-end">Действие</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($requests as $req): ?>
                <tr>
                    <td><?= htmlspecialchars($req['name']) ?></td>
                    <td><?= htmlspecialchars($req['phone']) ?></td>
                    <td><?= htmlspecialchars($req['created_at']) ?></td>
                    <td class="text-end">
                        <form action="/admin/requests/delete/<?= $req['id'] ?>" method="post" onsubmit="return confirm('Удалить эту заявку?');">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="bi bi-trash me-1"></i> Удалить
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
