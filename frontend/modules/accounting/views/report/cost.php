<?
$js = <<<JS

    $('#btnSave').on('click', function(){
        $('#mainTable').DataTable().ajax.reload();
        console.log($("#mainForm1").serialize());
        // var url1 = "/accounting/report/get-costs";
        // var data = $(this).serialize();
        // $.ajax({
        //     url: url1,
        //     type: 'POST',
        //     data: data,
        //     success: function(res){
        //         var text = "";
        //         $.each(res.datas, function(key,index) {
        //           text += "<tr class='tableRow'>" +
        //                 "<td>" + index.accountDate + "</td>" +
        //                 "<td>" + index.comment + "</td>" +
        //                 "<td>" + index.expenseId + "</td>" +
        //                 "<td>" + index.clientName + "</td>" +
        //                 "<td>" + index.summ + "</td>" +
        //             "</tr>";
        //         });
        //         $("#mainTable tbody").html(text);
        //     },            
        //     error: function(xhr){
        //         console.log(xhr.responseText);
        //     }
        // });
        //
        // return false; 
    });    

$("#mainTable").DataTable({
        columns: [
            {"data":'accountDate'},
            {"data":'comment'},
            {"data":'expenseId'},
            {"data":'clientName'},
            {"data":'summ'}
        ],

        language: {
            decimal: "",
            emptyTable: "Нет данных в таблице",
            info: "Показать _START_ до _END_ из _TOTAL_ записей",
            infoEmpty: "Показать от 0 до 0 из 0 записей",
            infoFiltered: "(фильтровать по _MAX_)",
            infoPostFix: "",
            thousands: ",",
            lengthMenu: "Показать _MENU_ ",
            loadingRecords: "Загрузка...",
            processing: "Процесс...",
            search: "Поиск:",
            zeroRecords: "Нет соответствующих данных",
            paginate: {
                first: "Первый",
                last: "Конец",
                next: "След.",
                previous: "Пред."
            },
            aria: {
                sortAscending: ": Задать по нарастающему",
                sortDescending: ": Задать по убывающему"
            }
        },

         ajax: {
             url: "/accounting/report/get-costs",
             dataSrc: 'datas',
             data: function() {
               return $("#mainForm1").serialize();
             }
         },


    });
$(".dataTables_filter").hide();
    $.ajax({
        url: '/sold/expense/getsearch',
        type: 'POST',
        data:{'url':window.location.href},
        success: function(res){
            if(res!='not') {
                $('#searchInput').val(res);
                $('#mainTable').DataTable().search(res).draw();
                console.log(res.value + ' url:' + res.url);
            }

        },
        error: function(xhr){
            console.log(xhr.responseText);
        }

    });
$('#searchInput').keyup(function(){
                $('#mainTable').DataTable().search($(this).val()).draw() ;
            })
            $('#searchInput').change(function(){
                $('#mainTable').DataTable().search($(this).val()).draw() ;
            })
        
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
                    <button id="btnSave" type="button" class="btn btn-primary"><span class="oi oi-check"></span> Показать</button>

                </div>
            </div>
        </form>
    </div>

</div>

<div class="col">
    <table class="table" id="mainTable">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Дата расхода </th>
            <th scope="col">Услуга</th>
            <th scope="col">№ накладной</th>
            <th scope="col">Клиент</th>
            <th scope="col">Сумма</th>
        </tr>
        </thead>
    </table>

</div>