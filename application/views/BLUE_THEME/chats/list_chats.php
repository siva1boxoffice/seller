  <?php $this->load->view(THEME_NAME.'/common/header');?>
     <link rel="stylesheet" href="<?php echo base_url().THEME_NAME;?>/css/jquery-ui.min.css?v=1.2">
     
     <style>
      .messaging img{ max-width:100%;}
       .inbox_people {
  background: #f8f8f8 none repeat scroll 0 0;
  float: left;
  overflow: hidden;
  width: 40%; border-right:1px solid #c4c4c4;
}
.inbox_msg {
  border: 1px solid #c4c4c4;
  clear: both;
  overflow: hidden;
}
.top_spac{ margin: 20px 0 0;}


.recent_heading {float: left; width:40%;}
.srch_bar {
  display: inline-block;
  text-align: right;
  width: 60%;
}
.headind_srch{ padding:10px 29px 10px 20px; overflow:hidden; border-bottom:1px solid #c4c4c4;}

.recent_heading h4 {
  color: #05728f;
  font-size: 21px;
  margin: auto;
}
.srch_bar input{ border:1px solid #cdcdcd; border-width:0 0 1px 0; width:80%; padding:2px 0 4px 6px; background:none;}
.srch_bar .input-group-addon button {
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
  border: medium none;
  padding: 0;
  color: #707070;
  font-size: 18px;
}
.srch_bar .input-group-addon { margin: 0 0 0 -27px;}

.chat_ib h5{ font-size:15px; color:#464646; margin:0 0 8px 0;}
.chat_ib h5 span{ font-size:13px; float:right;}
.chat_ib p{ font-size:14px; color:#989898; margin:auto}
.chat_img {
  float: left;
  width: 11%;
}
.chat_ib {
  float: left;
  padding: 0 0 0 15px;
  width: 88%;
}

.chat_people{ overflow:hidden; clear:both;}
.chat_list {
  border-bottom: 1px solid #c4c4c4;
  margin: 0;
  padding: 18px 16px 10px;
  cursor: pointer;
}
.inbox_chat { height: 320px; overflow-y: auto;}

.active_chat{ background:#ebebeb;}

.incoming_msg_img {
  display: inline-block;
  width: 6%;
}
.received_msg {
  display: inline-block;
  padding: 0 0 0 10px;
  vertical-align: top;
  width: 92%;
 }
 .received_withd_msg p {
  background: #ebebeb none repeat scroll 0 0;
  border-radius: 3px;
  color: #646464;
  font-size: 14px;
  margin: 0;
  padding: 5px 10px 5px 12px;
  width: 100%;
}
.time_date {
  color: #747474;
  display: block;
  font-size: 12px;
  margin: 8px 0 0;
}
.received_withd_msg { width: 57%;}
.mesgs {
  float: left;
  padding: 30px 15px 0 25px;
  width: 60%;
}

 .sent_msg p {
  background: #05728f none repeat scroll 0 0;
  border-radius: 3px;
  font-size: 14px;
  margin: 0; color:#fff;
  padding: 5px 10px 5px 12px;
  width:100%;
}
.outgoing_msg{ overflow:hidden; margin:26px 0 26px;}
.sent_msg {
  float: right;
  width: 46%;
}
.input_msg_write input {
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
  border: medium none;
  color: #4c4c4c;
  font-size: 15px;
  min-height: 48px;
  width: 100%;
}

.type_msg {border-top: 1px solid #c4c4c4;position: relative;}
.msg_send_btn {
  background: #05728f none repeat scroll 0 0;
  border: medium none;
  border-radius: 50%;
  color: #fff;
  cursor: pointer;
  font-size: 17px;
  height: 33px;
  position: absolute;
  right: 0;
  top: 11px;
  width: 33px;
}
.messaging { padding: 0 0 50px 0;}
.msg_history {
  height: 280px;
  overflow-y: auto;
}
     </style>
   <div class="main page_full_widd">
      <div class="container mt-5">
        <div class="row">
          <div class="col-lg-12 ">
   
              <div class="container">
 <?php if($chats_list) { ?>
        <div class="messaging">
      <div class="inbox_msg">
        <div class="inbox_people">
          <div class="headind_srch">
            <div class="recent_heading">
              <h4>Chats</h4>
            </div>
            <div class="srch_bar">
            <!--   <div class="stylish-input-group">
                <input type="text" class="search-bar"  placeholder="Search" >
                <span class="input-group-addon">
                <button type="button"> <i class="fa fa-search" aria-hidden="true"></i> </button>
                </span> </div> -->
            </div>
          </div>
          <div class="inbox_chat">
            <?php if($chats_list) { foreach ($chats_list as $key => $value) {
            ?>
            <div class="chat_list  <?php echo $key == 0  ?  "active_chat" : "" ?>" data-id="<?php echo $value->booking_id;?>" data-user="<?php echo $value->user_id;?>">
              <div class="chat_people">
                <div class="chat_img"> <img src="<?php echo base_url().THEME_NAME;?>/images/profile-chats.png" alt="sunil"> </div>
                <div class="chat_ib">
                  <h5><?php echo $value->booking_id;?> <span class="badge"><?php echo $value->total_unread;?></span>
                   <!-- <span class="chat_date"><?php echo $value->created_at;?></span> -->
                 </h5>
                  <p><?php echo $value->created_at;?></p>
                </div>
              </div>
            </div>
          <?php }  } ?>
            
          </div>
        </div>
        <div class="mesgs">
          <div class="msg_history">
            
          </div>
          <div class="type_msg">
            <form method="post" action="" id="chats_form">
              <input type="hidden" name="message_id" id="message_id" value="">
              <input type="hidden" name="user_id" id="user_id" value="">
              <div class="input_msg_write">
                <input type="text" class="write_msg" placeholder="Type a message" required name="message" />
                <button class="msg_send_btn"  type="submit"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
              </div>
            </form>
          </div>
        </div>
      </div>
      
             </div>

           <?php }  else  {?>
            <h3 class="text-center">No Chats</h3>
           <?php } ?>
           </div>
         </div>
       </div>
     </div>
      <?php $this->load->view(THEME_NAME.'/common/footer');?>
<script type="text/javascript">
  setInterval(function () { if($(".msg_history").length > 0) fetch_user(2) }, 5000);


 $(".chat_list").click(function(){
  var id = $(this).attr("data-id"); 

  $("#message_id").val(id);

   var user_id = $(this).attr("data-user"); 

    $("#user_id").val(user_id);
  $(".chat_list").removeClass("active_chat");
  $(this).find(".badge").html("0");
  $(this).addClass("active_chat");

  fetch_user(1);

  
 }); 
  $("#chats_form").validate({
          submitHandler: function (form) {
            console.log(form);
          $.ajax({
                url: "<?php echo base_url('chats/users/save_chats') ;?>",
                method:"POST",
                data : $(form).serialize(),
                dataType: 'json',
                success:function(data){


                    var chat_message  = $(".write_msg").val();
                    var current_time = new Date().toLocaleString() ;
                    var _new_message = "";

                      _new_message +=  '<div class="outgoing_msg"> <div class="sent_msg"> <p>'+chat_message+'</p><span class="time_date"> '+current_time+'</span></span> </div></div>';


                      $(".msg_history").append(_new_message);
                     var div = $(".msg_history");
                    div.scrollTop(div.prop('scrollHeight'));
                      $(".write_msg").val("");
                   return false;
                }
            });
          return false;

          }
     });
        var message_id = $(".chat_list.active_chat").data('id') ;
          //alert(message_id);
        $("#message_id").val(message_id);
          var user_id = $(".chat_list.active_chat").data('user') ;
         $("#user_id").val(user_id);
          fetch_user();


          function fetch_user(status = 1)
       {
        var message_id = $("#message_id").val() ;
        console.log(message_id);
        $.ajax({
            url: "<?php echo base_url('chats/users/ajax_list') ;?>",
            method:"GET",
            data : { status : status , booking_id : message_id },
            dataType: 'json',
            success:function(data){
              //console.log(data);
                var _new_message = "";
                if(data.message.length > 0){
                    $.each(data.message, function(i, item) {
                      if(status == 1){
                        $(".msg_history").html(""); 
                      }

                       if(status == 2)
                        var new_message_message = "new_message";
                    else
                         var new_message_message = "";

                      if(item.send_by == 1){
                       _new_message +=  '<div class="incoming_msg '+new_message_message+'"> <div class="incoming_msg_img"> <img src="<?php echo base_url().THEME_NAME;?>/images/profile-chats.png" alt="'+item.first_name+'"> </div><div class="received_msg"> <div class="received_withd_msg"> <p>'+item.message+'</p><span class="time_date"> '+item.created_at+'</span></div></div></div>';
                      }
                      else{
                           _new_message +=  '<div class="outgoing_msg '+new_message_message+'"> <div class="sent_msg"> <p>'+item.message+'</p><span class="time_date"> '+item.created_at+'</span></span> </div></div>';
                      }

                        
                    });
                    

                }

                 $(".msg_history").append(_new_message);
                     var div = $(".msg_history");
                    div.scrollTop(div.prop('scrollHeight'));
                    // console.log("--------------");
                    // console.log(data.ajax_list.length);
                     if(data.ajax_list.length > 0){
                       $.each(data.ajax_list, function(i, item) {
                        console.log(item.booking_id + "------");
                         $("[data-id='" + item.booking_id + "']").find(".badge").html(item.message); 

                       });
                     }
               
            }
            });
    }

</script>