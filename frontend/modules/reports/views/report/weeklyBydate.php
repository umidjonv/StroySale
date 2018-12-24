<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 25.11.2018
 * Time: 15:43
 */


?>
<style type="text/css">
    th
    {
        text-align: center;
    }

</style>
<h3>Недельный отчет по приходу/уходу товаров</h3>
<div class="row">
    <div class="col">

        <form action="\reports\report\weekcountreport" class="form-inline" method="post">
            <div class="input-group">
                <div class="input-group-prepend">
                <label class="input-group-text">Неделя </label>
                </div>

                <input type="date" class=" form-control" name="date" value="<?=isset($dateMain)? $dateMain:date('d.m.Y')?>"/>
            </div>
            <div class="input-group">
                <input type="submit" class="btn btn-info form-control" value="Обновить"/>
            </div>
        </form>
    </div>
</div>
<div class="row">
    <div class="col"><?=$table?></div>
</div>


