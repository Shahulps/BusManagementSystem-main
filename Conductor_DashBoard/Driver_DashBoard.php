<?php
session_start();

if ($_SESSION['status'] != "Active") {
    header("location:../Login/dist/login.php");
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Driver Dashboard</title>
    <link rel="icon" type="image/x-icon" href="../Images/favicon.ico">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css"
        integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <link rel="stylesheet" href="../Login/dist/style.css">
    <style>
        html,
        body {
            min-height: 100%;
        }

        body,
        div,
        form,
        input,
        select,
        p {
            padding: 0;
            margin: 0;
            outline: none;
            font-family: Roboto, Arial, sans-serif;
            font-size: 16px;
            color: #eee;
        }

        body {
            background: url("https://images.livemint.com/img/2021/06/09/600x338/20210609166L_1623247807414_1623247834987.jpg") no-repeat center;
            background-size: cover;
        }

        h1,
        h2 {
            text-transform: uppercase;
            font-weight: 400;
        }

        h2 {
            margin: 0 0 0 8px;
        }

        .main-block {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
            padding: 25px;
            background: rgba(0, 0, 0, 0.5);
        }

        .left-part,
        form {
            padding: 25px;
        }

        .left-part {
            text-align: center;
        }


        .btn-item,
        button {
            padding: 10px 5px;
            margin-top: 20px;
            border-radius: 5px;
            border: none;
            background: #26a9e0;
            text-decoration: none;
            font-size: 15px;
            font-weight: 400;
            color: #fff;
        }

        .btn-item {
            display: inline-block;
            margin: 20px 5px 0;
        }

        button {
            width: 100%;
        }

        button:hover,
        .btn-item:hover {
            background: #85d6de;
        }

        @media (min-width: 568px) {

            html,
            body {
                height: 100%;
            }

            .main-block {
                flex-direction: row;
                height: calc(100% - 50px);
            }

            .left-part,
            form {
                flex: 1;
                height: auto;
            }
        }
    </style>
</head>

<body>
    <nav id="mainNavbar" class="navbar navbar-light navbar-expand-md py-1 px-2 fixed-top"
        style="background-color: #0cb2f9;">
        <a class="navbar-brand" href="#">
            <img src="../Images/icon.png" width="45" height="35" class="d-inline-block align-middle" alt="">
            BUS MAESTRO
        </a>

        <button class="navbar-toggler" data-toggle="collapse" data-target="#navLinks" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-between" id="navLinks">


            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="Driver_Dashboard.php" class="nav-link">HOME</a>
                </li>
                <li class="nav-item">
                    <a href="../about.html" class="nav-link">ABOUT</a>
                </li>
                <li class="nav-item">
                    <a href="../team.html" class="nav-link">TEAM</a>
                </li>


            </ul>

            <span class="nav-item">
                <a class="nav-link" role="button" href="../Login/dist/logout.php">Logout</a>
            </span>

        </div>
    </nav>
    <div class="main-block" style="width: 100%; margin: 0 auto; height: 100%;">
        <div>
            <img src="https://cdn-icons-png.flaticon.com/512/2684/2684218.png">
            <br>
            <h3>Driver ID:
                <?php echo $_SESSION['username'] ?>
            </h3>
            <h4> <!-- PHP CODE TO PRINT WELCOME STATMENT -->
                <?php
                //PHP CONNECTION
                $hostName = "localhost";
                $userName = "root";
                $password = "";
                $databaseName = "db";
                $conn = new mysqli($hostName, $userName, $password, $databaseName);
                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }


                $db = $conn;
                $tableName = "bus_details";

                $columns = ['trip_no', 'bus_no', 'TripDate'];
                $fetchData = fetch_data($db, $tableName, $columns);
                function fetch_data($db, $tableName, $columns)
                {
                    if (empty($db)) {
                        $msg = "Database connection error";
                    } elseif (empty($columns) || !is_array($columns)) {
                        $msg = "columns Name must be defined in an indexed array";
                    } elseif (empty($tableName)) {
                        $msg = "Table Name is empty";
                    } else {

                        $conID = $_SESSION['username'];
                        $result = $db->query("SELECT  `bus_details`.`trip_no` AS `trip_no`,`bus_details`.`bus_no` AS `bus_no`,`bus_details`.`TripDate` AS `TripDate`
                        FROM (`bus_details` join `trip_incharge` on(`trip_incharge`.`trip_no_incharge` = `bus_details`.`trip_no`))
                        WHERE `Driver_emp_id`='$conID'
                        ORDER BY TripDate DESC
                        LIMIT 1;");

                        //take code from specificRevenue.php-->
                        //$query="SELECT * FROM Milage ORDER BY DESC";
//$db->query($query1);
                


                        if ($result == true) {
                            if ($result->num_rows > 0) {
                                $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
                                $msg = $row;
                            } else {
                                $msg = "No Data Found";
                            }
                        } else {
                            $msg = mysqli_error($db);
                        }
                    }
                    return $msg;
                } {
                    foreach ((array) $fetchData as $data) {
                        $chk = $data['bus_no'] ?? '';
                        if (!$chk) {
                ?>Welcome! No trips Assigned Yet!
                <?php
                        } else {
                ?>
                  WELCOME!!!
                <!Welcome! Latest Trip ID:(for
                <//?php echo $data['TripDate'] ?? ''; ?>):
                <//?php echo $data['trip_no'] ?? ''; ?><br>
                Bus number:
                <?php echo $data['bus_no'] ?? ''; ?>



                <?php
                        }
                    }
                }
                ?>

            </h4>


        </div>
        <div class=" left-part">

            <h1>Driver Dashboard</h1>
            <br><br><br><br><br>
            <h3>Enter Details</h3>

            <div class="">
                <a class="btn btn-item btn-block" style="width: 50%;" href="driverDetails.php">Enter trip Details</a>



            </div>

            <br><br><br>

            <h3>Your Schedule</h3>
            <div class="">
                <!-- DISPLAYS ONLY THOSE TRIPS WHICH HE HAS BEEN ASSIGNED -->
                <a class="btn btn-item btn-block" style="width: 25%;" href="DriTripView.php">Assigned Trips</a>
            </div>

        </div>

    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
        crossorigin="anonymous"></script>

    <script>
        $(function () {
            $(document).scroll(function () {
                var $nav = $("#mainNavbar");
                $nav.toggleClass("scrolled", $(this).scrollTop() > $nav.height());
            });
        });
    </script>

</body>

</html>