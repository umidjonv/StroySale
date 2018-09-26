
<?php
$js = <<<JS

$("#mainTable").Custom({
    Columns:[
            {"data":'measureId'},
            {"data":'name'},
            {
                "mDataProp": function (source, type, val) {
                    if (type === 'set') {
                        return;
                    }
                    else if (type === 'display') {
                        return '<a href="#" class="btn btn-default" name="deleteRecord" id="delRecord' + source.measureId + '"><span class="oi oi-x"></span></a>';
                    }
                },
                "sDefaultContent": '<a href="#" class="btn btn-default" name="deleteRecord"><span class="oi oi-x"></span></a>'
            },

        ],
    refreshUrl:"/calc/measure/refreshd",
    deleteUrl:"/calc/measure/delete",
    saveUrl:"/calc/measure/save",
    newUrl:"/calc/measure/new"

});

        
JS;

$this->registerJs($js);
?>

<h3>Ед.Изм.</h3>
<div id="error"></div>
<div class="row">
    <div class="col">
        <form class="mainForm" id="mainForm1" action="/measure/save" method="POST">
            <div class="form-group row">
                <label for="formID" class="col-sm-2 col-form-label">ID</label>
                <div class="col-sm-6">
                    <input type="text" readonly class="form-control-plaintext" name="measureId" id="formID" value="0">
                </div>
            </div>
            <div class="form-group row pull-right">
                <label for="inputMeasureName" class="col-sm-2 col-form-label">Наименование категории</label>
                <div class="col-sm-5">
                    <input type="text"  class="form-control" name="name" id="inputMeasureName" placeholder="введите наименование">
                </div>
                <div class="col-sm-2">
                    <button id="btnSave" type="submit" class="btn btn-primary"><span class="oi oi-check"></span> Сохранить</button>
                </div>
                <div class="col-sm-2">
                    <a href="#" id="btnNew" class="btn btn-primary"><span class="oi oi-plus"></span> Новый</a>

                </div>

            </div>
        </form>
    </div>

</div>
<br/>
<br/>


<div class="col">
    <table class="table" id="mainTable">
        <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Наименование</th>
            <th></th>
        </tr>
        </thead>
        <?php if(isset($models)):?>
            <tbody>

            <?php foreach($models->all() as $model){?>
                <tr class="tableRow">
                    <td scope="row"><?= $model->measureId ?></td>
                    <td><?= $model->name ?></td>
                    <td></td>
                </tr>

            <?php } ?>
            </tbody>
        <?php endif;?>
    </table>

</div>
            
 
