<?php require 'document/header.php'; ?>

<div class="container">
    <a href="<?=href('index') ?>">На главную</a>
    <a href="<?=href('login') ?>">Логин</a>
    <a href="<?=href('logout') ?>">Разлогин</a>
    <form class="w-50 mx-auto mt-5" method="post" action="/user/auth">
        <h2 class="text-center">Авторизация</h2>
        <div class="form-group mb-3">
            <label for="login">Логин</label>
            <input type="text" class="form-control" id="login" placeholder="Логин" name="login">
        </div>
        <div class="form-group mb-3">
            <label for="password">Пароль</label>
            <input type="password" class="form-control" id="password" placeholder="Пароль" name="password">
        </div>

        <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
    </form>
</div>

<?php require 'document/footer.php'; ?>