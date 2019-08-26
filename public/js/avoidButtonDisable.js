$(document).ready(function(){

    $("#liturgy_text_request_submit").on("click", function()
    {
        setTimeout(function() {
            $("#liturgy_text_request_submit").prop("disabled", false); // Element(s) are now enabled.
        }, 7000);
    });

});

