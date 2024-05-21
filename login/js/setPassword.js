$(document).ready(function() {
    $.fn.codeCheck = function() {
        var queryString = decodeURIComponent(window.location.search);
        queryString = queryString.substring(1);
        /*  if (queryString = '') {
             location.href = "/login/index.html";
         } */
        var code = queryString;
        $('#crud_req').val(code);

        $.get("../../api/read_newEmail.php?emailCode=" + code, function(returnData) {
            var success, status, message;

            success = returnData.success;
            status = returnData.status;
            message = returnData.messageN;

            if (success === 1) {

                $('.welcomeBack').html(message);

            } else {
                alert(message)
                location.href = "/login/index.html";

            }
        }, "json");

        /* var formData1 = {
            emailCode: code,
        };

        $.ajax({
            type: "POST",
            url: "../../api/read_newEmail.php",
            data: formData1,
            dataType: "json",
            encode: true,

        })

        .done(function(returnData) {

            var success, status, message;

            success = returnData.success;
            status = returnData.status;
            message = returnData.message;

            if (success === 1) {

                $('.welcomeBack').html('Welcome Back ' + message);

            } else {
                alert(message)
                location.href = "/login/index.html";

            }
        }); */

    }


    /********************************Set Password******************************************/
    $("form").submit(function(event) {



        var postCrud = $("#crud_req").val();


        //alert(postCrud);



        if ($("#new_password").val() == '') {
            $("#new_password").css('border', '1px solid red');
            $("#h_new_password").html('Enter your new password');
            return false;
        }

        if ($("#new_password").val() != '') {
            var password1 = $("#new_password").val();
            if (password1.length < 6) {
                $("#new_password").css('border', '1px solid red');
                $("#e_new_password").html('Your password must be at least 6 character');
                return false;
            } else {
                $("#new_password").css('border', '2px solid green');
                $("#e_new_password").html('');
                var postNewPassword = password1;
                //return true;
            }
        }

        if ($("#confirm_new_password").val() == '') {
            $("#confirm_new_password").css('border', '1px solid red');
            $("#e_confirm_new_password").html('Confirm your new password');
            return false;
        }

        if ($("#confirm_new_password").val() != '') {
            var confirm_password1 = $("#confirm_new_password").val();
            if (confirm_password1 != password1) {
                $("#confirm_new_password").css('border', '1px solid red');
                $("#e_confirm_new_password").html('Password does not match');
                return false;
            } else {
                $("#confirm_new_password").css('border', '2px solid green');
                $("#e_confirm_new_password").html('');
                var postConfirm_New_password = confirm_password1;
                //return true;
            }
        }

        var formData = {
            emailCode: postCrud,
            password: postNewPassword
        };




        $.ajax({
            type: "POST",
            url: "../../api/createPassword.php",
            data: formData,
            dataType: "json",
            encode: true,
            Cache: false,

        })

        .done(function(returnData) {
            // alert('returnData');
            var success, status, message;

            success = returnData.success;
            status = returnData.status;
            message = returnData.message;


            alert(message);
            if (success == 1)
            // alert(message)
                location.href = "/login/index.html";

        });
        event.preventDefault();

    })


})