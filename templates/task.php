<?php require 'document/header.php'; ?>

<div class="d-flex flex-wrap justify-content-center">

    <?php foreach ($tasks as $task): ?>
        <?php
        $boolTask = true;
        if ($task['status'] == 0) {
            $boolTask = false;
        }

        $alert = 'danger';
        if ($boolTask) {
            $alert = 'success';
        }

        $btnTxt = ($boolTask) ? 'Взять в работу обратно' : 'Выполнить';
        ?>

        <div class="card text-center mb-5 mx-3 alert alert-<?=$alert ?>" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title"><?=$task['task_name'] ?></h5>
                <form action="<?=href('task.update', ['(\d+)' => $task['id']]) ?>" method="post">
                    <input type="hidden" name="_method" value="put" />
                    <button class="btn btn-primary" type="submit"><?=$btnTxt ?></button>
                </form>
            </div>
        </div>

    <?php endforeach; ?>

</div>

<?php require 'document/footer.php'; ?>
