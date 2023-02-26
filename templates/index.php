<?php require 'document/header.php'; ?>
<div class="container">
    <form class="w-50 mx-auto mt-5" method="post" action="<?=href('user.create') ?>">
        <h2 class="text-center">Регистрация</h2>
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