$(document).ready(function () {
    $('.submit-new').click(function(e) {
    	
    	$('input').removeClass('error');
    	
    	var data = {
    		   pr_name: $('#pr_name').val(),
	    	  pr_price: $('#pr_price').val(),
	    	pr_country: $('#pr_country').val(),
	    	   pr_city: $('#pr_city').val(),
	    	    pr_fee: $('#pr_fee').val(),
	    pr_description: $('#pr_description').val()}

    	$.post('/api/v1/create', data, function(r) {
    		if (r.request != 'OK') {
    			$(r.error_code).addClass('error');
    		}
            else {
                $('#pr_name').val('');
                $('#pr_price').val('');
                $('#pr_country').val(7);
                $('#pr_city').val('');
                $('#pr_fee').val('');
                $('#pr_description').val('');
            }
    	});
    })
});