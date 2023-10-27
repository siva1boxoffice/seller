
var notyf = new Notyf({
	duration: 2e3,
	position: { x: "right", y: "bottom" },
	types: [
		{ type: "warning", background: themeColors.warning, icon: { className: "fas fa-hand-paper", tagName: "i", text: "" } },
		{ type: "info", background: themeColors.info, icon: { className: "fas fa-info-circle", tagName: "i", text: "" } },
		{ type: "primary", background: themeColors.primary, icon: { className: "fas fa-car-crash", tagName: "i", text: "" } },
		{ type: "accent", background: themeColors.accent, icon: { className: "fas fa-car-crash", tagName: "i", text: "" } },
		{ type: "purple", background: themeColors.purple, icon: { className: "fas fa-check", tagName: "i", text: "" } },
		{ type: "blue", background: themeColors.blue, icon: { className: "fas fa-check", tagName: "i", text: "" } },
		{ type: "green", background: themeColors.green, icon: { className: "fas fa-check", tagName: "i", text: "" } },
		{ type: "orange", background: themeColors.orange, icon: { className: "fas fa-check", tagName: "i", text: "" } },
	],
});


// $('#update_password_submit').on('click', function (e) {

$('.validate_form_bulk').validate({
    errorPlacement: function(error, element) {
      var placement = $(element).data('error');
      if (placement) {
        $(placement).append(error)
      } else {
        error.insertAfter(element);
      }
    },
    onfocusout: false,
    invalidHandler: function(form, validator) { 
        var errors = validator.numberOfInvalids();
        if (errors) {  // console.log(validator.errorList[0].element.focus());                 
           // validator.errorList[0].element.focus();
           var errorid = validator.errorList[0].element.getAttribute('id');
          // console.log(errorid);
            $('html, body').animate({
            scrollTop: ($('#'+errorid).offset().top)
            },500);
        }
    } ,
     rules: {
        "uploadFile": "required",
    },
    messages: {
        "uploadFile": "Upload Excel file is Required.",
    },
  submitHandler: function(form) {
    
  var myform = $('#'+$(form).attr('id'))[0];
    //is-loading no-click
   // branch-form-btn
  var formData = new FormData(myform);

    $('#'+$(form).attr('id')+'-btn').addClass("is-loading no-click");

    $('.has-loader').addClass('has-loader-active');
    
    var submit = $('#'+$(form).attr('id')).find(':submit');
    submit.attr("disabled", true);
    submit.html('<i class="fa fa-spinner fa-spin" style="color:#color: #325edd;"></i>&nbsp;Please Wait ...');


  var action = $(form).attr('action');
    $.ajax({
      type: "POST",
      enctype: 'multipart/form-data',
      url: action,
      data: formData,
      processData: false,
      contentType: false,
      cache: false,
      dataType: "json",

      success: function(data) { 

        submit.html("Upload Tickets");

        $('#'+$(form).attr('id')+'-btn').removeClass("is-loading no-click");

        if(data.status == 1) {

          notyf.success(data.msg, "Success", {
          timeOut: "1800"
          });
          setTimeout(function(){ window.location.href = data.redirect_url; }, 2000);
        }else if(data.status == 0) {
          submit.attr("disabled", false); 
           notyf.error(data.msg, "Failed", "Oops!", {
          timeOut: "1800"
          });
          setTimeout(function(){ window.location.href = data.redirect_url; }, 2000);
          
        }
      }
    })
    return false;
  }
});


$('#save_bank_accounts').validate({
  errorPlacement: function(error, element) { 
      var placement = $(element).data('error');
      if (placement) { 
        $(placement).append(error)
      } else { 
        error.insertAfter(element);
      }
    },
    onfocusout: false,
  submitHandler: function(form) {
    
  var myform = $('#'+$(form).attr('id'))[0];
  
  var formData = new FormData(myform);
    
  var action = $(form).attr('action');
    $.ajax({
      type: "POST",
      enctype: 'multipart/form-data',
      url: action,
      data: formData,
      processData: false,
      contentType: false,
      cache: false,
      dataType: "json",

      success: function(data) { 

     
        if(data.status == 1) {

          notyf.success(data.msg, "Success", {
          timeOut: "1800"
          });
         setTimeout(function(){ window.location.reload(); }, 2000);
        }else if(data.status == 0) {
           notyf.error(data.msg, "Failed", "Oops!", {
          timeOut: "1800"
          });
          
           setTimeout(function(){ window.location.reload(); }, 2000);
        }
      }
    })
    return false;
  }
});

$('#upload_document').validate({
  errorPlacement: function(error, element) { 
      var placement = $(element).data('error');
      if (placement) { 
        $(placement).append(error)
      } else { 
        error.insertAfter(element);
      }
    },
    onfocusout: false,
  submitHandler: function(form) {
    
  var myform = $('#'+$(form).attr('id'))[0];
  
  var formData = new FormData(myform);
    
  var action = $(form).attr('action');
    $.ajax({
      type: "POST",
      enctype: 'multipart/form-data',
      url: action,
      data: formData,
      processData: false,
      contentType: false,
      cache: false,
      dataType: "json",

      success: function(data) { 

     
        if(data.status == 1) {

          notyf.success(data.msg, "Success", {
          timeOut: "1800"
          });
         setTimeout(function(){ window.location.reload(); }, 2000);
        }else if(data.status == 0) {
           notyf.error(data.msg, "Failed", "Oops!", {
          timeOut: "1800"
          });
          
           setTimeout(function(){ window.location.reload(); }, 2000);
        }
      }
    })
    return false;
  }
});

$('#upload_contract').validate({
  errorPlacement: function(error, element) { 
      var placement = $(element).data('error');
      if (placement) { 
        $(placement).append(error)
      } else { 
        error.insertAfter(element);
      }
    },
    onfocusout: false,
  submitHandler: function(form) {
    
  var myform = $('#'+$(form).attr('id'))[0];
  
  var formData = new FormData(myform);
    
  var action = $(form).attr('action');
    $.ajax({
      type: "POST",
      enctype: 'multipart/form-data',
      url: action,
      data: formData,
      processData: false,
      contentType: false,
      cache: false,
      dataType: "json",

      success: function(data) { 

     
        if(data.status == 1) {

          notyf.success(data.msg, "Success", {
          timeOut: "1800"
          });
         setTimeout(function(){ window.location.reload(); }, 2000);
        }else if(data.status == 0) {
           notyf.error(data.msg, "Failed", "Oops!", {
          timeOut: "1800"
          });
          setTimeout(function(){ window.location.reload(); }, 2000);
          
        }
      }
    })
    return false;
  }
});

$('.validate_form_v3').validate({
	  errorPlacement: function(error, element) { 
      var placement = $(element).data('error');
      if (placement) {
        $(placement).append(error)
      } else {
        error.insertAfter(element);
      }
    },
    onfocusout: false,
    invalidHandler: function(form, validator) {  
        var errors = validator.numberOfInvalids();
        if (errors) {  // console.log(validator.errorList[0].element.focus());                 
           // validator.errorList[0].element.focus();
           var errorid = validator.errorList[0].element.getAttribute('id');
          
          // console.log(errorid);
            $('html, body').animate({
            scrollTop: ($('#'+errorid).offset().top)
            },500);
        }
    } ,
     rules: {
        "ticket_types[]": "required",
        "add_qty_addlist[]": "required",
        "split_type[]": "required",
        "add_price_addlist[]": "required",
        "add_pricetype_addlist[]" : "required",
        "ticket_category[]": "required",
        //"home_town": "required",
       // "ticket_details[]": "required",
        "add_eventname_addlist[]": "required",
    },
    messages: {
        "ticket_types[]": "Ticket Type is Required.",
        "add_qty_addlist[]": "Ticket Quantity is Required.",
        "split_type[]": "Split Type is Required.",
        "add_price_addlist[]": "Ticket Price is Required.",
        "ticket_category[]": "Ticket Category is Required.",
        //"home_town": "Home/Down is Required.",
       // "ticket_details[]": "Ticket Details is Required.",
        "add_pricetype_addlist[]" : "Ticket Currency is Required.",
         "add_eventname_addlist[]": "Match Event is Required."
    },
  submitHandler: function(form) { 


    $(".seat_error").html("");
        var quantity =  $("input[name*='add_qty_addlist']:checked").val();
        console.log(quantity)
        if(quantity > 1 ){

          var seat_type = $(".seat_type:checked").length;
        
          if(seat_type == 0) {
         
            $(".seat_error").html("Please Select One Option");
            $(".seat_error").show();
            return false;
          }
          else if(seat_type >  1) {
         
            $(".seat_error").html("Please Select One Option");
            $(".seat_error").show();
            return false;
          }
        }

  	
	var myform = $('#'+$(form).attr('id'))[0];
    //is-loading no-click
   // branch-form-btn
	var formData = new FormData(myform);

    $('#'+$(form).attr('id')+'-btn').addClass("is-loading no-click");

    $('.has-loader').addClass('has-loader-active');
    
    var submit = $('#'+$(form).attr('id')).find(':submit');
    submit.attr("disabled", true);
    submit.html('<i class="fa fa-spinner fa-spin" style="color:#color: #325edd;"></i>&nbsp;Please Wait ...');


	var action = $(form).attr('action');
    $.ajax({
      type: "POST",
      enctype: 'multipart/form-data',
      url: action,
      data: formData,
      processData: false,
      contentType: false,
      cache: false,
      dataType: "json",

      success: function(data) { 

        submit.html("LIST NOW");

        $('#'+$(form).attr('id')+'-btn').removeClass("is-loading no-click");

        if(data.status == 1) {

          notyf.success(data.msg, "Success", {
          timeOut: "1800"
          });
          setTimeout(function(){ window.location.href = data.redirect_url; }, 2000);
        }else if(data.status == 0) {
          submit.attr("disabled", false); 
           notyf.error(data.msg, "Failed", "Oops!", {
          timeOut: "1800"
          });
          setTimeout(function(){ window.location.href = data.redirect_url; }, 2000);
          
        }
      }
    })
    return false;
  }
});


$('.validate_event_form').validate({

  submitHandler: function(form) {
  
  var duplicate_check_action = $(form).attr('duplicate-check');

  var myform = $('#'+$(form).attr('id'))[0];
    //is-loading no-click
   // branch-form-btn
  var formData = new FormData(myform);

   // $('#'+$(form).attr('id')+'-btn').addClass("is-loading no-click");

   // $('.has-loader').addClass('has-loader-active');

   var action = $(form).attr('action');
    $.ajax({
      type: "POST",
      enctype: 'multipart/form-data',
      url: duplicate_check_action,
      data: formData,
      processData: false,
      contentType: false,
      cache: false,
      dataType: "json",

      success: function(data) {
            if(data.status == 0){

               swal({
    title: 'Events URL was used',
    text: data.msg,
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#0CC27E',
    cancelButtonColor: '#FF586B',
    confirmButtonText: 'Yes, Update it!',
    cancelButtonText: 'No, cancel!',
    confirmButtonClass: 'button h-button is-primary',
    cancelButtonClass: 'button h-button is-danger',
    buttonsStyling: false
  }).then(function (res) {

    var update_url = 0;

    if (res.value == true) {
       update_url = 1
    }
    formData.append("update_url", update_url);
    var action = $(form).attr('action');
    $.ajax({
      type: "POST",
      enctype: 'multipart/form-data',
      url: action,
      data: formData,
      processData: false,
      contentType: false,
      cache: false,
      dataType: "json",

      success: function(data) { 

        if(data.status == 1) {

          notyf.success(data.msg, "Success", {
          timeOut: "1800"
          });
        }else if(data.status == 0) {
           notyf.error(data.msg, "Failed", "Oops!", {
          timeOut: "1800"
          });
          
        }
      }
    })


  }, function (dismiss) {

  });


            }
            else{

var update_url = 0;
    formData.append("update_url", update_url);
var action = $(form).attr('action');
               $.ajax({
      type: "POST",
      enctype: 'multipart/form-data',
      url: action,
      data: formData,
      processData: false,
      contentType: false,
      cache: false,
      dataType: "json",

      success: function(data) { 

        if(data.status == 1) {

          notyf.success(data.msg, "Success", {
          timeOut: "1800"
          });
        }else if(data.status == 0) {
           notyf.error(data.msg, "Failed", "Oops!", {
          timeOut: "1800"
          });
          
        }
      }
    })

            }
        }
    })
    return false;
  }
});

$('.admin_login').validate({
    errorPlacement: function(error, element) {
      var placement = $(element).data('error');
      if (placement) {
        $(placement).append(error)
      } else {
        error.insertAfter(element);
      }
    },
    onfocusout: false,
    invalidHandler: function(form, validator) { 
        var errors = validator.numberOfInvalids();
        if (errors) {   
           var errorid = validator.errorList[0].element.getAttribute('id');
          
        }
    } ,
     rules: {
        "username": "required",
        "password": "required"
    },
    messages: {
        "username": "Email id is Required.",
        "password": "Password is Required."
    },
  submitHandler: function(form) {
    

    
  var myform = $('#'+$(form).attr('id'))[0];
  var formData = new FormData(myform);

  var submit = $('#'+$(form).attr('id')).find(':submit');
  submit.attr("disabled", true);
  submit.html('<i class="fa fa-spinner fa-spin" style="color:#fff;"></i>&nbsp;Please Wait ...');
  
  var action = $(form).attr('action');
    $.ajax({
      type: "POST",
      enctype: 'multipart/form-data',
      url: action,
      data: formData,
      processData: false,
      contentType: false,
      cache: false,
      dataType: "json",

      success: function(data) {

          submit.html("LOGIN");
     //   $('#'+$(form).attr('id')+'-btn').removeClass("is-loading no-click");

        if(data.status == 1) {

          notyf.success(data.msg, "Success", {
          timeOut: "1800"
          });
          setTimeout(function(){ window.location.href = data.redirect_url; }, 2000);
        }else if(data.status == 0) {
             submit.attr("disabled", false); 
           notyf.error(data.msg, "Failed", "Oops!", {
          timeOut: "1800"
          });
          setTimeout(function(){ window.location.href = data.redirect_url; }, 2000);
          
        }
      }
    })
    return false;
  }
});


$('.forget_password').validate({
    errorPlacement: function(error, element) {
      var placement = $(element).data('error');
      if (placement) {
        $(placement).append(error)
      } else {
        error.insertAfter(element);
      }
    },
    onfocusout: false,
    invalidHandler: function(form, validator) { 
        var errors = validator.numberOfInvalids();
        if (errors) {   
           var errorid = validator.errorList[0].element.getAttribute('id');
          
        }
    } ,
     rules: {
        "email_id": "required"
    },
    messages: {
        "email_id": "Email id is Required."
    },
  submitHandler: function(form) {
    

    
  var myform = $('#'+$(form).attr('id'))[0];
  var formData = new FormData(myform);
  var submit = $('#'+$(form).attr('id')).find(':submit');
  submit.attr("disabled", true);
  submit.html('<i class="fa fa-spinner fa-spin" style="color:#fff;"></i>&nbsp;Please Wait ...');

  var action = $(form).attr('action');
    $.ajax({
      type: "POST",
      enctype: 'multipart/form-data',
      url: action,
      data: formData,
      processData: false,
      contentType: false,
      cache: false,
      dataType: "json",

      success: function(data) { 

     //   $('#'+$(form).attr('id')+'-btn').removeClass("is-loading no-click");
         submit.attr("disabled", false); 
        if(data.status == 1) {

          notyf.success(data.msg, "Success", {
          timeOut: "1800"
          });
         // setTimeout(function(){ window.location.href = data.redirect_url; }, 2000);
        }else if(data.status == 0) {
           submit.html("CONFIRM");
           notyf.error(data.msg, "Failed", "Oops!", {
          timeOut: "1800"
          });
          //setTimeout(function(){ window.location.href = data.redirect_url; }, 2000);
          
        }
      }
    })
    return false;
  }
});

$('.validate_form_v1').validate({
  submitHandler: function(form) {
  	
	var myform = $('#'+$(form).attr('id'))[0];
    //is-loading no-click
   // branch-form-btn
	var formData = new FormData(myform);

   // $('#'+$(form).attr('id')+'-btn').addClass("is-loading no-click");

   // $('.has-loader').addClass('has-loader-active');
    
	var action = $(form).attr('action');
    $.ajax({
      type: "POST",
      enctype: 'multipart/form-data',
      url: action,
      data: formData,
      processData: false,
      contentType: false,
      cache: false,
      dataType: "json",

      success: function(data) { 

     //   $('#'+$(form).attr('id')+'-btn').removeClass("is-loading no-click");

        if(data.status == 1) {

          notyf.success(data.msg, "Success", {
          timeOut: "1800"
          });
         // setTimeout(function(){ window.location.href = data.redirect_url; }, 2000);
        }else if(data.status == 0) {
           notyf.error(data.msg, "Failed", "Oops!", {
          timeOut: "1800"
          });
          //setTimeout(function(){ window.location.href = data.redirect_url; }, 2000);
          
        }
      }
    })
    return false;
  }
});

$('#request_event').validate({
    errorPlacement: function(error, element) {
      var placement = $(element).data('error');
      if (placement) {
        $(placement).append(error)
      } else {
        error.insertAfter(element);
      }
    },
    onfocusout: false,
    invalidHandler: function(form, validator) { 
        var errors = validator.numberOfInvalids();
        if (errors) {   
           var errorid = validator.errorList[0].element.getAttribute('id');
          
        }
    } ,
  submitHandler: function(form) { 
    
  var myform = $('#'+$(form).attr('id'))[0];
    //is-loading no-click
   // branch-form-btn
  var formData = new FormData(myform);

   // $('#'+$(form).attr('id')+'-btn').addClass("is-loading no-click");

   // $('.has-loader').addClass('has-loader-active');
    $('#request_event')[0].reset();
  var action = $(form).attr('action');
    $.ajax({
      type: "POST",
      enctype: 'multipart/form-data',
      url: action,
      data: formData,
      processData: false,
      contentType: false,
      cache: false,
      dataType: "json",

      success: function(data) { 

     //   $('#'+$(form).attr('id')+'-btn').removeClass("is-loading no-click");
         $('.close').trigger('click');
        if(data.status == 1) {

          notyf.success(data.msg, "Success", {
          timeOut: "1800"
          });
        }else if(data.status == 0) {
           notyf.error(data.msg, "Failed", "Oops!", {
          timeOut: "1800"
          });
          
          
        }
       
      }
    })
    return false;
  }
});




  
$('#report_issue').validate({
  submitHandler: function(form) { 
    
  var myform = $('#'+$(form).attr('id'))[0];
    //is-loading no-click
   // branch-form-btn
  var formData = new FormData(myform);

   // $('#'+$(form).attr('id')+'-btn').addClass("is-loading no-click");

   // $('.has-loader').addClass('has-loader-active');
    
  var action = $(form).attr('action');
    $.ajax({
      type: "POST",
      enctype: 'multipart/form-data',
      url: action,
      data: formData,
      processData: false,
      contentType: false,
      cache: false,
      dataType: "json",

      success: function(data) { 

     //   $('#'+$(form).attr('id')+'-btn').removeClass("is-loading no-click");

        if(data.status == 1) {

          notyf.success(data.msg, "Success", {
          timeOut: "1800"
          });
         setTimeout(function(){ $('.close').trigger('click'); }, 2000);
        }else if(data.status == 0) {
           notyf.error(data.msg, "Failed", "Oops!", {
          timeOut: "1800"
          });
          setTimeout(function(){ $('.close').trigger('click'); }, 2000);
          
        }
      }
    })
    return false;
  }
});


$('#change_ticket_type').validate({
  submitHandler: function(form) { 
    
  var myform = $('#'+$(form).attr('id'))[0];
    //is-loading no-click
   // branch-form-btn
  var formData = new FormData(myform);

   // $('#'+$(form).attr('id')+'-btn').addClass("is-loading no-click");

   // $('.has-loader').addClass('has-loader-active');
    
  var action = $(form).attr('action');
    $.ajax({
      type: "POST",
      enctype: 'multipart/form-data',
      url: action,
      data: formData,
      processData: false,
      contentType: false,
      cache: false,
      dataType: "json",

      success: function(data) { 

     //   $('#'+$(form).attr('id')+'-btn').removeClass("is-loading no-click");

        if(data.status == 1) {

          notyf.success(data.msg, "Success", {
          timeOut: "1800"
          });
         setTimeout(function(){ $('.close').trigger('click'); }, 2000);
        }else if(data.status == 0) {
           notyf.error(data.msg, "Failed", "Oops!", {
          timeOut: "1800"
          });
          setTimeout(function(){ $('.close').trigger('click'); }, 2000);
          
        }
      }
    })
    return false;
  }
});


$('.validate_form_v2').validate({
  submitHandler: function(form) { 
    
  var myform = $('#'+$(form).attr('id'))[0];
    //is-loading no-click
   // branch-form-btn
  var formData = new FormData(myform);

   // $('#'+$(form).attr('id')+'-btn').addClass("is-loading no-click");

   // $('.has-loader').addClass('has-loader-active');
  
var submit = $('#'+$(form).attr('id')).find(':submit');
submit.attr("disabled", true);
submit.html('<i class="fa fa-spinner fa-spin" style="color:#color: #325edd;"></i>&nbsp;Please Wait ...');


  var action = $(form).attr('action');
    $.ajax({
      type: "POST",
      enctype: 'multipart/form-data',
      url: action,
      data: formData,
      processData: false,
      contentType: false,
      cache: false,
      dataType: "json",

      success: function(data) { 
        submit.html("Submit");
     //   $('#'+$(form).attr('id')+'-btn').removeClass("is-loading no-click");

        if(data.status == 1) {

          notyf.success(data.msg, "Success", {
          timeOut: "1800"
          });
          setTimeout(function(){ window.location.href = data.redirect_url; }, 2000);
        }else if(data.status == 0) {
          submit.attr("disabled", false); 
           notyf.error(data.msg, "Failed", "Oops!", {
          timeOut: "1800"
          });
          setTimeout(function(){ window.location.href = data.redirect_url; }, 2000);
          
        }
      }
    })
    return false;
  }
});




function update_ticket_status(id,status){

 swal({
    title: 'Are you sure want to change E-Ticket Status ?',
    text: "Approve or Reject E-Ticket !",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#0CC27E',
    cancelButtonColor: '#FF586B',
    confirmButtonText: 'Yes, Change it!',
    cancelButtonText: 'No, cancel!',
    confirmButtonClass: 'button h-button is-primary',
    cancelButtonClass: 'button h-button is-danger',
    buttonsStyling: false
  }).then(function (res) {


    if (res.value == true) {
      var reason = "";
      if(status == 6){
       reason = prompt("Please Enter the reason for Rejection ", "Invalid File Format.");
      }
      


      $.ajax({
        url: base_url + 'game/orders/update_ticket_status',
        method: "POST",
        data : {"ticket_id" : id,"status" : status,"reason" : reason},
        dataType: 'json',
        success: function (result) {

           if (result) {

            swal('Updated !', result.msg, 'success');

          }
          else {
            swal('Updation Failed !', result.msg, 'error');

          }

          setTimeout(function () { window.location.reload(); }, 2000);
        }
      });
    }
    else {

    }



  }, function (dismiss) {

  });

}

function update_booking_status(bg_id,status){

var sendmail = $('#sendmail').is(":checked");
var mail_enable = 0;
if(sendmail == true){
 mail_enable = 1;
}

 swal({
    title: 'Are you sure want to change Booking Status ?',
    text: "Email will go to user if status change !",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#0CC27E',
    cancelButtonColor: '#FF586B',
    confirmButtonText: 'Yes, Change it!',
    cancelButtonText: 'No, cancel!',
    confirmButtonClass: 'button h-button is-primary',
    cancelButtonClass: 'button h-button is-danger',
    buttonsStyling: false
  }).then(function (res) {


    if (res.value == true) {

      $.ajax({
        url: base_url + 'game/orders/update_booking_status',
        method: "POST",
        data : {"bg_id" : bg_id,"status" : status,"mail_enable" : mail_enable},
        dataType: 'json',
        success: function (result) {

          if (result) {

            swal('Updated !', result.msg, 'success');

          }
          else {
            swal('Updation Failed !', result.msg, 'error');

          }

          setTimeout(function () { window.location.reload(); }, 2000);
        }
      });
    }
    else {

    }



  }, function (dismiss) {

  });

}
function delete_data(id) {


	var action = $("#branch_" + id).attr("data-href");
  

	swal({
		title: 'Are you sure?',
		text: "Are you sure want to do the changes!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#0CC27E',
		cancelButtonColor: '#FF586B',
		confirmButtonText: 'Yes, Proceed!',
		cancelButtonText: 'No, Cancel!',
		confirmButtonClass: 'button h-button is-primary',
		cancelButtonClass: 'button h-button is-danger',
		buttonsStyling: false
	}).then(function (res) {


		if (res.value == true) {

			$.ajax({
				url: action,
				method: "POST",
				dataType: 'json',
				success: function (result) {

					if (result) {

						swal('Deleted!', result.msg, 'success');

					}
					else {
						swal('Cancelled', result.msg, 'error');

					}

					setTimeout(function () { window.location.reload(); }, 2000);
				}
			});
		}
		else {

		}



	}, function (dismiss) {

	});



}

function remove_ticket_file(action) {

  swal({
    title: 'Are you sure?',
    text: "You won't be able to remove this ticket!",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#0CC27E',
    cancelButtonColor: '#FF586B',
    confirmButtonText: 'Yes, delete it!',
    cancelButtonText: 'No, cancel!',
    confirmButtonClass: 'button h-button is-primary',
    cancelButtonClass: 'button h-button is-danger',
    buttonsStyling: false
  }).then(function (res) {


    if (res.value == true) {

      $.ajax({
        url: action,
        method: "POST",
        dataType: 'json',
        success: function (result) {

          if (result) {

            swal('Deleted!', result.msg, 'success');

          }
          else {
            swal('Cancelled', result.msg, 'error');

          }

          setTimeout(function () { window.location.reload(); }, 2000);
        }
      });
    }
    else {

    }



  }, function (dismiss) {

  });



}

jQuery().ready(function () {

  $('#upload_ticket').validate({

  submitHandler: function(form) { 
    
    var myform = $('#'+$(form).attr('id'))[0];
    //is-loading no-click
   // branch-form-btn
    var formData = new FormData(myform);
    
    var action = $(form).attr('action');
    $.ajax({
      type: "POST",
      url: action,
      data: formData,
      processData: false,
      contentType: false,
      cache: false,
      dataType: "json",

      success: function(data) {

         if(data.status == 1) {

          notyf.success(data.msg, "Success", {
          timeOut: "1800"
          });
        }else if(data.status == 0) {
           notyf.error(data.msg, "Failed", "Oops!", {
          timeOut: "1800"
          });
          
          
        }
        setTimeout(function(){ window.location.reload(); }, 2000);

      }
    })
    return false;
  }
});


	jQuery.validator.addMethod("alphas", function (value, element) {
		return this.optional(element) || /^[a-zA-Z ]*$/.test(value);
	}, "Letters only please");
	jQuery.validator.addMethod("alphanums", function (value, element) {
		return this.optional(element) || /^[a-zA-Z ]*$/.test(value);
	}, "Letters and numbers only please");
	jQuery("#category-form").validate({
		rules: {
			category_name: {
				required: !0,
				minlength: 2,
				alphanums: !0
			},
			status: {
				required: !0
			}
		},
		messages: {
			category_name: {
				required: "Please enter category name",
			},
			status: {
				required: "Please select status",
			},

		}
	});
	$('.form_req_validation').find('.required').each(function() {
    $(this).rules('add', {
        required: true,
        minlength: 2,
        messages: {
            required: "Required input",
            minlength: jQuery.format("At least {0} characters are necessary")
        }
    });
});

});
