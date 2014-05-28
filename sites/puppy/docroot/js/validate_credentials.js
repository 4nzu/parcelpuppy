function validate_email(id) { var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/; return reg.test($('#'+id).val()); }
function values_match(id1, id2) { var match = $('#'+id1).val(); if (match == $('#'+id2).val()) return true; else return false; }
function validate_input(page_id) { 
    //if ($('#full_name').val().length == 0) { $('#full_name').addClass('error'); return false; } else { $('#full_name').removeClass('error'); }
    // if ($('#last_name').val().length == 0) { $('.last_name').addClass('error'); return false; } else { $('.last_name').removeClass('error'); }
    if (page_id == 2) {
        if (!validate_email('email2')) { $('#email2').addClass('error'); return false; } else {  $('#email2').removeClass('error'); }
    } 
    if (page_id == 0) {
        if (!validate_email('email')) { $('#email').addClass('error'); return false; } else {  $('#email').removeClass('error'); }
    }
    if (page_id == 1) { 
        if (!validate_email('email1')) { $('#email1').addClass('error'); return false; } else {  $('#email1').removeClass('error'); }
    }
    // if (!validate_email('email2')) { $('.email2').addClass('error'); return false; } else {  $('.email2').removeClass('error'); }
    // if (!values_match('email1', 'email2')) { $('.email1').addClass('error'); $('.email2').addClass('error'); return false; } else { $('.email1').removeClass('error'); $('.email2').removeClass('error'); }
    if (page_id == 2) {
        if ($('#pass2').val().length == 0) { $('#pass2').addClass('error'); return false; } else { $('#pass2').removeClass('error'); }
    } 
    if (page_id == 1) {
        if ($('#pass1').val().length == 0) { $('#pass1').addClass('error'); return false; } else { $('#pass1').removeClass('error'); }
    }
    if (page_id == 0) {
        if ($('#pass').val().length == 0) { $('#pass').addClass('error'); return false; } else { $('#pass').removeClass('error'); }
    }
    // if ($('#pass2').val().length == 0) { $('.pass2').addClass('error'); return false; } else { $('.pass2').removeClass('error'); }
    // if (!values_match('pass1', 'pass2')) { $('.pass1').addClass('error'); $('.pass2').addClass('error'); return false; } else { $('.pass1').removeClass('error'); $('.pass2').removeClass('error'); }
    //if (page_id==1 && !$('#ppcheck').is(':checked')) { $('.ppcheck_div').css('border-color', 'red'); return false; } else { $('.ppcheck_div').css('border-color', 'white'); }
    return true;
}
