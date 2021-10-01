$(function () {

    'use strict';

    // switch between signIn and signUp

    $('.authentication-page h1 span').click(function(){
    $(this).addClass('selected').siblings().removeClass('selected');
        $('form').hide();
        $('.' + $(this).data('class')).fadeIn(100);
    });

    //  Calls the selectBoxIt method on your HTML select box and uses the default theme
    $("select").selectBoxIt({
        autoWidth: false
    });

    // Hide place holder on form focus 

    $('[placeholder]').focus(function () {
        $(this).attr('data-text', $(this).attr('placeholder'));
        $(this).attr('placeholder', '');
    }).blur(function () {
        $(this).attr('placeholder', $(this).attr('data-text'));
        $(this).attr('data-text', '');
    })

    // add asterisk to required inputs 

    $('input').each(function () {
        if ($(this).attr('required') === 'required')
            $(this).after('<span class="asterisk">*</span>');
    });

    // confirm the delete of a member

    $('.confirm').click(function () {
        return confirm('are you sure you want to delete this user');
    }
    );

    // create new item , live preview

    $('.live').keyup(function(){

        // console.log($(this).data('class'));
        
        $($(this).data('class')).text($(this).val());
    });
})