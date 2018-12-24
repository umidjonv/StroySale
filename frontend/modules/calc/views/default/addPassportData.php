<?php

$js = <<<JS
var field = "";
var passportId = "";
   $(document).on("click",".typeRadio", function() {
       var types = $(this).val();
       field = types;
      $.ajax({
           url: 'get-type',
           type: 'POST',
           data: "type="+field,
           success: function(res){
               $("#mainForm").html(res);
           },
           error: function(xhr){
               console.log(xhr.responseText);
           }
       });  
   });
   $(document).on("change","select", function() {
       $.getFieldVal();
      
   });
   
   $.getFieldVal = function() {
      var id,fieldName;
       id = $("#mainForm .id").val();
       fieldName = $("#mainForm .field").val();
      $.ajax({
           url: 'get-type-value',
           type: 'POST',
           data: "type="+field+"&id="+id+"&field="+fieldName,
           success: function(res){
               
               console.log(res);
                $("#mainForm  .value").val(res.val);
                passportId = res.id;
               
           },
           error: function(xhr){
               console.log(xhr.responseText);
           }
       });  
   };
   
   $(document).on("click","#saveType", function() {
       var formData = $("#mainForm").serialize();
       $.ajax({
           url: 'add-type',
           type: 'POST',
           data: formData+"&fieldType="+field+"&passportId="+passportId,
           success: function(res){
               $(".value").val("");
           },
           error: function(xhr){
               console.log(xhr.responseText);
           }
       });
   });
    // $.getFieldVal();
JS;
$this->registerJs($js);
?>
<div class="col-sm-6">
        <div class="form-group row">
            <label class="radio-inline">
                <input type="radio" class="typeRadio" id="category" value="category" name="type"> Категория   &nbsp;
            </label>
            <label class="radio-inline">
                <input type="radio" class="typeRadio" id="stuff" value="stuff" name="type"> Продукция   &nbsp;
            </label>
        </div>

    <form id="mainForm">

    </form>
</div>