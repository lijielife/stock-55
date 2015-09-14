
<div class="collapse navbar-collapse" id="navbar-collapse-1">
    <ul class="nav navbar-nav">
        <!--        <li><a href="#">Active Link</a></li>
                <li><a href="#">Link</a></li>-->

        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                Example <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li class="dropdown dropdown-submenu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope fa-fw"></i>Charts 
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="<?php echo URL::to('pages/flot') ?>">Flot Charts</a>
                        </li>
                        <li>
                            <a href="<?php echo URL::to('pages/morris') ?>">Morris.js Charts</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="<?php echo URL::to('pages/tables') ?>"><i class="fa fa-table fa-fw"></i> Tables</a>
                </li>
                <li>
                    <a href="<?php echo URL::to('pages/forms') ?>"><i class="fa fa-edit fa-fw"></i> Forms</a>
                </li>
                <li class="divider"></li>
                <li class="dropdown dropdown-submenu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-wrench fa-fw"></i> UI Elements
                    </a>
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
                        <li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown Link 4</a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Dropdown Submenu Link 4.1</a></li>
                                <li><a href="#">Dropdown Submenu Link 4.2</a></li>
                                <li><a href="#">Dropdown Submenu Link 4.3</a></li>
                                <li><a href="#">Dropdown Submenu Link 4.4</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>

        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-list"></i> Logs <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">


                <li>
                    <a href="<?php echo URL::to('logs/import') ?>">
                        <i class="glyphicon glyphicon-save"></i> Import                    
                    </a>
                </li>

                <li>
                    <a href="<?php echo URL::to('logs/active') ?>">
                        <i class="fa fa-inbox"></i> Active
                    </a>
                </li>

                <li>
                    <a href="<?php echo URL::to('logs/profile') ?>">
                        <i class="fa fa-list"></i> Profile
                    </a>
                </li>
                
                <li>
                    <a href="<?php echo URL::to('logs/dividend') ?>">
                        <i class="fa fa-list"></i> Dividend
                    </a>
                </li>
                
                
                
                <!--                <li>
                                    <a href="<?php echo URL::to('admin/blank') ?>">Blank Page</a>
                                </li>
                                <li>
                                    <a href="<?php echo URL::to('admin/login') ?>">Login Page</a>
                                </li>
                                <li>
                                    <a href="<?php echo URL::to('admin/user') ?>">User Page</a>
                                </li>-->

                <li class="divider"></li>
            </ul>
        </li>


        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-bar-chart-o fa-fw"></i>Charts  <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li>
                    <a href="<?php echo URL::to('single/stock') ?>">Single Stock</a>
                </li>
            </ul>
        </li>

    </ul>



    <ul class="nav navbar-nav navbar-right">


        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-gears fa-fw"></i> <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">

                <li class="dropdown dropdown-submenu push-left">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-database"></i> Data
                    </a>
                    <ul class="dropdown-menu">
                        <li class="dropdown dropdown-submenu push-left">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                Investor
                            </a>
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
                        <li class="dropdown dropdown-submenu push-left">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                RuayHoon
                            </a>
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
                        <li>
                            <a href="<?php echo URL::to('backup') ?>">Backup</a>
                        </li>

                        <li>
                            <a href="<?php echo URL::to('sub-table') ?>">Create Sub Table</a>
                        </li>
                    </ul>
                </li>

                <li class="dropdown dropdown-submenu push-left">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-users"></i> Users
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="<?php echo URL::to('admin/user') ?>">
                                User Page
                            </a>
                        </li>
                    </ul>

                </li>
                <li class="dropdown dropdown-submenu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="glyphicon glyphicon-play"></i> Test
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="<?php echo URL::to('check-connect') ?>">
                                Test Connection
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo URL::to('check-model') ?>">
                                Test Model
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>

<!--    <li><a href="<?php echo URL::to('pages/index') ?>"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a></li>-->


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
        -->    
        <li class="dropdown">
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

</div><!-- /.navbar-collapse -->