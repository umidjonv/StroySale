$(function(){
    //Invoice page
    //modal shown
    $('#mainModal').on('shown.bs.modal', function () {
      $('#myInput').trigger('focus');
    })
    
    
    $('button[name=modalButton]').click(function(){
        $("#contentModal").text($(this).text());
        
        //ajax content load data from server
    });
    
    //tempdata must changed with ajax data
    var tempdata =  {   1: "Цемент",
                        2:"Песок И5",
                        4:"Готовая смесь БЖ56",
                        5:"Плита 50х50СМ"
                    };
    
})