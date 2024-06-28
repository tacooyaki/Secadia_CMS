<?php
require_once 'connect.php';
require_once 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Secadia Mycological Society CMS</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .carousel-item img {
            max-height: 450px;
            max-width: 790px;
            object-fit: cover;
            margin: auto;
        }
        .carousel-caption {
            background-color: rgba(0, 0, 0, 0.6); /* Semi-transparent background */
            padding: 10px;
            border-radius: 5px;
        }
        .carousel-caption h5,
        .carousel-caption p {
            color: #e0e896; /* Text color */
        }
    </style>
</head>
<body>
<header class="container-fluid text-center py-3">
    <img src="uploads/logo.png" alt="Secadia Mycological Society Logo" class="img-fluid mb-2">
    <p>Secadia Mycological Society's Content Management System</p>
</header>
<div class="container mt-4">
    <section id="carousel">
        <div id="featuredCarousel" class="carousel slide" data-ride="carousel" data-interval="7000">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="uploads/mushroom_stalk.png" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block" >
                        <h5>Mycena</h5>
                        <p >Commonly referred to as "fairy helmets", they are known for their delicate,
                            slender stems and bell-shaped caps</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="uploads/mushroom_gills.png" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Golden Chanterelle</h5>
                        <p>The Cantharellus cibarius are distinguished by their vibrant yellow to orange color,
                            funnel-shaped cap, and the ridges underneath.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="uploads/mushroom_cap_spore_print.png" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Fly Agaric</h5>
                        <p>Amanita muscaria, recognized by its bright red cap with white spots,
                            which are remnants of the universal veil that covers the mushroom when it is young.</p>
                    </div>
                </div>
            </div>
            <a class="carousel-control-prev" href="#featuredCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#featuredCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </section>
    <section id="news-events" class="mt-5">
        <h3>News & Events</h3>
        <div class="row">
            <div class="col-md-6">
                <h5>Recent News</h5>
                <p>Latest updates from the Secadia Mycological Society.</p>
            </div>
            <div class="col-md-6">
                <h5>Upcoming Events</h5>
                <p>Information about upcoming events and meetings.</p>
            </div>
        </div>
    </section>
    <section id="about-us" class="mt-5">
        <h3>About Us</h3>
        <p>Founded on principles of scientific exploration and community collaboration, the Secadia Mycological Society offers a model for mycology researchers. As a non-profit organization, we're committed to the documentation and recording of mycological findings in nature.</p>
        <p>We aim to connect enthusiasts and professionals alike through our shared passion for mycology. Join us in our efforts to explore, document, and preserve the diverse world of fungi.</p>
    </section>
</div>

<?php require_once 'footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
