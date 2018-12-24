<link rel="stylesheet" href="/css/bootstrap.css">
<style>
    .textFont{
        font-size: 1.3vh;
    }
    .leftside{
        width: 64%;
        display: inline-block;
    }
    .rightside{
        font-weight: bold;
        width: 35%;
        display: inline-block;
    }

</style>    
<div class="" style="font-family: 'Times New Roman'; width:70vh ; height: 96.6vh">
    <div style="font-size: 4vh" class="text-center"><img src="/images/stroy-logo.png" style="width: 10vh" alt=""> <?=$from["fromName"]?></div>
    <hr style="border: 1px solid #000; margin-bottom: 0.5vh;margin-top: 1vh;">
    <hr style="border: 1px solid #000; margin: 0!important;">
    <div class="float-left text-left textFont">
        <div>
            ИНН/КПП <?=$from["inn"]?> / <?=$from["okpo"]?>
        </div>
        <div>
            Адрес: <?=$from["address"]?>
        </div>
    </div>
    <div class="float-right text-right textFont">
        <div>
            тел. <?=$from["tel"]?>
        </div>
        <div>
            e-mail: email@mail.com
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="textFont container" style="border: 1px solid #000;    margin-top: 4vh;">

        <div class="form-group">
            <div class="leftside">
                <span style="font-weight: bold; font-size: 2vh"><?=$res["category"]["title"]?></span>
            </div>
            <div class="rightside">
                <?=$number?>
            </div>
        </div>
        <div class="form-group">
            <div class="leftside">
                Наименование организации изготовителя:
            </div>
            <div class="rightside">
                <?=$from["fromName"]?>
            </div>
        </div>
        <div class="form-group">
            <div class="leftside">
                Адрес, телефон, факс изготовителя:
            </div>
            <div class="rightside">
                <?=$from["address"]?>
            </div>
        </div>
        <div class="form-group">
            <div class="leftside">
                Потрибитель:
            </div>
            <div class="rightside">
                <?=$adr?>
            </div>
        </div>
        <div class="form-group">
            <div class="leftside">
                Объем, м3:
            </div>
            <div class="rightside">
                <?=$v?>
            </div>
        </div>
        <div class="form-group">
            <div class="leftside">
                <?=$res["category"]["view"]?>
            </div>
            <div class="rightside">
                <?=$res["stuff"]["view"]?>
            </div>
        </div>
        <div class="form-group">
            <div class="leftside">
                <?=$res["category"]["stack"]?>
            </div>
            <div class="rightside">
                <?=$res["stuff"]["stack"]?>
            </div>
        </div>
        <div class="form-group">
            <div class="leftside">
                <?=$res["category"]["number"]?>
            </div>
            <div class="rightside">
                <?=$res["stuff"]["number"]?>
            </div>
        </div>
        <div class="form-group">
            <div class="leftside">
                Дата отправки растворной смеси:
            </div>
            <div class="rightside">
                <?=$sendDate?>
            </div>
        </div>
        <div class="form-group">
            <div class="leftside">
                <?=$res["category"]["mark"]?>
            </div>
            <div class="rightside">
                <?=$res["stuff"]["mark"]?>
            </div>
        </div>
        <div class="form-group">
            <div class="leftside">
                <?=$res["category"]["ratio"]?>
            </div>
            <div class="rightside">
                <?=$res["stuff"]["ratio"]?>
            </div>
        </div>
        <div class="form-group">
            <div class="leftside">
                <?=$res["category"]["strength"]?>
            </div>
            <div class="rightside">
                <?=$res["stuff"]["strength"]?>
            </div>
        </div>
        <div class="form-group">
            <div class="leftside">
                <?=$res["category"]["mobil"]?>
            </div>
            <div class="rightside">
                <?=$res["stuff"]["mobil"]?>
            </div>
        </div>
        <div class="form-group">
            <div class="leftside">
                <?=$res["category"]["mass,name"]?>
            </div>
            <div class="rightside">
                <?=$res["stuff"]["mass,name"]?> <?=($addition != "") ? ", ".$addition."-".$additionCnt : ""?>
            </div>
        </div>
        <div class="form-group">
            <div class="leftside">
                <?=$res["category"]["class"]?>
            </div>
            <div class="rightside">
                <?=$res["stuff"]["class"]?>
            </div>
        </div>
        <div class="form-group">
            <div class="leftside">
                <?=$res["category"]["size"]?>
            </div>
            <div class="rightside">
                <?=$res["stuff"]["size"]?>
            </div>
        </div>
        <div class="form-group">
            <div class="leftside">
                Выдан <?=$sendDate?>
            </div>
        </div>
        <div class="form-group">
        </div>
        <div class="form-group">
            <div class="">
                Начальник БСУ ______________________________Пириев Н.
            </div>
        </div>
        <div class="form-group">
            <img src="/images/sign.png" alt="" style="width: 11vh;    position: relative;    left: 20vh;    top: 1vh;">
            <div class="">
                Начальник лаборатории ______________________________Иванов И.А.
            </div>
        </div>
        <div class="form-group">
            <div class="" style="font-size: 1vh">
                Примечание: Конечные свойства бетона зависит от соблюдения СНиП ао укладке бетонной смеси и уходу за бетоном на мтроительном объекте.
            </div>
        </div>
    </div>


</div>