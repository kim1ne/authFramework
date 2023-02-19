<?php require 'templates/document/header.php' ?>

<h1>Парсинг сайта</h1>
<form method="post" action="<?=href('query.parse') ?>">
    <div class="form-group">
        <label for="exampleFormControlInput1">Адрес сайта</label>
        <input type="text" name="url" class="form-control" id="exampleFormControlInput1" placeholder="site.com">
    </div>
    <input type="submit" value="Парсить">

</form>

<?php require 'templates/document/footer.php' ?>
