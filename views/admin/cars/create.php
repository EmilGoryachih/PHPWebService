<?php include __DIR__ . '/../../layouts/header.php'; ?>

<div class="container py-5">
    <h1>Добавить новую машину</h1>

    <form action="/admin/cars/store"
          method="post" enctype="multipart/form-data" class="mt-4">

        <input type="hidden" name="csrf_token"
               value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

        <div class="mb-3">
            <label class="form-label">Марка</label>
            <input name="make" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Модель</label>
            <input name="model" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Год</label>
            <input name="year" type="number" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Цена</label>
            <input name="price" type="number" step="0.01" class="form-control" required>
        </div>



        <div class="mb-3">
            <label class="form-label">Пробег (км)</label>
            <input name="mileage" type="number" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Описание</label>
            <textarea name="description" class="form-control" rows="4"></textarea>
        </div>
        <div class="row g-3 mt-4">
            <div class="col-12 col-lg-6">
                <label class="form-label">Главное фото</label>
                <input class="form-control" type="file"
                       name="images_main"
                       id="mainImageInput"
                       accept="image/*">

                <!-- превью сюда -->
                <div id="mainImagePreview" class="mt-3">
                    <!-- сюда JS вставит <img> -->
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <label class="form-label">Дополнительные фото</label>
                <input class="form-control" type="file"
                       name="images_extra[]"
                       id="extraImagesInputCreate"
                       accept="image/*"
                       multiple>
                <div id="newImagesPreviewCreate" class="row g-3 mt-3"></div>
            </div>
        </div>

        <div class="mt-4">
            <button class="btn btn-success">Создать</button>
            <a href="/catalog" class="btn btn-secondary ms-2">← Назад</a>
        </div>
    </form>
</div>

<script>
    (function() {
        const input   = document.getElementById('extraImagesInputCreate');
        const preview = document.getElementById('newImagesPreviewCreate');
        const dt      = new DataTransfer();

        input.addEventListener('change', () => {
            // Для каждого нового файла
            for (const file of input.files) {
                // Пропускаем, если уже добавлен
                if ([...dt.files].some(f =>
                    f.name === file.name && f.lastModified === file.lastModified)) {
                    continue;
                }

                // Кладём в DataTransfer
                dt.items.add(file);

                // Рисуем превью
                const reader = new FileReader();
                reader.onload = e => {
                    preview.insertAdjacentHTML(
                        'beforeend',
                        `<div class="col-4 position-relative">
           <img src="${e.target.result}"
                class="img-fluid rounded">
           <button type="button"
                   class="btn btn-sm btn-danger rounded-circle
                          position-absolute top-0 end-0 m-1"
                   data-name="${file.name}"
                   title="Удалить">&times;</button>
         </div>`
                    );
                };
                reader.readAsDataURL(file);
            }

            // Возвращаем накопленный список в <input>
            input.files = dt.files;
            // НЕ очищаем input.value — иначе файлы пропадут
        });

        // Удаление файла по кнопке ×
        preview.addEventListener('click', e => {
            if (!e.target.matches('button[data-name]')) return;

            const name = e.target.dataset.name;
            for (let i = 0; i < dt.items.length; i++) {
                if (dt.items[i].getAsFile().name === name) {
                    dt.items.remove(i);
                    break;
                }
            }
            input.files = dt.files;
            e.target.parentElement.remove();
        });
    })();


    (function() {
        // поле главного фото
        const mainInput   = document.getElementById('mainImageInput');
        const mainPreview = document.getElementById('mainImagePreview');

        mainInput.addEventListener('change', () => {
            const file = mainInput.files[0];
            if (!file) return;

            // очищаем прошлый превью
            mainPreview.innerHTML = '';

            const reader = new FileReader();
            reader.onload = e => {
                mainPreview.insertAdjacentHTML(
                    'beforeend',
                    `<img src="${e.target.result}"
          class="img-fluid rounded"
          style="max-width: 200px;">`
                );
            };
            reader.readAsDataURL(file);
        });
    })();
</script>


<?php include __DIR__ . '/../../layouts/footer.php'; ?>