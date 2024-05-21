$(document).ready(function() {
    /* $('.bulkUpload').click(function() {
        $('.uploadss').html('heiuhfirh');
        // $.fn.bulkUpload();
    }); */

    $(document).on('click', '.bulkUpload', function() {
        $('.uploadss').html('');
        $.fn.bulkUpload();
    });

    $(document).on('click', '.backMain', function() {
        /* $('.data-rht-side').html(''); */
        /*  $.fn.base(); */
        location.reload(true);
        /*  window.location = "homeb.html"; */
    });

    $(document).on('click', '.csvSample', function() {
        window.location.assign("../uploads/led-beneficiary-sample-upload.csv");
    });
    $.fn.bulkUpload = function() {
        var bulkcont = `<div class="bulkcontan"><div class="change-password bulkformcont">
        <div class="change-password-header">
            <h3>Bulk upload Beneficiaries</h3>
            <div class="button-controls" style="margin-top: 0.2em;">
            <button class=" save-password csvSample" style="background-color:#11be62;">Click to download CSV Sample <i class="fa fa-table" aria-hidden="true"></i></button>
        </div>
        </div>
      <div class=" change-password-body userForm7 uploadBen">
      
      <form onsubmit="return false;">
      <div class="form-control">
      <label for="fullName">upload signature</label>
      <input type="file" name="file" class="fullName">
          <small style="color: red;" id="e_fullName"></small>
      </div>
      
      <div class="button-controls" style="margin-top: 0.2em;">
          <input type="hidden" name="crud_req" value="update_signature">
          <input type="hidden" name="uniqueId" value="f5323713403">
          <button class="save-password reset" value="submit" id="save_password">Upload file</button>
      </div>
  </form>
  <div class="allAD"><button class="backMain" name="close" type="close">Close</button>
  <div class="loader"></div></div>
  
  </div>

    </div></div>`
        $('.uploadss').html(bulkcont);

        /*  e.preventDefault(); */
        $("form").submit(function(e) {
            if ($(".fullName").val() != '') {

                $('.loader').addClass('activeAni');
            }

            $.ajax({
                type: 'POST',
                url: '../../api/bulkupload.php',
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
            })

            .done(function(returnData) {

                var success, status, message;

                success = returnData.success;
                status = returnData.status;
                message = returnData.message;


                alert(message)
                if (status == 200) {
                    $('.loader').removeClass('activeAni');
                    //$('.userForm7').html('errmsg');
                    $.each(message, function(i, messages) {
                        var email = messages.emails;
                        var errmsg = `<p>` + email + `</p>`;
                        $('.userForm7').html(errmsg);
                    });


                }
                elseif(status == 403)
                $('#save_password').prop('disabled', false)
                $.fn.logout();
                console.log(returnData)
            });
        })
    }

})