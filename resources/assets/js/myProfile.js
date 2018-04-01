$(document).ready(function() {

    /* Populates countries */
    $.ajax({
        url: 'https://restcountries.eu/rest/v2/all',
        success: function(data) {
            for (var i = 0; i < data.length; i++) {
                $("#country").append('<option value="' + data[i].name + '">' + data[i].name + '</option>')
            }
            $('#country').selectpicker('refresh');;
        }
    });

    $('#country').on('changed.bs.select', function (e) {
        
    });
});