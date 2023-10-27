function filter_search(filter="",pageno=""){

    
    var action = base_url + "tickets/index/filter_search/"+pageno;
    

     $.ajax({
      type: "POST",
      dataType: "json",
       url: action,
      data: {"filter":filter},
      success: function(list) {

       
       $('#search_flag').val(filter);
         $('#list_body').html("");
        $('#list_body').html(list.tickets);
        
        //$('#state').val(state_city.state);
      }
    });
}

function auto_save(column_name,column_value,sno,id){

                  
                    if(column_name == "price"){ 
                        $("#close-"+id).removeClass("showen");
                        $("#close-"+id).addClass("hidden");

                        $("#check-"+id).removeClass("showen");
                        $("#check-"+id).addClass("hidden");

                        $("#spin-"+id).removeClass("hidden");
                        $("#spin-"+id).addClass("showen");
                       
                    }
                    $.ajax({
                        type: 'POST',
                        url: base_url + 'tickets/index/auto_save',
                        data: {
                            'column_name' : column_name,
                            'column_value' : column_value,
                            'sno'   : sno,
                        },
                        dataType: "json",
                        success: function(data) {
                        if(data.status == 1) {

                        $("#spin-"+id).removeClass("showen");
                        $("#spin-"+id).addClass("hidden");

                        $("#check-"+id).removeClass("hidden");
                        $("#check-"+id).addClass("showen");

                          setTimeout(function(){
                            $("#check-"+id).removeClass("showen");
                            $("#check-"+id).addClass("hidden");
                           }, 2000);

                        }else if(data.status == 0) {
                            $("#check-"+id).removeClass("showen");
                            $("#check-"+id).addClass("hidden");

                            $("#spin-"+id).removeClass("showen");
                            $("#spin-"+id).addClass("hidden");

                            $("#close-"+id).removeClass("hidden");
                            $("#close-"+id).addClass("showen");

                            setTimeout(function(){
                            $("#close-"+id).removeClass("showen");
                            $("#close-"+id).addClass("hidden");
                           }, 2000);
                            
                        }
                        //load_tickets_details(match_id,0);
                            

                        }
                    });
}



  $(".auto_disable").on("change", function(evt) {

               var match_id = $(this).attr('match-id');
               var hours = $(this).val();
                var action = base_url + "tickets/update_auto_disable/";
                $.ajax({
                type: "POST",
                dataType: "json",
                url: action,
                data: {"match_id":match_id,"auto_disable":hours},
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
                setTimeout(window.location.reload(), 100);

                }
                });

            });


function update_enquiry_status(id,status,flag){


    var action = base_url + "tickets/index/update_enquiry_status/";
    

     $.ajax({
      type: "POST",
      dataType: "json",
       url: action,
      data: {"id":id,"status":status,"flag":flag},
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
    });

}

function load_tickets_v1(match_id="",pageno="",seller_id='',last_ticket_id=''){


  
    $.ajax({
      type: "POST",
      dataType: "json",
      url: base_url + 'tickets/index/load_tickets/'+pageno+'/',
      data: {'match_id' : match_id,'seller_id' : seller_id,'last_ticket_id' : last_ticket_id},
      success: function(list) {

       
        $('#list_body').html("");
        $('#list_body').html(list.tickets);
      }
    })

}


function load_tickets(match_id="",pageno="",seller_id='',last_ticket_id=''){

    $('#list_body').html('<span style="text-align:center !important;"><i class="fa fa-spinner fa-spin" style="color:#0037D5;"></i>&nbsp;Please Wait ...</span>');
    $.ajax({
      type: "POST",
      dataType: "json",
      url: base_url + 'tickets/index/load_tickets/'+pageno+'/',
      data: {'match_id' : match_id,'seller_id' : seller_id,'last_ticket_id' : last_ticket_id},
      success: function(list) {

       
        $('#list_body').html("");
        $('#list_body').html(list.tickets);
      }
    })

}
function load_tickets_details(match_id="",pageno="",seller_id='',last_ticket_id=''){

  
    $.ajax({
      type: "POST",
      dataType: "json",
      url: base_url + 'tickets/index/load_tickets_details/'+pageno+'/',
      data: {'match_id' : match_id,'seller_id' : seller_id,'last_ticket_id' : last_ticket_id},
      success: function(list) {

       
        $('#list_body').html("");
        $('#list_body').html(list.tickets);
      }
    })

}

function oe_load_tickets_details(match_id="",pageno="",seller_id='',last_ticket_id=''){

  
    $.ajax({
      type: "POST",
      dataType: "json",
      url: base_url + 'tickets/index/oe_load_tickets_details/'+pageno+'/',
      data: {'match_id' : match_id,'seller_id' : seller_id,'last_ticket_id' : last_ticket_id},
      success: function(list) {

       
        $('#list_body').html("");
        $('#list_body').html(list.tickets);
      }
    })

}

function load_filter(pageno=""){

   

    $.ajax({
      type: "POST",
      dataType: "json",
      url: base_url + 'tickets/index/filter_tickets/'+pageno,
      data:  $('#filter-form').serialize(),
      success: function(list) {

       
        $('#list_body').html("");
        $('#list_body').html(list.tickets);
        
        
        //$('#state').val(state_city.state);
      }
    })

}


 

$(document).on('click', ".ticket_save", function(e) {
    e.stopImmediatePropagation();



var ticketid        = $(this).attr("data-s_no");
var match_id        = $(this).attr("data-match");
var ticket          = $(this).attr("data-ticket");
var ticket_status   = $("#ticket-status-"+ticket).is(":checked");
var ticket_type     = $("#ticket-type-"+ticket).val();
var ticket_category = $("#ticket-category-"+ticket).val();
var ticket_block    = $("#ticket-block-"+ticket).val();
/*var home_down       = $("#ticket-home-down-"+ticket).val();*/
var ticket_row      = $("#ticket-row-"+ticket).val();
/*var ticket_seat     = $("#ticket-seat-"+ticket).val();
var ticket_quantity = $("#ticket-quantity-"+ticket).val();*/
var ticket_split    = $("#ticket-split-"+ticket).val();
var ticket_price    = $("#ticket-price-"+ticket).val();
/*var sell_type       = $("#sell-type-"+ticket).val();
var ticket_track    = $("#ticket-track-"+ticket).val();*/

if(ticket_price == '' || ticket_price <= 0){ 
return false;
}
/*if(ticket_quantity == '' || ticket_quantity <= 0){ 
return false;
}*/

var search_flag     = $("#search_flag").val();
                    $.ajax({
                        type: 'POST',
                        url: base_url + 'tickets/index/ticket_update_v1',
                        data: {
                            'ticketid' : ticketid,
                            'match_id' : match_id,
                            'ticket'   : ticket,
                            'ticket_status'   : ticket_status,
                            'ticket_type'   : ticket_type,
                            'ticket_category'   : ticket_category,
                            'ticket_block'   : ticket_block,
                            //'home_down'   : home_down,
                            'ticket_row'   : ticket_row,
                            //'ticket_seat'   : ticket_seat,
                            //'ticket_quantity'   : ticket_quantity,
                            'ticket_split'   : ticket_split,
                            'ticket_price' : ticket_price,
                            /*'sell_type' : sell_type,
                            'ticket_track' : ticket_track*/
                        },
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
                        load_tickets_details(match_id,0);

                           /* if(search_flag == "listing"){
                                load_tickets(match_id);
                            }
                            else{
                                $("#match_id").val(match_id);
                                $("#filter-form").submit();
                            }*/
                            

                        }
                    });
});

$(document).on('change', ".ticket_status", function(e) { 
 e.stopImmediatePropagation();
var ticket_status   = $(this).is(":checked");
var flag = $(this).attr("data-flag");

                    $.ajax({
                        type: 'POST',
                        url: base_url + 'tickets/index/update_ticket_status_v1',
                        data: {
                            'ticket_id' : $(this).attr("data-sno"),
                            'ticket_status'   : ticket_status,
                            'flag' : flag
                        },
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
                        if(data.flag == 'details'){
                        //load_tickets_details(data.match_id,0);
                        }
                        

                         //load_tickets();

                        }
                    });
});


$(document).on('change', ".update_ticket_status", function(e) { 
 e.stopImmediatePropagation();
var ticket_status   = $(this).is(":checked");
var flag = $(this).attr("data-flag");

                    $.ajax({
                        type: 'POST',
                        url: base_url + 'tickets/index/update_ticket_status',
                        data: {
                            'ticket_id' : $(this).attr("ticket_id"),
                            'ticket_status'   : ticket_status,
                            'flag' : flag
                        },
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
                        
                        

                         //load_tickets();

                        }
                    });
});

$(document).on('change', ".all_ticket_status", function(e) {
 e.stopImmediatePropagation();
var ticket_status   = $(this).is(":checked");
var flag = $(this).attr("data-flag");

                    $.ajax({
                        type: 'POST',
                        url: base_url + 'tickets/index/ticket_update_status',
                        data: {
                            'match_id' : $(this).attr("match-id"),
                            'ticket_status'   : ticket_status,
                            'flag' : flag
                        },
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
                        if(data.flag == 'details'){
                        load_tickets_details(data.match_id,0);
                        }
                        

                         //load_tickets();

                        }
                    });
});


$(document).on('click', ".save_ticket_details_btn", function(e) { 
     e.stopImmediatePropagation();
     var search_flag     = $("#search_flag").val();

    var myid   = $(this).attr("data-url");
   
    var myform = $("#"+myid)[0];
    var formData = new FormData(myform);
    var action = $("#"+myid).attr('action');
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

         $('.notes_cancel').trigger('click');
        if(data.status == 1) {

          notyf.success(data.msg, "Success", {
          timeOut: "1800"
          });
        }else if(data.status == 0) {
           notyf.error(data.msg, "Failed", "Oops!", {
          timeOut: "1800"
          });
          
        }
        load_tickets_details(data.match_id,0);
        /*if(search_flag == "listing"){
        load_tickets(match_id);
        }
        else{
        $("#match_id").val(match_id);
        $("#filter-form").submit();
        }*/

      }
    })
    return false;
  

})



$(document).on('click', ".save_mass_duplicates", function(e) { 
     e.stopImmediatePropagation();
     
    var myid   = $(this).attr("data-url");console.log('myid' + $(this).attr("data-url"));
   
    var myform = $("#"+myid)[0];
    var formData = new FormData(myform);
    var action = $("#"+myid).attr('action');

    var submit = $('#'+myid).find('button:first');
    submit.attr("disabled", true);
    submit.html('<i class="fa fa-spinner fa-spin" style="color:#color: #325edd;"></i>&nbsp;Duplicating ...');



    //do something special
  
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

          submit.html("Save");
         $('.close').trigger('click');
        setTimeout(
  function() 
  {
        if(data.status == 1) {

          notyf.success(data.msg, "Success", {
          timeOut: "1800"
          });
        }else if(data.status == 0) {
             submit.attr("disabled", false); 
           notyf.error(data.msg, "Failed", "Oops!", {
          timeOut: "1800"
          });
          
        }
        if(data.event_flag == 'E'){
        load_tickets_details(data.match_id,0,'',data.ticket_last_id);
        }
        else{
        oe_load_tickets_details(data.match_id,0,'',data.ticket_last_id);
        }
        
        }, 500);

      }
    })
    return false;
  

})

$(document).on('click', ".save_mass_duplicates_oe", function(e) { 
     e.stopImmediatePropagation();
     
    var myid   = $(this).attr("data-url");console.log('myid' + $(this).attr("data-url"));
   
    var myform = $("#"+myid)[0];
    var formData = new FormData(myform);
    var action = $("#"+myid).attr('action');

    var submit = $('#'+myid).find('button:first');
    submit.attr("disabled", true);
    submit.html('<i class="fa fa-spinner fa-spin" style="color:#color: #325edd;"></i>&nbsp;Duplicating ...');



    //do something special
  
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

          submit.html("Save");
         $('.close').trigger('click');
        setTimeout(
  function() 
  {
        if(data.status == 1) {

          notyf.success(data.msg, "Success", {
          timeOut: "1800"
          });
        }else if(data.status == 0) {
             submit.attr("disabled", false); 
           notyf.error(data.msg, "Failed", "Oops!", {
          timeOut: "1800"
          });
          
        }
        oe_load_tickets_details(data.match_id,0,'',data.ticket_last_id);
        }, 500);

      }
    })
    return false;
  

})

$(document).on('click', ".mass_duplicate", function(e) {
    e.stopImmediatePropagation();
    var ticketid   = $(this).attr("data-s_no");
    var match_id   = $(this).attr("data-match");
    $("#duplication_part").html('<span class="text-center loading_img"><img src="'+base_url +'assets/img/loader.gif" width="50px" alt="Loading..." ></span>');
    

     $.ajax({
                        type: 'POST',
                        url: base_url + 'tickets/index/mass_duplicate',
                        data: {
                            'ticketid' : ticketid,
                            'match_id' : match_id
                        },
                        dataType: "json",
                        success: function(data) {
                            $("#duplication_part").html(data.tickets);
                        }
                    });
})

$(document).on('click', ".ticket_copy", function(e) {
    e.stopImmediatePropagation();

var ticketid   = $(this).attr("data-s_no");
var match_id   = $(this).attr("data-match");
var search_flag     = $("#search_flag").val();


                    $.ajax({
                        type: 'POST',
                        url: base_url + 'tickets/index/ticket_duplicate',
                        data: {
                            'ticketid' : ticketid,
                            'match_id' : match_id
                        },
                        dataType: "json",
                        success: function(data) {
                           // console.log(data);
                        if(data.status == 1) {

                        notyf.success(data.msg, "Success", {
                        timeOut: "1800"
                        });
                        }else if(data.status == 0) {
                        notyf.error(data.msg, "Failed", "Oops!", {
                        timeOut: "1800"
                        });
                        } 
                        load_tickets_details(match_id,0,'',data.ticket_last_id);

                         

                        }
                    });
});
             

$(document).on('click', ".accounts_delete", function() { 

var addressid        = $(this).attr("data-account"); 


initConfirm('Delete Bank Account Alert', "Are you sure to delete this Bank Account?", false, false, 'Delete','Cancel', function (closeEvent) {
 
    

                    $.ajax({
                        type: 'POST',
                        url: base_url + 'payout/bank_account_delete',
                        data: {
                            'addressid' : addressid
                        },
                        dataType: "json",
                        success: function(data) {
                           
                           $('.close').trigger('click');

                                 setTimeout(function(){
                            
                             if(data.status == 1) {

                            notyf.success(data.msg, "Success", {
                            timeOut: "1800"
                            });
                            }else if(data.status == 0) {
                            notyf.error(data.msg, "Failed", "Oops!", {
                            timeOut: "1800"
                            });
                            }
                            
                            window.location.reload();
                           }, 500);

                           
                         

                        }
                    });
});
                });


$(document).on('click', ".ticket_delete", function() { 

var ticketid        = $(this).attr("data-s_no");
var match_id        = $(this).attr("data-match");   
var search_flag     = $("#search_flag").val();


initConfirm('Delete Ticket Alert', "Are you sure to Delete Ticket?", false, false, 'Delete','Cancel', function (closeEvent) {
 
    

                    $.ajax({
                        type: 'POST',
                        url: base_url + 'tickets/index/ticket_delete',
                        data: {
                            'ticketid' : ticketid,
                            'match_id' : match_id
                        },
                        dataType: "json",
                        success: function(data) {
                           
                           $('.close').trigger('click');

                                 setTimeout(function(){
                            
                             if(data.status == 1) {

                            notyf.success(data.msg, "Success", {
                            timeOut: "1800"
                            });
                            }else if(data.status == 0) {
                            notyf.error(data.msg, "Failed", "Oops!", {
                            timeOut: "1800"
                            });
                            }
                             load_tickets_details(match_id,0);


                           }, 500);

                           
                            /*if(search_flag == "listing"){
                                load_tickets(match_id);
                            }
                            else{
                                $("#match_id").val(match_id);
                                $("#filter-form").submit();
                            }*/

                        }
                    });
});
                });

$(document).on('change', ".ticket_category", function() {

    var match_id        = $(this).attr("data-match");
    var ticketid        = $(this).attr("data-ticket");
    var ticketflag      = $(this).attr("data-flag");
    var ticket_category = $(this).val();

                    $.ajax({
                        type: 'POST',
                        url: base_url + 'tickets/get_block_by_stadium_id',
                        data: {
                            'match_id': match_id,
                            'ticketid' : ticketid,
                            'category_id': ticket_category
                        },
                        dataType: "json",
                        success: function(data) {
                           
                         if(ticketflag == "orderinfo"){

                            $("#ticket-blocks-"+ticketid).empty().html('<option value="0" >Any</option>');
                            if (data) {
                                $.each(data, function(index, item) {

                                    $("#ticket-blocks-"+ticketid).append('<option value="' + item + '">' + index + '</option>');

                                })

                            }
                            

                           }
                           else if(ticketflag == "clone"){

                            $("#clone-block-"+ticketid).empty().html('<option value="0" >Any</option>');
                            if (data) {
                                $.each(data, function(index, item) {

                                    $("#clone-block-"+ticketid).append('<option value="' + item + '">' + index + '</option>');

                                })

                            }

                           }
                           else{ 

                            $("#ticket-block-"+ticketid).empty().html('<option value="0" >Any</option>');
                            if (data) {
                                $.each(data, function(index, item) {

                                    $("#ticket-block-"+ticketid).append('<option value="' + item + '">' + index + '</option>');

                                })

                            }

                           }
                        }
                    });

                });


function deleteaction(actionType,selltype) {

   var proceed = false;

            initConfirm('Delete Ticket Alert', "Are you sure to Delete Ticket?", false, false, 'Delete','Cancel', function (closeEvent) {
              proceed = true;
              doaction(actionType,selltype);
          });


 }
  function doaction(actionType,selltype) {

    
          var tickets_data = [];
          var apply = false;

          $('.singlecheck').each(function () {
               
               
              if ($(this).is(':checked')) {
                tickets_data.push($(this).val());
                apply = true;
              }
              
          });

          if(apply == true){

                  var action = base_url + "tickets/index/update_ticket_status";

                  $.ajax({
                  type: "POST",
                  dataType: "json",
                  url: action,
                  data: {'tickets' : tickets_data,'ticket_action' : actionType,'selltype' : selltype},
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
                      if(data.selltype == 'match'){ 
                      if(data.event_flag == 'OE'){
                      get_oe_ticket_search('',$("#publish_unpublish").val());
                      }
                      else{
                      get_ticket_search('',$("#publish_unpublish").val());
                      }
                        
                      }
                      else if(data.selltype == 'tickets'){
                       if(data.event_flag == 'OE'){
                       oe_load_tickets_details(data.match_id);
                       }
                       else{
                       load_tickets_details(data.match_id);
                       }
                        
                      }
                    
                     
                  }
                  });
                  $('input.checkall').prop("checked", false);
              }
    

    }
   $(document).on("click",".checkall",function() { 
    
  
    if ($('input.checkall').is(':checked')) {

          $('.singlecheck').each(function () {
          $(this).prop("checked", true);
          });

    }
    else{

        $('.singlecheck').each(function () {
        $(this).prop("checked", false);
        });

    }
});






function get_order_search(order_keyword='',sort_type="",ele="",flag=""){ 

    //if(ticket_keyword.length >= 1){
        var sort_value = "";
        var sort_label = "";
        if(sort_type != ""){
             sort_label = sort_type;
             sort_value = ele.getAttribute('sort-attr');
             var elementId = ele.getAttribute('id');
             if(sort_value == "DESC"){
                var new_sort_value = "ASC"
             }
             else if(sort_value == "ASC"){
                var new_sort_value = "DESC"
             }
             ele.setAttribute('sort-attr',new_sort_value);
        }
        $('#lis_order_ajax').html('<span style="text-align:center !important;"><i class="fa fa-spinner fa-spin" style="color:#0037D5;"></i>&nbsp;Please Wait ...</span>');
    
        
        var action = base_url + "game/orders/get_order_search";

        $.ajax({
                  type: "POST",
                  dataType: "json",
                  url: action,
                  data: {'flag' : flag,'keyword' : order_keyword,'sort_label' : sort_label,'sort_value' : sort_value},
                  success: function(data) {
                            $('#lis_order_ajax').html("");
                            $('#lis_order_ajax').html(data.orders);
                  }
                  });
    //}   
    
}

$(".add_tickets").on("click",function(){
   var length_checked = $(".matchcheck:checkbox:checked").length;
   if(length_checked >= 1){
     var match_ids = [];
     $(".matchcheck:checkbox:checked").each(function() {
        match_ids.push($(this).val());
    });
     if(match_ids.length >= 1){

      var action = base_url + "tickets/get_bulk_events";
    

        $.ajax({
                  type: "POST",
                  dataType: "json",
                  url: action,
                  data: {'flag' : 'save','match_ids' : match_ids},
                  success: function(data) {
                     if(data.status == 1) {

                            notyf.success(data.msg, "Success", {
                            timeOut: "1800"
                            });
                            setTimeout(function(){ window.location.href = data.redirect_url; }, 2000);
                          
                            }else if(data.status == 0) {
                            notyf.error(data.msg, "Failed", "Oops!", {
                            timeOut: "1800"
                            });
                            }
                  }
                  });
   /*  $.ajax({
      type: "POST",
      dataType: 'json',
    cache : false,
      url: action,
      data: {"match_ids":match_ids},
      success: function(list) {

      }
    });*/

     }
   
   }
   else{
    notyf.error("Please Choose Any Event.", "Failed", "Oops!", {
                            timeOut: "1800"
                            });
   }
})




function get_ticket_search(ticket_keyword='',match_type=''){

    //if(ticket_keyword.length >= 1){
      $('#list_body').html(' <div style="background: #FAFBFF;     font-size: 15px; padding: 10px; text-align: center; font-weight: 700;border: 1px solid #dee2e6;color: #323A70;"><i class="fa fa-spinner fa-spin" style="color:#323A70;"></i>&nbsp;Please Wait ...</div>');
    
        
        var action = base_url + "tickets/index/get_ticket_search";

        $.ajax({
                  type: "POST",
                  dataType: "json",
                  url: action,
                  data: {'keyword' : ticket_keyword,'match_type' : match_type},
                  success: function(data) {
                            $('#list_body').html("");
                            $('#list_body').html(data.tickets);
                  }
                  });
    //}   
    
}

function get_oe_ticket_search(ticket_keyword='',match_type=''){

    //if(ticket_keyword.length >= 1){
        $('#list_body').html('<span style="text-align:center !important;"><i class="fa fa-spinner fa-spin" style="color:#0037D5;"></i>&nbsp;Please Wait ...</span>');
    
        
        var action = base_url + "tickets/index/get_oe_ticket_search";

        $.ajax({
                  type: "POST",
                  dataType: "json",
                  url: action,
                  data: {'keyword' : ticket_keyword,'match_type' : match_type},
                  success: function(data) {
                            $('#list_body').html("");
                            $('#list_body').html(data.tickets);
                  }
                  });
    //}   
    
}
