<?php
//$date = new DateTime($ddate);
//$week = $date->format("W");
$js = <<<JS

$('#clients').chosen({width: "100%"});

$('#clientxls').on('click', function(){
    id = $('#clients').val();
    //$(this).attr('href','/reports/report/clientreportxls/'+id);
    
    $('form[name="mainForm"]').attr('action', '/reports/report/clientreportxls/'+id);
    $('form[name="mainForm"]').submit();
    
});
$('#refresh').on('click', function(){
    id = $('#clients').val();
    //$(this).attr('href','/reports/report/clientreportxls/'+id);
    
    $('form[name="mainForm"]').attr('action', '/reports/report/clientreport');
    $('form[name="mainForm"]').submit();
    
});


JS;

$this->registerJs($js);


?>
<h3>
    Отчет по клиентам
</h3>

<div class="row">
    <div class="col">

        <form name="mainForm" action="\reports\report\clientreport" class="form-inline" method="post">
            <div class="form-group col-1">
                <label for="clients">Клиент </label>
            </div>
            <div class="form-group col-2">
                <select class="form-control chosen" id="clients" name="clientId">
                    <?php foreach ($clients as $client) {
                        if($clientId==$client->clientId)
                        echo '<option selected value="'.$client->clientId.'">'.$client->clientName.'</option>';
                        else
                            echo '<option value="'.$client->clientId.'">'.$client->clientName.'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group col-1">

                <label>С: </label>
            </div>
            <div class="form-group col-2">

                <input type="date" class="form-control" name="dateFrom" value="<?= (isset($curDate)? $curDate: date('Y-m-01')) ?>"/>
            </div>
            <div class="form-group col-1">

                <a href="javascript:;" class="btn btn-info" value="Обновить" id="refresh">Обновить</a>
            </div>
            <div class="form-group col-1">

                <a href="javascript:;" class="btn btn-info" id="clientxls" >Скачать</a>
            </div>

        </form>
    </div>
</div>
<div class="row">
    <div class="col">
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>Неделя</th>
            <th>Дата</th>
            <th>№ накладной</th>
            <th>Фирма</th>
            <th>Клиент</th>
            <th>Операция</th>
            <th>Артикул</th>
            <th>Кол-во</th>
            <th>Цена</th>
            <th>Сумма</th>
            <th>Адрес</th>
            <th>Водитель</th>
            <th>Прим.</th>
        </tr>
    </thead>
    <tbody>
    <?php $i=1;
    foreach ($model as $one): ?>
        <tr>
            <td><?= $i++; ?></td>
            <td><?= date('W', strtotime($one['dateSum'])); ?></td>
            <td><?= $one['dateSum'] ?></td>
            <td><?= $one['expenseId'] ?></td>
            <td>НВЛ</td>

            <td><?= $one['clientName'] ?></td>
            <td><?= $one['typeS'] ?></td>
            <td><?= $one['description'] ?></td>
            <td><?= $one['kol'] ?></td>
            <td><?= $one['cena'] ?></td>
            <td><?= $one['summ'] ?></td>
            <td><?= $one['address'] ?></td>
            <td><?= $one['driver'] ?></td>
            <td><?= $one['prim'] ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>

</table>
    </div>
</div>