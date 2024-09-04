<?php

// Import the file where we defined the connection to Database.     
require_once "php/db.php";

$per_page_record = 100;  // Number of entries to show in a page.   
// Look for a GET variable page if not found default is 1.        
if (isset($_GET["page"])) {
    $page  = $_GET["page"];
} else {
    $page = 1;
}
$start_from = ($page - 1) * $per_page_record;

$query = "SELECT * FROM orders ORDER BY id DESC LIMIT $start_from, $per_page_record";
$result = mysqli_query($conn, $query);
?>
<!doctype html>
<html lang="en">



<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- TITLE OF SITE -->
    <title>Copy Book | Sank - Magic Copy Book</title>

    <meta name="description" content="Sank magic copy book - E-learning/smart kids" />
    <meta name="keywords" content="kids, learning, e-learning, magic copy, sank, copy book, education, smart kid, grown kids, primary schools, one page" />
    <meta name="author" content="Themedept">

    <!-- FAVICON  -->
    <!-- Place your favicon.ico in the images directory -->
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">

    <!-- =========================
       STYLESHEETS 
    ============================== -->
    <!-- BOOTSTRAP CSS -->
    <link rel="stylesheet" href="css/plugins/bootstrap.min.css">

    <!-- FONT ICONS -->
    <link rel="stylesheet" href="css/icons/iconfont.css">
    <link rel="stylesheet" href="maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

    <!-- GOOGLE FONTS -->
    <!-- <link href='https://fonts.googleapis.com/css?family=Lato:300,400,700,900,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'> -->
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:300,400,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Jura:300,400,500,600,700" rel="stylesheet" />

    <!-- PLUGINS STYLESHEET -->
    <link rel="stylesheet" href="css/plugins/magnific-popup.css">
    <link rel="stylesheet" href="css/plugins/owl.carousel.css">
    <link rel="stylesheet" href="css/plugins/loaders.css">
    <link rel="stylesheet" href="css/plugins/animate.css">
    <link rel="stylesheet" href="css/plugins/pickadate-default.css">
    <link rel="stylesheet" href="css/plugins/pickadate-default.date.css">

    <!-- CUSTOM STYLESHEET -->
    <link rel="stylesheet" href="css/style.css">

    <!-- RESPONSIVE FIXES -->
    <link rel="stylesheet" href="css/responsive.css">
    <style>
        .search-box {
            width: 100%;
            position: relative;
            display: inline-block;
            font-size: 25px;
        }

        .search-box input[type="text"] {
            height: 32px;
            padding: 5px 10px;
            margin-bottom: 30px;
            border: 1px solid #CCCCCC;
            font-size: 25px;
        }

        .pagination {
            display: inline-block;
        }

        .pagination a {
            font-weight: bold;
            font-size: 18px;
            color: black;
            float: left;
            padding: 8px 16px;
            text-decoration: none;
            border: 1px solid black;
        }

        .pagination a.active {
            background-color: #FFB606;
        }

        .pagination a:hover:not(.active) {
            background-color: skyblue;
        }
    </style>


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>


<body data-spy="scroll" data-target="#main-navbar">

    <!-- Preloader -->
    <div class="loader bg-blue">
        <div class="loader-inner ball-scale-ripple-multiple vh-center">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>


    <div class="main-container" id="page">

        <!-- =========================
            HEADER 
        ============================== -->
        <header id="nav2-1">

            <nav class="navbar navbar-fixed-top" id="main-navbar">
                <div class="container">

                    <div class="navbar-header">
                        <!-- Menu Button for Mobile Devices -->
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>

                        <!-- Image Logo -->
                        <!-- note:
                            recommended sizes
                                width: 150px;
                                height: 35px;
                        -->
                        <a href="#" class="navbar-brand smooth-scroll"><img src="images/logo-black.png" alt="logo" /></a>
                        <!-- Image Logo For Background Transparent -->
                        <!--
                            <a href="#" class="navbar-brand logo-black smooth-scroll"><img src="images/logo-black.png" alt="logo" /></a>
                            <a href="#" class="navbar-brand logo-white smooth-scroll"><img src="images/logo-white.png" alt="logo" /></a> 
                        -->
                    </div><!-- /End Navbar Header -->

                </div><!-- /End Container -->
            </nav><!-- /End Navbar -->
        </header>
        <!-- /End Header Navigation -->

        <!-- =========================
           TIMETABLE
        ============================== -->
        <section id="timetable1-1" class="p-y-lg bg-edit">
            <div class="container">
                <!-- Section Header -->
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="section-header text-center wow fadeIn">
                            <h2>Customers Order List</h2>
                            <p class="lead">Please update the deliver Status here.</p>
                        </div>
                    </div>
                </div>
                <!-- Timetable Tab -->
                <div class="row">
                    <div class="col-md-12 timetable">
                        <!-- <div class="col-xs-12 search-box">
                            <label>Search Customer Details</label>
                            <input type="text" name="search" id="search" placeholder="Enter Customer Details" class="form-control" />
                            <div id="result"></div>
                        </div> -->


                        <!-- Tab Content -->
                        <div class="tab-content">
                            <!-- Tab Panel 1 -->
                            <div role="tabpanel" class="tab-pane fade in active" id="monday">
                                <div class="table-responsive text-center">
                                    <?php
                                    if (
                                        mysqli_num_rows($result) >
                                        0
                                    ) { ?>

                                        <table class="table text-uppercase table-striped">
                                            <thead class="bg-purple text-white">
                                                <tr>
                                                    <th>
                                                        <span class="custom-checkbox">
                                                            <input type="checkbox" id="selectAll">
                                                            <label for="selectAll"></label>
                                                        </span>
                                                    </th>
                                                    <th class="text-edit">S/N</th>
                                                    <th class="text-edit">CUSTOMER NAME</th>
                                                    <th class="text-edit">ORDER PACK</th>
                                                    <th class="text-edit">PHONE NUMBER</th>
                                                    <th class="text-edit">ALTERNATIVE NUMBER</th>
                                                    <th class="text-edit">ADDRESS</th>
                                                    <th class="text-edit">STATE</th>
                                                    <th class="text-edit">ORDER DATE & TIME</th>
                                                    <th class="text-edit">UPDATED DELIVERY STATUS</th>
                                                    <th class="text-edit">DELIVERY STATUS</th>
                                                    <th>ACTION</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($result as $row) { ?>
                                                    <tr id="<?php echo $row["id"]; ?>">
                                                        <td>
                                                            <span class="custom-checkbox">
                                                                <input type="checkbox" class="user_checkbox" data-user-id="<?php echo $row["id"]; ?>">
                                                                <label for="checkbox2"></label>
                                                            </span>
                                                        </td>

                                                        <th scope="row"><?php echo $row['id']; ?></th>
                                                        <td><?php echo $row["fullname"]; ?></td>
                                                        <td><?php echo $row["pack"]; ?></td>
                                                        <td><?php echo $row["phone"]; ?></td>
                                                        <td><?php echo $row["altphone"]; ?></td>
                                                        <td><?php echo $row["address"]; ?></td>
                                                        <td><?php echo $row["state"]; ?></td>
                                                        <td><?php echo $row["created_at"]; ?></td>
                                                        <td><?php echo $row["updated_at"]; ?></td>
                                                        <td><?php echo $row["delivery_status"]; ?></td>
                                                        <td>
                                                            <a href="#editEmployeeModal" class="edit" data-toggle="modal">
                                                                <i class="material-icons update" data-toggle="tooltip" data-id="<?php echo $row["id"]; ?>" data-pack="<?php echo $row["delivery_status"]; ?>" title="Edit">Edit Delivery Status</i>
                                                            </a>

                                                        </td>


                                                    </tr>
                                                <?php } ?>

                                            </tbody>
                                        </table>
                                    <?php
                                    } else {
                                        echo "<h3>No record found</h3>";
                                    }
                                    ?>

                                </div>
                                <div class="pagination">
                                    <?php
                                    $query = "SELECT COUNT(*) FROM orders";
                                    $result = mysqli_query($conn, $query);
                                    $row = mysqli_fetch_row($result);
                                    $total_records = $row[0];

                                    echo "</br>";
                                    // Number of pages required.   
                                    $total_pages = ceil($total_records / $per_page_record);
                                    $pagLink = "";

                                    if ($page >= 2) {
                                        echo "<a href='customers_lists.php?page=" . ($page - 1) . "'>  Prev </a>";
                                    }

                                    for ($i = 1; $i <= $total_pages; $i++) {
                                        if ($i == $page) {
                                            $pagLink .= "<a class = 'active' href='customers_lists.php?page="
                                                . $i . "'>" . $i . " </a>";
                                        } else {
                                            $pagLink .= "<a href='customers_lists.php?page=" . $i . "'>   
                                                " . $i . " </a>";
                                        }
                                    };
                                    echo $pagLink;

                                    if ($page < $total_pages) {
                                        echo "<a href='customers_lists.php?page=" . ($page + 1) . "'>  Next </a>";
                                    }

                                    ?>
                                </div>
                            </div><!-- /End Tab-Panel 1 -->


                        </div><!-- /End Tab Content -->
                    </div><!-- /End Col-12 Timetable -->
                </div><!-- /End Row -->
                <!-- Section Footer -->
                <!-- <div class="col-md-8 col-md-offset-2 text-center m-t-lg wow fadeIn">
                    <h4 class="m-t f-w-900">Choose Your Classes and Start Your Training</h4>
                    <p class="p-opacity m-b-md">Quis dolorem architecto nemo, enim pariatur, aliquid laudantium voluptatum animi. Whoever you are and whatever you’re looking for, you’ll find your place at Getleads Yoga.</p>
                    <a href="#subscription6-1" class="btn btn-shadow btn-purple text-uppercase">Get your free week</a>
                </div> -->
            </div><!-- /End Main Container -->

        </section>
        <!-- /End Timetable Section -->
        <!-- Edit Modal HTML -->
        <div id="editEmployeeModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="update_form">
                        <div class="modal-header">
                            <h4 class="modal-title">Update deliver report</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="hidden" id="id_u" name="id" class="form-control" required>
                                <label>Delivery Status/Report</label>
                                <input type="text" class="form-control" id="delivery_status" name="delivery_status">
                            </div>
                        </div>

                        <!-- <div class="form-group">
                            <label>Delivery Status/Report</label>
                            <input type="text" id="delivery_status" name="delivery_status" class="form-control" required>
                        </div> -->
                </div>
                <div class="modal-footer">
                    <input type="hidden" value="2" name="type">
                    <input type="button" class="btn btn-red btn-shadow text-uppercase" data-dismiss="modal" value="Cancel">
                    <button type="button" class="btn btn-shadow btn-purple text-uppercase" id="update">Update</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- =========================
             FOOTER
        ============================== -->
    <footer id="footer1-2" class="p-y-md footer f1 bg-edit bg-dark">
        <div class="container">
            <div class="row">
                <!-- Copy -->
                <div class="col-sm-8 text-white">
                    <p>All rights reserved - Copyright &copy; <?php echo date("Y"); ?> Sank Magic Copy Book by <a href="#" class="f-w-900 inverse"> Emerald Digital Prime Tech Global

                        </a></p>
                </div>
                <!-- Social Links -->
                <div class="col-sm-4">
                    <ul class="footer-social inverse">
                        <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                        <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                    </ul>
                </div>
            </div><!-- /End Row -->
        </div><!-- /End Container -->
    </footer>







    </div><!-- /End Main Container -->


    <!-- Back to Top Button -->
    <a href="#" class="top" style="background-color:#439FE0;">Top</a>


    <!-- =========================
         SCRIPTS 
    ============================== -->
    <script src="js/plugins/jquery1.11.2.min.js"></script>
    <script src="js/plugins/bootstrap.min.js"></script>
    <script src="js/plugins/jquery.easing.1.3.min.js"></script>
    <script src="js/plugins/jquery.countTo.js"></script>
    <script src="js/plugins/jquery.formchimp.min.js"></script>
    <script src="js/plugins/jquery.jCounter-0.1.4.js"></script>
    <script src="js/plugins/jquery.magnific-popup.min.js"></script>
    <script src="js/plugins/jquery.vide.min.js"></script>
    <script src="js/plugins/owl.carousel.min.js"></script>
    <script src="js/plugins/spectragram.min.js"></script>
    <script src="js/plugins/twitterFetcher_min.js"></script>
    <script src="js/plugins/wow.min.js"></script>
    <script src="js/plugins/picker.js"></script>
    <script src="js/plugins/picker.date.js"></script>
    <!-- Custom Script -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
    <script src="js/ajax.js"></script>
    <script src="js/custom.js"></script>


</body>



</html>