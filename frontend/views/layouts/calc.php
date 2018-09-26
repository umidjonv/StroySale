<?php $this->beginContent('@app/views/layouts/stroy.php'); ?>
<div class="row">
    <div class="col-sm-12">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="/calc/product">Продукты</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/calc/measure">Ед.Изм.</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/calc/category">Категории</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="/calc/stuff">Рецепты</a>
                        </li>
                    </ul>
                </div>
</div>
<?=$content?>
<?php $this->endContent(); ?>