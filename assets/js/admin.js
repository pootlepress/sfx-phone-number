( function( $ ) {
      $( document).ready( function(){

          $('.sfxtp-color-field').wpColorPicker({clear: true, width: 232 });

          var $cta = $( '#sfxtp-phone-item-cta' );
          var $phone = $( '#sfxtp-phone-item-phone' );
          var $skype = $( '#sfxtp-phone-item-skype' );
          var $color = $( '#sfxtp-phone-item-color' );
          var $color_hover = $( '#sfxtp-phone-item-hover-color' );
          var $icon = $( '#sfxtp-phone-item-icon' )

          $( '#sfxtp-submit-phone' ).click( function(){
              var title = $cta.val() + ' ';
              var phone = $phone.val().replace( /[^+\d]/g, '' );
              var skype = $skype.val().replace( /[^a-z.\d]/g, '' );

              //Check if either of skype or phone is set
              if ( skype != '' ) {
                  var url = 'http://skype?closeproto&' + skype;
                  title +=  $skype.val();
              } else if ( phone != '' ) {
                  var url = 'http://callto?closeproto&' + phone;
                  title +=  $phone.val();
              } else {
                  return false;
              }

              //Return if title not set
              if ( ! title )
                  return false;

              var color = $color.val();
              var color_hover = $color_hover.val();
              var icon_class = 'sfxtp-phone ' + $icon.val();

              data = {
                  'menu-item-type': 'custom',
                  'menu-item-classes': icon_class,
                  'menu-item-url': url,
                  'menu-item-title': title,
                  'menu-item-icon-color': color,
                  'menu-item-icon-hover-color': color_hover
              };

              sfxtp_phone_menu.sfxtp_phone_menu_item_save( data );

              $( '.sfxtp-field, .sfxtp-color-field' ).val( '' );
              $( '.wp-color-result' ).css( 'background-color', '' );

          } );

      } );


    sfxtp_phone_menu = {
        addItemToMenu : function (menuItem, processMethod, callback) {
            var menu = $('#menu').val(),
                nonce = $('#menu-settings-column-nonce').val(),
                params;

            processMethod = processMethod || function(){};
            callback = callback || function(){};

            params = {
                'action': 'sfxtp_add_menu_item',
                'menu': menu,
                'menu-settings-column-nonce': nonce,
                'menu-item': menuItem
            };

            $.post( ajaxurl, params, function(menuMarkup) {
                var ins = $('#menu-instructions');

                menuMarkup = $.trim( menuMarkup ); // Trim leading whitespaces
                processMethod(menuMarkup, params);

                // Make it stand out a bit more visually, by adding a fadeIn
                $( 'li.pending' ).hide().fadeIn('slow');
                $( '.drag-instructions' ).show();
                if( ! ins.hasClass( 'menu-instructions-inactive' ) && ins.siblings().length )
                    ins.addClass( 'menu-instructions-inactive' );

                callback();
            });
        },

        sfxtp_phone_menu_item_save : function ( data ) {
            var url = $('#sfxtp-phone-item-url').val(),
                label = $('#sfxtp-phone-item-title').val();

            // Show the ajax spinner
            $('.customlinkdiv .spinner').show();


            sfxtp_phone_menu.addItemToMenu( { '-1': data }, wpNavMenu.addMenuItemToBottom, function() {
                // Remove the ajax spinner
                $('.customlinkdiv .spinner').hide();
                // Set custom link form back to defaults
                $('#custom-menu-item-name').val('').blur();
                $('#custom-menu-item-url').val('http://');
            } );

        }

    }
} )( jQuery );