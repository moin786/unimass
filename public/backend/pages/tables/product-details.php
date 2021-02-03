<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>UK | Product Details</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../../bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../../bower_components/Ionicons/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../../dist/css/skins/_all-skins.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <?php
    include("../../header.php");
    ?>
  
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <?php
    include("../../sidebar.php");  
    ?>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Product Details</h1>
      
      <ol class="breadcrumb">
        <li><a href="../../index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"></a></li>
        <li class="active">Product Details</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
         
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Product Details With Full Features</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped table-hover">
                <thead>
                <tr>
                  <th>Image</th>
                  <th>Item Code</th>
                  <th>Product Name</th>
                  <th>UK Stock</th>
                  <th>In Transit Stock</th>
                  <th>My Stock</th>
                  <th>Price (RM) - (FP/INS)</th>
                  <th class="text-center">Action</th>
                </tr>
                </thead>
                
                <tbody>
                    <tr>
                      <td>Fossil</td>
                      <td>FOS-574</td>
                      <td>FOSSIL FELICITY TOTE CROCO</td>
                      <td>0</td>
                      <td>0</td>
                      <td>0</td>
                      <td><a href="#">(RM) 399.00/439.00</a></td>
                      <td class="text-center"><a href="#">View</a> &nbsp; <a href="#">Edit</a> &nbsp; <a href="#">Delete</a></td>
                    </tr>
                    <tr>
                      <td>Longchamp</td>
                      <td>LCM-124</td>
                      <td>Longchamp Li Pliage Travel Bag Large Bilberry</td>
                      <td>0</td>
                      <td>0</td>
                      <td>0</td>
                      <td><a href="#">(RM) 429.00/459.00</a></td>
                      <td class="text-center"><a href="#">View</a> &nbsp; <a href="#">Edit</a> &nbsp; <a href="#">Delete</a></td>
                    </tr>

                    <tr>
                      <td>Tory</td>
                      <td>TORY-35</td>
                      <td>Tory Emerson Chain Wallet Yellow</td>
                      <td>0</td>
                      <td>0</td>
                      <td>0</td>
                      <td><a href="#">(RM) 899.00/975.00</a></td>
                      <td class="text-center"><a href="#">View</a> &nbsp; <a href="#">Edit</a> &nbsp; <a href="#">Delete</a></td>
                    </tr>

                    <tr>
                      <td>Tory</td>
                      <td>TORY-34</td>
                      <td>Tory Thea Shoulderbag Navy</td>
                      <td>0</td>
                      <td>0</td>
                      <td>0</td>
                      <td><a href="#">(RM) 1369.00/1489.00</a></td>
                      <td class="text-center"><a href="#">View</a> &nbsp; <a href="#">Edit</a> &nbsp; <a href="#">Delete</a></td>
                    </tr>

                    <tr>
                      <td>Tory</td>
                      <td>TORY-33</td>
                      <td>Tory Bombe-T Zip Continental Wallet Blck 483120418x</td>
                      <td>0</td>
                      <td>0</td>
                      <td>0</td>
                      <td><a href="#">(RM) 359.00/399.00</a></td>
                      <td class="text-center"><a href="#">View</a> &nbsp; <a href="#">Edit</a> &nbsp; <a href="#">Delete</a></td>
                    </tr>

                    <tr>
                      <td>Fossil</td>
                      <td>FOS-572</td>
                      <td>FOSSIL WATCH HYBRID FTW5022</td>
                      <td>0</td>
                      <td>0</td>
                      <td>0</td>
                      <td><a href="#">(RM) 369.00/419.00</a></td>
                      <td class="text-center"><a href="#">View</a> &nbsp; <a href="#">Edit</a> &nbsp; <a href="#">Delete</a></td>
                    </tr>

                    <tr>
                      <td>Fossil</td>
                      <td>FOS-571</td>
                      <td>Fossil Kayla Clutch Black/Brown SL8792015</td>
                      <td>0</td>
                      <td>0</td>
                      <td>0</td>
                      <td><a href="#">(RM) 199.00/229.00</a></td>
                      <td class="text-center"><a href="#">View</a> &nbsp; <a href="#">Edit</a> &nbsp; <a href="#">Delete</a></td>
                    </tr>

                    <tr>
                      <td>Tory</td>
                      <td>TORY-32</td>
                      <td>Tory Juliette Printed Mini Top-Handle Satchel Blck Stamped Floral 443401117</td>
                      <td>0</td>
                      <td>0</td>
                      <td>0</td>
                      <td><a href="#">(RM) 499.00/549.00</a></td>
                      <td class="text-center"><a href="#">View</a> &nbsp; <a href="#">Edit</a> &nbsp; <a href="#">Delete</a></td>
                    </tr>

                    <tr>
                      <td>CK</td>
                      <td>CAT-271</td>
                      <td>CK GWP zip Purse Bath Flowers Ink 771658</td>
                      <td>0</td>
                      <td>0</td>
                      <td>0</td>
                      <td><a href="#">(RM) 39.00/59.00</a></td>
                      <td class="text-center"><a href="#">View</a> &nbsp; <a href="#">Edit</a> &nbsp; <a href="#">Delete</a></td>
                    </tr>
                     
                    <tr>
                      <td>Fossil</td>
                      <td>FOS-574</td>
                      <td>FOSSIL FELICITY TOTE CROCO</td>
                      <td>0</td>
                      <td>0</td>
                      <td>0</td>
                      <td><a href="#">(RM) 399.00/439.00</a></td>
                      <td class="text-center"><a href="#">View</a> &nbsp; <a href="#">Edit</a> &nbsp; <a href="#">Delete</a></td>
                    </tr>
                    <tr>
                      <td>Longchamp</td>
                      <td>LCM-124</td>
                      <td>Longchamp Li Pliage Travel Bag Large Bilberry</td>
                      <td>0</td>
                      <td>0</td>
                      <td>0</td>
                      <td><a href="#">(RM) 429.00/459.00</a></td>
                      <td class="text-center"><a href="#">View</a> &nbsp; <a href="#">Edit</a> &nbsp; <a href="#">Delete</a></td>
                    </tr>

                    <tr>
                      <td>Tory</td>
                      <td>TORY-35</td>
                      <td>Tory Emerson Chain Wallet Yellow</td>
                      <td>0</td>
                      <td>0</td>
                      <td>0</td>
                      <td><a href="#">(RM) 899.00/975.00</a></td>
                      <td class="text-center"><a href="#">View</a> &nbsp; <a href="#">Edit</a> &nbsp; <a href="#">Delete</a></td>
                    </tr>

                    <tr>
                      <td>Tory</td>
                      <td>TORY-34</td>
                      <td>Tory Thea Shoulderbag Navy</td>
                      <td>0</td>
                      <td>0</td>
                      <td>0</td>
                      <td><a href="#">(RM) 1369.00/1489.00</a></td>
                      <td class="text-center"><a href="#">View</a> &nbsp; <a href="#">Edit</a> &nbsp; <a href="#">Delete</a></td>
                    </tr>

                    <tr>
                      <td>Tory</td>
                      <td>TORY-33</td>
                      <td>Tory Bombe-T Zip Continental Wallet Blck 483120418x</td>
                      <td>0</td>
                      <td>0</td>
                      <td>0</td>
                      <td><a href="#">(RM) 359.00/399.00</a></td>
                      <td class="text-center"><a href="#">View</a> &nbsp; <a href="#">Edit</a> &nbsp; <a href="#">Delete</a></td>
                    </tr>

                    <tr>
                      <td>Fossil</td>
                      <td>FOS-572</td>
                      <td>FOSSIL WATCH HYBRID FTW5022</td>
                      <td>0</td>
                      <td>0</td>
                      <td>0</td>
                      <td><a href="#">(RM) 369.00/419.00</a></td>
                      <td class="text-center"><a href="#">View</a> &nbsp; <a href="#">Edit</a> &nbsp; <a href="#">Delete</a></td>
                    </tr>

                    <tr>
                      <td>Fossil</td>
                      <td>FOS-571</td>
                      <td>Fossil Kayla Clutch Black/Brown SL8792015</td>
                      <td>0</td>
                      <td>0</td>
                      <td>0</td>
                      <td><a href="#">(RM) 199.00/229.00</a></td>
                      <td class="text-center"><a href="#">View</a> &nbsp; <a href="#">Edit</a> &nbsp; <a href="#">Delete</a></td>
                    </tr>

                    <tr>
                      <td>Tory</td>
                      <td>TORY-32</td>
                      <td>Tory Juliette Printed Mini Top-Handle Satchel Blck Stamped Floral 443401117</td>
                      <td>0</td>
                      <td>0</td>
                      <td>0</td>
                      <td><a href="#">(RM) 499.00/549.00</a></td>
                      <td class="text-center"><a href="#">View</a> &nbsp; <a href="#">Edit</a> &nbsp; <a href="#">Delete</a></td>
                    </tr>

                    <tr>
                      <td>CK</td>
                      <td>CAT-271</td>
                      <td>CK GWP zip Purse Bath Flowers Ink 771658</td>
                      <td>0</td>
                      <td>0</td>
                      <td>0</td>
                      <td><a href="#">(RM) 39.00/59.00</a></td>
                      <td class="text-center"><a href="#">View</a> &nbsp; <a href="#">Edit</a> &nbsp; <a href="#">Delete</a></td>
                    </tr>
                
                </tbody>
                <tfoot>
                    <tr>
                      <th>Image</th>
                      <th>Item Code</th>
                      <th>Product Name</th>
                      <th>UK Stock</th>
                      <th>In Transit Stock</th>
                      <th>My Stock</th>
                      <th>Price (RM) - (FP/INS)</th>
                      <th class="text-center">Action</th>
                    </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
          
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<footer class="main-footer">
    <?php
        include('../../footer.php');
    ?>    
</footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-user bg-yellow"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                <p>New phone +1(800)555-1234</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                <p>nora@example.com</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-file-code-o bg-green"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                <p>Execution time 5 seconds</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="label label-danger pull-right">70%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Update Resume
                <span class="label label-success pull-right">95%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Laravel Integration
                <span class="label label-warning pull-right">50%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Back End Framework
                <span class="label label-primary pull-right">68%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Allow mail redirect
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Other sets of options are available
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Expose author name in posts
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Allow the user to show his name in blog posts
            </p>
          </div>
          <!-- /.form-group -->

          <h3 class="control-sidebar-heading">Chat Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Show me as online
              <input type="checkbox" class="pull-right" checked>
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Turn off notifications
              <input type="checkbox" class="pull-right">
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Delete chat history
              <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
            </label>
          </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="../../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="../../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../../bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
<!-- page script -->
<script>
  $(function () {
    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>
</body>
</html>
