<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <script type="text/javascript" src="../ChartNew/ChartNew.js"></script>
    <title>ATP Tennis 2014 Tour Anaysis - David Dalisay </title>
    <link href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/one-page-wonder.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Home</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="#about">About</a>
                    </li>
                    <li>
                        <a href="#contact">Contact</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Full Width Image Header -->
    <header class="header-image">
        <div class="headline">
                <h1>ATP Tennis 250 Series Analysis</h1>
                <!-- <h2>David V Dalisay</h2> -->
        </div>
    </header>

    <!-- Page Content -->
    <div class="container">

        <hr class="featurette-divider">

        <!-- First Featurette -->
        <div class="featurette" id="about">
                <div style="width: 100%; overflow: hidden;">
                <div style="width: 550px; float: left;">
                    <h2 class="featurette-heading">Number of players per country
                         <p class="lead">
                            <br></br>
                            Each year, the ATP Tennis Pro Series involves a number of professional tennis players representing
                            particular countries. Observe the number of tennis players representing each country. After seeing
                            these numbers, compare in the next graphs each country's performance in various ways. Specifically,
                            observe the performance in terms of specific players and particular court surfaces. The graph to
                            the right shows the top 10 countries that have the most players in the 250 series.
                         </p>
                    </h2>
                </div>
                <div style="margin-left: 605px;">
                    <canvas id="canvas" height="500" width="550"></canvas>
                    <?php
                    $mysqli = new mysqli("localhost", "root", "root", "ATPTennis");
                    if (mysqli_connect_errno()) {
                        printf("Connect failed: %s\n", mysqli_connect_error());
                        exit();
                    }
                    $data=mysqli_query($mysqli,"select * from (select count(*) as number_of_players,country from PlayerCountry group by country) as data order by number_of_players desc limit 10;");
                    ?>
                    <script>
                        var newopts = {
                            inGraphDataShow: true,
                            inGraphDataFontSize : 10
                        }
                        var PieChart = new Array();
			<?php while($row = $data->fetch_assoc()) { ?>
				 var letters = '0123456789ABCDEF'.split('');
				 var color = '#';
				 for (var i = 0; i < 6; i++ ) {
					color += letters[Math.floor(Math.random() * 16)];
				 }
        			 PieChart.push({value:'<?php echo $row["number_of_players"] ?>',label:'<?php echo $row["country"] ?>',title:'<?php echo $row["country"] ?>', color:color});
			<?php } mysqli_close($mysqli); ?>
                        //PieChart.push({value:42,label:"orange",title:"orange",color:"orange"});
			var pieCtx = document.getElementById('canvas').getContext('2d');
                        new Chart(pieCtx).Pie(PieChart, newopts);
                     </script>
                </div>
                </div>
        </div>

        <hr class="featurette-divider">

        <!-- Second Featurette -->
        <div class="featurette" id="services">

            <div style="width: 100%; overflow: hidden;">
                <div>
                    <h2 class="featurette-heading">Number of wins per country
                        <p class="lead">
                            </br>
                            Obvious calculations reflect that amount of tennis players per country. Though Spain produces amazing tennis players, they also have
                            the most amount of tennis players in the 250 ATP series. The bar graph shows the dominance in wins per country in the ATP series given
                            how many players represent said country. But, there are indicators of a country's performance based off of the large amount of wins even
                            given a small amount of representative professional players. Switzerland, not even in the top 10 for number of players, has more wins
                            than USA and Italy. This bar graph shows both the obvious and surprising amount of wins per country given the previous data provided in the last graph.
                        </p>
                    </h2>
                </div>
                <div>
                    <canvas id="canvas2" height="500" width="1200"></canvas>
                    <?php
                    $mysqli = new mysqli("localhost", "root", "root", "ATPTennis");
                    if (mysqli_connect_errno()) {
                        printf("Connect failed: %s\n", mysqli_connect_error());
                        exit();
                    }
                    $data=mysqli_query($mysqli,"select count(a.Winner) as wins,b.country as country from tennisMatch a inner join PlayerCountry b on a.Winner = b.player group by b.country;");
                    ?>
                    <script>
                        var opts = {}
                        var theData = {
                            labels: [],
                            datasets: [
                                {
                                    label: "Num of wins per country",
                                    fillColor: "rgba(220,220,220,0.5)",
                                    strokeColor: "rgba(220,220,220,0.8)",
                                    highlightFill: "rgba(220,220,220,0.75)",
                                    highlightStroke: "rgba(220,220,220,1)",
                                    data: []
                                }
                            ]
                        };
//                        theData.datasets[0].data.push(7);
                        <?php while($row = $data->fetch_assoc()) { ?>
                            theData.labels.push("<?php echo $row["country"] ?>");
                            theData.datasets[0].data.push(<?php echo $row["wins"]; ?>);
                        <?php } ?>

                        var barCtx = document.getElementById('canvas2').getContext('2d');
                        new Chart(barCtx).Bar(theData,opts);
                    </script>
                </div>
            </div>
        </div>

        <hr class="featurette-divider">

        <!-- Third featurette -->
        <div class="featurette" id="services">

            <div>
                <!--style="width: 100%; overflow: hidden;" -->
                <div>
                    <h2 class="featurette-heading">Number of wins per country
                        <p class="lead">
                            </br>
                            Observe how the number of wins varies greatly when calculated per tennis court surface. The left radar graph shows the number of wins per country
                            on clay surfaces. The right radar graph shows the number of wins per country on hard surfaces. Both graphs present data from the top 10 countries
                            with the most players in the series. Spain still dominated because of its large amount of talented tennis players. But, notice
                            how Argentina and Italy have a larger amount of wins on clay courts than France and United States. While, on hard courts,
                            Argentina and Italy are so far below France and United States. The majority of ATP Tennis Courts are on hard courts. But that
                            is an even bigger indicator of expertise on grass or clay courts for particular countries.
                        </p>
                    </h2>
                </div>
                <div style="width: 550px; float: left;">
                    <canvas id="canvas3" height="400" width="600"></canvas>
                    <?php
                    $mysqli = new mysqli("localhost", "root", "root", "ATPTennis");
                    if (mysqli_connect_errno()) {
                        printf("Connect failed: %s\n", mysqli_connect_error());
                        exit();
                    }
                    $data=mysqli_query($mysqli,'select * from (select count(a.winner) as wins,b.country,a.surface from tennisMatch a inner join PlayerCountry b
                    on a.winner = b.player where a.surface="Clay" and b.country in ("Australia","Croatia","Ukraine","Russia","Italy","Germany",
                    "Argentina","United States","France","Spain") group by b.country,a.surface) t order by wins;');
                    ?>
                    <script>
                        var opts = {
//                            animation: false,
                            inGraphDataShow: true,
//                            datasetFill: false,
//                            legend : true,
                            graphMin : 0,
                            graphMax : 50
                        }
                        var theData = {
                            labels: [],
                            datasets: [
                                {
                                    fillColor : "rgba(220,220,220,0.5)",
                                    strokeColor : "rgba(220,220,220,1)",
                                    pointColor : "rgba(220,220,220,1)",
                                    pointStrokeColor : "#fff",
                                    markerShape: "circle",
                                    pointDotRadius : 5,
                                    pointStrokeWidth : 2,
                                    data : [],
                                    title : "Wins per country per tennis court surface 2014"
                                }
                            ]
                        };
                        <?php while($row = $data->fetch_assoc()) { ?>
                        theData.labels.push("<?php echo $row["country"] ?>");
                        theData.datasets[0].data.push(<?php echo $row["wins"]; ?>);
                        <?php } ?>

                        var radarCtx = document.getElementById('canvas3').getContext('2d');
                        new Chart(radarCtx).Radar(theData,opts);
                    </script>
                </div>
                <div style="width: 550px; float: left;">
                    <canvas id="canvas4" height="400" width="600">
                    </canvas>
                    <?php
                    $mysqli = new mysqli("localhost", "root", "root", "ATPTennis");
                    if (mysqli_connect_errno()) {
                        printf("Connect failed: %s\n", mysqli_connect_error());
                        exit();
                    }
                    $data=mysqli_query($mysqli,'select * from (select count(a.winner) as wins,b.country,a.surface from tennisMatch a inner join PlayerCountry b on a.winner = b.player where a.surface="Hard"
                    and b.country in ("Australia","Croatia","Ukraine","Russia","Italy","Germany",
                    "Argentina","United States","France","Spain") group by b.country,a.surface) t order by wins;');
                    ?>
                    <script>
                        var opts = {
//                            animation: false,
                            inGraphDataShow: true,
//                            datasetFill: false,
//                            legend : true,
                            graphMin : 0,
                            graphMax : 100
                        }
                        var theData = {
                            labels: [],
                            datasets: [
                                {
                                    fillColor : "rgba(220,220,220,0.5)",
                                    strokeColor : "rgba(220,220,220,1)",
                                    pointColor : "rgba(220,220,220,1)",
                                    pointStrokeColor : "#fff",
                                    markerShape: "circle",
                                    pointDotRadius : 5,
                                    pointStrokeWidth : 2,
                                    data : [],
                                    title : "Wins per country per tennis court surface 2014"
                                }
                            ]
                        };
                        <?php while($row = $data->fetch_assoc()) { ?>
                        theData.labels.push("<?php echo $row["country"] ?>");
                        theData.datasets[0].data.push(<?php echo $row["wins"]; ?>);
                        <?php } ?>

                        var radarCtx = document.getElementById('canvas4').getContext('2d');
                        new Chart(radarCtx).Radar(theData,opts);
                    </script>
                </div>
            </div>
        </div>

        <hr class="featurette-divider">
        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>dalisaydavid.com</p>
                </div>
            </div>
        </footer>

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
