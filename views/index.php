<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Автоподбор</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', sans-serif; }
        .hero {
            background: url('/img/hero.jpg') center/cover no-repeat;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-shadow: 0 0 10px rgba(0,0,0,0.6);
        }
        .feature-icon { font-size: 2rem; color: #0d6efd; }
        .carousel-inner img { object-fit: cover; height: 400px; }
        .card img { height: 200px; object-fit: cover; }
    </style>
</head>
<body>

<!-- Header -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="/">Автоподбор</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a href="/catalog" class="nav-link">Каталог</a></li>
                <li class="nav-item"><a href="/#contact" class="nav-link">Оставить заявку</a></li>
                <li class="nav-item"><a href="/login" class="nav-link">Админ</a></li>
            </ul>
        </div>
    </div>
</nav>

<?php if (!empty($_SESSION['flash'])): ?>
    <div class="container mt-3">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['flash']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Закрыть"></button>
        </div>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>


<!-- Hero Section -->
<header class="hero text-center position-relative">
    <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100"
         style="background:rgba(0,0,0,0.4);"></div>

    <div class="position-relative text-white px-3">
        <h1 class="display-3 fw-bold">Найдём авто вашей мечты</h1>
        <p class="lead bg-dark bg-opacity-50 d-inline-block px-3 py-1 rounded">
            Подбор, проверка, сопровождение — всё включено
        </p>
        <div class="mt-4">
            <a href="/catalog" class="btn btn-primary btn-lg">Смотреть каталог</a>
        </div>
    </div>
</header>

<!-- Features -->
<section class="py-5 bg-light text-center">
    <div class="container">
        <h2 class="mb-4">Наши преимущества</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-icon mb-3"><i class="bi bi-graph-up"></i></div>
                <h5>Большая база авто</h5>
                <p>Мы подбираем лучшие машины со всего рынка</p>
            </div>
            <div class="col-md-4">
                <div class="feature-icon mb-3"><i class="bi bi-clock-history"></i></div>
                <h5>Экономия времени</h5>
                <p>Мы всё делаем за вас — от звонков до проверки</p>
            </div>
            <div class="col-md-4">
                <div class="feature-icon mb-3"><i class="bi bi-award"></i></div>
                <h5>Гарантия качества</h5>
                <p>Только проверенные авто и прозрачные сделки</p>
            </div>
        </div>
    </div>
</section>

<!-- Process -->
<section class="py-5 text-center">
    <div class="container">
        <h2 class="mb-4">Как мы работаем</h2>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="feature-icon"><i class="bi bi-chat-dots"></i></div>
                <h6 class="mt-2">1. Анализ запроса</h6>
            </div>
            <div class="col-md-3">
                <div class="feature-icon"><i class="bi bi-search"></i></div>
                <h6 class="mt-2">2. Поиск и проверка</h6>
            </div>
            <div class="col-md-3">
                <div class="feature-icon"><i class="bi bi-file-earmark-text"></i></div>
                <h6 class="mt-2">3. Отчёт и подбор</h6>
            </div>
            <div class="col-md-3">
                <div class="feature-icon"><i class="bi bi-car-front"></i></div>
                <h6 class="mt-2">4. Сделка и выдача</h6>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">Отзывы клиентов</h2>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="carousel slide" data-bs-ride="carousel" id="reviewCarousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <blockquote class="blockquote">
                                <p>Нашли отличный Passat за 3 дня! Спасибо ребятам за честность и сопровождение!</p>
                                <footer class="blockquote-footer">Андрей Петров</footer>
                            </blockquote>
                        </div>
                        <div class="carousel-item">
                            <blockquote class="blockquote">
                                <p>Выбрали Kia Ceed с пробегом, машина как новая. Очень доволен.</p>
                                <footer class="blockquote-footer">Ирина Волкова</footer>
                            </blockquote>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#reviewCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#reviewCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact / Request -->
<section class="py-5 text-center bg-primary text-white" id="contact">
    <div class="container">
        <h2>Оставьте заявку на подбор</h2>
        <p>Наш менеджер свяжется с вами в ближайшее время</p>
        <form action="/request" method="post" class="row g-3 justify-content-center">
            <div class="col-md-4">
                <input type="text" name="name" class="form-control" placeholder="Ваше имя" required>
            </div>
            <div class="col-md-4">
                <input type="tel" id="phone" name="phone" class="form-control" placeholder="+7(123)-456-7890" required>
            </div>
            <div class="col-md-2">
                <button class="btn btn-light w-100">Отправить</button>
            </div>
        </form>
    </div>
</section>

<!-- Footer -->
<footer class="py-4 bg-dark text-light text-center">
    <div class="container">
        <small>© <?= date('Y') ?> Автоподбор. Все права защищены.</small>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


<script src="https://cdn.jsdelivr.net/npm/inputmask@5.0.8/dist/inputmask.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var phoneInput = document.querySelector('input[name="phone"]');
        Inputmask({
            mask: "+7(999)-999-9999",
            showMaskOnHover: false,
            showMaskOnFocus: true,
            clearIncomplete: true,
        }).mask(phoneInput);
    });
</script>


</body>
</html>
