$(function(){
    
    var pathUrl = window.location.pathname;
    
    var indexSlash = pathUrl.indexOf('/', 1);
    
    if(indexSlash!=-1)
    pathUrl = pathUrl.slice(0,indexSlash);
    pathUrl = pathUrl.replace('/','');
    
    
    $.controller = "";   
$.labelId = "";
    $.labelsName = [];
    switch(pathUrl)
    {
        case 'provider':
            $.controller = 'provider';   
            $.labelId = "providerId";
            $.labelsName = ["name", "address"];
            
            break;
        case 'invoice':
            $.controller = 'invoice';   
            $.labelId = "invoiceId";
            $.labelsName = ["name", "address"];
            break;
        
    }
    
 
    
    $.cols = [];
    switch(pathUrl)
    {
        case 'provider':
            
            $.cols = [
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

            ];
            break;
        case 'invoice':
            $.cols = [
                {"data":'invoiceId'},
                {"data":'invoiceDate'},
                {"data":'transportType'},
                {"data":'description'},
                {"data":'providerId'},
                {
                    "mDataProp":function ( source, type, val ) {
                if (type === 'set') {

                  return;
                }
                else if (type === 'display') {
                  return '<a href="#" class="btn btn-default" name="deleteRecord" id="delRecord'+source.invoiceId+'"><span class="oi oi-x"></span></a>';
                }

            },
                    "sDefaultContent": '<a href="#" class="btn btn-default" name="deleteRecord"><span class="oi oi-x"></span></a>'
                },

            ];
            break;
            
            
    }
    });
    
    