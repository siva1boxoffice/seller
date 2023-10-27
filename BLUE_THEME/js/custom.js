$( document ).ready(function() {
                $('.select2').select2();


});

var loadFile = function(event) {

  var output = document.getElementById('display_profile');
  output.src = URL.createObjectURL(event.target.files[0]);
  output.onload = function() {
  URL.revokeObjectURL(output.src) // free memory
  }
      
};

 function display_match(){ 

 
  var team_1 = "";
  if($("#team1").val() != ''){
    var team_1 = $("#team1 option:selected").text();
  }
  var team_2 = "";
  if($("#team2").val() != ''){
    var team_2 = $("#team2 option:selected").text();
  }
  

  $('#matchname').val(team_1 +' Vs '+ team_2);

 }

function get_state_city(country_id) {
  if(country_id != ''){ 
    $('#city').html('');
    $.ajax({
      type: "POST",
      dataType: "json",
      url: base_url + 'event/matches/get_city',
      data: {'country_id' : country_id},
      success: function(res_data) {

          var state_city = JSON.parse(JSON.stringify(res_data));

        $('#city').html(state_city.city);
        $('#state').val(state_city.state);
      }
    })

  }
}

function get_state(country_id){
  if(country_id != ''){ 

    $.ajax({
      type: "POST",
      url: base_url + 'home/master/get_state',
      data: {'country_id' : country_id},
      dataType: "json",
      success: function(data) {
       
        var country = JSON.parse(JSON.stringify(data));
        var states  = country.states;
        var stateArr = [];
        var state_name = state;
        //$('#state_name').html('');
        stateArr[0] = "<option value=''>-Select State-</option>";
        $.each(states, function(i, option)
        { 
        var sel = '';
        if(state_name == option['id']){
        sel = 'selected';
        }
        stateArr[i+1] = "<option value='" + option['id'] + "' "+sel +">" + option['name'] + "</option>";


        });

        $('#state').html(stateArr.join(''));
      
      }
    })

  }
}


function get_city(state_id){
  if(state_id != ''){ 

    $.ajax({
      type: "POST",
      url: base_url + 'home/master/get_city',
      data: {'state_id' : state_id},
      dataType: "json",
      success: function(data) {
       
        var state = JSON.parse(JSON.stringify(data));
        var citites  = state.cities;
        var cityArr = [];
        var city_name = city;
        //$('#state_name').html('');
        cityArr[0] = "<option value=''>-Select City-</option>";
        $.each(citites, function(i, option)
        { 
        var sel = '';
        if(city_name == option['id']){
        sel = 'selected';
        }
        cityArr[i+1] = "<option value='" + option['id'] + "' "+sel +">" + option['name'] + "</option>";

        
        });

        $('#city').html(cityArr.join(''));
      
      }
    })

  }
}

function set_language(language_code){

  $.ajax({
      type: "POST",
      url: base_url + 'home/master/set_language',
      data: {'language_code' : language_code},
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
         setTimeout(function(){   window.location.reload(); }, 2000);
        
      }
    })
}

function set_storefront(admin_id){

 initConfirm('Switch Storefront Alert', "Setting will be switched to New Storefront.Are you sure to switch ?", false, false, 'Switch','Cancel', function (closeEvent) {
  $.ajax({
      type: "POST",
      url: base_url + 'home/master/set_storefront',
      data: {'admin_id' : admin_id},
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
         setTimeout(function(){   window.location.reload(); }, 2000);
      }
    })

          })
}

