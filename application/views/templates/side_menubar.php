<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">

        <li id="dashboardMainMenu">
          <a href="<?php echo base_url('dashboard') ?>">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
        <?php
         if($user_permission): ?>
          <?php if(in_array('createUser', $user_permission) || in_array('updateUser', $user_permission) || in_array('viewUser', $user_permission) || in_array('deleteUser', $user_permission)): ?>
            <li class="treeview" id="mainUserNav">
            <a href="#">
              <i class="fa fa-users"></i>
              <span>Users</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <?php if(in_array('createUser', $user_permission)): ?>
              <li id="createUserNav"><a href="<?php echo base_url('users/create') ?>"><i class="fa fa-circle-o"></i> Add User</a></li>
              <?php endif; ?>

              <?php if(in_array('updateUser', $user_permission) || in_array('viewUser', $user_permission) || in_array('deleteUser', $user_permission)): ?>
              <li id="manageUserNav"><a href="<?php echo base_url('users') ?>"><i class="fa fa-circle-o"></i> Manage Users</a></li>
            <?php endif; ?>
            </ul>
          </li>
          <?php endif; ?>

          <?php if(in_array('createGroup', $user_permission) || in_array('updateGroup', $user_permission) || in_array('viewGroup', $user_permission) || in_array('deleteGroup', $user_permission)): ?>
            <li class="treeview" id="mainGroupNav">
              <a href="#">
                <i class="fa fa-files-o"></i>
                <span>Role Permission</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <?php if(in_array('createGroup', $user_permission)): ?>
                  <li id="addGroupNav"><a href="<?php echo base_url('groups/create') ?>"><i class="fa fa-circle-o"></i> Add Role</a></li>
                <?php endif; ?>
                <?php if(in_array('updateGroup', $user_permission) || in_array('viewGroup', $user_permission) || in_array('deleteGroup', $user_permission)): ?>
                <li id="manageGroupNav"><a href="<?php echo base_url('groups') ?>"><i class="fa fa-circle-o"></i> Manage Permission</a></li>
                <?php endif; ?>
              </ul>
            </li>
          <?php endif; ?>

          
          <?php if(in_array('createAsset', $user_permission) || in_array('updateAsset', $user_permission) || in_array('viewAsset', $user_permission) || in_array('deleteAsset', $user_permission)): ?>
              <li id="assetNav">
                <a href="<?php echo base_url('asset/') ?>">
                  <i class="fa fa-files-o"></i> <span>Assets</span>
                </a>
              </li>
            <?php endif; ?>          

          <?php if(in_array('createCategory', $user_permission) || in_array('updateCategory', $user_permission) || in_array('viewCategory', $user_permission) || in_array('deleteCategory', $user_permission)): ?>
            <li id="categoryNav">
              <a href="<?php echo base_url('category/') ?>">
                <i class="fa fa-files-o"></i> <span>Category</span>
              </a>
            </li>
          <?php endif; ?>


          <?php if(in_array('createExpenses', $user_permission) || in_array('updateExpenses', $user_permission) || in_array('viewExpenses', $user_permission) || in_array('deleteExpenses', $user_permission)): ?>
            <li id="expensesNav">
              <a href="<?php echo base_url('expenses/') ?>">
                <i class="fa fa-files-o"></i> <span>Expenses</span>
              </a>
            </li>
          <?php endif; ?>


          <?php if(in_array('createCode', $user_permission) || in_array('updateCode', $user_permission) || in_array('viewCode', $user_permission) || in_array('deleteCode', $user_permission)): ?>
            <li id="exCodeNav">
              <a href="<?php echo base_url('code/') ?>">
                <i class="fa fa-files-o"></i> <span>Code</span>
              </a>
            </li>
          <?php endif; ?>


          <?php if(in_array('createFactory', $user_permission) || in_array('updateFactory', $user_permission) || in_array('viewFactory', $user_permission) || in_array('deleteFactory', $user_permission)): ?>
            <li id="factoryNav">
              <a href="<?php echo base_url('factory/') ?>">
                <i class="fa fa-files-o"></i> <span>Factory Info</span>
              </a>
            </li>
          <?php endif; ?>
          

          <?php if(in_array('createCustomers', $user_permission) || in_array('updateCustomers', $user_permission) || in_array('viewCustomers', $user_permission) || in_array('deleteCustomers', $user_permission)): ?>
            <li id="CustomersNav">
              <a href="<?php echo base_url('customers/') ?>">
                <i class="fa fa-files-o"></i> <span>Customers</span>
              </a>
            </li>
          <?php endif; ?>

          <?php if(in_array('createAttribute', $user_permission) || in_array('updateAttribute', $user_permission) || in_array('viewAttribute', $user_permission) || in_array('deleteAttribute', $user_permission)): ?>
          <li id="attributeNav">
            <a href="<?php echo base_url('attributes/') ?>">
              <i class="fa fa-files-o"></i> <span>Attributes</span>
            </a>
          </li>
          <?php endif; ?>

          <?php if(in_array('createComInvoice', $user_permission) || in_array('updateComInvoice', $user_permission) || in_array('viewComInvoice', $user_permission) || in_array('deleteComInvoice', $user_permission)): ?>
          <li id="attributeNav">
            <a href="<?php echo base_url('commercialinvoice') ?>">
              <i class="fa fa-files-o"></i> <span>Commercial Invoice</span>
            </a>
          </li>
          <?php endif; ?>

          <?php if(in_array('createProduct', $user_permission) || in_array('updateProduct', $user_permission) || in_array('viewProduct', $user_permission) || in_array('deleteProduct', $user_permission)): ?>
            <li class="treeview" id="mainProductNav">
              <a href="#">
                <i class="fa fa-cube"></i>
                <span>Products</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <?php if(in_array('createProduct', $user_permission)): ?>
                  <li id="addProductNav"><a href="<?php echo base_url('products/create') ?>"><i class="fa fa-circle-o"></i> Add Product</a></li>
                <?php endif; ?>
                <?php if(in_array('updateProduct', $user_permission) || in_array('viewProduct', $user_permission) || in_array('deleteProduct', $user_permission)): ?>
                <li id="manageProductNav"><a href="<?php echo base_url('products') ?>"><i class="fa fa-circle-o"></i> Manage Products</a></li>
                <?php endif; ?>
              </ul>
            </li>
          <?php endif; ?>


          <?php if(in_array('createOrder', $user_permission) || in_array('updateOrder', $user_permission) || in_array('viewOrder', $user_permission) || in_array('deleteOrder', $user_permission)): ?>
            <li class="treeview" id="mainOrdersNav">
              <a href="#">
                <i class="fa fa-dollar"></i>
                <span>Orders</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <?php if(in_array('createOrder', $user_permission)): ?>
                  <li id="addOrderNav"><a href="<?php echo base_url('orders/create') ?>"><i class="fa fa-circle-o"></i> Add Order</a></li>
                <?php endif; ?>
                <?php if(in_array('updateOrder', $user_permission) || in_array('viewOrder', $user_permission) || in_array('deleteOrder', $user_permission)): ?>
                <li id="manageOrdersNav"><a href="<?php echo base_url('orders') ?>"><i class="fa fa-circle-o"></i> Manage Orders</a></li>
                <?php endif; ?>
              </ul>
            </li>
          <?php endif; ?>

          <?php
          /*  if(in_array('viewReports', $user_permission)): ?>
            <li id="reportNav">
              <a href="<?php echo base_url('reports/') ?>">
                <i class="glyphicon glyphicon-stats"></i> <span>Reports</span>
              </a>
            </li>
          <?php endif; 
          */
          ?>

<?php if($this->session->userdata('report')=='1'){ ?>

 <?php if(in_array('viewReports', $user_permission)): ?>
            <li class="treeview menu-open" id="reportNav">
              <a href="#">
                <i class="fa fa-files-o"></i>
                <span>Reports</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu" style="display:block">
                 <?php if(in_array('viewOrderReports', $user_permission)): ?>
                  <li id="manageGroupNav"><a href="<?php echo base_url('reports/') ?>"><i class="fa fa-circle-o"></i> Orders</a></li>
                 <?php endif; ?>
                 <?php if(in_array('viewProfitReports', $user_permission)): ?>
                  <li id="profitNav"><a href="<?php echo base_url('profit/') ?>"><i class="fa fa-circle-o"></i> Profit</a></li>  
                 <?php endif; ?>
                 <?php if(in_array('viewProfitShareReports', $user_permission)): ?>
                   <li id="profitshareNav"><a href="<?php echo base_url('profitshare/') ?>"><i class="fa fa-circle-o"></i> Profit Share</a></li>
                <?php endif; ?>
                <?php if(in_array('viewLedgerReports', $user_permission)): ?>
                <li id="profitNav"><a href="<?php echo base_url('ledger/') ?>"><i class="fa fa-circle-o"></i> Ledger</a></li>
                <?php endif; ?>
                <?php if(in_array('viewInventoryReports', $user_permission)): ?>
                <li id="profitNav"><a href="<?php echo base_url('reports/inventoryreport')?>"><i class="fa fa-circle-o"></i>Inventory</a></li>
                <?php endif; ?>

                <?php if(in_array('viewSalseReports', $user_permission)): ?>
                <li id="profitNav"><a href="<?php echo base_url('reports/salesreport')?>"><i class="fa fa-circle-o"></i>Sales</a></li>
                <?php endif; ?>

                <?php if(in_array('viewDueReports', $user_permission)): ?>
                <li id="profitNav"><a href="<?php echo base_url('reports/duereport')?>"><i class="fa fa-circle-o"></i>Due</a></li>
                <?php endif; ?>

                <?php if(in_array('viewAccountReports', $user_permission)): ?>
                <li id="addReport"><a href="<?php echo base_url('reports/account');?>"><i class="fa fa-circle-o"></i>Account</a></li>
                <?php endif; ?>

                <?php if(in_array('viewInvoiceReports', $user_permission)): ?>
                <li id="addReport"><a href="<?php echo base_url('reports/invoice_report');?>"><i class="fa fa-circle-o"></i>Invoice</a></li>
                <?php endif; ?>

                <?php if(in_array('viewBalanceReports', $user_permission)): ?>
                <li id="addReport"><a href="<?php echo base_url('reports/balance_sheet_report');?>"><i class="fa fa-circle-o"></i>Balance Sheet</a></li>
                <?php endif; ?>

                <?php if(in_array('viewProductModelReports', $user_permission)): ?>
                <li id="addReport"><a href="<?php echo base_url('reports/product_model');?>"><i class="fa fa-circle-o"></i>Product Model</a></li>
                <?php endif; ?>

                <?php if(in_array('viewExpensesReports', $user_permission)): ?>
                <li id="addReport"><a href="<?php echo base_url('reports/expense_report');?>"><i class="fa fa-circle-o"></i>Expenses</a></li>
                <?php endif; ?>
                <?php if(in_array('viewConsolidatedReports', $user_permission)): ?>
                 <li id="addReport"><a href="<?php echo base_url('reports/consolidated');?>"><i class="fa fa-circle-o"></i>Consolidated</a></li>
                 <?php endif; ?>
              </ul>
            </li>
          <?php endif; ?>
<?php }else{ ?>
  
 <?php if(in_array('viewReports', $user_permission)): ?>
            <li class="treeview" id="reportNav">
              <a href="#">
                <i class="fa fa-files-o"></i>
                <span>Reports</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">                
                  
                  <?php if(in_array('viewOrderReports', $user_permission)): ?>
                  <li id="manageGroupNav"><a href="<?php echo base_url('reports/') ?>"><i class="fa fa-circle-o"></i> Orders</a></li>
                 <?php endif; ?>
                 <?php if(in_array('viewProfitReports', $user_permission)): ?>
                  <li id="profitNav"><a href="<?php echo base_url('profit/') ?>"><i class="fa fa-circle-o"></i> Profit</a></li>  
                 <?php endif; ?>
                 <?php if(in_array('viewProfitShareReports', $user_permission)): ?>
                   <li id="profitshareNav"><a href="<?php echo base_url('profitshare/') ?>"><i class="fa fa-circle-o"></i> Profit Share</a></li>
                <?php endif; ?>
                <?php if(in_array('viewLedgerReports', $user_permission)): ?>
                <li id="profitNav"><a href="<?php echo base_url('ledger/') ?>"><i class="fa fa-circle-o"></i> Ledger</a></li>
                <?php endif; ?>
                <?php if(in_array('viewInventoryReports', $user_permission)): ?>
                <li id="profitNav"><a href="<?php echo base_url('reports/inventoryreport')?>"><i class="fa fa-circle-o"></i>Inventory</a></li>
                <?php endif; ?>

                <?php if(in_array('viewSalseReports', $user_permission)): ?>
                <li id="profitNav"><a href="<?php echo base_url('reports/salesreport')?>"><i class="fa fa-circle-o"></i>Sales</a></li>
                <?php endif; ?>

                <?php if(in_array('viewDueReports', $user_permission)): ?>
                <li id="profitNav"><a href="<?php echo base_url('reports/duereport')?>"><i class="fa fa-circle-o"></i>Due</a></li>
                <?php endif; ?>

                <?php if(in_array('viewAccountReports', $user_permission)): ?>
                <li id="addReport"><a href="<?php echo base_url('reports/account');?>"><i class="fa fa-circle-o"></i>Account</a></li>
                <?php endif; ?>

                <?php if(in_array('viewInvoiceReports', $user_permission)): ?>
                <li id="addReport"><a href="<?php echo base_url('reports/invoice_report');?>"><i class="fa fa-circle-o"></i>Invoice</a></li>
                <?php endif; ?>

                <?php if(in_array('viewBalanceReports', $user_permission)): ?>
                <li id="addReport"><a href="<?php echo base_url('reports/balance_sheet_report');?>"><i class="fa fa-circle-o"></i>Balance Sheet</a></li>
                <?php endif; ?>

                <?php if(in_array('viewProductModelReports', $user_permission)): ?>
                <li id="addReport"><a href="<?php echo base_url('reports/product_model');?>"><i class="fa fa-circle-o"></i>Product Model</a></li>
                <?php endif; ?>

                <?php if(in_array('viewExpensesReports', $user_permission)): ?>
                <li id="addReport"><a href="<?php echo base_url('reports/expense_report');?>"><i class="fa fa-circle-o"></i>Expenses</a></li>
                <?php endif; ?>
                <?php if(in_array('viewConsolidatedReports', $user_permission)): ?>
                 <li id="addReport"><a href="<?php echo base_url('reports/consolidated');?>"><i class="fa fa-circle-o"></i>Consolidated</a></li>
                 <?php endif; ?>
                 
              </ul>
            </li>
          <?php endif; ?>
           <?php } ?>
          <?php if(in_array('updateCompany', $user_permission)): ?>
            <li id="companyNav"><a href="<?php echo base_url('company/') ?>"><i class="fa fa-files-o"></i> <span>Company</span></a></li>
          <?php endif; ?>

        <!-- <li class="header">Settings</li> -->

        <?php if(in_array('viewProfile', $user_permission)): ?>
          <li><a href="<?php echo base_url('users/profile/') ?>"><i class="fa fa-user-o"></i> <span>Profile</span></a></li>
        <?php endif; ?>
        <?php if(in_array('updateSetting', $user_permission)): ?>
          <li><a href="<?php echo base_url('users/setting/') ?>"><i class="fa fa-wrench"></i> <span>Setting</span></a></li>          
        <?php endif; ?>

        <?php if(in_array('viewInvestors', $user_permission)): ?>
        <li id="investorsNav"><a href="<?php echo base_url('investors/') ?>"><i class="fa fa-wrench"></i> <span>Investors</span></a></li>
        <?php endif; ?>
       

        <?php endif; ?>
        <!-- user permission info -->
        <li>
          <a href="<?php echo base_url('auth/logout') ?>"><i class="glyphicon glyphicon-log-out"></i> <span>Logout</span></a>
        </li>

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>