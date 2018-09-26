<?php
echo 'aaa';
?>
<h3>Продажа дополнительно</h3>
<div id="error"></div>
<div class="row">
    <div class="col-6">
        <form class="form">
            <div class="form-group row">
                <label for="staticId" class="col-sm-6 col-form-label">Номер накладной</label>
                <div class="col-sm-6">
                    <input type="text" readonly class="form-control-plaintext" id="vformID" value="<?=$model->expenseId; ?> ">
                </div>
            </div>
            <div class="form-group row">
                <label for="staticId" class="col-sm-6 col-form-label">Тип оплаты</label>
                <div class="col-sm-6">
                    <input type="text" readonly class="form-control-plaintext" id="vformID" value="<?=$model->paidType==0?'Наличные':($model->paidType==1?'Без наличный':'Перечисление'); ?> ">
                </div>
            </div>
            <div class="form-group row">
                <label for="staticId" class="col-sm-6 col-form-label">Сумма продажи</label>
                <div class="col-sm-6">
                    <input type="text" readonly class="form-control-plaintext" id="vformID" value="<?=$model->expSum; ?> ">
                </div>
            </div>
        </form>
    </div>
    <div class="col-6">

        <form class="mainForm" id="mainForm1" action="/sold/delivery/nakladnaya" method="POST">
            <div class="form-group row">
                <label for="staticAddress" class="col-sm-4 col-form-label">Адрес</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="address" id="formID" value="<?=$modelDelivery->address; ?> ">
                </div>
            </div>
            <div class="form-group row">
                <label for="staticAddress" class="col-sm-4 col-form-label">Тип</label>
                <div class="col-sm-6">
                    <div class="custom-control custom-radio">
                        <input type="radio" id="customRadio1" name="deliveryType" class="custom-control-input"  checked>
                        <label class="custom-control-label" for="customRadio1">Самовывоз</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input type="radio" id="customRadio2" name="deliveryType" class="custom-control-input">
                        <label class="custom-control-label" for="customRadio2">Доставка</label>
                    </div>


                </div>
            </div>
            <div class="form-group row">
                <label for="staticName" class="col-sm-4 col-form-label">Водитель</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="name" value="<?=$modelDelivery->name; ?> ">
                </div>
            </div>
            <div class="form-group row">
                <label for="staticDescription" class="col-sm-4 col-form-label">Описание</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="description" value="<?=$modelDelivery->description; ?> ">
                </div>
            </div>
            <div class="form-group row">
                <label for="staticPrice" class="col-sm-4 col-form-label">Стоимость доставки</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="price" value="<?=$modelDelivery->price; ?> ">
                </div>
            </div>


            <div class="form-group row">

                <div class="col">
                    <a id="btnNext" href="/sold/expense/step2" class="btn btn-warning"><span class="oi oi-check"></span> Продолжить</a>

                </div>
                <div class="col-2">
                    <a id="btnModal" href="#" class="btn btn-primary" data-toggle="modal" data-target="#mainModal" >Добавить товар</a>
                </div>

            </div>
        </form>
    </div>

</div>