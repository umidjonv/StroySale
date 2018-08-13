$(function(){
    $(".btn.btn-default").click(function(){
        alert("ALL");
    });
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
   
    

  
});





