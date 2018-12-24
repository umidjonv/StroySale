
<?php
$js = <<<JS


$("#mainTable").Custom({
    Columns:[
            {"data":'stuffProdId'},
            {"data":'prodName'},
            {"data":'cnt'},
            {"data":"measure"},
            {
                "mDataProp": function (source, type, val) {                    
                    if(source.idType==true)
                        return "Продукция" ;
                    else
                        return "Продукт";
                },
            },

        ],
     tableId:"#mainTable",
    refreshUrl:"/balance/refreshd",

});
JS;

$this->registerJs($js);
?>
<h3>Баланс</h3>
<div id="error"></div>



<div class="col">
    <table class="table" id="mainTable">
        <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Нименование</th>
            <th scope="col">Кол-во</th>
            <th scope="col">Ед.Изм.</th>
            <th scope="col">Тип</th>
        </tr>
        </thead>
    </table>

</div>


