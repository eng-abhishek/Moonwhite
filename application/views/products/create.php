<style type="text/css">
#product_image-error{
    margin-top: 5px;
    padding-left: 20px;
}
</style>
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


        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Add Product</h3>
          </div>
          <!-- /.box-header -->
          <form role="form" id="productForm" action="<?php base_url('products/create') ?>" method="post" enctype="multipart/form-data">
              <div class="box-body">

                <?php echo validation_errors(); ?>

                <div class="form-group">

                  <label for="product_image">Image</label>
                  <div class="kv-avatar">
                      <div class="file-loading">
                          <input id="product_image" name="product_image" type="file" required="">
                      </div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="factory_id">Invoice No</label>
                  <select class="form-control select_group" id="factory_id" name="factory_id">
                        <option value="">--select--</option>
                        <?php foreach ($invoice_no as $k => $v): ?>
                          <option <?php if($invoice_Id==$v['id']){ echo"selected";}?> value="<?php echo $v['id'] ?>"><?php echo $v['invoice_no'] ?></option>
                        <?php endforeach ?>
                      </select>
                </div>

               <!--  <div class="form-group">
                  <label for="product_name">Product name</label>
                  <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Enter product name" autocomplete="off"/>
                </div> -->

                <div class="form-group">
                  <label for="model_no">Model Number</label>
                  <input type="text" class="form-control" id="model_no" name="model_no" placeholder="Enter Model Number" autocomplete="off" required="" />
                </div>

                <div class="form-group">
                  <label for="cif">CIF(Inward Price)</label>
                  <input type="text" class="form-control" id="cif" name="cif" placeholder="Enter CIF" autocomplete="off" required=""/>
                </div>

                 <div class="form-group">
                  <label for="cost">Cost Price</label>
                  <input type="text" class="form-control" id="price" name="price" placeholder="Enter cost price" autocomplete="off" required=""/>
                 </div>

                  <div class="form-group">
                  <label for="price_selling">WSP(Selling Price)</label>
                  <input type="text" class="form-control" id="price_selling" name="price_selling" placeholder="Enter Selling Price" autocomplete="off" required=""/>
                  </div>

                 <div class="form-group">
                  <label for="sku">SKU</label>
                  <input type="text" class="form-control" id="sku" name="sku" placeholder="Enter sku" autocomplete="off" />
                </div>

                <div class="form-group">
                  <label for="unit">Unit</label>
                  <input type="text" class="form-control" id="unit" name="unit" placeholder="Enter Unit" autocomplete="off" />
                </div>               

                <div class="form-group">
                  <label for="qty">Qty</label>
                  <input type="text" class="form-control" id="qty" name="qty" placeholder="Enter Qty" autocomplete="off" />
                </div>

                <div class="form-group">
                  <label for="description">Description</label>
                  <textarea type="text" class="form-control" id="description" name="description" placeholder="Enter 
                  description" autocomplete="off" required="">
                  </textarea>
                </div>

                <?php if($attributes): ?>
                  <?php foreach ($attributes as $k => $v): ?>
                    <div class="form-group">
                      <label for="groups"><?php echo $v['attribute_data']['name'] ?></label>
                      <select class="form-control select_group" id="attributes_value_id" name="attributes_value_id[]">
                        <option value="">~~Select~~</option>
                        <?php foreach ($v['attribute_value'] as $k2 => $v2): ?>
                         
                          <option value="<?php echo $v2['id'] ?>"><?php echo $v2['value'] ?></option>
                        <?php endforeach ?>
                      </select>
                    </div>    
                  <?php endforeach ?>
                <?php endif; ?>

               <!--  <div class="form-group">
                  <label for="brands">Brands</label>
                  <select class="form-control select_group" id="brands" name="brands[]" multiple="multiple">
                    <?php foreach ($brands as $k => $v): ?>
                      <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                    <?php endforeach ?>
                  </select>
                </div> -->

                <div class="form-group">
                  <label for="category">Category</label>
                  <select class="form-control select_group" id="category" name="category[]" multiple="multiple">
                    <?php foreach ($category as $k => $v): ?>
                      <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                    <?php endforeach ?>
                  </select>
                </div>
<!-- 
                <div class="form-group">
                  <label for="store">Store</label>
                  <select class="form-control select_group" id="store" name="store">
                    <?php foreach ($stores as $k => $v): ?>
                      <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                    <?php endforeach ?>
                  </select>
                </div> -->

             <!--     <div class="form-group">
                  <label for="">Invoice No</label>
                 <input type="text" name="invoice_no" class="form-control" required="">
                 </div> -->

             <!--      <div class="form-group">
                  <label for="">Delivery Date</label>
                  <input type="text" name="delivery_date" id="delivery_date" class="form-control" required="">
                  </div> -->

                <div class="form-group">
                  <label for="store">Availability</label>
                  <select class="form-control" id="availability" name="availability">
                    <option value="1">Yes</option>
                    <option value="2">No</option>
                  </select>
                </div>

              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="<?php echo base_url('products/') ?>" class="btn btn-warning">Back</a>
              </div>
            </form>
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

<script type="text/javascript">
  $(document).ready(function() {

    $('#delivery_date').flatpickr({
      enableTime: true,
      dateFormat: "d-m-Y H:i:s",
    });
 
    $("#productForm").validate({
    rules:{
    model_no:'required',
    cif:'required',
    price:'required',
    price_selling:'required',
    qty:'required',
    description:'required',
    },
    messages:{
    model_no:'Please enter model no',
    cif:'Please enter cif price',
    price:'Please enter cost price',
    price_selling:'Please enter selling price',
    qty:'Please enter quantity',
    description:'Please enter description',
    }
    });
    $(".select_group").select2();
    $("#description").wysihtml5();

    $("#mainProductNav").addClass('active');
    $("#addProductNav").addClass('active');
    
    var btnCust = '<button type="button" class="btn btn-secondary" title="Add picture tags" ' + 
        'onclick="alert(\'Call your custom code here.\')">' +
        '<i class="glyphicon glyphicon-tag"></i>' +
        '</button>'; 
    $("#product_image").fileinput({
        overwriteInitial: true,
        maxFileSize: 2000,
        showClose: false,
        showCaption: false,
        browseLabel: '',
        removeLabel: '',
        browseIcon: '<i class="glyphicon glyphicon-folder-open"></i>',
        removeIcon: '<i class="glyphicon glyphicon-remove"></i>',
        removeTitle: 'Cancel or reset changes',
        elErrorContainer: '#kv-avatar-errors-1',
        msgErrorClass: 'alert alert-block alert-danger',
        // defaultPreviewContent: '<img src="/uploads/default_avatar_male.jpg" alt="Your Avatar">',
        layoutTemplates: {main2: '{preview} ' +  btnCust + ' {remove} {browse}'},
        allowedFileExtensions: ["jpg", "png", "gif"]
    });

  });

</script>