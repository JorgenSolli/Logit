logit = {
    initPickColor: function(){
        $('.pick-class-label').click(function(){
            var new_class = $(this).attr('new-class');
            var old_class = $('#display-buttons').attr('data-class');
            var display_div = $('#display-buttons');
            if(display_div.length) {
            var display_buttons = display_div.find('.btn');
            display_buttons.removeClass(old_class);
            display_buttons.addClass(new_class);
            display_div.attr('data-class', new_class);
            }
        });
    },

    checkFullPageBackgroundImage: function(){
        $page = $('.full-page');
        image_src = $page.data('image');

        if(image_src !== undefined){
            image_container = '<div class="full-page-background" style="background-image: url(' + image_src + ') "/>'
            $page.append(image_container);
        }
    },

    initFormExtendedDatetimepickers: function(){
        $('.datetimepicker').datetimepicker({
            icons: {
                time: "fal fa-clock",
                date: "fal fa-calendar",
                up: "fal fa-chevron-up",
                down: "fal fa-chevron-down",
                previous: 'fal fa-chevron-left',
                next: 'fal fa-chevron-right',
                today: 'fal fa-screenshot',
                clear: 'fal fa-trash',
                close: 'fal fa-remove',
                inline: true
            }
         });

         $('.datepicker').datetimepicker({
            format: 'MM/DD/YYYY',
            icons: {
                time: "fal fa-clock",
                date: "fal fa-calendar",
                up: "fal fa-chevron-up",
                down: "fal fa-chevron-down",
                previous: 'fal fa-chevron-left',
                next: 'fal fa-chevron-right',
                today: 'fal fa-screenshot',
                clear: 'fal fa-trash',
                close: 'fal fa-remove',
                inline: true
            }
         });

         $('.timepicker').datetimepicker({
            format: 'h:mm A',    //use this format if you want the 12hours timpiecker with AM/PM toggle
            icons: {
                time: "fal fa-clock",
                date: "fal fa-calendar",
                up: "fal fa-chevron-up",
                down: "fal fa-chevron-down",
                previous: 'fal fa-chevron-left',
                next: 'fal fa-chevron-right',
                today: 'fal fa-screenshot',
                clear: 'fal fa-trash',
                close: 'fal fa-remove',
                inline: true

            }
         });
    },
}


$(document).ready(function() {

    // Gets notifications
    $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method: 'POST',
        url: '/social/notifications/check',
        success: function(data) {
            if (data.notifications.length) {
                /* We have some data to append, so clear this first */
                $("#user-notifications").empty();
                $("#user-notifications-amount").html('<span class="notification">' + data.notifications.length + '</span>')
            }

            for (var i = 0; i < data.notifications.length; i++) {
                var nf = data.notifications[i]
                if (nf.icon) {
                    $("#user-notifications").append(
                        '<li>' +
                            '<a id="' + nf.id + '" href="' + nf.url + '"><i class="material-icons"> ' + nf.icon + '</i>' + nf.content + '</a>' +
                        '</li>'
                    )
                }
                else {
                    $("#user-notifications").append(
                        '<li>' +
                            '<a id="' + nf.id + '" href="' + nf.url + '">' + nf.content + '</a>' +
                        '</li>'
                    )
                }
            }
        }
    })

    $(document).on('click', '#user-notifications li a', function(e) {
        e.preventDefault();
        var id = $(this).attr('id')

        /* making sure there is somethine to be clicked first... */
        if (id) {
            var href = $(this).attr('href')
            $.ajax({
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'POST',
                url: '/social/notifications/clear',
                data: {
                    id: id
                },
                success: function() {
                    window.location.href = href;
                }
            })
        }
    })
})