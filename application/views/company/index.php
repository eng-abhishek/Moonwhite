

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Manage
        <small>Company</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">company</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-md-12 col-xs-12">
          
          <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <?php echo $this->session->flashdata('success'); ?>
            </div>
          <?php elseif($this->session->flashdata('error')): ?>
            <div class="alert alert-error alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <?php echo $this->session->flashdata('error'); ?>
            </div>
          <?php endif; ?>

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Manage Company Information</h3>
            </div>
            <form role="form" id="cmpForm" action="<?php base_url('company/update') ?>" method="post">
              <div class="box-body">

                <?php echo validation_errors(); ?>

                <div class="form-group">
                  <label for="company_name">Company Name</label>
                  <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Enter company name" value="<?php echo $company_data['company_name'] ?>" autocomplete="off">
                </div>

                  <div class="form-group">
                  <label for="ar_company_name">Ar Company Name</label>
                  <input type="text" class="form-control" id="ar_company_name" name="ar_company_name" placeholder="Enter company name" value="<?php echo $company_data['ar_company_name'] ?>" autocomplete="off">
                </div>

                <div class="form-group">
                  <label for="service_charge_value">Charge Amount (%)</label>
                  <input type="text" class="form-control" id="service_charge_value" name="service_charge_value" placeholder="Enter charge amount %" value="<?php echo $company_data['service_charge_value'] ?>" autocomplete="off">
                </div>
                <div class="form-group">
                  <label for="vat_charge_value">Vat Charge (%)</label>
                  <input type="text" class="form-control" id="vat_charge_value" name="vat_charge_value" placeholder="Enter vat charge %" value="<?php echo $company_data['vat_charge_value'] ?>" autocomplete="off">
                </div>
                <div class="form-group">
                  <label for="address">Address</label>
                  <input type="text" class="form-control" id="address" name="address" placeholder="Enter address" value="<?php echo $company_data['address'] ?>" autocomplete="off">
                </div>
                
                <div class="form-group">
                  <label for="address">Ar Address</label>
                  <input type="text" class="form-control" id="ar_address" name="ar_address" placeholder="Enter address" value="<?php echo $company_data['ar_address'] ?>" autocomplete="off">
                </div>    
                
                <div class="form-group">
                  <label for="phone">Phone</label>
                  <input type="number" class="form-control" id="phone" name="phone" placeholder="Enter phone" value="<?php echo $company_data['phone'] ?>" autocomplete="off">
                </div>
                
                 <div class="form-group">
                  <label for="phone">Ar Phone</label>
                  <input type="text" class="form-control" id="ar_phone" name="ar_phone" placeholder="Enter phone" value="<?php echo $company_data['ar_phone'] ?>" autocomplete="off">
                </div>

                <div class="form-group">
                  <label for="phone">Email</label>
                  <input type="text" class="form-control" id="email" name="email" placeholder="Enter email id" value="<?php echo $company_data['email'] ?>" autocomplete="off">
                </div>

                <div class="form-group">
                  <label for="country">Country</label>
                  <input type="text" class="form-control" id="country" name="country" placeholder="Enter country" value="<?php echo $company_data['country'] ?>" autocomplete="off">
                </div>

                <div class="form-group">
                  <label for="country">Ar Country</label>
                  <input type="text" class="form-control" id="ar_country" name="ar_country" placeholder="Enter country" value="<?php echo $company_data['ar_country'] ?>" autocomplete="off">
                </div>

                <div class="form-group">
                  <label for="permission">Message</label>
                  <textarea class="form-control" id="message" name="message">
                     <?php echo $company_data['message'] ?>
                  </textarea>
                </div>

                <div class="form-group">
                  <label for="currency">Currency</label>
                  <?php ?>
                  <select class="form-control" id="currency" name="currency">
                    <option value="">~~SELECT~~</option>

                    <?php foreach ($currency_symbols as $k => $v): ?>
                      <option value="<?php echo trim($k); ?>" <?php if($company_data['currency'] == $k) {
                        echo "selected";
                      } ?>><?php echo $k ?></option>
                    <?php endforeach ?>
                   </select>
                  </div>

                  <div class="form-group">
                  <label for="currency">Date Format</label>
                  <?php ?>
                  <select class="form-control" id="date_format" name="date_format">
                    <option value="">~~SELECT~~</option>

                    <?php foreach ($date_format as $date => $date_fr): ?>
                      <option value="<?php echo $date_fr['id']; ?>" <?php if($date_fr['id'] == $company_data['date_format']) {
                        echo "selected";
                      } ?>><?php echo $date_fr['date_format']; ?></option>
                    <?php endforeach ?>
                   </select>
                  </div>
                
              <div class="form-group">
              <label for="country">Capital</label>
              <input type="number" class="form-control" id="capital" name="capital" placeholder="Enter capital" value="<?php echo $company_data['capital'] ?>" autocomplete="off">
              </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Save Changes</button>
              </div>
            </form>
          </div>
          <!-- /.box -->
        </div>
        <!-- col-md-12 -->
      </div>
      <!-- /.row -->
      

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<script type="text/javascript">
  $(document).ready(function() {
    $("#companyNav").addClass('active');
    $("#message").wysihtml5();
  });
</script>
<script type="text/javascript">
   $("#cmpForm").validate({
    rules:{
    company_name:{
    required:true,      
    },
    ar_company_name:{
    required:true,
    },
    service_charge_value:{
    number:true,
    },
    email:{
    required:true,
    email:true,
    },
    address:{
    required:true,
    },
    message:{
    required:true,
    },
    phone:{
    maxlength:15,
    minlength:8,  
    },
    vat_charge_value:{
    number:true,
    }
    },
    messages:{
    company_name:{
    required:"Please enter company name",      
    },
    ar_company_name:{
    required:"Please enter company name",    
    },
    email:{
    required:'Please enter email id',
    },
    address:{
     minlength:'Please enter address',
    },
    message:{
     minlength:'Please enter message',
    },
    phone:{
    maxlength:'Phone no should not more than 15 digits',
    minlength:'Phone no must be atlease 8 digits',
    }
    }
    });
   </script>