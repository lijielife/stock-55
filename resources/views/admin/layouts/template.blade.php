<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	<meta name="robot" content="noindex, nofollow" />

    <title>Super Charts</title>

    
    <!-- Bootstrap Core CSS -->
    @include('admin.layouts.inc-stylesheet')
	@yield('stylesheet')
        
        
    <!-- jQuery -->
	@include('admin.layouts.inc-scripts')
    @yield('scripts')
    
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo URL::to('pages/index') ?>">
                    <i class="fa fa-dashboard fa-fw"></i> Super Charts</a>
            </div>
            <!-- /.navbar-header -->

            @include('admin.layouts.inc-header')
            <!-- /.navbar-top-links -->

            <?php //@include('admin.layouts.inc-left-sidebar') ?>
            <!-- /.navbar-static-side -->
        </nav>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                @yield('content')
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

        
      <!--<div class="container" style="height: 30px;"></div>-->
        
    </div>
    <!-- /#wrapper -->

    
    
    
<!--    <button type="button" class="btn" 
            style="position: fixed; bottom: 50px; right: 20px">
        <i class="glyphicon glyphicon-chevron-ups">asd</i>
    </button>-->
    
    <button type="button" class="btn back-to-top" 
             style="position: fixed; bottom: 50px; right: 15px">
            <i class="glyphicon glyphicon-chevron-up"></i>
    </button>
    
    <footer class="footer">
      <div class="container">
        <p class="text-muted">Copyright © 2015 Super Charts Team</p>
      </div>
    </footer>
    
</body>

</html>
