<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Manage
      <small>Commercial Invoice</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Commercial Invoice</li>
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
        <?php if(in_array('createComInvoice', $user_permission)): ?>
        <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">Add Invoice</button>
             <br /> <br />
        <?php endif; ?>
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Manage Commercial Invoice</h3>
    
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <table id="manageTable" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>S.No</th>
                <th>Invoice No</th>
                <th>Delivery Date</th>
                <th>Total Billable Amount</th>
                <th>Paid amount</th>
                <th>Balance Amount</th>
                <?php //if(in_array('updateComInvoice', $user_permission)): ?>
                <th>Action</th>
              <?php //endif;?>
               </tr>
              </thead>  
             <?php if($comerisal_invoice){ foreach ($comerisal_invoice as $key => $value){ ?>
               <tr> 
				<td><?php echo $key+1;?></td>
				<td><?php echo $value['invoice_no'];?></td>
				<td><?php echo $value['delivery_date'];?></td>
				<td><?php echo $value['total_balance'];?></td>
				<td><?php echo $value['paid_amount'];?></td>
			  <td><?php echo $value['avaliable_balence'];?></td>
			  <td>
        <a href="<?php echo base_url('commercialinvoice/payment_history/'.base64_encode($value['id']));?>"><button type="button" title="Click here to view payment status"><i class="fa fa-cart-arrow-down"></i></button></a>&nbsp;&nbsp;
        
        <a href="<?php echo base_url('products/view_product_list/'.base64_encode($value['id']));?>">
        <button type="button" title="Click here to view products"><i class="fa fa-caret-square-o-up"></i></button></a>&nbsp;&nbsp;
        
        <a href="<?php echo base_url('products/create/'.base64_encode($value['id']));?>">
        <button type="button" title="Click here to add new product"><i class="fa fa-external-link"></i></button></a>&nbsp;&nbsp;
        <?php if(in_array('updateComInvoice', $user_permission)): ?>
        <button type="button" onclick="editFunc('<?php echo $value['id'];?>')" data-toggle="modal" data-target="#editModal" title="edit invoice"><i class="fa fa-edit"></i></button>
		    <?php endif; ?>
        </td
        >
    	 <!--   <button type="button" class="btn btn-default" onclick="editFunc('.$value['id'].')" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil"></i></button> -->
    
         </tr>
             <?php }  } ?>
              
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

<div class="modal fade" tabindex="-1" role="dialog" id="addModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add Invoice</h4>
      </div>
  <form role="form" action="<?php echo base_url('commercialinvoice/create') ?>" method="post" id="createForm">
  <div class="modal-body">
  <div class="form-group">
  <label for="invoice_no">Invoice No</label>
  <input type="text" name="invoice_no" class="form-control" id="invoice_no">
  </div>

  <div class="form-group">
  <label for="delivery_date">Delivery Date</label>
  <input type="text" name="delivery_date" id="delivery_date" class="form-control">
  </div>

  <div class="form-group">
  <label for="factory">Factory</label>
  <select name="factory" class="form-control" id="factory">
  <option value="">--select--</option>
  <?php foreach($factory as $factoryData){ ?>
  <option value="<?php echo $factoryData['id'];?>"><?php echo $factoryData['name'];?></option>  
  <?php }?>
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

<div class="modal fade" tabindex="-1" role="dialog" id="editModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit Invoice</h4>
      </div>

      <form role="form" action="<?php echo base_url('commercialinvoice/update') ?>" method="post" id="updateForm">
        <div class="modal-body">
        <div id="messages"></div>

        <div class="form-group">
        <label for="edit_invoice_no">Invoice No</label>
        <input type="text" name="edit_invoice_no" class="form-control"  id="edit_invoice_no">
        <input type="text"  name="old_invoice_no"  hidden id="old_invoice_no">

        </div>

        <div class="form-group">
        <label for="edit_delivery_date">Delivery Date</label>
        <input type="text" name="edit_delivery_date" id="edit_delivery_date" class="form-control" required="">
        </div>

        <div class="form-group">
        <label for="edit_factory">Factory</label>
        <select name="edit_factory" class="form-control" id="edit_factory">
        <option value="">--select--</option>
        <?php foreach($factory as $factoryData){ ?>
        <option value="<?php echo $factoryData['id'];?>"><?php echo $factoryData['name'];?></option>  
        <?php }?>
        </select>
        </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" id="updateBtnSubmit" class="btn btn-primary">Save changes</button>
        </div>
      </form>


    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- /.content-wrapper -->
<script type="text/javascript">
  flatpickr("#delivery_date", {
      enableTime: true,
      dateFormat: "d-m-Y H:i:s",
  });

$('#manageTable').dataTable();	

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
      $("#manageTable").load(location.href + " #manageTable");
        //manageTable.ajax.reload(null, false);

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
             console.log(response);
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


// edit function
function editFunc(id)
{

  flatpickr("#edit_delivery_date", {
      enableTime: true,
      dateFormat: "d-m-Y H:i",
  });

  $.ajax({
    url: '<?php echo base_url("commercialinvoice/fetchInvoiceDataById/");?>'+id,
    type: 'post',
    dataType: 'json',
    success:function(response) {

$('#edit_invoice_no').val(response.invoice_no);
$('#edit_delivery_date').val(response.delivery_date);
$('#edit_factory').val(response.factory_id);

$('#old_invoice_no').val(response.invoice_no);
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

            $("#manageTable").load(location.href + " #manageTable");

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

</script>
                      
