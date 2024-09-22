<style type="text/css">
    .buttons-excel {
    float: right;
     padding-right: 15px; 
    margin-right: 30px;
    margin-bottom: 10px;
    border-color: #218838;
    background-color: #218838;
    color: white;
    font-size: 14px;
    margin-top: 10px;
    margin-right: 45px;
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Manage
      <small>Customers</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Customers</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-md-12 col-xs-12">

        <div id="messages"></div>

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

        <?php if(in_array('createCustomers', $user_permission)): ?>
          <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">Add Customers</button>
          <br /> <br />
        <?php endif; ?>
      
         
        <div class="box">
          <div class="row">
          <div class="col-sm-6">
              <div class="box-header">
            <h3 class="box-title">Manage Customers</h3>
          </div>
         
          </div>  
          <div class="col-sm-6">
          <a href="<?php echo base_url('customers/downloadCustomerData');?>"><button type="button" class="buttons-excel">Export Data</button></a>
          </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <table id="manageTable" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>Customer Details</th>
                <th>Customer Code</th>
                <th>Address</th>
                <th>Contact</th>
                <th>Email</th>
                <th>Area</th>
                <th>TRN</th>
                <th>Status</th>
                <?php if(in_array('updateCustomers', $user_permission) || in_array('deleteCustomers', $user_permission)): ?>
                  <th>Action</th>
                <?php endif; ?>
              </tr>
              </thead>

            </table>
          </div>
          <!-- /.box-body -->
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

<?php if(in_array('createCustomers', $user_permission)): ?>
<!-- create brand modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="addModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add Customer</h4>
      </div>

      <form role="form" action="<?php echo base_url('customers/create') ?>" method="post" id="createForm">

        <div class="modal-body">

          <div class="form-group">
            <label for="brand_name">Customer Details</label>
            <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Enter Customer name" autocomplete="off">
          </div>

          <div class="form-group">
            <label for="brand_name">Address</label>
            <textarea type="text" class="form-control" id="customer_address" name="customer_address" placeholder="Customer Address" autocomplete="off" style="resize:none !important"></textarea>
          </div>

          <div class="form-group">
            <label for="brand_name">Contact</label>
            <input type="number" class="form-control" id="customer_contact" name="customer_contact" placeholder="Enter Contact No." autocomplete="off">
          </div>

          <div class="form-group">
            <label for="customer_email">Email Id</label>
            <input type="text" class="form-control" id="customer_email" name="customer_email" placeholder="Enter Email Id." autocomplete="off">
          </div>

          <div class="form-group">
            <label for="brand_name">Area</label>
            <input type="text" class="form-control" id="customer_area" name="customer_area" placeholder="Enter Area" autocomplete="off">
          </div>

          <div class="form-group">
            <label for="brand_name">TRN</label>
            <input type="text" class="form-control" id="customer_trn" name="customer_trn" placeholder="Enter TRN" autocomplete="off">
          </div>


          <div class="form-group">
            <label for="active">Status</label>
            <select class="form-control" id="active" name="active">
              <option value="1">Active</option>
              <option value="2">Inactive</option>
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>

      </form>


    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php endif; ?>

<?php if(in_array('updateCustomers', $user_permission)): ?>
<!-- edit brand modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="editModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit Customer</h4>
      </div>

      <form role="form" action="<?php echo base_url('customers/update') ?>" method="post" id="updateForm">

        <div class="modal-body">
          <div id="messages"></div>

            <div class="form-group">
            <label for="brand_name">Customer Details</label>
            <input type="text" class="form-control" id="edit_customer_name" name="edit_customer_name" placeholder="Enter Customer name" autocomplete="off">
          </div>

          <div class="form-group">
            <label for="brand_name">Address</label>
            <textarea type="text" class="form-control" id="edit_customer_address" name="edit_customer_address" placeholder="Customer Address" autocomplete="off" style="resize:none !important"></textarea>
          </div>

          <div class="form-group">
            <label for="brand_name">Contact</label>
            <input type="number" class="form-control" id="edit_customer_contact" name="edit_customer_contact" placeholder="Enter Contact No." autocomplete="off">
          </div>
            <input type="text" name="old_customer_contact" id="old_customer_contact" hidden>

          <div class="form-group">
            <label for="email">Email Id</label>
            <input type="text" class="form-control" id="edit_customer_email" name="edit_customer_email" placeholder="Enter Email Id." autocomplete="off">
          </div>
               <input type="text" name="old_customer_email" id="old_customer_email" hidden>

          <div class="form-group">
            <label for="brand_name">Area</label>
            <input type="text" class="form-control" id="edit_customer_area" name="edit_customer_area" placeholder="Enter Area" autocomplete="off">
          </div>

          <div class="form-group">
            <label for="brand_name">TRN</label>
            <input type="text" class="form-control" id="edit_customer_trn" name="edit_customer_trn" placeholder="Enter TRN" autocomplete="off">
          </div>
            <input type="text" name="old_customer_trn" id="old_customer_trn" hidden>

          <div class="form-group">
            <label for="edit_active">Status</label>
            <select class="form-control" id="edit_active" name="edit_active">
              <option value="1">Active</option>
              <option value="2">Inactive</option>
            </select>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>

      </form>


    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php endif; ?>

<?php if(in_array('deleteCustomers', $user_permission)): ?>
<!-- remove brand modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="removeModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Remove Customer</h4>
      </div>

      <form role="form" action="<?php echo base_url('customers/remove') ?>" method="post" id="removeForm">
        <div class="modal-body">
          <p>Do you really want to remove?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>          
          <button type="submit" class="btn btn-danger">Confirm</button>
        </div>
      </form>


    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php endif; ?>



<script type="text/javascript">
var manageTable;

$(document).ready(function() {

  $("#CustomersNav").addClass('active');

  // initialize the datatable
  manageTable = $('#manageTable').DataTable({
    'ajax': 'fetchCustomersData',
    'order': []
  });

  // submit the create from
  $("#createForm").unbind('submit').on('submit', function() {
    var form = $(this);
  
    // remove the text-danger
    $(".text-danger").remove();

    $.ajax({
      url: form.attr('action'),
      type: form.attr('method'),
      data: form.serialize(), // /converting the form data into array and sending it to server
      dataType: 'json',
      success:function(response) {
        manageTable.ajax.reload(null, false);
     
        if(response.success === true) {
          $("#messages").html('<div class="alert alert-success alert-dismissible" role="alert">'+
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
            '<strong> <span class="glyphicon glyphicon-ok-sign"></span> </strong>'+response.messages+
          '</div>');


          // hide the modal
          $("#addModal").modal('hide');

          // reset the form
          $("#createForm")[0].reset();
          $("#createForm .form-group").removeClass('has-error').removeClass('has-success');

        } else {

          if(response.messages instanceof Object) {
            $.each(response.messages, function(index, value) {
              var id = $("#"+index);

              id.closest('.form-group')
              .removeClass('has-error')
              .removeClass('has-success')
              .addClass(value.length > 0 ? 'has-error' : 'has-success');

              id.after(value);

            });
          } else {
            $("#messages").html('<div class="alert alert-warning alert-dismissible" role="alert">'+
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
              '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>'+response.messages+
            '</div>');
          }
        }
      }
    });
    return false;
  });

});

// edit function
function editFunc(id)
{
  $.ajax({
    url: 'fetchCustomersDataById/'+id,
    type: 'post',
    dataType: 'json',
    success:function(response) {

      $("#edit_customer_name").val(response.name);      
      $("#edit_customer_address").val(response.address);
      $("#edit_customer_contact").val(response.contact);
      $("#old_customer_contact").val(response.contact);
      $("#edit_customer_email").val(response.email);
      $("#old_customer_email").val(response.email);
      $("#edit_customer_area").val(response.area);
      $("#edit_customer_trn").val(response.trn);
      $("#old_customer_trn").val(response.trn);
      

      $("#edit_active").val(response.active);

      // submit the edit from
      $("#updateForm").unbind('submit').bind('submit', function() {
        var form = $(this);

        // remove the text-danger
        $(".text-danger").remove();

        $.ajax({
          url: form.attr('action') + '/' + id,
          type: form.attr('method'),
          data: form.serialize(), // /converting the form data into array and sending it to server
          dataType: 'json',
          success:function(response) {

            manageTable.ajax.reload(null, false);

            if(response.success === true) {
              $("#messages").html('<div class="alert alert-success alert-dismissible" role="alert">'+
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                '<strong> <span class="glyphicon glyphicon-ok-sign"></span> </strong>'+response.messages+
              '</div>');


              // hide the modal
              $("#editModal").modal('hide');
              // reset the form
              $("#updateForm .form-group").removeClass('has-error').removeClass('has-success');

            } else {

              if(response.messages instanceof Object) {
                $.each(response.messages, function(index, value) {
                  var id = $("#"+index);

                  id.closest('.form-group')
                  .removeClass('has-error')
                  .removeClass('has-success')
                  .addClass(value.length > 0 ? 'has-error' : 'has-success');

                  id.after(value);

                });
              } else {
                $("#messages").html('<div class="alert alert-warning alert-dismissible" role="alert">'+
                  '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                  '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>'+response.messages+
                '</div>');
              }
            }
          }
        });

        return false;
      });

    }
  });
}

// remove functions
function removeFunc(id)
{
  if(id) {
    $("#removeForm").on('submit', function() {

      var form = $(this);

      // remove the text-danger
      $(".text-danger").remove();

      $.ajax({
        url: form.attr('action'),
        type: form.attr('method'),
        data: { customer_id:id },
        dataType: 'json',
        success:function(response) {

          manageTable.ajax.reload(null, false);

          if(response.success === true) {
            $("#messages").html('<div class="alert alert-success alert-dismissible" role="alert">'+
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
              '<strong> <span class="glyphicon glyphicon-ok-sign"></span> </strong>'+response.messages+
            '</div>');

            // hide the modal
            $("#removeModal").modal('hide');

          } else {

            $("#messages").html('<div class="alert alert-warning alert-dismissible" role="alert">'+
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
              '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>'+response.messages+
            '</div>');
          }
        }
      });

      return false;
    });
  }
}


</script>
