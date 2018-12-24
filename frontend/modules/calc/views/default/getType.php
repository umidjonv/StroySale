<?


switch ($type){
    case "category":?>

        <div class="form-group row">
            <label for="" class="col-sm-4 control-label">Категория</label>
            <div class="col-sm-8">
                <select name="id" id="" class="form-control id">
                    <?foreach ($category as $val){ ;?>
                        <option value="<?=$val["categoryId"]?>"><?=$val["name"]?></option>
                    <?}?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-4 control-label">Поле</label>
            <div class="col-sm-8">
                <select name="fieldName" id="" class="form-control field">
                    <?foreach ($passField as $val){ ;?>
                        <option value="<?=$val["name"]?>"><?=$val["value"]?></option>
                    <?}?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-9">
                <input type="text" name="value" class="form-control value" placeholder="Текст" id="number">
            </div>
            <div class="col-sm-3">
                <button class="btn btn-default" id="saveType" type="button">Сохранить</button>
            </div>
        </div>
        <? break;
    case "address":?>

        <div class="form-group row">
            <label for="" class="col-sm-4 control-label">Адрес</label>
            <div class="col-sm-8">
                <select name="id" id="" class="form-control id">
                    <option value="1">Бирюлева</option>
                    <option value="2">Мытищи</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-4 control-label">Поле</label>
            <div class="col-sm-8">
                <select name="fieldName" id="" class="form-control field">
                    <?foreach ($passField as $val){ ;?>
                        <option value="<?=$val["name"]?>"><?=$val["value"]?></option>
                    <?}?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-9">
                <input type="text" name="value" class="form-control value" placeholder="Текст" id="number">
            </div>
            <div class="col-sm-3">
                <button class="btn btn-default" id="saveType" type="button">Сохранить</button>
            </div>
        </div>
        <? break;
    case "firm":?>

        <div class="form-group row">
            <label for="" class="col-sm-4 control-label">Фирма</label>
            <div class="col-sm-8">
                <select name="id" id="" class="form-control id">
                    <option value="1">Юг</option>
                    <option value="2">Высота</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-4 control-label">Поле</label>
            <div class="col-sm-8">
                <select name="fieldName" id="" class="form-control field">
                    <?foreach ($passField as $val){ ;?>
                        <option value="<?=$val["name"]?>"><?=$val["value"]?></option>
                    <?}?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-9">
                <input type="text" name="value" class="form-control value" placeholder="Текст" id="number">
            </div>
            <div class="col-sm-3">
                <button class="btn btn-default" id="saveType" type="button">Сохранить</button>
            </div>
        </div>
        <? break;
    case "stuff":?>

        <div class="form-group row">
            <label for="" class="col-sm-4 control-label">Продукция</label>
            <div class="col-sm-8">
                <select name="id" id="" class="form-control id">
                    <?foreach ($model as $val){ ;?>
                        <option value="<?=$val["stuffId"]?>"><?=$val["name"]?></option>
                    <?}?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-4 control-label">Поле</label>
            <div class="col-sm-8">
                <select name="fieldName" id="" class="form-control field">
                    <?foreach ($passField as $val){ ;?>
                        <option value="<?=$val["name"]?>"><?=$val["value"]?></option>
                    <?}?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-9">
                <input type="text" name="value" class="form-control value" placeholder="Текст" id="number">
            </div>
            <div class="col-sm-3">
                <button class="btn btn-default" id="saveType" type="button">Сохранить</button>
            </div>
        </div>
        <? break;
}

?>