<?php
//$date = new DateTime($ddate);
//$week = $date->format("W");
$js = <<<JS

$('#clients').chosen({width: "100%"});
$('#products').chosen({width: "100%"});

$('#clientxls').on('click', function(){
    id = $('#clients').val();
    //$(this).attr('href','/reports/report/clientreportxls/'+id);
    
    $('form[name="mainForm"]').attr('action', '/reports/report/clientinvoicereportxls/'+id);
    $('form[name="mainForm"]').submit();
    
});
$('#refresh').on('click', function(){
    id = $('#clients').val();
    //$(this).attr('href','/reports/report/clientreportxls/'+id);
    
    $('form[name="mainForm"]').attr('action', '/reports/report/clientinvocereport');
    $('form[name="mainForm"]').submit();
    
});


JS;

$this->registerJs($js);


?>
<h3>
    Отчет по приходу товаров по клиентам
</h3>

<div class="row">
    <div class="col">

        <form name="mainForm" action="\reports\report\clientreport" class="form-inline" method="post">
            <div class="form-group col-1">
                <label for="clients">Клиент </label>
            </div>
            <div class="form-group col-2">
                <select class="form-control chosen" id="clients" name="clientId">
                    <option value="0">Все клиенты</option>
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

                <label>Продукт: </label>
            </div>
            <div class="form-group col-2">
                <select class="form-control chosen" id="products" name="productId">
                    <option value="0">Все продукты</option>
                    <?php foreach ($products as $product) {
                        if($productId==$product->productId)
                            echo '<option selected value="'.$product->productId.'">'.$product->name.'</option>';
                        else
                            echo '<option value="'.$product->productId.'">'.$product->name.'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group col-2">
                <label>Оплата: &nbsp;&nbsp;&nbsp; </label>
                <select class="form-control chosen" id="invoicePay" name="invoicePay">
                    <option value="cash" <? isset($invoicePay)&&$invoicePay=="cash"? "selected": ""?>>Наличные</option>
                    <option value="transfer" <? isset($invoicePay)&&$invoicePay=="transfer"? "selected": ""?>>Перечисление</option>
                </select>
            </div>

            <div class="form-group col-2">
                <label>С: &nbsp;&nbsp;&nbsp;</label>

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
            <th>Дата поставки</th>
            <th>Неделя</th>
            <th>Тип транспорта</th>
            <th>№ накладной</th>
            <th>Клиент</th>
            <th>№ договора</th>
            <th>код. товара</th>
            <th>Продукт</th>
            <th>ед. изм.</th>
            <th>Тип оплаты</th>
            <th>Кол.</th>
            <th>Цена</th>
            <th>Сумма</th>
            <th>Дата прихода</th>
            <th>Водитель</th>
            <th>Номер машины</th>
            <th>Тел.</th>
        </tr>
    </thead>
    <tbody>
    <?php $i=1;
    foreach ($model as $one): ?>
        <tr>
            <td><?= $i++; ?></td>
            <td><?= $one['deliveryDate']  ?></td>
            <td><?= date('W', strtotime($one['deliveryDate'])); ?></td>
            <td><?= $one['transportType'] ?></td>
            <td><?= $one['expNum'] ?></td>
            <td><?= $one['clientName'] ?></td>
            <td><?= $one['dogNum'] ?></td>
            <td><?= $one['productId'] ?></td>
            <td><?= $one['productName'] ?></td>
            <td><?= $one['measureName'] ?></td>
            <td><?= ($one['invoicePay'] == 'cash'?'Наличные':'Перечисление') ?></td>
            <td><?= $one['cnt'] ?></td>
            <td><?= (isset($one['exSumm'])&&$one['exSumm']!=0?$one['exSumm']/$one['cnt'] : 0) ?></td>
            <td><?= $one['exSumm'] ?></td>
            <td><?= $one['invoiceDate'] ?></td>
            <td><?= $one['driver'] ?></td>
            <td><?= $one['carNumber'] ?></td>
            <td><?= $one['phone'] ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>

</table>
    </div>
</div>