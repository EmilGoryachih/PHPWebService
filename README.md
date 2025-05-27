# README

## Локальный запуск

### 1. Клонирование


### 2. Создать файл настроек `.env` в корне (рядом с `docker-compose.yml`):

```
DB_HOST=db
DB_NAME=carsdb
DB_USER=user
DB_PASS=userpass
ADMIN_USER=admin
ADMIN_PASS_HASH=$2y$10$...   # хеш пароля администратора (password_hash)
```

### 3. Сборка и поднятие контейнеров

```bash
docker compose up -d --build
```

### 4. Установка зависимостей Composer

```bash
docker compose exec app composer install
```

### 5. Миграции: создание таблиц

```bash
docker compose exec db mysql -uuser -puserpass carsdb -e "
    CREATE TABLE IF NOT EXISTS cars (
      id INT AUTO_INCREMENT PRIMARY KEY,
      make VARCHAR(50) NOT NULL,
      model VARCHAR(50) NOT NULL,
      year YEAR NOT NULL,
      image VARCHAR(255) NOT NULL DEFAULT '',
      mileage INT NOT NULL DEFAULT 0,
      price DECIMAL(10,2) NOT NULL,
      description TEXT NOT NULL,
      created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
      updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

    CREATE TABLE IF NOT EXISTS car_images (
      id INT AUTO_INCREMENT PRIMARY KEY,
      car_id INT NOT NULL,
      filename VARCHAR(255) NOT NULL,
      is_main TINYINT(1) NOT NULL DEFAULT 0,
      FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

    CREATE TABLE IF NOT EXISTS requests (
      id INT AUTO_INCREMENT PRIMARY KEY,
      name VARCHAR(100) NOT NULL,
      phone VARCHAR(30) NOT NULL,
      created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
"
```

### 6. Доступ

* Каталог: [http://localhost:8080/](http://localhost:8080/)
* Админка (логин): [http://localhost:8080/login](http://localhost:8080/login)

---

## Деплой на сервере

1. **Копировать репозиторий** на сервер (Git, SFTP, rsync).
2. **Создать** в корне файл `.env` с теми же параметрами (урл БД, креды, хеш пароля).
3. **Установить** Docker & Docker Compose.
4. **Скопировать** в `/etc/nginx/conf.d/default.conf` ваш конфиг, чтобы `root /var/www/html/public;`.
5. **Поднять** контейнеры:

   ```bash
   docker compose pull
   docker compose up -d --build
   ```
6. **Создать таблицы** (как в локальном разделе) через `docker compose exec db mysql …`.
7. **Проверить** логи:

   ```bash
   docker compose logs -f app
   docker compose logs -f nginx
   ```
8. **Открыть** в браузере ваш домен.

---

## Полезные команды

* Перезапуск PHP-контейнера:

  ```bash
  docker compose restart app
  ```
* Остановить и удалить:

  ```bash
  docker compose down
  ```
* Открыть shell в контейнере:

  ```bash
  docker compose exec app bash
  ```
* Запустить миграции вручную (пример):

  ```bash
  docker compose exec db mysql -uuser -puserpass carsdb < migrations.sql
  ```
* Обновить зависимости:

  ```bash
  docker compose exec app composer update
  ```

---

Теперь у вас есть полностью рабочая локальная среда и инструкция по деплою на сервер. Удачной разработки!
