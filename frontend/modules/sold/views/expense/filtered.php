
<?php
$js = <<<JS

$.ajax({                                                                                     
                url: '/sold/delivery/getdrivers',                                                      
                type: 'POST',                                                                   
                                                                                               
                success: function(res){              
                    selected = $('#driverTemp').val()
                    str = '<option value="">Водитель</option>';
                    $.each(res, function(index, val){
                        if(selected == val.driver)
                        str += '<option selected value="'+val.driver+'">'+val.driver+'</option>';
                        else
                            {str += '<option  value="'+val.driver+'">'+val.driver+'</option>';}
                    });
                    $('#driverSelect').html(str);
                    
                    $('#driverSelect').chosen({width: "100%"});
                },                                                                              
                error: function(xhr){                                                           
                    console.log(xhr.responseText);                                              
                }                                                                               
            });
$('#productList').on('change', function(evt, params) {
         target = $(evt.target),
            $('#stuffProdId').val(target.val());
         $('#productGet').text($("#productList option:selected").text());   
         
            $('#idType').val(0);
            $('#modalWindow').modal('hide');
   
        });
     $('#stuffList').on('change', function(evt, params) {
         target = $(evt.target),
            $('#stuffProdId').val(target.val());
            $('#productGet').text($("#stuffList option:selected").text());   
            $('#idType').val(1);
            $('#modalWindow').modal('hide');
   
        });
     
     $('#productList').chosen({width: "100%"});
     $('#stuffList').chosen({width: "100%"});
     $('#clients').chosen({width: "100%"});
     $('#productGet').on('click', function(){
         
         $('#modalWindow').on('shown.bs.modal', function () {
              
            })
     });
      $('#packCountLabel').text($('#packSum').val());
      $('#faktCountLabel').text($('#faktSum').val());
      $('#ordersSummLabel').text($("#ordersSum").val());
JS;
$this->registerJs($js);
$packCountSum = 0;
$faktCountSum = 0;
$ordersSumm = 0;

$sess = \Yii::$app->session['filtrRefreshClients'];
$clientId = $sess['clientId'];
$idType = $sess['idType'];
$stuffProdId = $sess['stuffProdId'];

$prodName = $sess['prodName'];
$dateFrom = $sess['dateFrom'];
$dateTo = $sess['dateTo'];
$driver = $sess['driver'];

?>
<style>
    #clients_chosen
    {
        /*margin-top: -20px;margin-right: 10px;*/
    }

</style>
<h3>Поиск по товару и дате</h3>
<div id="error"></div>

<br>
<div class="row">
    <div class="col-9">
        <input type="hidden" id="driverTemp" name="driverTemp" value="<?=isset($driver)?$driver:''?>"/>
        <form id="form" action="/sold/expense/refreshdbyclients" method="post">

            <input type="hidden" id="idType" name="idType" value="<?=isset($idType)?$idType:''?>"/>
            <input type="hidden" id="stuffProdId" name="stuffProdId" value="<?=isset($stuffProdId)?$stuffProdId:''?>"/>
            <div class="row">
                <div class="col-2"><input class="form-control-plaintext" readonly value="Выбрать" /></div>
                <div class="col-4">
                    <a href="javascript:;" class="form-control-plaintext" id="productGet" data-toggle="modal" data-target=".bd-example-modal-lg"><?=isset($prodName)&&$prodName!=''?$prodName:'продукт/продукцию'?></a>
                </div>

                <div class="col-2">
                    <select class="form-control" name="clientId" id="clients" >
                        <option value="0">Выбрать клиента</option>
                        <?php
                        $str = "";

                        foreach($mClients as $tone){
                            if(isset($clientId)&&$clientId==$tone->clientId)
                                $str .= '<option selected value="'.$tone->clientId.'">'. $tone->clientName.'</option>';
                            else
                                $str .= '<option value="'.$tone->clientId.'">'. $tone->clientName.'</option>';


                        }
                        echo $str;
                        ?>

                    </select>
                </div>
                <div class="col-2">
                    <select id="driverSelect" name="driver">

                    </select>
                </div>

                <div class="col-2">
                    <button class="form-control btn btn-info" type="submit">Обновить</button>
                </div>
            </div>
            <br/>
            <div class="row">
                <div class="col-2">
                    <label>Выберите дату</label>
                </div>
                <div class="col-1">
                    C:
                </div>

                <div class="col-3">
                    <input type="date" value="<?=$dateFrom==''?date("Y-m-d", mktime(0, 0, 0, 1, 1, 2018)):$dateFrom?>"  class="form-control" name="dateFrom" id="inputFrom" placeholder="введите Дату"/>
                </div>
                <div class="col-1">
                    По:
                </div>

                <div class="col-3">
                    <input type="date" value="<?=$dateTo==''? date("Y-m-d"):$dateTo?>"  class="form-control right" name="dateTo" id="inputTo" placeholder="введите Дату"/>
                </div>
                <div class="col-2">
                    <a href="/sold/expense/clearfilter" class="btn btn-info" type="submit">Очистить фильтр</a>
                </div>
            </div>


        </form>
    </div>

    <div class="col-3">
        <ul style="list-style: none;">
            <li>Количество: <label id="packCountLabel">0</label></li>
            <li>Фактически: <label id="faktCountLabel">0</label></li>
            <li>Сумма: <label id="ordersSummLabel">0</label></li>
        </ul>
    </div>
</div>
<div class="col">
    <table class="table" id="mainTable">
        <thead class="thead-dark">
        <tr>
            <th scope="col"># продажи</th>
            <th scope="col">Дата</th>
            <th scope="col">Клиент</th>
            <th scope="col">№ дог.</th>
            <th scope="col">Наименование</th>
            <th scope="col">Количество</th>
            <th scope="col">По факту</th>
            <th scope="col">Цена</th>
            <th scope="col">Цена доставки</th>
            <th scope="col">Водитель</th>
            <th scope="col">Сумма</th>

        </tr>
        </thead>

        <tbody>

        <?php foreach ($records as $one): ?>
        <tr class="tableRowOrder">
            <td><?=$one['expenseId']?></td>
            <td><?=$one['expenseDate']?></td>
            <td><?=$one['clientName']?></td>
            <td><?=$one['dogNum']?></td>
            <td><?=$one['name']?><?=($one["addition"] != 0) ? " с ".$one["Pname"]."-".$one["additionCnt"] : ""?></td>
            <td><?=$one['packCount']?></td>
            <td><?=$one['faktCount']?></td>
            <td><?=$one['price']?></td>
            <td><?=$one['deliveryPrice']?></td>
            <td><?=$one['driver']?></td>
            <td><?=$one['orderSumm']?></td>

            <?php
            $packCountSum += $one['packCount'];
            $faktCountSum += $one['faktCount'];
            $ordersSumm += $one['orderSumm'];
            endforeach;?>
        </tr>


        </tbody>

    </table>

</div>
<input type="hidden" value="<?=$packCountSum?>" id="packSum"/>
<input type="hidden" value="<?=$faktCountSum?>" id="faktSum"/>
<input type="hidden" value="<?=$ordersSumm?>" id="ordersSum"/>
<div id="modalWindow" class="modal fade bd-example-modal-lg col-lg-12" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg col-lg-12">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Выбор продукта / продукции</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div id="">
                    <div class="col-sm-12">
                        <form action="" id="cardForm"  method="POST">
                            <div class="form-group row">

                                <div class="col-sm-1">
                                    <input type="text" name="idType" id="idType" class="invisible">
                                </div><div class="col-sm-1">
                                    <input type="text" name="stuffProdId" id="stuffProdId" class="invisible">
                                </div>
                            </div>
                            <div class="form-group row">

                                <div class="col-6">
                                    <label>Продукты</label>
                                    <select id="productList">
                                        <?php
                                        $str = "";

                                        foreach($mProduct as $tone){
                                            $str .= '<option value="'.$tone->productId.'">'. $tone->name.'</option>';
                                        }
                                        echo $str;
                                        ?>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label>Продукция</label>
                                    <select id="stuffList">
                                        <?php
                                        $str2 = "";
                                        foreach($mStuff as $tone){
                                            $str2 .= '<option value="'.$tone->stuffId.'">'. $tone->name.'</option>';
                                        }
                                        echo $str2;
                                        ?>

                                    </select>
                                </div>
                            </div>



                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
