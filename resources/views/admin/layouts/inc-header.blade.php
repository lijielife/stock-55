<ul class="nav navbar-top-links navbar-right">



<!--    <li>
        <a href="<?php echo URL::to('pages/index') ?>"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
    </li>-->


    <li class="dropdown">
        <a id="dLabel" role="button" data-toggle="dropdown" data-target="#">
            <i class="fa fa-files-o fa-fw"></i>Example <span class="caret"></span>
        </a>
        <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
            <li class="dropdown-submenu">
                <a tabindex="-1" href="#"><i class="fa fa-envelope fa-fw"></i> Charts</a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="<?php echo URL::to('pages/flot') ?>">Flot Charts</a>
                    </li>
                    <li>
                        <a href="<?php echo URL::to('pages/morris') ?>">Morris.js Charts</a>
                    </li>
                </ul>
            </li>
            <li class="divider"></li>

            <li>
                <a href="<?php echo URL::to('pages/tables') ?>"><i class="fa fa-table fa-fw"></i> Tables</a>
            </li>
            <li>
                <a href="<?php echo URL::to('pages/forms') ?>"><i class="fa fa-edit fa-fw"></i> Forms</a>
            </li>

            <li class="divider"></li>

            <li class="dropdown-submenu">
                <a tabindex="-1" href="#"><i class="fa fa-wrench fa-fw"></i> UI Elements</a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="<?php echo URL::to('pages/panels-wells') ?>">Panels and Wells</a>
                    </li>
                    <li>
                        <a href="<?php echo URL::to('pages/buttons') ?>">Buttons</a>
                    </li>
                    <li>
                        <a href="<?php echo URL::to('pages/notifications') ?>">Notifications</a>
                    </li>
                    <li>
                        <a href="<?php echo URL::to('pages/typography') ?>">Typography</a>
                    </li>
                    <li>
                        <a href="<?php echo URL::to('pages/icons') ?>"> Icons</a>
                    </li>
                    <li>
                        <a href="<?php echo URL::to('pages/grid') ?>">Grid</a>
                    </li>
                </ul>
            </li>



            <li class="divider"></li>
            <li>
                <a href="<?php echo URL::to('backup') ?>">Backup</a>
            </li>

            <li>
                <a href="<?php echo URL::to('sub-table') ?>">Create Sub Table</a>
            </li>
        </ul>
    </li>



    <li class="dropdown">
        <a id="dLabel" role="button" data-toggle="dropdown" data-target="#">
            <i class="fa fa-files-o fa-fw"></i> User <span class="caret"></span>
        </a>
        <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
            <li>
                <a href="<?php echo URL::to('admin/blank') ?>">Blank Page</a>
            </li>
            <li>
                <a href="<?php echo URL::to('admin/login') ?>">Login Page</a>
            </li>
            <li>
                <a href="<?php echo URL::to('admin/user') ?>">User Page</a>
            </li>

            <li class="divider"></li>
            <li class="dropdown-submenu">
                <a tabindex="-1" href="#"><i class="fa fa-files-o fa-fw"></i>Test</a>
                <ul class="dropdown-menu">
                    <!--<li><a tabindex="-1" href="#">Second level</a></li>-->
                    <!--                    <li class="dropdown-submenu">
                                            <a href="#">Even More..</a>
                                            <ul class="dropdown-menu">
                                                <li><a href="#">3rd level</a></li>
                                                <li><a href="#">3rd level</a></li>
                                            </ul>
                                        </li>-->
                    <li>
                        <a href="<?php echo URL::to('check-connect') ?>">Check Connect</a>
                    </li>
                    <li>
                        <a href="<?php echo URL::to('check-model') ?>">Check Model</a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>


    <li class="dropdown">
        <a id="dLabel" role="button" data-toggle="dropdown" data-target="#">
            <i class="fa fa-files-o fa-fw"></i>History <span class="caret"></span>
        </a>
        <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
            <li class="dropdown-submenu">
                <a tabindex="-1" href="#"><i class="fa fa-files-o fa-fw"></i>Investor</a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="<?php echo URL::to('history/get') ?>">Get History</a>
                    </li>
                    <li>
                        <a href="<?php echo URL::to('history/load') ?>">Load History</a>
                    </li>
                </ul>
            </li>
            <li class="divider"></li>
            <li class="dropdown-submenu">
                <a tabindex="-1" href="#"><i class="fa fa-files-o fa-fw"></i>RuayHoon</a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="<?php echo URL::to('history/get2') ?>">Get History</a>
                    </li>

                    <li>
                        <a href="<?php echo URL::to('history/load2') ?>">Load History</a>
                    </li>
                </ul>
            </li>
            <li class="divider"></li>
            <li>
                <a href="<?php echo URL::to('backup') ?>">Backup</a>
            </li>

            <li>
                <a href="<?php echo URL::to('sub-table') ?>">Create Sub Table</a>
            </li>
        </ul>
    </li>


    <li class="dropdown">
        <a id="dLabel" role="button" data-toggle="dropdown" data-target="#">
            <i class="fa fa-files-o fa-fw"></i>Charts <span class="caret"></span>
        </a>
        <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
            <li>
                <a href="<?php echo URL::to('single-stock') ?>">Single Stock</a>
            </li>
        </ul>
        <!-- /.nav-second-level -->
    </li>

<!--    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            <i class="fa fa-envelope fa-fw"></i>  <i class="fa fa-caret-down"></i>
        </a>
        <ul class="dropdown-menu dropdown-messages">
            <li>
                <a href="#">
                    <div>
                        <strong>John Smith</strong>
                        <span class="pull-right text-muted">
                            <em>Yesterday</em>
                        </span>
                    </div>
                    <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
                </a>
            </li>
            <li class="divider"></li>
        </ul>
         /.dropdown-messages 
    </li>
     /.dropdown 
    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            <i class="fa fa-bell fa-fw"></i>  <i class="fa fa-caret-down"></i>
        </a>
        <ul class="dropdown-menu">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-bell fa-fw"></i>  <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="#">
                            <div>
                                <i class="fa fa-comment fa-fw"></i> New Comment
                                <span class="pull-right text-muted small">4 minutes ago</span>
                            </div>
                        </a>
                    </li>

                </ul>
                 /.dropdown-alerts 
            </li>
        </ul>
         /.dropdown-alerts 
    </li>
     /.dropdown 
-->    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
        </a>
        <ul class="dropdown-menu dropdown-user">
            <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
            </li>
            <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
            </li>
            <li class="divider"></li>
            <li><a href="{{url('admin/login/logout')}}"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
            </li>
        </ul>
         <!--/.dropdown-user--> 
    </li>
    <!-- /.dropdown -->
</ul>