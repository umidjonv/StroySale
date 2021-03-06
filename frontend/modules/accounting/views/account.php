
<?php
$js = <<<JS

$("#mainTable").Custom({
    Columns:[
            {"data":'pointId'},
            {"data":'name'},
            {
                "mDataProp": function (source, type, val) {
                    if (type === 'set') {

                        return;
                    }
                    else if (type === 'display') {
                        return '<a href="#" class="btn btn-default" name="deleteRecord" id="delRecord' + source.pointId + '"><span class="oi oi-x"></span></a>';
                    }

                },
                "sDefaultContent": '<a href="#" class="btn btn-default" name="deleteRecord"><span class="oi oi-x"></span></a>'
            },

        ],
    refreshUrl:"/accounting/point/refreshd",
    deleteUrl:"/accounting/point/delete",
    saveUrl:"/accounting/point/save",
    newUrl:"/accounting/point/new"

});

        
JS;

$this->registerJs($js);
?>

<h3>Точка</h3>
<div id="error"></div>
<?php// print_r($models->all()[0]->name) ?>
<div class="row">
    <div class="col">
        <form class="mainForm" id="mainForm1" action="/point/save" method="POST">
            <div class="form-group row">
                <label for="formID" class="col-sm-2 col-form-label">ID</label>
                <div class="col-sm-6">
                    <input type="text" readonly class="form-control-plaintext" name="pointId" id="formID" value="0">
                </div>
            </div>
            <div class="form-group row pull-right">
                <label for="inputPointName" class="col-sm-2 col-form-label">Наименование точки</label>
                <div class="col-sm-5">
                    <input type="text"  class="form-control" name="name" id="inputPointName" placeholder="введите наименование">
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
                    <td scope="row"><?= $model->pointId ?></td>
                    <td><?= $model->name ?></td>
                    <td></td>
                </tr>

            <?php } ?>
            </tbody>
        <?php endif;?>
    </table>

</div>


