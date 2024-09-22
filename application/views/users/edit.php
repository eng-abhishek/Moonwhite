<?php error_reporting(1); ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Manage
        <small>Users</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Users</li>
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
              <h3 class="box-title">Edit User</h3>
            </div>
            <form role="form" action="<?php base_url('users/create') ?>" id="user_form" method="post">
              <div class="box-body">

                <?php echo validation_errors(); ?>

                <div class="form-group">
                  <label for="groups">Groups</label>
                  <select class="form-control" name="groups" required>
                    <option value="">Select Groups</option>
                    <?php foreach ($group_data as $k => $v): ?>
                      <option value="<?php echo $v['id'];?>" <?php if($user_group['id'] == $v['id']) { echo 'selected'; } ?> ><?php echo $v['group_name'];?></option> 
                    <?php endforeach ?>
                  </select>
                </div>

                <div class="form-group">
                  <label for="username">Username</label>
                  <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="<?php echo $user_data['username'] ?>" autocomplete="off" required>
                </div>

                <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?php echo $user_data['email'] ?>" autocomplete="off" required>
                </div>                
                
                <input type="text" hidden name="oldemail" id="oldemail" value="<?php echo $user_data['email'];?>">

                <div class="form-group">
                  <label for="fname">First name</label>
                  <input type="text" class="form-control" id="fname" name="fname" placeholder="First name" value="<?php echo $user_data['firstname'] ?>" autocomplete="off" required>
                </div>

                <div class="form-group">
                  <label for="lname">Last name</label>
                  <input type="text" class="form-control" id="lname" name="lname" placeholder="Last name" value="<?php echo $user_data['lastname'] ?>" autocomplete="off">
                </div>

                <div class="form-group">
                  <label for="phone">Phone</label>
                  <input type="number" class="form-control" id="phone" name="phone" placeholder="Phone" value="<?php echo $user_data['phone'] ?>" autocomplete="off">
                </div>

                <div class="form-group">
                  <label for="gender">Gender</label>
                  <div class="radio">
                    <label>
                      <input type="radio" name="gender" required id="male" value="1" <?php if($user_data['gender'] == 1) {
                        echo "checked";
                      } ?>>
                      Male
                    </label>
                    <label>
                      <input type="radio" name="gender" required id="female" value="2" <?php if($user_data['gender'] == 2) {
                        echo "checked";
                      } ?>>
                      Female
                    </label>
                  </div>
                </div>

                <div class="form-group">
                  <div class="alert alert-info alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      Leave the password field empty if you don't want to change.
                  </div>
                </div>

                <div class="form-group">
                  <label for="password">Password</label>
                  <input type="password" class="form-control" id="password" name="password" placeholder="Password"  autocomplete="off">
                </div>

                <div class="form-group">
                  <label for="cpassword">Confirm password</label>
                  <input type="password" class="form-control" id="cpassword" name="cpassword" placeholder="Confirm Password" autocomplete="off">
                </div>

              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="<?php echo base_url('users/') ?>" class="btn btn-warning">Back</a>
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
    $("#groups").select2();

    $("#mainUserNav").addClass('active');
    $("#manageUserNav").addClass('active');
  });
</script>
 <script type="text/javascript">
   $("#user_form").validate({
    rules:{
    groups:{
    required:true,      
    },
    fname:{
    required:true,
    },
    username:{
    required:true,
    maxlength:12,
    minlength:5,  
    },
    email:{
    required:true,
    email:true,
    remote:{
    url:'<?php echo base_url('users/editCheckUserEmail')?>',
    method:'post',
    data:{old_email:$('#oldemail').val()},
    }
    },
    password:{
    minlength:8, 
    },
    cpassword:{
    minlength:8,
    equalTo:'#password',
    },
    phone:{
    maxlength:15,
    minlength:8,  
    }
    },
    messages:{
    groups:{
    required:"Please select the group",      
    },
    fname:{
    required:"Please enter first name",
    },
    username:{
    required:'Please enter username',
    maxlength:'User name should not more than 12 characters',
    minlength:'User name must be atlease 5 characters',
    },
    email:{
    required:'Please enter email id',
    remote:'This email id already exist try an other'
    },
    password:{
     minlength:'Password must be atlease 8 characters',
    },
    cpassword:{
     minlength:'Password  must be atlease 8 characters',
     equalTo:'Password and confirm password does not match',
    },
    phone:{
    maxlength:'Phone no should not more than 15 digits',
    minlength:'Phone no must be atlease 8 digits',
    }
    }
    });
   </script>
  