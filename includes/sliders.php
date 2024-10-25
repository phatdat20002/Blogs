<?php  
$sliders = new sliders($db);
$stmt_sliders = $sliders->readAll();
?>
<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <?php
        $slideCount = 0;
        while($rows = $stmt_sliders->fetch()){
            $activeClass = $slideCount === 0 ? 'active' : '';
            echo '<li data-target="#carouselExampleIndicators" data-slide-to="' . $slideCount . '" class="' . $activeClass . '"></li>';
            $slideCount++;
        }
        ?>
    </ol>

    <div class="carousel-inner">
        <?php  
        $stmt_sliders->execute(); // Re-execute to reset the pointer
        $firstSlide = true;
        while($rows = $stmt_sliders->fetch()){
            $activeClass = $firstSlide ? 'active' : '';
            $firstSlide = false;
            ?>
            <div class="carousel-item <?php echo $activeClass; ?>">
                <img class="d-block w-100" src="images/sliders/<?php echo $rows['image'] ?>" alt="Slide">
                <div class="carousel-caption d-none d-md-block">
                    <h5><?php echo $rows['title'] ?></h5>
                </div>
            </div>
            <?php  
        }
        ?>
    </div>

    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
