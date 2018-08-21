$(function(){
    
  $("tr.tableRow").click(function() {
    var tableData = $(this).children("td").map(function() {
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
                            Нет: function () {                           		                              $('body').append('<h1>Confirm Dialog Result: <i>No</i></h1>');
                            
                                $(this).dialog("close");
                            }
                        },
                        close: function (event, ui) {
                            $(this).remove();
                        }
                    });
    };    
    
  
});





