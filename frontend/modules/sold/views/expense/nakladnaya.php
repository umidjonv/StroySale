<?php
$js = <<<JS
$('#fromsSelf').on('change', function(){
    $('#froms').val($(this).val()); 
});
$('#pogruzka').on('change', function(){
    if($(this).val()==0) 
    {
        $('[name="otvlicoPogruzka"]').val('Пириев Н.');        
    }else
        $('[name="otvlicoPogruzka"]').val('Ражабов Ф.');
    
    
});

JS;

$this->registerJs($js);


?>
<span class="alert-success"><?=$alert; ?></span>
<h3>Распечатать накладную</h3>
<div id="error"></div>
<div class="row">
    <div class="col">

        <form class="mainForm" id="mainForm2" action="/sold/expense/printn" method="POST">
            <input type="hidden" class="form-control" name="number" value="<?=$model->expenseId;?>"/>
            <input type="hidden" class="form-control" name="date" value="<?=$model->expenseDate;?>"/>
            <input type="hidden" class="form-control" name="director" value="<?=$model->from->director;?>"/>
            <input type="hidden" class="form-control" name="fromaddress" value="<?=$model->from->address;?>"/>

            <div class="form-group row">
                <label for="staticId" class="col-sm-2 col-form-label">Грузоотправитель</label>
                <div class="col-sm-6">
                    <select class="form-control" name="fromId" id="froms">
                        <?php
                        foreach ($mFrom as $item)
                        {
                            echo '<option selected value="'.$item->fromName.', '.$item->address.','.$item->schet.'">'.$item->fromName.', '.$item->address.','.$item->schet.'</option>';
                        }
                        ?>
                        <option value="">Заполнить самостоятельно</option>


                    </select>
                </div>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="sender" id="fromsSelf"/>
                </div>
            </div>
            <div class="form-group row">
                <label for="staticClients" class="col-sm-2 col-form-label">Клиент</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control-plaintext" readonly name="client" value="<?php echo str_replace("\"", "&#34;", $model->client!=null?($model->client->clientName. ', '. $model->client->address.' Р/С:'.$model->client->schet):"Прямая продажа" ) ?>"/>
                </div>
            </div>
            <div class="form-group row">
                <label for="staticId" class="col-sm-2 col-form-label">Водитель</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control-plaintext" readonly name="voditel" value="<?=$mDelivery->driver; ?>"/>
                </div>
            </div>

            <div class="form-group row">
                <label for="staticId" class="col-sm-2 col-form-label">Вид перевозки</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control-plaintext" name="perevozka" value="<?=($mDelivery->deliveryType==0?'Самовывоз':'Автотранспортом')  ?>"/>
                </div>
            </div>
            <div class="form-group row">
                <label for="staticId" class="col-sm-2 col-form-label">Адрес</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="address" value="<?=($mDelivery->address)  ?>"/>
                </div>
            </div>
            <div class="form-group row">
                <label for="staticId" class="col-sm-2 col-form-label">Пункт погрузки</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control-plaintext" readonly name="pogruzka" value="Город Москва , 1-я Северная Линия, дом 1, стр 16"/>

                </div>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="otvlicoPogruzka" value=""/>
                </div>

            </div>

            <div class="form-group row">

                <div class="col">
                    <a href="/sold/expense/step1" class="btn btn-success"><-новая продажа</a>
                    <a href="/sold/expense/inprocesslist" class="btn btn-success">список продаж</a>
                    <button type="submit" id="btnNext" class="btn btn-warning">Скачать</button>
                    <a href="/calc/default/getblank?number=36&adr=<?=$mDelivery->address?>&v=<?=$orders->packCount?>&sendDate=<?=date("Y-m-d",strtotime($model->expenseDate))?>&stuff=<?=$orders->stuffProdId?>&addition=<?=$orders->addition?>&additionCnt=<?=$orders->additionCnt?>" target="_blank" class="btn btn-success" >Паспорт</a>


                </div>


            </div>
        </form>
    </div>

