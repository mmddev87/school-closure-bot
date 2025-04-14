<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بررسی تعطیلی شهر</title>
    <link rel="stylesheet" href="<?= BASE_URL . '/public/index.css' ?>">
</head>
<body>
    <div class="container">
        <h1>بررسی وضعیت تعطیلی شهر</h1>
        <div class="input-group">
            <input type="text" id="cityInput" placeholder="نام شهر را وارد کنید...">
            <button onclick="checkHoliday()">بررسی کن</button>
        </div>
        <div id="result"></div>
    </div>

    <script src="<?= BASE_URL . '/public/index.js' ?>"></script>
</body>
</html>