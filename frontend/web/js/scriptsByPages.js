$(function(){
    //Invoice page
    //modal shown
    $('#mainModal').on('shown.bs.modal', function () {
      $('#myInput').trigger('focus');
    });
    
    
    $('button[name=modalButton]').click(function(){
        $("#contentModal").text($(this).text());
        
        //ajax content load data from server
    });
    
    
    
    
    
})