<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Manage
      <small>Products</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Products</li>
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

        <?php if(in_array('createProduct', $user_permission)): ?>
          <a href="<?php echo base_url('products/create') ?>" class="btn btn-primary">Add Product</a>
          <br /> <br />
        <?php endif; ?>

        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Manage Products</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <table id="manageTable" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>Invoice No</th>
                   
                <th>Image</th>
                <th>Name</th>
                <th>Model</th>
                <th>Color</th>
               
                <th>CIF Price</th>
                <th>Cost Price</th>
                <th>Selling Price</th>
                <th>Actual Stock</th> 
                <th>Available Stock</th>                
                <th>Availability</th>
                <?php if(in_array('updateProduct', $user_permission) || in_array('deleteProduct', $user_permission)): ?>
                  <th>Action</th>
                <?php endif; ?>
              </tr>
              </thead>
              <tbody>
               
               <?php foreach ($dataList as $key => $value) { ?>
               <tr>
              <td><?php echo $value['oth_invoice_no']; ?></td>
            <td><?php
            if($value['image']=='<p>You did not select a file to upload.</p>'){ ?>
            <img src="<?php echo base_url('assets/images/product_image/default_user.png'); ?>" alt="<?php echo $value['name']; ?>" class="img-circle" width="50" height="50" />
            <?php }else{ ?>
            <img src="<?php echo base_url($value['image']);?>" alt="<?php echo $value['name']; ?>" class="img-circle" width="50" height="50" />
           <?php  } 
                ?>
              </td>
              <td><?php echo $value['description']; ?></td>
              <td><?php echo $value['model_no']; ?></td>
              <td><?php echo $value['color']; ?></td>
              <td><?php echo $value['cif']; ?></td>
              <td><?php echo $value['price']; ?></td>
              <td><?php echo $value['price_selling']; ?></td>
              <td><?php echo $value['initial_qty']; ?></td>
              <td><?php echo $value['qty']; ?></td>
              <td>
              <?php $avali=($value['availability'] == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">Inactive</span>';
              echo $avali;
              ?>  
              </td>
              <td> 
         <?php if(in_array('updateProduct', $this->permission)) { ?>
           <a href="<?php echo base_url('products/update/'.$value['id']); ?>" class="btn btn-default"><i class="fa fa-pencil"></i></a>
           <?php } ?>

           <?php if(in_array('deleteProduct', $this->permission)) { ?>
           <button type="button" class="btn btn-default" onclick="removeFunc('<?php echo $value['id'] ?>')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>

          <button type="button" class="btn btn-default" onclick="cloneProd('<?php echo $value['id'];?>')" data-toggle="modal" data-target="#cloneModal"><i class="fa fa-clone"></i></button>
           <?php } ?>
 
              </td>

               </tr>
              <?php } ?>
                      
              </tbody>
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

<?php if(in_array('deleteProduct', $user_permission)): ?>
<!-- remove brand modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="removeModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Remove Product</h4>
      </div>

      <form role="form" action="<?php echo base_url('products/remove') ?>" method="post" id="removeForm">
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


<div class="modal fade" tabindex="-1" role="dialog" id="cloneModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Clone Product</h4>
      </div>

      <form role="form" action="<?php echo base_url('products/clone') ?>" method="post" id="cloneForm">
        <div class="modal-body">
          <p>Do you really want to clone this product?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Yes</button>
        </div>
      </form>


    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script type="text/javascript">
var manageTable;
var base_url = "<?php echo base_url(); ?>";

$(document).ready(function() {

  $("#mainProductNav").addClass('active');

  // initialize the datatable 
 $('#manageTable').DataTable();
  // manageTable = $('#manageTable').DataTable({
  //   'ajax': base_url + 'products/fetchProductData',
  //   'order': []
  // });

});

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
        data: { product_id:id }, 
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


// clone functions 
function cloneProd(id)
{
  if(id) {
    $("#cloneForm").on('submit', function() {

      var form = $(this);

      // remove the text-danger
      $(".text-danger").remove();

      $.ajax({
        url: form.attr('action'),
        type: form.attr('method'),
        data: { product_id:id }, 
        dataType: 'json',
        success:function(response) {

          manageTable.ajax.reload(null, false); 

          if(response.success === true) {
            $("#messages").html('<div class="alert alert-success alert-dismissible" role="alert">'+
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
              '<strong> <span class="glyphicon glyphicon-ok-sign"></span> </strong>'+response.messages+
            '</div>');

            // hide the modal
            $("#cloneModal").modal('hide');

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
