
var importar = true;
var filenameX = '';
var extensionX = '';
var osMonthBody = $("#tbody-qtd-os-mes");
var osStatusBody = $("#tbody-qtd-os-status");
var osRankingBody = $("#tbody-ranking");
var osCityStatesBody = $("#tbody-qtd-os-cid-uf");

function getOsMonth(tbname) {
    
    $.ajax({

        type: "GET",
        url: "./class/Rest.php",
        data: "tbname=" + tbname + "&target=os_month",
        dataType: "json",

        success: function(rsp) {
            
            osMonthBody.html("");
            $.each(rsp.os_month, function(i, obj){
                
                osMonthBody.append("<tr>\n\
                                <td>"+obj.mesOs+"</td>\n\
                                <td class='text-right'>"+obj.qtd+"</td>\n\
                            </tr>");
                
            });
            
        },

        error: function(err) {
            console.error("Error", err);
            $("#container").html(err.responseText);
        }

    });
}

function getOsStatus(tbname) {
    
    $.ajax({

        type: "GET",
        url: "./class/Rest.php",
        data: "tbname=" + tbname + "&target=os_status",
        dataType: "json",

        success: function(rsp) {
            
            osStatusBody.html("");
            $.each(rsp.os_status, function(i, obj){
                
                osStatusBody.append("<tr>\n\
                                <td>"+obj.statusOs+"</td>\n\
                                <td class='text-right'>"+obj.qtd+"</td>\n\
                            </tr>");
            });
            
        },

        error: function(err) {
            console.error("Error", err);
            $("#container").html(err.responseText);
        }

    });
}

function getOsRanking(tbname) {
    
    $.ajax({

        type: "GET",
        url: "./class/Rest.php",
        data: "tbname=" + tbname + "&target=os_ranking",
        dataType: "json",

        success: function(rsp) {
            
            osRankingBody.html("");
            $.each(rsp.os_ranking, function(i, obj){
                
                osRankingBody.append("<tr>\n\
                                <td>"+obj.produto+"</td>\n\
                                <td>"+obj.modelo+"</td>\n\
                                <td class='text-right'>"+obj.qtd+"</td>\n\
                            </tr>");
                
            });
            
        },

        error: function(err) {
            console.error("Error", err);
            $("#container").html(err.responseText);
        }

    });
}

function getOsCityState(tbname) {
    
    $.ajax({

        type: "GET",
        url: "./class/Rest.php",
        data: "tbname=" + tbname + "&target=os_city_state",
        dataType: "json",

        success: function(rsp) {
            
            osCityStatesBody.html("");
            $.each(rsp.os_city_state, function(i, obj){
                
                osCityStatesBody.append("<tr>\n\
                                <td>"+obj.cidade+"</td>\n\
                                <td>"+obj.uf+"</td>\n\
                                <td class='text-right'>"+obj.qtd+"</td>\n\
                            </tr>");
                
            });
            
        },

        error: function(err) {
            console.error("Error", err);
            $("#container").html(err.responseText);
        }

    });
}

function importXlsFile(xfile) {

    $.ajax({

        type: "GET",
        url: "./class/ReaderSaveExcel.php",
        data: "xls=" + xfile,
        dataType: "json",

        success: function(rsp) {

            if(rsp.import === true) {
            
                viewDataFromDatabase(rsp.tableName);

                $("#container-import").addClass('hide');
                $("#container-import").hide();
                
                $("#span_qty_os").html(rsp.qtyOs);
                $("#span_qty_success").html(rsp.qtySuccess);
                $("#span_qty_error").html(rsp.qtyError);
                $("#span_xlsname").html(xfile);
                
                $("#container-fastdash").show();

            } else {
                alert('Erro na importação do arquivo\n\n' + rsp.error);
            }

        },

        error: function(err) {
            console.error("Error", err);
            $("#container").html(err.responseText);
        }

    });
}

function viewDataFromDatabase(tbname) {
    getOsMonth(tbname);
    getOsStatus(tbname);
    getOsRanking(tbname);
    getOsCityState(tbname);
}

$(document).ready(function(){
    
    $("#container-fastdash").hide();
    
    $("#btn-choose-file").on('click', function(){
        
        $("#xlsfilename").val('');
        $("#container-import").removeClass('hide');
        $("#container-import").show();
        
    });
    
    $("#btn-cancel-import").on('click', function(){
        
        $("#container-import").addClass('hide');
        $("#container-import").hide();
        
    });
    
    $("#xlsfilename").change(function(){
        filenameX = $("#xlsfilename").val();
        extensionX = filenameX.split('.');
        
        if(extensionX[1] !== 'xlsx' && extensionX[1] !== 'xls') {
            alert('Arquivo Invalido !');
            $("#xlsfilename").val('');
        }
    });
    
    $("#btn-import").on('click', function(){
        
        if(!filenameX){
            return false;
        }
        
        var formData = new FormData($("form[name='formX']")[0]);
        
        $.ajax({

            type: "POST",
            url: "./class/UploadXlsFiles.php",
            data: formData,
            dataType: "json",
            processData: false,
            contentType: false,

            success: function(rsp) {
                
                if(rsp.upload === true) {

                    importXlsFile(rsp.upfile);

                } else {
                    alert('Erro no upload do arquivo');
                }

            },

            error: function(err) {
                console.error("Error", err);
                $("#container").html(err.responseText);
            }

        });
    });
});