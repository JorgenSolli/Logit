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

    toggleSidebar: function() {
        var didTransition = false;
        if ($(window).width() <= 1400 && (md.misc.sidebar_mini_active == false || !md.misc.sidebar_mini_active)) {
            $('.sidebar .collapse').collapse('hide').on('hidden.bs.collapse',function(){
                $(this).css('height','auto');
            });

            if(isWindows){
                $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar('destroy');
            }

            setTimeout(function(){
                $('body').addClass('sidebar-mini');

                $('.sidebar .collapse').css('height','auto');
                md.misc.sidebar_mini_active = true;
            },300);

            didTransition = true;
        } 
        else if ($(window).width() > 1400 && md.misc.sidebar_mini_active == true) {
            $('body').removeClass('sidebar-mini');
            md.misc.sidebar_mini_active = false;

            if(isWindows){
                $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar();
            }

            didTransition = true;
        }

        if (didTransition) {
            // we simulate the window Resize so the charts will get updated in realtime.
            var simulateWindowResize = setInterval(function(){
                window.dispatchEvent(new Event('resize'));
            },180);

            // we stop the simulation of Window Resize after the animations are completed
            setTimeout(function(){
                clearInterval(simulateWindowResize);
            },1000);
        }
    },

    initModal: function(title, body, large) {
        var $modal = large ? $("#logit-modal-large") : $("#logit-modal");
        var $t = $modal.find('.modal-title');
        var $b = $modal.find ('.modal-body');

        $t.html(title);
        $b.html(body);

        $modal.modal('show');
    }
}

$(window).resize(function(){
    logit.toggleSidebar();
});

$(document).ready(function() {
    logit.toggleSidebar();
    
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
});