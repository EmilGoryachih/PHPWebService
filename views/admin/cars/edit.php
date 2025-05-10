<?php include __DIR__ . '/../../layouts/header.php'; ?>

<div class="container py-5">
    <h1>Редактировать машину #<?= htmlspecialchars($car['id']) ?></h1>

    <!-- Галерея уже загруженных фото -->
    <?php if (!empty($images)): ?>
        <h4 class="mt-4 mb-3">Текущие фотографии</h4>
        <div class="row g-3">

            <?php
            // 1) находим главное и удаляем из массива
            $mainImg = null;
            foreach ($images as $k => $i) {
                if ($i['is_main']) {
                    $mainImg = $i;
                    unset($images[$k]);
                    break;
                }
            }
            ?>

            <?php foreach ($images as $img): ?>
                <!-- 3) теперь все остальные как раньше -->
                <div class="col-4 mb-3 position-relative">
                    <img src="/img/<?= htmlspecialchars($img['filename']) ?>"
                         class="img-fluid rounded">
                    <form method="post"
                          action="/admin/cars/image/delete/<?= (int)$img['id'] ?>"
                          class="position-absolute top-0 end-0 m-1">
                        <input type="hidden" name="csrf_token"
                               value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                        <button type="submit"
                                class="btn btn-danger btn-sm rounded-circle"
                                style="--bs-btn-padding-y:.2rem;--bs-btn-padding-x:.45rem"
                                title="Удалить фото">&times;</button>
                    </form>
                </div>
            <?php endforeach; ?>

        </div>
    <?php endif; ?>

    <!-- === Форма редактирования машины === -->
    <form class="mt-5"
          action="/admin/cars/update/<?= $car['id'] ?>"
          method="post" enctype="multipart/form-data">

        <input type="hidden" name="csrf_token"
               value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Марка</label>
                <input class="form-control"
                       name="make"
                       value="<?= htmlspecialchars($car['make']) ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Модель</label>
                <input class="form-control"
                       name="model"
                       value="<?= htmlspecialchars($car['model']) ?>" required>
            </div>

            <div class="col-md-3">
                <label class="form-label">Год</label>
                <input class="form-control" type="number"
                       name="year"
                       value="<?= htmlspecialchars($car['year']) ?>" required>
            </div>

            <div class="col-md-3">
                <label class="form-label">Пробег (км)</label>
                <input class="form-control" type="number"
                       name="mileage"
                       value="<?= htmlspecialchars($car['mileage']) ?>" required>
            </div>

            <div class="col-md-3">
                <label class="form-label">Цена (₽)</label>
                <input class="form-control" type="number" step="0.01"
                       name="price"
                       value="<?= htmlspecialchars($car['price']) ?>" required>
            </div>

            <div class="col-12">
                <label class="form-label">Описание</label>
                <textarea class="form-control" rows="4"
                          name="description"><?= htmlspecialchars($car['description']) ?></textarea>
            </div>

            <hr class="mt-4">

            <div class="col-12 col-lg-6">
                <label class="form-label">Новое главное фото</label>
                <input class="form-control" type="file"
                       name="images_main"
                       id="mainImageInput"      <!-- ID для JS -->

                <!-- контейнер для превью нового выбора -->
                <div id="mainImagePreviewNew" class="mt-3"></div>
            </div>
            <label class="form-label">Добавить ещё фото</label>
                <input class="form-control" type="file"
                   name="images_extra[]" id="extraImagesInput"
                   accept="image/*" multiple>

            <!-- контейнер, куда будут появляться миниатюры -->
                <div id="newImagesPreview" class="row g-3 mt-3"></div>
            </div>
        </div>

        <div class="mt-4">
            <button class="btn btn-primary">Обновить</button>
            <a href="/catalog" class="btn btn-secondary ms-2">← Назад</a>
        </div>
    </form>
</div>

<script>
    const input   = document.getElementById('extraImagesInput');
    const preview = document.getElementById('newImagesPreview');
    const dt      = new DataTransfer();              // копилка всех файлов

    input.addEventListener('change', () => {
        for (const file of input.files) {

            /* 1.  Пропускаем дубликат, если уже добавляли такой файл */
            if ([...dt.files].some(f =>
                f.name === file.name &&
                f.lastModified === file.lastModified)) continue;

            /* 2.  Запоминаем файл */
            dt.items.add(file);

            /* 3.  Показываем миниатюру */
            const reader = new FileReader();
            reader.onload = ev => preview.insertAdjacentHTML(
                'beforeend',
                `<div class="col-4 position-relative">
                 <img src="${ev.target.result}"
                      class="img-fluid rounded">
                 <button type="button"
                         class="btn btn-sm btn-danger rounded-circle
                                position-absolute top-0 end-0 m-1"
                         data-name="${file.name}"
                         title="Убрать">&times;</button>
             </div>`
            );
            reader.readAsDataURL(file);
        }

        /* 4.  Передаём накопленный список обратно в <input> */
        input.files = dt.files;


    });

    /* 6.  Удаление миниатюры + файла из списка */
    preview.addEventListener('click', e => {
        if (!e.target.matches('button[data-name]')) return;

        const name = e.target.dataset.name;

        /*  удаляем из DataTransfer */
        for (let i = 0; i < dt.items.length; i++) {
            if (dt.items[i].getAsFile().name === name) {
                dt.items.remove(i);
                break;
            }
        }
        input.files = dt.files;          // обновляем <input>
        e.target.parentElement.remove(); // убираем миниатюру
    });

    (function(){
        const mainInput  = document.getElementById('mainImageInput');
        const previewNew = document.getElementById('mainImagePreviewNew');

        mainInput.addEventListener('change', () => {
            const file = mainInput.files[0];
            if (!file) return;

            // Очищаем прошлое превью нового файла
            previewNew.innerHTML = '';

            const reader = new FileReader();
            reader.onload = e => {
                previewNew.insertAdjacentHTML(
                    'beforeend',
                    `<img src="${e.target.result}"
                class="img-fluid rounded"
                style="max-width:200px">`
                );
            };
            reader.readAsDataURL(file);
        });
    })();
</script>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
