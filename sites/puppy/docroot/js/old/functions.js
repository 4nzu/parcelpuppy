function updateURLParameter(url, param, paramVal){
    var newAdditionalURL = "";
    var tempArray = url.split("?");
    var baseURL = tempArray[0];
    var additionalURL = tempArray[1];
    var temp = "";
    if (additionalURL) {
        tempArray = additionalURL.split("&");
        for (i=0; i<tempArray.length; i++){
            if(tempArray[i].split('=')[0] != param){
                newAdditionalURL += temp + tempArray[i];
                temp = "&";
            }
        }
    }

    var rows_txt = temp + "" + param + "=" + paramVal;
    return baseURL + "?" + newAdditionalURL + rows_txt;
}

function swap_url_parameter(url, parameter, new_value) {
    var pattern = new RegExp('&'+parameter+'=([a-z0-9]*)|\\?'+parameter+'=([a-z0-9]*)');
    var match = pattern.exec(url);
    if (match) {
        if (typeof match[1] != 'undefined') { var cur_settings = match[1]; var separator = '&'; }
        else if (typeof match[2] != 'undefined') { var cur_settings = match[2]; var separator = '?'; }
        return url.replace(separator+parameter+'='+cur_settings, separator+parameter+'='+new_value);

    }
    else return (url.indexOf('?') == -1) ? url+'?'+parameter+'='+new_value : url+'&'+parameter+'='+new_value;
}

function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

function authors_cut(str) {
    var AuthorsArr = str.split(',');
    var AuthorsArrLength = AuthorsArr.length;
    if (AuthorsArrLength > 14) {
        var AuthorsStr = '';
        $.each(AuthorsArr, function(index, element) {
            if (index == 13) {
                AuthorsStr = AuthorsStr + '<span class="authors-toggle-dots">...,</span> ';
                AuthorsStr = AuthorsStr + '<span class="authors-toggle-text">' + AuthorsArr[index] + ', '; 
            } else {
                if (index == AuthorsArrLength - 1) { 
                    AuthorsStr = AuthorsStr + '</span>' + AuthorsArr[index] + '&nbsp;<a href="#" class="authors-toggle">[+]</a>';
                } else {
                    AuthorsStr = AuthorsStr + AuthorsArr[index] + ', ';
                }
            }
        });
    } else {
        var AuthorsStr = str;
    }
    return AuthorsStr;
}

function get_parameter_by_name(name) {
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"), results = regex.exec(location.search);
    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}