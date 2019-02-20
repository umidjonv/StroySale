<? $balance = new \app\modules\accounting\models\Balancesum();
$js = <<<JS
    var accid = 0;
    $('#mainForm1').on('submit', function(){
        var url1 = "/accounting/report/get-balance";
        var data = $(this).serialize();
        $.ajax({
            url: url1,
            type: 'POST',
            data: data,
            success: function(res){
                var text = "";
                $.each(res.datas, function(key,index) {
                  text += "<tr class='tableRow' id='"+index.accountId+"'>" +
                        "<td>" + index.accountDate + "</td>" +
                        "<td>" + index.comment + "</td>" +
                        "<td>" + index.expenseId + "</td>" +
                        "<td>" + index.clientName + "</td>" +
                        "<td>" + index.summ + "</td>" +
                        "<td>" + ((index.accountType == 1) ? "Приход" : "Расход") + "</td>" +
                        "<td>" + index.identity + "</td>" +
                        "<td><a href='javascript:;' class='btn accountdelete' ><span class='oi oi-x'></span></a></td>" +
                    "</tr>";
                });
                text += "<tr>"+
                        "<td colspan='4'>Остаток</td>"+
                        "<td colspan='2'>"+res.balance+"</td>"+
                    "</tr>";
                console.log(text);
                $("#mainTable tbody").html(text);
            },            
            error: function(xhr){
                console.log(xhr.responseText);
            }
        });
        
        return false; 
    });    
    var deleteAcc = function() {
      $(this).dialog("close");
    };
    
    $.ConfirmDialog =  function (message, yesFunction){
    $('<div></div>').appendTo('body')
                    .html('<div><h6>'+message+'?</h6></div>')
                    .dialog({

                        modal: true, title: 'Сообщение', zIndex: 10000, autoOpen: true,
                        width: '250px', resizable: false,
                        buttons: {
                            ДА: yesFunction,
                            Нет: function () {  
                                //$('body').append('<h1>Confirm Dialog Result: <i>No</i></h1>');
                            
                                $(this).dialog("close");
                            }
                        },
                        close: function (event, ui) {
                            $(this).remove();
                        }
                    });
    };    
    
    $('#mainForm1').submit();
    $(document).on("click", ".accountdelete", function(){
        accid = $(this).parent().parent().attr("id");
        deleteAcc = function () {
            $.ajax({
                url: "delete",
                type: 'POST',
                data: "id="+accid,
                success: function(res){
                    $("#"+accid).remove();     
                },            
                error: function(xhr){
                    console.log(xhr.responseText);
                }
            });
            $(this).dialog("close");
        };  
        $.ConfirmDialog('Удалить запись', deleteAcc);
            
    });       
    
     function deleteAcc() {
        
    };
JS;

$this->registerJs($js);
?>

<h3>Расход по дате</h3>
<div class="row">
    <div class="col">
        <form class="mainForm" id="mainForm1" action="/category/save" method="POST">
            <div class="form-group row pull-right">
                <label for="inputPhone" class="col-sm-1 col-form-label">Дата с</label>
                <div class="col-sm-3">
                    <input type="date" value="<?=date("Y-m-d", strtotime("-1 month", strtotime(date('Y-m-d'))))?>"  class="form-control" name="dateFrom" id="inputPhone" placeholder="введите Дату">
                </div>
                <label for="inputPhone" class="col-sm-1 col-form-label">по</label>
                <div class="col-sm-3">
                    <input type="date" value="<?=date("Y-m-d",time()+86400)?>"  class="form-control" name="dateTo" id="inputPhone" placeholder="введите Дату">
                </div>
                <div class="col-sm-2">
                    <button id="btnSave" type="submit" class="btn btn-primary"><span class="oi oi-check"></span> Показать</button>

                </div>
            </div>
        </form>
    </div>

</div>
<? $date = "";?>
<div class="col">
    <table class="table" id="mainTable">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Дата расхода </th>
            <th scope="col">Услуга</th>
            <th scope="col">№ накладной</th>
            <th scope="col">Клиент</th>
            <th scope="col">Сумма</th>
            <th scope="col">Приход/Расход</th>
            <th scope="col">Идентификация</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <?php if(isset($models)):?>
            <tbody>
            <?php foreach($models as $model){?>
                <tr class="tableRow">
                    <td scope="row"><?= $model["accountDate"] ?></td>
                    <td><?= $model["comment"] ?></td>
                    <td><?= $model["expenseId"] ?></td>
                    <td><?= $model["clientName"] ?></td>
                    <td><?= $model["summ"] ?></td>
                    <td><?= ($model["accountType"] == 1) ? "Приход" : "Расход" ?></td>
                    <td><?= $model["identity"] ?></td>
                </tr>

            <?php $date = $model["accountDate"]; } ?>
            <tr>
                <td colspan="4">Остаток</td>
                <td colspan="2"><?=$balance->getSum($date)?></td>
            </tr>
            </tbody>
        <?php endif;?>
    </table>

</div>