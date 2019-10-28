$(document).ready(function(){
    $("#field_actions_sd737e59e34_emailSubscription").append(
        '<div id="demo_div"><div class="btn btn-success" id="demo-button">Demonstração</div><span class="help-block sonata-ba-field-help">Você receberá um email demo com os textos litúrgicos de acordo com a configuração.</span></div>'
    );

    $("#demo-button").on("click", function() {
        $("#demo-button").prop("disabled");
        setTimeout(function() {
            $("#demo-button").prop("disabled", false); // Element(s) are now enabled.
        }, 7000);
    });
    $("#demo-button").click(function() {
        var daily = $("#sd737e59e34_emailSubscription_periodicity_0").is(":checked");
        var weekly = $("#sd737e59e34_emailSubscription_periodicity_1").is(":checked");
        var biweekly = $("#sd737e59e34_emailSubscription_periodicity_2").is(":checked");
        var cnbb = $("#sd737e59e34_emailSubscription_source_0").is(":checked");
        var igreja = $("#sd737e59e34_emailSubscription_source_1").is(":checked");
        var docx =$("#sd737e59e34_emailSubscription_format_0").is(":checked");
        var pdf = $("#sd737e59e34_emailSubscription_format_1").is(":checked");
        if (!cnbb && !igreja) {
            
            alert("Selecione pelo menos uma fonte.");
        }
        else if (!docx && !pdf) {

            alert("Selecione pelo menos um formato.");
        }
        else{
            var period = "daily";
            var format = "ALL";
            var source = "ALL";
            var newRoute = route; /*this is the route generated from the template*/
            if (cnbb && !igreja)
            {
                source = "CNBB";
            }
            else if (!cnbb && igreja)
            {
                source = "Igreja_Santa_Ines";
            }

            if (docx && !pdf)
            {
                format = "DOCX";
            }
            else if (!docx && pdf)
            {
                format = "PDF";
            }

            if (weekly)
            {
                period= "weekly";
            }
            else if (biweekly)
            {
                period= "biweekly";
            }
            newRoute = newRoute.replace("daily", period);
            newRoute = newRoute.replace("CNBB", source);
            newRoute = newRoute.replace("PDF", format);

            $.getJSON( newRoute, function( data ) {
                var items = [];
                $.each( data, function( key, val ) {
                    items.push( "<li id='" + key + "'>" + val + "</li>" );
                });
                
                $( "<ul/>", {
                    "class": "response",
                    html: items.join( "" )
                }).appendTo( "#demo-div" );
            });
/*            alert(newRoute);*/
        }

    }     
    );

});

