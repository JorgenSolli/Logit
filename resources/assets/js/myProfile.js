$(document).ready(function() {
    var getQuote = function() {
        $.ajax({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "https://api.forismatic.com/api/1.0/?",
            dataType: "jsonp",
            data: "method=getQuote&format=jsonp&lang=en&jsonp=?",
            success: function(data) {
                $("#quoteText").text(data.quoteText)
                $("#quoteAuthor").text(data.quoteAuthor)
            }
        })
    }

    getQuote();

    $("#newQuote").on('click', function() {
        getQuote()
    })

    $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: 'https://restcountries.eu/rest/v2/all',
        success: function(data) {

            for (var i = 0; i < data.length; i++) {
                $("#country").append('<option value="' + data[i].name + '">' + data[i].name + '</option>')
            }
            $('.selectpickerAjax').selectpicker({
              style: 'btn-primary',
              size: 'auto',
              mobile: true,
            });
        }
    })
})