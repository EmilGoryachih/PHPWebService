<?php
namespace CarCatalog\App\Controllers;

use CarCatalog\App\Core\Connection;
use CarCatalog\App\Models\Car;

class CatalogController
{
    // ------------------------------------------------------------------------
    // 1) Список всех машин
    // ------------------------------------------------------------------------
    public function index(): void
    {
        // Берём PDO и вытаскиваем все записи
        $pdo  = Connection::make();
        $cars = Car::getAll();  // внутри модели тоже вызывается Connection::make()

        include __DIR__ . '/../../views/catalog/index.php';
    }

    // ------------------------------------------------------------------------
    // 2) Детальная страница одной машины
    // ------------------------------------------------------------------------
    public function view(int $id): void
    {
        $car    = Car::getById($id);          // карточка авто
        $images = Car::getImages($id);        // все его фото

        include __DIR__ . '/../../views/catalog/view.php';
    }



    // ------------------------------------------------------------------------
    // ------------------------------------------------------------------------
    // 3) Форма создания новой машины (GET /admin/cars/create)
    // ------------------------------------------------------------------------
    public function create(): void
    {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        include __DIR__ . '/../../views/admin/cars/create.php';
    }

    /**
     * @param array $file — элемент $_FILES['image_file']
     * @return string имя сохранённого файла
     */
    protected function handleImageUpload(array $file): string
    {
        $tmpName = $file['tmp_name'];
        $origName = basename($file['name']);
        $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
        $newName = 'car_' . uniqid() . '.' . $ext;
        $destPath = __DIR__ . '/../../public/img/' . $newName;

        // Читаем содержимое и создаём GD-изображение любым форматом
        $data = file_get_contents($tmpName);
        $img = @imagecreatefromstring($data);
        if ($img === false) {
            // Если не удалось распознать — просто скопируем без изменения
            move_uploaded_file($tmpName, $destPath);
            return $newName;
        }

        // Масштабируем (например, ширина 800, высота сохраняется пропорционально)
        $w = 800;
        $h = (int)(imagesy($img) * ($w / imagesx($img)));
        $resized = imagescale($img, $w, $h);

        // Сохраняем в зависимости от формата
        switch ($ext) {
            case 'png':
                imagepng($resized, $destPath);
                break;
            case 'gif':
                imagegif($resized, $destPath);
                break;
            default:
                // JPEG по умолчанию с качеством 85%
                imagejpeg($resized, $destPath, 85);
        }

        // Чистим память
        imagedestroy($img);
        imagedestroy($resized);

        return $newName;
    }


    // ------------------------------------------------------------------------
    // 4) Сохранение новой машины (POST /admin/cars/store)
    // ------------------------------------------------------------------------
    public function store(): void
    {
        $this->checkCsrf();

        $data = $_POST;
        $pdo  = Connection::make();

        // 1) Загрузка главного фото в cars.image
        $mainImage = '';
        if (!empty($_FILES['images_main']['tmp_name']) && is_uploaded_file($_FILES['images_main']['tmp_name'])) {
            $mainImage = $this->handleImageUpload($_FILES['images_main']);
        }

        // 2) Сохраняем запись с главной картинкой
        $stmt = $pdo->prepare("
        INSERT INTO cars 
          (make, model, year, mileage, price, image, description)
        VALUES
          (:make, :model, :year, :mileage, :price, :image, :description)
    ");
        $stmt->execute([
            'make'        => $data['make'],
            'model'       => $data['model'],
            'year'        => $data['year'],
            'mileage'     => $data['mileage'],
            'price'       => $data['price'],
            'image'       => $mainImage,
            'description' => $data['description'] ?? '',
        ]);

        $carId = $pdo->lastInsertId();

        // 3) Сохраняем дополнительные фото
        foreach ($_FILES['images_extra']['tmp_name'] as $i => $tmp) {
            if (is_uploaded_file($tmp)) {
                $file = [
                    'tmp_name' => $tmp,
                    'name'     => $_FILES['images_extra']['name'][$i],
                    'type'     => $_FILES['images_extra']['type'][$i],
                    'error'    => $_FILES['images_extra']['error'][$i],
                    'size'     => $_FILES['images_extra']['size'][$i],
                ];
                if ($fn = $this->handleImageUpload($file)) {
                    $pdo->prepare("
                    INSERT INTO car_images (car_id, filename)
                    VALUES (:car, :fn)
                ")->execute(['car'=>$carId,'fn'=>$fn]);
                }
            }
        }

        header('Location: /catalog');
        exit;
    }




    // ------------------------------------------------------------------------
    // 5) Форма редактирования (GET /admin/cars/edit/{id})
    // ------------------------------------------------------------------------
    public function edit(int $id): void
    {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        $pdo = Connection::make();

        // машина
        $stmt = $pdo->prepare("SELECT * FROM cars WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $car  = $stmt->fetch();

        // все изображения этой машины
        $images = Car::getImages($id);   // [{id, filename, is_main}, …]

        include __DIR__ . '/../../views/admin/cars/edit.php';
    }

    // ------------------------------------------------------------------------
    // 6) Сохранение изменений (POST /admin/cars/update/{id})
    // ------------------------------------------------------------------------
    public function update(int $id): void
    {
        $this->checkCsrf();
        $data = $_POST;
        $pdo  = Connection::make();

        // 1) Загрузка нового главного фото
        $mainImage = $_FILES['images_main']['tmp_name']
            ? $this->handleImageUpload($_FILES['images_main'])
            : null;
        if ($mainImage === null) {
            // если не загрузили, берём старое
            $old = $pdo->prepare("SELECT image FROM cars WHERE id=:id");
            $old->execute(['id'=>$id]);
            $mainImage = $old->fetchColumn();
        }

        // 2) Обновляем cars
        $stmt = $pdo->prepare("
        UPDATE cars SET
          make        = :make,
          model       = :model,
          year        = :year,
          mileage     = :mileage,
          price       = :price,
          image       = :image,
          description = :description
        WHERE id = :id
    ");
        $stmt->execute([
            'make'        => $data['make'],
            'model'       => $data['model'],
            'year'        => $data['year'],
            'mileage'     => $data['mileage'],
            'price'       => $data['price'],
            'image'       => $mainImage,
            'description' => $data['description'] ?? '',
            'id'          => $id,
        ]);

        // 3) Дополнительные фото (добавляем новые; старые оставляем)
        foreach ($_FILES['images_extra']['tmp_name'] as $i => $tmp) {
            if (is_uploaded_file($tmp)) {
                $file = [
                    'tmp_name' => $tmp,
                    'name'     => $_FILES['images_extra']['name'][$i],
                    'type'     => $_FILES['images_extra']['type'][$i],
                    'error'    => $_FILES['images_extra']['error'][$i],
                    'size'     => $_FILES['images_extra']['size'][$i],
                ];
                if ($fn = $this->handleImageUpload($file)) {
                    $pdo->prepare("
                    INSERT INTO car_images (car_id, filename)
                    VALUES (:car, :fn)
                ")->execute(['car'=>$id,'fn'=>$fn]);
                }
            }
        }

        header('Location: /catalog');
        exit;
    }


    // ------------------------------------------------------------------------
    // 7) Удаление записи (POST /admin/cars/delete/{id})
    // ------------------------------------------------------------------------
    public function delete(int $id): void
    {
        $this->checkCsrf();

        $pdo  = Connection::make();
        $stmt = $pdo->prepare("DELETE FROM cars WHERE id = :id");
        $stmt->execute(['id' => $id]);

        header('Location: /catalog');
    }

    // ------------------------------------------------------------------------
    // Вспомогательный метод для проверки CSRF
    // ------------------------------------------------------------------------
    private function checkCsrf(): void
    {
        $token = $_POST['csrf_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            die('Invalid CSRF token');
        }
    }

    /**
     * POST /admin/cars/image/delete/{id}
     */
    public function deleteImage(int $imageId): void
    {
        $this->checkCsrf();

        $pdo = Connection::make();

        // 1) Получаем имя файла
        $stmt = $pdo->prepare("SELECT filename FROM car_images WHERE id = :id");
        $stmt->execute(['id' => $imageId]);
        $row = $stmt->fetch();

        // 2) Удаляем запись из БД
        $pdo->prepare("DELETE FROM car_images WHERE id = :id")
            ->execute(['id' => $imageId]);

        // 3) Удаляем сам файл, если он есть
        if ($row && !empty($row['filename'])) {
            $path = __DIR__ . '/../../public/img/' . $row['filename'];
            if (is_file($path)) {
                @unlink($path);
            }
        }

        // 4) Возвращаем пользователя назад
        $referer = $_SERVER['HTTP_REFERER'] ?? '/admin/cars/edit/' . $row['car_id'] ?? '/catalog';
        header('Location: ' . $referer);
        exit;
    }


}
