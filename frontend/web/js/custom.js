var tabpertable;
$(function(){
    

    
        
    
    tabpertable =  $('#mainTable').DataTable({
        columns:[
            {"data":'providerId'},
            {"data":'name'},
            {"data":'address'},
            {
                "mDataProp":function ( source, type, val ) {
            if (type === 'set') {

              return;
            }
            else if (type === 'display') {
              return '<a href="#" class="btn btn-default" name="deleteRecord" id="delRecord'+source.providerId+'"><span class="oi oi-x"></span></a>';
            }

        },
                "sDefaultContent": '<a href="#" class="btn btn-default" name="deleteRecord"><span class="oi oi-x"></span></a>'
            },

        ],

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

        ajax: { url:'/provider/refreshd',
        dataSrc:'datas'},

        createdRow: function ( row, data, dataIndex, cells ) {
            $(row).addClass('tableRow');

        },
        initComplete:function(settings, json)
        {
            

        },
        drawCallback:function( settings ) {
            
            $('[name="deleteRecord"]').on('click', function() {
                $.DeleteRecord(this);

            });    
            $("tr.tableRow").on("click",function(){ 
                $.rowClick(this);
            });

        }

    
    });

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
        provId = $(elem).attr('id');
        provId= provId.replace('delRecord', '');
        yesFunc = function () {
                $.ajax({
                url: '/provider/delete',
                type: 'POST',
                data:{'id':provId},
                success: function(res){
                    var tableRow = $("td").filter(function() {
                        return $(this).text() == provId;
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

            $(this).val(tableData[i]);
            i++;
        });
    };
    
    $.emptyValues = function(){
        $('[name="providerId"]').val('0');
        $('[name="name"]').val('');
        $('[name="address"]').val('');
    }
    $('#btnNew').on('click', $.emptyValues());
    $('#mainForm1').on('submit', function(){
        var formId = $('#formID').val();
        var url1 = '/provider/save';
        if(formId == 0)
        {
            url1 = '/provider/new';
        
        }
        
        var data = $(this).serialize();
        $.ajax({
            url: url1,
            type: 'POST',
            data: data,
            success: function(res){
            $('#mainTable').DataTable().ajax.reload();
            //tabpertable.DataTable().draw();
            /*if(formId == 0)
            {
                var str = '<tr class="tableRow"><td>'+res.providerId+'</td>'+
                '<td>'+res.name+'</td>'+
                '<td>'+res.address+'</td>'
                    '<td><a href="#" class="btn btn-default" name="deleteRecord" id="delRecord'+res.providerId+'"><span class="oi oi-x"></span></a></td></tr>';

                $('#mainTable >  tbody:last').append(str);
            }else
            {
               var tableRow = $("td").filter(function() {
                    return $(this).text() == formId;
                }).closest("tr");
                
                $(tableRow).find('td:eq(1)').text(res.name);
                $(tableRow).find('td:eq(2)').text(res.address);
                
            
            }*/
            
                                
        
                
            },
            error: function(xhr){
                console.log(xhr.responseText);
            }
        });
        
        return false; 
    });    
  
  

});
