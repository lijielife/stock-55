<div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                <input type="text" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                            <!-- /input-group -->
                        </li>
                        <li>
                            <a href="<?php echo URL::to('pages/index') ?>"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> Charts<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="<?php echo URL::to('pages/flot') ?>">Flot Charts</a>
                                </li>
                                <li>
                                    <a href="<?php echo URL::to('pages/morris') ?>">Morris.js Charts</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="<?php echo URL::to('pages/tables') ?>"><i class="fa fa-table fa-fw"></i> Tables</a>
                        </li>
                        <li>
                            <a href="<?php echo URL::to('pages/forms') ?>"><i class="fa fa-edit fa-fw"></i> Forms</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-wrench fa-fw"></i> UI Elements<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
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
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-sitemap fa-fw"></i> Multi-Level Dropdown<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="#">Second Level Item</a>
                                </li>
                                <li>
                                    <a href="#">Second Level Item</a>
                                </li>
                                <li>
                                    <a href="#">Third Level <span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="#">Third Level Item</a>
                                        </li>
                                        <li>
                                            <a href="#">Third Level Item</a>
                                        </li>
                                        <li>
                                            <a href="#">Third Level Item</a>
                                        </li>
                                        <li>
                                            <a href="#">Third Level Item</a>
                                        </li>
                                    </ul>
                                    <!-- /.nav-third-level -->
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li class="active">
                            <a href="#"><i class="fa fa-files-o fa-fw"></i> Sample Pages<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="<?php echo URL::to('admin/blank') ?>">Blank Page</a>
                                </li>
                                <li>
                                    <a href="<?php echo URL::to('admin/login') ?>">Login Page</a>
                                </li>

                                <li>
                                    <a href="<?php echo URL::to('admin/user') ?>">User Page</a>
                                </li>
                                

                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>