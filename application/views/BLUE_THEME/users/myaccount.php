  <?php $this->load->view(THEME_NAME.'/common/header');?>
    <div class="main">
      <div class="container">

        
<?php //echo "<pre>";print_r($user);?>
    <!-- seller profile Start -->
    <section class="onebox-seller-area section_50">
      <form id="profile-form" method="post" class="validate_form_v2 form_req_validation login-wrapper" action="<?php echo base_url();?>home/save_my_accounts">
                        <input type="hidden" name="admin_id" value="<?php echo $user->user_id;?>">
                        <input type="hidden" name="address_details_id" value="<?php echo $user->address_details_id;?>">
        <div class="container">
            <div class="row onebox-seller">
               
                <div class="col-md-6">
                  <div class="onebox-seller-form">
                    <h3>Personal Information</h3>
                      
                        <div class="form-row">
                          <div class="col-md-6 mb-3">
                            <label for="validationDefault01">First name</label><span class="text-danger">*</span>
                             <input type="text" class="form-control" placeholder="First Name" name="first_name" value="<?php echo $user->admin_name;?>" required>

                          </div>
                          <div class="col-md-6 mb-3">
                            <label for="validationDefault02">Last name</label><span class="text-danger">*</span>
                             <input type="text" class="form-control" placeholder="Last Name" name="last_name" value="<?php echo $user->admin_last_name;?>" required>
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="col-md-12 mb-3">
                                <label for="exampleInputEmail1">Email address</label><span class="text-danger">*</span>
                              <input type="email" name="email" class="form-control" placeholder="Email" value="<?php echo $user->admin_email;?>" required>
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="col-md-6 mb-3">
                                <label for="exampleFormControlSelect1">Area Code</label><span class="text-danger">*</span>
                                  <select class="form-control select2" id="area_code" name="area_code" required >
                                                            <?php foreach($country_lists as $country){ ?>
                                                            <option value="<?php echo $country->phonecode;?>" <?php if($user->phone_code == $country->phonecode){?> selected <?php } ?>><?php echo $country->countryid;?></option>
                                                            <?php } ?>
                                                        </select> 
                          </div>
                          <div class="col-md-6 mb-3">
                            <label for="validationDefault02">Phone</label><span class="text-danger">*</span>
                              <input type="text" name="mobile_no" class="form-control" placeholder="Mobile No." value="<?php echo $user->admin_cell_phone;?>" required>
                          </div>
                        </div>
                        <div class="form-row">
                          <div class="col-md-6 mb-3">
                            <label for="validationDefault01">Company</label><span class="text-danger">*</span>
                            <input type="text" name="company_name" class="form-control" placeholder="Company Name." value="<?php echo $user->company_name;?>" required>
                          </div>
                          <div class="col-md-6 mb-3">
                            <label for="validationDefault02">Website </label><span class="text-danger">*</span>
                            <input type="text" name="company_url" class="form-control" placeholder="Company Url." value="<?php echo $user->company_url;?>" required>
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="col-md-12 mb-3">
                                <label for="validationDefault02">Company Address</label><span class="text-danger">*</span>
                                <textarea class="form-control" rows="4" placeholder="Address" name="address" required><?php echo $user->address;?></textarea>

                          </div>
                        </div>


                        <div class="form-row">
                          <div class="col-md-6 mb-3">
                            <label for="validationDefault05">Zip Code</label><span class="text-danger">*</span>
                            <input type="text" class="form-control" placeholder="Zip Code." name="zip_code" value="<?php echo $user->zip_code;?>" required>
                          </div>

                          <div class="col-md-6 mb-3">
                            <label for="validationDefault03">Country</label><span class="text-danger">*</span>
                            <select class="form-control select2" id="country" name="country" onchange="get_state(this.value);" required>
                                                                    <option value="">-Select Country-</option>
                                                                    <?php foreach($country_lists as $country_list){ ?>
                                                                    <option value="<?php echo $country_list->id;?>" <?php if($country_list->id == $user->country){?> selected <?php } ?>><?php echo $country_list->name;?></option>
                                                                    <?php } ?>
                                                                </select>
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="col-md-6 mb-3">
                            <label for="validationDefault05">State/Province</label><span class="text-danger">*</span>
                             <select class="form-control select2" id="state" name="state" onchange="get_city(this.value);" required>
                                                                     <option value="">-Select State-</option>
                                                                </select>
                            
                          </div>

                          <div class="col-md-6 mb-3">
                            <label for="validationDefault03">City</label><span class="text-danger">*</span>
                            <select class="form-control select2" id="city" name="city" required>
                                                                 <option value="">-Select City-</option>
                                                                </select>
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="col-md-6 mb-3">
                            <label for="validationDefault03">Password</label><span class="text-danger">*</span>
                           <input type="text" class="form-control" placeholder="Password" name="password">
                          </div>
                          <div class="col-md-6 mb-3">
                            <label for="validationDefault04">Conform Password</label><span class="text-danger">*</span>
                            <input type="text" class="form-control" placeholder="Confirm Password" name="cpassword">
                          </div>
                           <div class="col-md-6 mb-3">
                              <label class="checkbox is-outlined is-primary" style="padding:5px 0px">
                                                    <input type="checkbox" name="ignore_password_update" value="1">
                                                    <span></span>
                                                    Ignore Password Update
                                                </label>
                          </div>
                        </div>
                      </form>
                  </div>
                </div>



                <div class="col-md-6">
                    <div class="onebox-seller-form seller-form-right margin-top">
                        <h3>Payment Method : Bank Transfer - <?php echo $user->seller_currency;?></h3>
                        <div class="form-row">
                        <div class="col-md-6 mb-3">
                        <div class="project-grid-item-sell">
                        <label>
                        <input type="radio" class="tdcheckbox" name="currency" value="EUR" <?php if($user->seller_currency == "EUR"){?> checked <?php } ?>>
                        <span></span> <i class="fas fa-euro-sign"></i> EUR
                        </label>
                        </div>
                        </div>
                          <div class="col-md-6">
                              <div class="project-grid-item-sell">

                                <label>
                                <input class="tdcheckbox" type="radio" name="currency" value="GBP" <?php if($user->seller_currency == "GBP"){?> checked <?php } ?>>
                                <span></span> <i class="fas fa-pound-sign"></i> GBP
                                </label>
                              </div>
                          </div>

                          <div class="col-md-12 mb-3">
                          <label for="validationDefault01">Beneficiary Full Name</label>
                          <input type="text" class="form-control" placeholder="Beneficiary Full Name
                          " name="beneficiary_name" value="<?php echo $user->beneficiary_name;?>" required>
                          </div>
                          <div class="col-md-12 mb-3">
                            <label for="validationDefault02">Bank Name</label><span class="text-danger">*</span>
                           <input type="text" class="form-control" placeholder="Bank Name" name="bank_name" value="<?php echo $user->bank_name;?>" required>
                          </div>

                          <div class="col-md-12 mb-3">
                            <label for="validationDefault02">IBAN Number</label><span class="text-danger">*</span>
                             <input type="text" class="form-control" placeholder="IBAN Number" name="iban_number" value="<?php echo $user->iban_number;?>" required>
                          </div>

                        <div class="col-md-12 mb-3">
                        <label for="validationDefault02">Beneficiary Address</label><span class="text-danger">*</span>
                        <input type="text" class="form-control" placeholder="Beneficiary Address
                        " name="beneficiary_address" value="<?php echo $user->beneficiary_address;?>" required>
                        </div>

                           <div class="col-md-12 mb-3">
                            <label for="validationDefault02">Bank Address</label><span class="text-danger">*</span>
                          <input type="text" class="form-control" placeholder="Bank Address" name="bank_address" value="<?php echo $user->bank_address;?>" required>
                          </div>


                          <div class="col-md-12 mb-3">
                            <label for="validationDefault02">Account Number</label><span class="text-danger">*</span>
                           <input type="text" class="form-control" placeholder="Account Number
" name="account_number" value="<?php echo $user->account_number;?>" required>
                          </div>
                          <div class="col-md-12 mb-3">
                            <label for="validationDefault02">BIC / SWIFT Code</label><span class="text-danger">*</span>
                            <input type="text" class="form-control" placeholder="BIC / SWIFT Code
" name="swift_code" value="<?php echo $user->swift_code;?>" required>
                          </div>

                        </div>

                        
                      
                    </div>
                </div>
                
            </div>
            <div class="row">
                    <div class="col-md-12">
                        <!-- <div class="form_submit">
                          <button type="submit" style="cursor: pointer;">Submit</button>
                        </div> -->
                        <div class="upcoming-match-btn-view-all">
                    <button type="submit" style="cursor: pointer;background: #0037D5;color:#fff;" class="onebox-btn">Submit</button>
                  </div>
                    </div>
                </div>
        </div>
        </form>
    </section>
    <!-- seller profile End -->
 

       
    </div>


    <script type="text/javascript">
            var base_url = "<?php echo base_url();?>";
            var country    = "<?php echo $user->country;?>";
            var state    = "<?php echo $user->state;?>";
            var city    = "<?php echo $user->city;?>";
        </script>
   <?php $this->load->view(THEME_NAME.'/common/footer');?>
    <script src="<?php echo base_url().THEME_NAME;?>/js/custom.js?ver=2.4.9" async></script>

   <script type="text/javascript">
        
        $( document ).ready(function() {
        
  if(state != ''){ 
  get_state(country);
  }
   if(city != ''){
  get_city(state);
  }
});

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


      var loadFile = function(event) {

      var output = document.getElementById('display_profile');
      output.src = URL.createObjectURL(event.target.files[0]);
      output.onload = function() {
      URL.revokeObjectURL(output.src) // free memory
      }

      };
                </script>

                <?php exit;?>
