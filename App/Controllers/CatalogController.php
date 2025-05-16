<?php
namespace CarCatalog\App\Controllers;

use CarCatalog\App\Core\Connection;
use CarCatalog\App\Models\Car;

class CatalogController
{

    public function index(): void
    {
        $pdo  = Connection::make();
        $cars = Car::getAll();

        include __DIR__ . '/../../views/catalog/index.php';
    }

    public function view(int $id): void
    {
        $car    = Car::getById($id);
        $images = Car::getImages($id);

        include __DIR__ . '/../../views/catalog/view.php';
    }

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

        $data = file_get_contents($tmpName);
        $img = @imagecreatefromstring($data);
        if ($img === false) {
            move_uploaded_file($tmpName, $destPath);
            return $newName;
        }

        $w = 800;
        $h = (int)(imagesy($img) * ($w / imagesx($img)));
        $resized = imagescale($img, $w, $h);

        switch ($ext) {
            case 'png':
                imagepng($resized, $destPath);
                break;
            case 'gif':
                imagegif($resized, $destPath);
                break;
            default:
                imagejpeg($resized, $destPath, 85);
        }

        imagedestroy($img);
        imagedestroy($resized);

        return $newName;
    }

    public function store(): void
    {
        $this->checkCsrf();

        $data = $_POST;
        $pdo  = Connection::make();

        $mainImage = '';
        if (!empty($_FILES['images_main']['tmp_name']) && is_uploaded_file($_FILES['images_main']['tmp_name'])) {
            $mainImage = $this->handleImageUpload($_FILES['images_main']);
        }

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


    public function edit(int $id): void
    {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        $pdo = Connection::make();

        $stmt = $pdo->prepare("SELECT * FROM cars WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $car  = $stmt->fetch();

        $images = Car::getImages($id);

        include __DIR__ . '/../../views/admin/cars/edit.php';
    }


    public function update(int $id): void
    {
        $this->checkCsrf();
        $data = $_POST;
        $pdo  = Connection::make();

        $mainImage = $_FILES['images_main']['tmp_name']
            ? $this->handleImageUpload($_FILES['images_main'])
            : null;
        if ($mainImage === null) {
            $old = $pdo->prepare("SELECT image FROM cars WHERE id=:id");
            $old->execute(['id'=>$id]);
            $mainImage = $old->fetchColumn();
        }

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

    public function delete(int $id): void
    {
        $this->checkCsrf();

        $pdo  = Connection::make();
        $stmt = $pdo->prepare("DELETE FROM cars WHERE id = :id");
        $stmt->execute(['id' => $id]);

        header('Location: /catalog');
    }

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

        $stmt = $pdo->prepare("SELECT filename FROM car_images WHERE id = :id");
        $stmt->execute(['id' => $imageId]);
        $row = $stmt->fetch();

        $pdo->prepare("DELETE FROM car_images WHERE id = :id")
            ->execute(['id' => $imageId]);

        if ($row && !empty($row['filename'])) {
            $path = __DIR__ . '/../../public/img/' . $row['filename'];
            if (is_file($path)) {
                @unlink($path);
            }
        }

        $referer = $_SERVER['HTTP_REFERER'] ?? '/admin/cars/edit/' . $row['car_id'] ?? '/catalog';
        header('Location: ' . $referer);
        exit;
    }


}
