<?php
session_start();
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: auth-login.html');
    exit;
}
$username = $_SESSION['username'] ?? 'Unknown User';
$email = $_SESSION['email'] ?? 'No email';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Mazer Admin Dashboard</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap.css">

    <link rel="stylesheet" href="assets/vendors/iconly/bold.css">

    <link rel="stylesheet" href="assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/app.css">
    <link rel="shortcut icon" href="assets/images/favicon.svg" type="image/x-icon">
    <style>
        .stats-icon.orange {
            background-color: #ff9500;
            color: white;
        }
        .stats-icon.teal {
            background-color: #20c997;
            color: white;
        }
        .stats-icon.indigo {
            background-color: #6610f2;
            color: white;
        }
        .stats-icon.pink {
            background-color: #e83e8c;
            color: white;
        }
    </style>
</head>

<body>
    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header">
                    <div class="d-flex justify-content-between">
                        <div class="logo">
                            <a href="index.php"><img src="assets/images/logo/logo.png" alt="Logo" srcset=""></a>
                        </div>
                        <div class="toggler">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-title">Menu</li>

                        <li class="sidebar-item active ">
                            <a href="index.php" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>基本資料</span>
                            </a>
                        </li>

                        <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-stack"></i>
                                <span>Components</span>
                            </a>
                            <ul class="submenu ">
                                <li class="submenu-item ">
                                    <a href="component-alert.html">Alert</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="component-badge.html">Badge</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="component-breadcrumb.html">Breadcrumb</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="component-button.html">Button</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="component-card.html">Card</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="component-carousel.html">Carousel</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="component-dropdown.html">Dropdown</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="component-list-group.html">List Group</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="component-modal.html">Modal</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="component-navs.html">Navs</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="component-pagination.html">Pagination</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="component-progress.html">Progress</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="component-spinner.html">Spinner</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="component-tooltip.html">Tooltip</a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-collection-fill"></i>
                                <span>Extra Components</span>
                            </a>
                            <ul class="submenu ">
                                <li class="submenu-item ">
                                    <a href="extra-component-avatar.html">Avatar</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="extra-component-sweetalert.html">Sweet Alert</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="extra-component-toastify.html">Toastify</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="extra-component-rating.html">Rating</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="extra-component-divider.html">Divider</a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-grid-1x2-fill"></i>
                                <span>Layouts</span>
                            </a>
                            <ul class="submenu ">
                                <li class="submenu-item ">
                                    <a href="layout-default.html">Default Layout</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="layout-vertical-1-column.html">1 Column</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="layout-vertical-navbar.html">Vertical with Navbar</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="layout-horizontal.html">Horizontal Menu</a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-title">Forms &amp; Tables</li>

                        <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-hexagon-fill"></i>
                                <span>Form Elements</span>
                            </a>
                            <ul class="submenu ">
                                <li class="submenu-item ">
                                    <a href="form-element-input.html">Input</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="form-element-input-group.html">Input Group</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="form-element-select.html">Select</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="form-element-radio.html">Radio</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="form-element-checkbox.html">Checkbox</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="form-element-textarea.html">Textarea</a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-item  ">
                            <a href="form-layout.html" class='sidebar-link'>
                                <i class="bi bi-file-earmark-medical-fill"></i>
                                <span>Form Layout</span>
                            </a>
                        </li>

                        <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-pen-fill"></i>
                                <span>Form Editor</span>
                            </a>
                            <ul class="submenu ">
                                <li class="submenu-item ">
                                    <a href="form-editor-quill.html">Quill</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="form-editor-ckeditor.html">CKEditor</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="form-editor-summernote.html">Summernote</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="form-editor-tinymce.html">TinyMCE</a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-item  ">
                            <a href="table.html" class='sidebar-link'>
                                <i class="bi bi-grid-1x2-fill"></i>
                                <span>Table</span>
                            </a>
                        </li>

                        <li class="sidebar-item  ">
                            <a href="table-datatable.html" class='sidebar-link'>
                                <i class="bi bi-file-earmark-spreadsheet-fill"></i>
                                <span>Datatable</span>
                            </a>
                        </li>

                        <li class="sidebar-title">Extra UI</li>

                        <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-pentagon-fill"></i>
                                <span>Widgets</span>
                            </a>
                            <ul class="submenu ">
                                <li class="submenu-item ">
                                    <a href="ui-widgets-chatbox.html">Chatbox</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="ui-widgets-pricing.html">Pricing</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="ui-widgets-todolist.html">To-do List</a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-egg-fill"></i>
                                <span>Icons</span>
                            </a>
                            <ul class="submenu ">
                                <li class="submenu-item ">
                                    <a href="ui-icons-bootstrap-icons.html">Bootstrap Icons </a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="ui-icons-fontawesome.html">Fontawesome</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="ui-icons-dripicons.html">Dripicons</a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-bar-chart-fill"></i>
                                <span>Charts</span>
                            </a>
                            <ul class="submenu ">
                                <li class="submenu-item ">
                                    <a href="ui-chart-chartjs.html">ChartJS</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="ui-chart-apexcharts.html">Apexcharts</a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-item  ">
                            <a href="ui-file-uploader.html" class='sidebar-link'>
                                <i class="bi bi-cloud-arrow-up-fill"></i>
                                <span>File Uploader</span>
                            </a>
                        </li>

                        <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-map-fill"></i>
                                <span>Maps</span>
                            </a>
                            <ul class="submenu ">
                                <li class="submenu-item ">
                                    <a href="ui-map-google-map.html">Google Map</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="ui-map-jsvectormap.html">JS Vector Map</a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-title">Pages</li>

                        <li class="sidebar-item  ">
                            <a href="application-email.html" class='sidebar-link'>
                                <i class="bi bi-envelope-fill"></i>
                                <span>Email Application</span>
                            </a>
                        </li>

                        <li class="sidebar-item  ">
                            <a href="application-chat.html" class='sidebar-link'>
                                <i class="bi bi-chat-dots-fill"></i>
                                <span>Chat Application</span>
                            </a>
                        </li>

                        <li class="sidebar-item  ">
                            <a href="application-gallery.html" class='sidebar-link'>
                                <i class="bi bi-image-fill"></i>
                                <span>Photo Gallery</span>
                            </a>
                        </li>

                        <li class="sidebar-item  ">
                            <a href="application-checkout.html" class='sidebar-link'>
                                <i class="bi bi-basket-fill"></i>
                                <span>Checkout Page</span>
                            </a>
                        </li>

                        <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-person-badge-fill"></i>
                                <span>Authentication</span>
                            </a>
                            <ul class="submenu ">
                                <li class="submenu-item ">
                                    <a href="auth-login.html">Login</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="auth-register.html">Register</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="auth-forgot-password.html">Forgot Password</a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-x-octagon-fill"></i>
                                <span>Errors</span>
                            </a>
                            <ul class="submenu ">
                                <li class="submenu-item ">
                                    <a href="error-403.html">403</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="error-404.html">404</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="error-500.html">500</a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-title">Raise Support</li>

                        <li class="sidebar-item  ">
                            <a href="https://zuramai.github.io/mazer/docs" class='sidebar-link'>
                                <i class="bi bi-life-preserver"></i>
                                <span>Documentation</span>
                            </a>
                        </li>

                        <li class="sidebar-item  ">
                            <a href="https://github.com/zuramai/mazer/blob/main/CONTRIBUTING.md" class='sidebar-link'>
                                <i class="bi bi-puzzle"></i>
                                <span>Contribute</span>
                            </a>
                        </li>

                        <li class="sidebar-item  ">
                            <a href="https://github.com/zuramai/mazer#donate" class='sidebar-link'>
                                <i class="bi bi-cash"></i>
                                <span>Donate</span>
                            </a>
                        </li>

                    </ul>
                </div>
                <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
            </div>
        </div>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="page-heading">
                <div class="d-flex align-items-center">
                    <h3 class="mb-0 me-3">基本資料</h3>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDataModal">
                        <i class="bi bi-plus-circle me-2"></i>新增數據
                    </button>
                </div>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12 col-lg-9">
                        <div class="row">
                            <div class="col-6 col-lg-2 col-md-6">
                                <div class="card">
                                    <div class="card-body px-3 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="stats-icon purple">
                                                    <i class="bi bi-clipboard-data"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="text-muted font-semibold">記錄筆數</h6>
                                                <h6 class="font-extrabold mb-0" id="total-records">0</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2 col-md-6">
                                <div class="card">
                                    <div class="card-body px-3 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="stats-icon teal">
                                                    <i class="bi bi-rulers"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="text-muted font-semibold">最新身高</h6>
                                                <h6 class="font-extrabold mb-0" id="avg-height">0.0 cm</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2 col-md-6">
                                <div class="card">
                                    <div class="card-body px-3 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="stats-icon blue">
                                                    <i class="bi bi-speedometer2"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="text-muted font-semibold">最新體重</h6>
                                                <h6 class="font-extrabold mb-0" id="avg-weight">0.0 kg</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2 col-md-6">
                                <div class="card">
                                    <div class="card-body px-3 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="stats-icon pink">
                                                    <i class="bi bi-calculator"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="text-muted font-semibold">最新BMI</h6>
                                                <h6 class="font-extrabold mb-0" id="avg-bmi">0.0</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2 col-md-6">
                                <div class="card">
                                    <div class="card-body px-3 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="stats-icon green">
                                                    <i class="bi bi-activity"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="text-muted font-semibold">最新骨骼肌</h6>
                                                <h6 class="font-extrabold mb-0" id="avg-skeletal">0.0 kg</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2 col-md-6">
                                <div class="card">
                                    <div class="card-body px-3 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="stats-icon indigo">
                                                    <i class="bi bi-droplet"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="text-muted font-semibold">最新體脂肪重</h6>
                                                <h6 class="font-extrabold mb-0" id="avg-body-fat">0.0 kg</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2 col-md-6">
                                <div class="card">
                                    <div class="card-body px-3 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="stats-icon orange">
                                                    <i class="bi bi-percent"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="text-muted font-semibold">最新體脂率</h6>
                                                <h6 class="font-extrabold mb-0" id="avg-fat-percentage">0.0%</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2 col-md-6">
                                <div class="card">
                                    <div class="card-body px-3 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="stats-icon red">
                                                    <i class="bi bi-fire"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="text-muted font-semibold">最新代謝量</h6>
                                                <h6 class="font-extrabold mb-0" id="avg-metabolism">0 kcal</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>體重變化趨勢</h4>
                                    </div>
                                    <div class="card-body">
                                        <div id="weight-chart"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-xl-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>身體組成變化</h4>
                                    </div>
                                    <div class="card-body">
                                        <div id="body-composition-chart"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-xl-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>體脂率變化趨勢</h4>
                                    </div>
                                    <div class="card-body">
                                        <div id="fat-percentage-chart"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-xl-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>BMI變化趨勢</h4>
                                    </div>
                                    <div class="card-body">
                                        <div id="bmi-chart"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-3">
                        <div class="card">
                            <div class="card-body py-4 px-5">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-xl">
                                        <img src="assets/images/faces/1.jpg" alt="Face 1">
                                    </div>
                                    <div class="ms-3 name">
                                        <h5 class="font-bold"><?php echo htmlspecialchars($username); ?></h5>
                                        <h6 class="text-muted mb-0"><?php echo htmlspecialchars($email); ?></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h4>健康目標追蹤</h4>
                            </div>
                            <div class="card-content pb-4">
                                <div class="goal-item d-flex px-4 py-3">
                                    <div class="goal-icon bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="bi bi-target text-white"></i>
                                    </div>
                                    <div class="goal-info ms-3">
                                        <h6 class="mb-1">目標體重</h6>
                                        <p class="text-muted mb-0">70.0 kg</p>
                                    </div>
                                </div>
                                <div class="goal-item d-flex px-4 py-3">
                                    <div class="goal-icon bg-success rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="bi bi-percent text-white"></i>
                                    </div>
                                    <div class="goal-info ms-3">
                                        <h6 class="mb-1">目標體脂率</h6>
                                        <p class="text-muted mb-0">15.0%</p>
                                    </div>
                                </div>
                                <div class="goal-item d-flex px-4 py-3">
                                    <div class="goal-icon bg-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="bi bi-activity text-white"></i>
                                    </div>
                                    <div class="goal-info ms-3">
                                        <h6 class="mb-1">目標肌肉量</h6>
                                        <p class="text-muted mb-0">35.0 kg</p>
                                    </div>
                                </div>
                                <div class="px-4">
                                    <button class='btn btn-block btn-xl btn-light-primary font-bold mt-3'>設定目標</button>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h4>身體組成比例</h4>
                            </div>
                            <div class="card-body">
                                <div id="body-composition-pie-chart"></div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <footer>
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start">
                        <p>2021 &copy; Mazer</p>
                    </div>
                    <div class="float-end">
                        <p>Crafted with <span class="text-danger"><i class="bi bi-heart"></i></span> by <a
                                href="http://ahmadsaugi.com">A. Saugi</a></p>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>

    <script src="assets/vendors/apexcharts/apexcharts.js"></script>
    <script src="assets/js/pages/dashboard.js"></script>

    <script src="assets/js/main.js"></script>
    
    <!-- 新增數據彈出視窗 -->
    <div class="modal fade" id="addDataModal" tabindex="-1" aria-labelledby="addDataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDataModalLabel">新增健康數據</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="healthDataForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="test_date" class="form-label">測量日期</label>
                                <input type="date" class="form-control" id="test_date" name="test_date">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="age" class="form-label">年齡 *</label>
                                <input type="number" class="form-control" id="age" name="age" min="1" max="120" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="height_cm" class="form-label">身高 (cm) *</label>
                                <input type="number" class="form-control" id="height_cm" name="height_cm" step="0.1" min="50" max="250" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="weight_kg" class="form-label">體重 (kg) *</label>
                                <input type="number" class="form-control" id="weight_kg" name="weight_kg" step="0.1" min="20" max="300" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="skeletal_muscle" class="form-label">骨骼肌重 (kg)</label>
                                <input type="number" class="form-control" id="skeletal_muscle" name="skeletal_muscle" step="0.1">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="body_fat" class="form-label">體脂肪重 (kg)</label>
                                <input type="number" class="form-control" id="body_fat" name="body_fat" step="0.1">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fat_percentage" class="form-label">體脂率 (%)</label>
                                <input type="number" class="form-control" id="fat_percentage" name="fat_percentage" step="0.1">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="basal_metabolism" class="form-label">基礎代謝量 (kcal)</label>
                                <input type="number" class="form-control" id="basal_metabolism" name="basal_metabolism" step="0.1">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="bmi" class="form-label">BMI (可選)</label>
                                <input type="number" class="form-control" id="bmi" name="bmi" step="0.1">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary" id="saveDataBtn">保存數據</button>
                </div>
            </div>
        </div>
    </div>

    <!-- 成功提示視窗 -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">成功</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="successMessage">數據保存成功！</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">確定</button>
                </div>
            </div>
        </div>
    </div>

    <!-- 錯誤提示視窗 -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">錯誤</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="errorMessage">保存數據時發生錯誤。</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">確定</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // 設置今天的日期為默認值（使用本地時區）
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        document.getElementById('test_date').value = `${year}-${month}-${day}`;
        
        // 載入健康數據統計
        fetch('get_health_stats.php')
            .then(response => {
                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error('Failed to load health stats');
                }
            })
            .then(data => {
                // 更新統計數據（顯示最新記錄）
                document.getElementById('total-records').textContent = data.total_records;
                document.getElementById('avg-weight').textContent = data.latest_weight + ' kg';
                document.getElementById('avg-height').textContent = data.latest_height + ' cm';
                document.getElementById('avg-skeletal').textContent = data.latest_skeletal_muscle + ' kg';
                document.getElementById('avg-body-fat').textContent = data.latest_body_fat + ' kg';
                document.getElementById('avg-fat-percentage').textContent = data.latest_fat_percentage + '%';
                document.getElementById('avg-metabolism').textContent = data.latest_basal_metabolism + ' kcal';
                document.getElementById('avg-bmi').textContent = data.latest_bmi;
            })
            .catch(error => {
                console.error('Error loading health stats:', error);
            });
        
        // 載入圖表數據
        function loadChartData() {
            fetch('get_chart_data.php')
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    } else {
                        throw new Error('Failed to load chart data');
                    }
                })
                .then(data => {
                    if (data.dates && data.dates.length > 0) {
                        createWeightChart(data);
                        createBodyCompositionChart(data);
                        createFatPercentageChart(data);
                        createBMIChart(data);
                    }
                })
                .catch(error => {
                    console.error('Error loading chart data:', error);
                });
        }

        // 創建體重變化圖表
        function createWeightChart(data) {
            const weightData = data.weight.filter(val => val !== null);
            const dates = data.dates.filter((_, index) => data.weight[index] !== null);
            
            if (weightData.length === 0) return;

            const options = {
                series: [{
                    name: '體重 (kg)',
                    data: weightData
                }],
                chart: {
                    type: 'line',
                    height: 350,
                    toolbar: {
                        show: false
                    }
                },
                colors: ['#435ebe'],
                dataLabels: {
                    enabled: true,
                    formatter: function(val) {
                        return val.toFixed(1);
                    }
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                xaxis: {
                    categories: dates,
                    labels: {
                        formatter: function(value) {
                            return new Date(value).toLocaleDateString('zh-TW', {
                                month: 'short',
                                day: 'numeric'
                            });
                        }
                    }
                },
                yaxis: {
                    title: {
                        text: '體重 (kg)'
                    }
                },
                tooltip: {
                    x: {
                        format: 'dd/MM/yyyy'
                    }
                }
            };

            const chart = new ApexCharts(document.querySelector("#weight-chart"), options);
            chart.render();
        }

        // 創建身體組成圖表
        function createBodyCompositionChart(data) {
            const skeletalData = data.skeletal_muscle.filter(val => val !== null);
            const bodyFatData = data.body_fat.filter(val => val !== null);
            const dates = data.dates.filter((_, index) => 
                data.skeletal_muscle[index] !== null || data.body_fat[index] !== null
            );

            if (skeletalData.length === 0 && bodyFatData.length === 0) return;

            const options = {
                series: [{
                    name: '骨骼肌重 (kg)',
                    data: skeletalData
                }, {
                    name: '體脂肪重 (kg)',
                    data: bodyFatData
                }],
                chart: {
                    type: 'line',
                    height: 350,
                    toolbar: {
                        show: false
                    }
                },
                colors: ['#4f46e5', '#dc2626'],
                dataLabels: {
                    enabled: true,
                    formatter: function(val) {
                        return val.toFixed(1);
                    }
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                xaxis: {
                    categories: dates,
                    labels: {
                        formatter: function(value) {
                            return new Date(value).toLocaleDateString('zh-TW', {
                                month: 'short',
                                day: 'numeric'
                            });
                        }
                    }
                },
                yaxis: {
                    title: {
                        text: '重量 (kg)'
                    }
                },
                tooltip: {
                    x: {
                        format: 'dd/MM/yyyy'
                    }
                }
            };

            const chart = new ApexCharts(document.querySelector("#body-composition-chart"), options);
            chart.render();
        }

        // 創建體脂率變化圖表
        function createFatPercentageChart(data) {
            const fatPercentageData = data.fat_percentage.filter(val => val !== null);
            const dates = data.dates.filter((_, index) => data.fat_percentage[index] !== null);
            
            if (fatPercentageData.length === 0) return;

            const options = {
                series: [{
                    name: '體脂率 (%)',
                    data: fatPercentageData
                }],
                chart: {
                    type: 'line',
                    height: 350,
                    toolbar: {
                        show: false
                    }
                },
                colors: ['#ff9500'],
                dataLabels: {
                    enabled: true,
                    formatter: function(val) {
                        return val.toFixed(1);
                    }
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                xaxis: {
                    categories: dates,
                    labels: {
                        formatter: function(value) {
                            return new Date(value).toLocaleDateString('zh-TW', {
                                month: 'short',
                                day: 'numeric'
                            });
                        }
                    }
                },
                yaxis: {
                    title: {
                        text: '體脂率 (%)'
                    }
                },
                tooltip: {
                    x: {
                        format: 'dd/MM/yyyy'
                    }
                }
            };

            const chart = new ApexCharts(document.querySelector("#fat-percentage-chart"), options);
            chart.render();
        }

        // 創建BMI變化圖表
        function createBMIChart(data) {
            const bmiData = data.bmi.filter(val => val !== null);
            const dates = data.dates.filter((_, index) => data.bmi[index] !== null);
            
            if (bmiData.length === 0) return;

            const options = {
                series: [{
                    name: 'BMI',
                    data: bmiData
                }],
                chart: {
                    type: 'line',
                    height: 350,
                    toolbar: {
                        show: false
                    }
                },
                colors: ['#e83e8c'],
                dataLabels: {
                    enabled: true,
                    formatter: function(val) {
                        return val.toFixed(1);
                    }
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                xaxis: {
                    categories: dates,
                    labels: {
                        formatter: function(value) {
                            return new Date(value).toLocaleDateString('zh-TW', {
                                month: 'short',
                                day: 'numeric'
                            });
                        }
                    }
                },
                yaxis: {
                    title: {
                        text: 'BMI'
                    }
                },
                tooltip: {
                    x: {
                        format: 'dd/MM/yyyy'
                    }
                }
            };

            const chart = new ApexCharts(document.querySelector("#bmi-chart"), options);
            chart.render();
        }

        // 載入圖表數據
        loadChartData();
        
        // 創建身體組成比例圓餅圖
        function createBodyCompositionPieChart() {
            fetch('get_health_stats.php')
                .then(response => response.json())
                .then(data => {
                    const latestSkeletal = parseFloat(data.latest_skeletal_muscle) || 0;
                    const latestBodyFat = parseFloat(data.latest_body_fat) || 0;
                    const latestWeight = parseFloat(data.latest_weight) || 0;
                    
                    // 計算其他組成（體重 - 骨骼肌 - 體脂肪）
                    const otherComposition = Math.max(0, latestWeight - latestSkeletal - latestBodyFat);
                    
                    const options = {
                        series: [latestSkeletal, latestBodyFat, otherComposition],
                        chart: {
                            type: 'donut',
                            height: 250
                        },
                        labels: ['骨骼肌', '體脂肪', '其他'],
                        colors: ['#4f46e5', '#dc2626', '#6b7280'],
                        dataLabels: {
                            enabled: true,
                            formatter: function(val, opts) {
                                return opts.w.globals.series[opts.seriesIndex].toFixed(1) + ' kg';
                            }
                        },
                        legend: {
                            position: 'bottom'
                        },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '60%',
                                    labels: {
                                        show: true,
                                        total: {
                                            show: true,
                                            label: '總重量',
                                            formatter: function(w) {
                                                return w.globals.seriesTotals.reduce((a, b) => a + b, 0).toFixed(1) + ' kg';
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    };
                    
                    const chart = new ApexCharts(document.querySelector("#body-composition-pie-chart"), options);
                    chart.render();
                })
                .catch(error => {
                    console.error('Error creating body composition pie chart:', error);
                });
        }
        
        // 載入身體組成比例圖表
        createBodyCompositionPieChart();

        // 保存數據按鈕點擊事件
        document.getElementById('saveDataBtn').addEventListener('click', function() {
            // 顯示保存中狀態
            const saveBtn = document.getElementById('saveDataBtn');
            saveBtn.disabled = true;
            saveBtn.textContent = '保存中...';
            
            const form = document.getElementById('healthDataForm');
            const formData = new FormData(form);
            
            // 將FormData轉換為JSON對象
            const data = {};
            formData.forEach((value, key) => {
                data[key] = value;
            });
            
            // 發送數據到服務器
            fetch('save_health_data.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                // 恢復按鈕狀態
                saveBtn.disabled = false;
                saveBtn.textContent = '保存數據';
                
                if (result.success) {
                    // 顯示成功消息
                    document.getElementById('successMessage').textContent = result.message;
                    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                    successModal.show();
                    
                    // 關閉新增數據視窗
                    const addDataModal = bootstrap.Modal.getInstance(document.getElementById('addDataModal'));
                    addDataModal.hide();
                    
                    // 重置表單
                    form.reset();
                    // 重新設置今天的日期為默認值
                    const today = new Date();
                    const year = today.getFullYear();
                    const month = String(today.getMonth() + 1).padStart(2, '0');
                    const day = String(today.getDate()).padStart(2, '0');
                    document.getElementById('test_date').value = `${year}-${month}-${day}`;
                    
                    // 重新載入統計數據
                    fetch('get_health_stats.php')
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('total-records').textContent = data.total_records;
                            document.getElementById('avg-weight').textContent = data.latest_weight + ' kg';
                            document.getElementById('avg-height').textContent = data.latest_height + ' cm';
                            document.getElementById('avg-skeletal').textContent = data.latest_skeletal_muscle + ' kg';
                            document.getElementById('avg-body-fat').textContent = data.latest_body_fat + ' kg';
                            document.getElementById('avg-fat-percentage').textContent = data.latest_fat_percentage + '%';
                            document.getElementById('avg-metabolism').textContent = data.latest_basal_metabolism + ' kcal';
                            document.getElementById('avg-bmi').textContent = data.latest_bmi;
                        })
                        .catch(error => {
                            console.error('Error reloading health stats:', error);
                        });
                    
                    // 重新載入圖表數據
                    loadChartData();
                } else {
                    // 顯示錯誤消息
                    document.getElementById('errorMessage').textContent = result.error || '保存數據時發生錯誤';
                    const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                    errorModal.show();
                }
            })
            .catch(error => {
                // 恢復按鈕狀態
                saveBtn.disabled = false;
                saveBtn.textContent = '保存數據';
                
                console.error('Error:', error);
                document.getElementById('errorMessage').textContent = '網絡錯誤，請稍後再試';
                const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                errorModal.show();
            });
        });
        
        // 為主要內容區域添加 PerfectScrollbar
        if(typeof PerfectScrollbar == 'function') {
            const mainContent = document.querySelector("#main");
            if(mainContent) {
                const mainScroll = new PerfectScrollbar(mainContent, {
                    wheelPropagation: false
                });
            }
        }
    </script>
</body>

</html> 