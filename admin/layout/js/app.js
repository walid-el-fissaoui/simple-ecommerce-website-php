$(function () {

    'use strict';

    // dashboard latest 

    $('.toggle-info').click(function(){
        $(this).toggleClass('selected').parent().next('.list-group').fadeToggle(200);
        if($(this).hasClass('selected')){
            $(this).html("<i class='fas fa-plus'></i>");
        }
        else{
            $(this).html("<i class='fas fa-minus'></i>");
        }
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

    // convert the type of the input from password type to text type

    var password = $('.inputPassword')
    $('.show-password').hover(function () {
        password.attr('type', 'text');
    }, function () {
        password.attr('type', 'password');
    });

    // confirm the delete of a member

    $('.confirm').click(function () {
        return confirm('are you sure you want to delete this user');
    }
    );

    // toggle between classic and full view for manage categories

    $('.categories ul h5').click(function(){
        $(this).next('.full-view').fadeToggle(200);
    });

    $('.categories .options span').click(function(){
        $(this).addClass('active').siblings('span').removeClass('active');
        if($(this).data('view') === 'full')
        {
            $('.categories .full-view').fadeIn(200);
        }
        else
        {
            $('.categories .full-view').fadeOut(200);
        }
    });

    // show delete link when hover the item of category or hide it

    $('.categories-children-list-item').hover(function(){
        $(this).find('.btn-delete').fadeIn(400);
    },function(){
        $(this).find('.btn-delete').fadeOut(400);
    });
})