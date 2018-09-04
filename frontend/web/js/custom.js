var tabpertable;

$.fn.Custom = function ( opts ) {
    var maintable = $(this);
    tabpertable = maintable.DataTable({
        columns: opts.Columns,

        language: {
            decimal: "",
            emptyTable: "Нет данных в таблицы",
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
            url: opts.refreshUrl,
            dataSrc: 'datas'
        },
        columnDefs:opts.columnDefs,

        createdRow: function (row, data, dataIndex, cells) {
            $(row).addClass('tableRow');

        },
        initComplete: function (settings, json) {


        },
        drawCallback: function (settings) {

            $('[name="deleteRecord"]').on('click', function () {
                $.DeleteRecord(this);

            });
            $("tr.tableRow").on("click", function () {
                $.rowClick(this);
            });

        }


    });
     
    
     
    /*
    tabpertable =  $('#mainTable').DataTable({
        columns:$.cols,

        language: {
            decimal: "",
            emptyTable: "Нет данных в таблицы",
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

        ajax: { url:'/'+$.controller+'/refreshd',
        dataSrc:'datas'},

        createdRow: function ( row, data, dataIndex, cells ) {
            $(row).addClass('tableRow');

        },
        initComplete:function(settings, json)
        {
            

        },
        drawCallback:function( settings ) {
            
            $('[name="deleteRecord"]').on('click', function() {
                $.DeleteRecord(this, $.controller);

            });    
            $("tr.tableRow").on("click",function(){ 
                $.rowClick(this);
            });

        }

    
    });*/

var yesFunc = function () {$(this).dialog("close");};   
var noFunc = function () {$(this).dialog("close");};

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
    
    
    $.DeleteRecord = function(elem){
        eId = $(elem).attr('id');
        eId= eId.replace('delRecord', '');
        yesFunc = function () {
                $.ajax({
                url: opts.deleteUrl,
                type: 'POST',
                data:{'id':eId},
                success: function(res){
                    var tableRow = $("td").filter(function() {
                        return $(this).text() == eId;
                    }).closest("tr");
                    $(tableRow).remove();
                    $('#mainTable').DataTable().ajax.reload();
                    console.log(res+ " record deleted");
                    $.emptyValues();
                },
                error: function(xhr){
                    console.log(xhr.responseText);
                }
            });
            $(this).dialog("close");
        };  
        $.ConfirmDialog('Удалить запись', yesFunc);
        
    };
    $.rowClick = function(elem) {
        var tableData = $(elem).children("td").map(function() {
            return $(this).text();
        }).get();

        console.log(tableData);

        var $inputs = $(".mainForm :input");

          var values = {};
          var i=0;
        $inputs.each(function() {
            //alert($(this).attr('name')+''+tableData[i]);
            $(this).val(tableData[i]);
            i++;
        });
    };
    
    $.emptyValues = function(){
        
         $("#mainForm1")[0].reset();
        $("#formID").val(0);
    };
    $('#btnNew').on('click', function(){
        $.emptyValues();
    });
    $('#mainForm1').on('submit', function(){
        var formId = $('#formID').val();
        var url1 = opts.saveUrl;
        if(formId == 0)
        {
            url1 = opts.newUrl;
        
        }
        
        var data = $(this).serialize();
        $.ajax({
            url: url1,
            type: 'POST',
            data: data,
            success: function(res){
            $('#mainTable').DataTable().ajax.reload();
            $.emptyValues();
            
                                
        
                
            },
            error: function(xhr){
                console.log(xhr.responseText);
            }
        });
        
        return false; 
    });    
            

}
