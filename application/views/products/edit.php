<?php error_reporting(1);?>
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
            <h3 class="box-title">Edit Product</h3>
          </div>
          <!-- /.box-header -->
          <form role="form" id="productForm" action="<?php base_url('users/update') ?>" method="post" enctype="multipart/form-data">
              <div class="box-body">
                <?php echo validation_errors(); ?>
          <div class="form-group" id="oldImg">
          <label>Image Preview: </label>
          <?php if($product_data['image']=='<p>You did not select a file to upload.</p>'){ ?>
          <img src="<?php echo base_url('assets/images/product_image/default_user.png');?>" width="150" height="150" class="img-circle">
          <?php }else{ ?>
          <img src="<?php echo base_url() . $product_data['image'] ?>" width="150" height="150" class="img-circle">
           <?php }?>
           </div>
                <div class="form-group">
                  <label for="product_image">Update Image</label>
                  <div class="kv-avatar">
                      <div class="file-loading">
                          <input id="product_image" onclick="uploadImg()" name="product_image" type="file">
                      </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="factory_id">Invoice No</label>
                  <?php //$attribute_id = json_decode($product_data['factory_id']); ?>
                  <select class="form-control select_group" id="factory_id" name="factory_id">
                         <option value="">--select--</option>
                        <?php foreach ($invoice_no as $k => $v): ?>
                          <?php if(empty($v['invoice_no'])){ ?>
 
                         <?php }else{ ?>
                  <option value="<?php echo $v['id'] ?>" <?php if($product_data['invoice_id'] == $v['id']) { echo "selected='selected'"; } ?>><?php echo $v['invoice_no'] ?></option>
                      <?php    } ?>
                         
                        <?php endforeach ?>
                      </select>
                </div>
            <!--     <div class="form-group">
                  <label for="product_name">Product name</label>
                  <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Enter product name" value="<?php echo $product_data['name']; ?>"  autocomplete="off"/>
                </div> -->

                 <div class="form-group">
                  <label for="model_no">Model Number</label>
                  <input type="text" class="form-control" id="model_no" name="model_no" placeholder="Enter Model Number" autocomplete="off" value="<?php echo $product_data['model_no']; ?>" required=""/>
                 </div>

                 <div class="form-group">
                  <label for="cif">CIF(Inward Price)</label>
                  <input type="text" class="form-control" id="cif" name="cif" placeholder="Enter CIF" autocomplete="off"  value="<?php echo $product_data['cif'];?>" required=""/>
                 </div>

                 <div class="form-group">
                  <label for="wsp">Cost Price</label>
                  <input type="text" class="form-control" id="price" name="price" placeholder="Enter Cost Price" autocomplete="off" value="<?php echo $product_data['price']; ?>" required=""/>
                 </div>

                 <div class="form-group">
                  <label for="price_selling">WSP(Selling Price)</label>
                  <input type="text" class="form-control" id="price_selling" name="price_selling" placeholder="Enter Selling Price" value="<?php echo $product_data['price_selling']; ?>" autocomplete="off" required=""/>
                 </div>

                <div class="form-group">
                  <label for="sku">SKU</label>
                  <input type="text" class="form-control" id="sku" name="sku" placeholder="Enter sku" value="<?php echo $product_data['sku']; ?>" autocomplete="off" />
                </div>

                <div class="form-group">
                  <label for="unit">Unit</label>
                  <input type="text" class="form-control" id="unit" name="unit" placeholder="Enter Unit" value="<?php echo $product_data['unit']; ?>" autocomplete="off" />
                </div>
                
                <div class="form-group">
                  <label for="qty">Qty</label>
                  <input type="text" class="form-control" id="qty" name="qty" placeholder="Enter Qty" value="<?php echo $product_data['qty']; ?>" autocomplete="off" required=""/>
                </div>
            
                <div class="form-group">
                  <label for="description">Description</label>
                  <textarea type="text" class="form-control" id="description" name="description" placeholder="Enter 
                  description" autocomplete="off" required="">
                    <?php echo $product_data['description']; ?>
                  </textarea>
                </div>

                <?php 
                 if(!empty($product_data['attribute_value_id'])){
                 $attribute_id = json_decode($product_data['attribute_value_id']); ?>
                <?php if($attributes): ?>
                  <?php foreach ($attributes as $k => $v): ?>
                    <div class="form-group">
                      <label for="groups"><?php echo $v['attribute_data']['name'];?></label>
                      <select class="form-control select_group" id="attributes_value_id" name="attributes_value_id01[]">
                     <option value="">~~Select~~</option>
                        <?php 
                      if(!empty($v['attribute_value']))
                      foreach ($v['attribute_value'] as $k2 => $v2): ?>
                    <option value="<?php echo $v2['id'];?>" <?php if(in_array($v2['id'], $attribute_id)) { echo "selected"; } ?>><?php echo $v2['value'] ?></option>
                      <?php endforeach ?>
                      </select>
                    </div>    
                  <?php endforeach ?>
                <?php endif; ?>
                  <?php }else{ ?>
 
                  <?php if($attributes): ?>
                  <?php foreach ($attributes as $k => $v): ?>
                    <div class="form-group">
                      <label for="groups"><?php echo $v['attribute_data']['name'] ?></label>
                      <select class="form-control select_group" id="attributes_value_id" name="attributes_value_id02[]">
                        <option value="">~~Select~~</option>
                        <?php foreach ($v['attribute_value'] as $k2 => $v2): ?>
                          <option value="<?php echo $v2['id'] ?>"><?php echo $v2['value'] ?></option>
                        <?php endforeach ?>
                      </select>
                    </div>    
                  <?php endforeach ?>
                <?php endif; ?>
                <?php  } ?>
 
                <!-- <div class="form-group">
                  <label for="brands">Brands</label>
                  <?php $brand_data = json_decode($product_data['brand_id']); ?>
                  <select class="form-control select_group" id="brands" name="brands[]" multiple="multiple">
                    <?php foreach ($brands as $k => $v): ?>
                      <option value="<?php echo $v['id'] ?>" <?php if(in_array($v['id'], $brand_data)) { echo 'selected="selected"'; } ?>><?php echo $v['name'] ?></option>
                    <?php endforeach ?>
                  </select>
                </div>
 -->
                <div class="form-group">
                  <label for="category">Category</label>
                  <?php $category_data = json_decode($product_data['category_id']); ?>
                  <select class="form-control select_group" id="category" name="category[]" multiple="multiple">
                    <?php foreach ($category as $k => $v): ?>
                    <?php if(empty($v['id'])){ ?>
                    <option value="<?php echo $v['id'] ?>" <?php if(in_array($v['id'], $category_data)) { echo 'selected="selected"'; } ?>><?php echo $v['name'] ?></option>
                   <?php }else{ } ?>
                    <?php endforeach ?>
                  </select>
                </div>

               <!--  <div class="form-group">
                  <label for="store">Store</label>
                  <select class="form-control select_group" id="store" name="store">
                    <?php foreach ($stores as $k => $v): ?>
                      <option value="<?php echo $v['id'] ?>" <?php if($product_data['store_id'] == $v['id']) { echo "selected='selected'"; } ?> ><?php echo $v['name'] ?></option>
                    <?php endforeach ?>
                  </select>
                </div> -->
                
                <!--   <div class="form-group">
                  <label for="">Invoice No</label>
                 <input type="text" name="invoice_no" value="<?php //echo $product_data['invoice_no']; ?>" class="form-control">
                 </div> -->

             <!--      <div class="form-group">
                  <label for="">Delivery Date</label>
                  <input type="text" name="delivery_date" id="delivery_date" value="<?php //echo $product_data['delivery_date']; ?>" class="form-control">
                  </div> -->

                <div class="form-group">
                  <label for="store">Availability</label>
                  <select class="form-control" id="availability" name="availability">
                    <option value="1" <?php if($product_data['availability'] == 1) { echo "selected='selected'"; } ?>>Yes</option>
                    <option value="2" <?php if($product_data['availability'] != 1) { echo "selected='selected'"; } ?>>No</option>
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
    $("#manageProductNav").addClass('active');
    
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

  function uploadImg(){
  $('#oldImg').hide();
  }
</script>