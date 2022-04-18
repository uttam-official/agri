

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?=BASE_URL?>" class="brand-link">
        <img src="<?=BASE_URL?>/dist/img/AdminLTELogo.png" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Admin Panel</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="<?=BASE_URL?>" class="nav-link dashboard">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?=BASE_URL.'/components/category/'?>" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p>
                            Category
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?=BASE_URL?>/components/subcategory/" class="nav-link">
                        <i class="nav-icon fas fa-list-alt"></i>
                        <p>
                            Sub Category
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?=BASE_URL?>/components/product/" class="nav-link">
                        <i class="nav-icon fa fa-box-open"></i>
                        <p>
                            Product
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?=BASE_URL?>/components/order/" class="nav-link">
                        <i class="nav-icon fa fa-shopping-cart"></i>
                        <p>
                            Order
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?=BASE_URL?>/components/discount/" class="nav-link">
                        <i class="nav-icon fa fa-percent"></i>
                        <p>
                            Discount
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?=BASE_URL?>/components/customer/" class="nav-link">
                        <i class="nav-icon fa fa-user"></i>
                        <p>
                            Customer
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?=BASE_URL?>/components/setting/" class="nav-link">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            Setting
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>