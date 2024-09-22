<?php error_reporting(1); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Reports - Profit Share
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Profit Share</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-md-12 col-xs-12">
          <form class="form-inline" action="<?php echo base_url('profitshare/') ?>" method="POST">
            <div class="form-group">
              <label for="date">Year</label>
              <select class="form-control" name="select_year" id="select_year">
                <?php foreach ($profit_years as $key => $value): ?>
                  <option value="<?php echo $value ?>" <?php if($value == $selected_year) { echo "selected"; } ?>><?php echo $value; ?></option>
                <?php endforeach ?>
              </select>
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
          </form>
        </div>

        <br /> <br />


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

        
          <!-- /.box -->
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Profit Share Data</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="datatables" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Year - Month</th>                  
                  <th>Total Profit</th>
                  <?php 
                    foreach ($results_investors as $rinv => $rev):
                  ?>
                  <th style="color : darkgreen !important; font-size : 18px">
                    <?php echo $rev['name']; ?> (<?php echo $rev['percentage']; ?> %)
                  </th>
                  
                  <?php 
                    endforeach
                  ?>
                  
                </tr>
                </thead>
                <tbody>
                  <?php foreach ($results as $k => $v): 
                    $amount_cal = $v['actual_profit'];
                    $amount_calc=round($amount_cal,2);
                  ?>                    
                    <tr>                      
                      <td><?php echo $k; ?></td>
                      <td>
                        <?php                       
                       //   echo $amount_calc.' '.MY_CURRENCY;                      
                        ?>                        
            <?php if($date_format['currency']=='INR' OR $date_format['currency']=='USD'){
            echo $amount_calc.' '.$date_format['currency'];
            }else{
            echo $date_format['currency'].' '.$amount_calc;
            }?>      
                      </td>
                     <?php
                        foreach ($results_investors as $rinv => $rev):
                  // echo"<pre>";
                  //            print_r($results_investors);  
                      ?>
                      <td>
                        <?php
                          $main_profit = 0;
                          if($rev['type'] == 'Primary'): ?>
                          <?php 
                            $main_profit = round($amount_calc,2);                          
                            $primary_amount = ($amount_calc * $rev['percentage'] / 100);

                           // echo $primary_amount.' '.MY_CURRENCY;       
                      // echo"----->".$primary_amount;
                      if($date_format['currency']=='INR' OR $date_format['currency']=='USD'){
                      echo round($primary_amountm,2).' '.$date_format['currency'];
                      }else{
                      echo $date_format['currency'].' '.round($primary_amount,2);
                      }
                            // echo $main_profit."s1";
                            // echo"s";
                            // echo $primary_amount."s2";
                            $to_move_secondary_amount = $main_profit - $primary_amount;                            ;
                          ?>
                          <?php endif?>

                        <?php if($rev['type'] == 'Secondary'): ?>
                          <?php 
                          // echo "AC".$to_move_secondary_amount;
                          //print_r($to_move_secondary_amount);
                            $secondary_amountA = ($to_move_secondary_amount * $rev['percentage'] / 100);
                            $secondary_amount=round($secondary_amountA,2);
                        //   echo $secondary_amount.' '.MY_CURRENCY;
                          ?>
                        <?php if($date_format['currency']=='INR' OR $date_format['currency']=='USD'){
                        echo $secondary_amount.' '.$date_format['currency'];
                        }else{
                        echo $date_format['currency'].' '.$secondary_amount;
                        }?>     

                        <?php endif ?>


                      </td>
                      <?php 
                        endforeach
                      ?>
                     
                    </tr>
                  <?php endforeach ?>                  
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
