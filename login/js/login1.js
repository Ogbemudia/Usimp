//form validation
$(document).ready(function() {
    $(".resetPassword").click(function() {
        location.href = "../login/reset-password.html";
    });

    $("form").submit(function(event) {
        /* $('.loginB').prop('disabled', true);
        $('.loginB').css('background-color', '#f1f3f1'); */

        var postLogin = $("#crud_req").val();



        if ($("#email").val() == '') {
            //$("#username").css('border', '1px solid red');
            $("#e_email").html('Enter your email');
            return false;
        } else {
            // $("#username").css('border', '2px solid green');
            $("#e_email").html('');
            var postUsername = $("#email").val();
            //return true;
        }




        if ($("#password").val() == '') {
            // $("#password").css('border', '1px solid red');
            $("#e_password").html('Enter your password');
            return false;
        }

        if ($("#password").val() != '') {
            var postPassword = $("#password").val();

            // $("#password").css('border', '2px solid green');
            $("#e_password").html('');

            //return true;
        }

        var formData = {
            crud_req: postLogin,
            email: postUsername,
            password: postPassword,
        };


        $.ajax({
            type: "POST",
            url: "../../validation/login.php",
            //url: "http://192.168.80.29:96/validation/login.php",
            data: formData,
            dataType: "json",
            encode: true,

        })

        .done(function(returnData) {

            var name, success, status, message, token;

            name = returnData.name;
            success = returnData.success;
            status = returnData.status;
            message = returnData.message;
            //token = returnData.token;


            //alert(message);


            if (success == 1) {
                /*  $.post("https://staffprofile.uniben.edu/api/validation.php", function(messages) {


                 }, "json"); */
                // $.get("https://staffprofile.uniben.edu/api/readLoginStaff.php", function(messages) {


                //}, "json");

                //var category1 = category;
                // $("#loginName").html(name);

                alert(message);

            } else {
                $('.loginB').prop('disabled', false)
                    //$("#username").css('border', '1px solid red');
                $("#warning").html(message);
            }



            //console.log(returnData)

        });
        event.preventDefault();


    });






})