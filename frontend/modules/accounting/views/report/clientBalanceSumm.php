<? $balance = new \app\modules\accounting\models\Clientbalancesum();


$this->registerJs($js);
?>

<h3>Расход по дате</h3>
<div class="row">
    <div class="col">
        <form class="" id="mainForm2" method="POST">
            <div class="form-group row pull-right">
                <label for="inputPhone" class="col-sm-1 col-form-label">Дата с</label>
                <div class="col-sm-3">
                    <input type="date" value="<?=date("Y-m-d")?>"  class="form-control" name="dateFrom" id="inputPhone" placeholder="введите Дату">
                </div>
                <label for="inputPhone" class="col-sm-1 col-form-label">по</label>
                <div class="col-sm-3">
                    <input type="date" value="<?=date("Y-m-d",time()+86400)?>"  class="form-control" name="dateTo" id="inputPhone" placeholder="введите Дату">
                </div>
                <div class="col-sm-2">
                    <button id="btnClientSave" type="button" class="btn btn-primary"><span class="oi oi-check"></span> Показать</button>

                </div>
            </div>
        </form>
    </div>

</div>
<? $date = "";?>
<div class="col">
    <table class="table" id="mainTable2">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Дата расхода </th>
            <th scope="col">Назначение</th>
            <th scope="col">Сумма</th>
            <th scope="col">Приход/Расход</th>
        </tr>
        </thead>
        <?php if(isset($models)):?>
            <tbody>
            <?php foreach($models->all() as $model){?>
                <tr class="tableRow">
                    <td scope="row"><?= $model->accountDate ?></td>
                    <td><?= $model->comment ?></td>
                    <td><?= $model->summ ?></td>
                    <td><?= ($model->accountType == 1) ? "Приход" : "Расход" ?></td>
                </tr>

                <?php $date = $model->accountDate; } ?>
            <tr>
                <td colspan="2">Остаток</td>
                <td colspan="2"><?=$balance->getSum($date,$clientId)?></td>
            </tr>
            </tbody>
        <?php endif;?>
    </table>

</div>