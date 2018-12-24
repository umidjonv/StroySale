
<?php
$js = <<<JS
    $.getBlank = function() {
    var sendData = $("#passportForm").serialize();
    console.log(sendData);
       $.ajax({
            url: 'getblank',
            type: 'GET',
            data: sendData,
            success: function(res){
                $("#data").html(res);
            },
            error: function(xhr){
                console.log(xhr.responseText);
            }
        });
    };
    $.getBlank();
    $("#stuff").chosen();
    $(document).on("change","select", function() {
      $.getBlank();
    });
    
    $(document).on("change","input", function() {
      $.getBlank();
    });
    
     
JS;
$this->registerJs($js);
?>
<div class="row container">
    <div class="col-sm-5">
        <form action="getblank" method="get" id="passportForm">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="№" name="number" >
            </div>
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Адрес" name="adr">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Объем" name="v">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Дата" name="sendDate">
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-4 control-label">Продукция</label>
                <div class="col-sm-8">
                    <select name="stuff" class="form-control">
                        <?foreach ($model as $key => $val){
                            if($key == 0){?>
                                <option selected value="<?=$val["stuffId"]?>"><?=$val["name"]?></option>
                            <?}
                            else{?>
                                <option value="<?=$val["stuffId"]?>"><?=$val["name"]?></option>
                            <?}?>
                        <?}?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-4 control-label">Добавки</label>
                <div class="col-sm-5">
                    <select name="addition" class="form-control">
                        <option selected value="">Без добавок</option>
                        <?foreach ($addition as $key => $val){?>
                            <option value="<?=$val["productId"]?>"><?=$val["name"]?></option>
                        <?}?>
                    </select>
                </div>
                <div class="col-sm-3">
                    <input type="text" name="additionCnt" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-success btnPrint" type="submit">Печать</button>
            </div>
        </form>
    </div>

    <div id="data" class="col-sm-7">

    </div>
</div>
