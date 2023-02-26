<?php require 'document/header.php'; ?>

<ul class="list-group w-25">
    <?php foreach ($categories as $category): ?>
        <li class="list-group-item"><a href="/category/<?=$category['id'] ?>"><?=$category['category_name'] ?></a></li>
    <?php endforeach; ?>
</ul>

<?php require 'document/footer.php'; ?>
