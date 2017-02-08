jQuery(document).ready(function(){
    jQuery('#sign-up_form row').each(function(){
        jQuery(this).find('input').each(function(){
            jQuery(this).on('keyup', function(){
                if(validate_signup()){
                    console.log('yes');
                    jQuery('button[type="submit"]').attr('disabled', null);
                }else{
                    console.log('no');
                    jQuery('button[type="submit"]').attr('disabled', 'disabled');
                }
            });
        });
    });
});

function validate_signup(){
    return validate_password() && validate_names()  && validate_dob();
}

function validate_password(){
    return jQuery('input[name="password"]').val == jQuery('input[name="confirm-password"]').val && jQuery('input[name="password"]').val.length >= 7;
}

function validate_names(){
    return !parseInt(jQuery('input[name="firstname"]').val) && !parseInt(jQuery('input[name="firstname"]').val);
}


function validate_dob(){
    return jQuery('input[name="day"]')/1 == jQuery('input[name="day"]') && jQuery('input[name="month"]')/1 == jQuery('input[name="month"]') && jQuery('input[name="year"]')/1 == jQuery('input[name="year"]');
}


function get_errors(){
    if(!validate_password()){
        console.log('Invalid: password');
    }
    if(!validate_names()){
        console.log('Invalid: names');
    }
    if(!validate_username()){
        console.log('Invalid: username');
    }
    if(!validate_dob()){
        console.log('Invalid: dob');
    }
}