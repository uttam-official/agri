
<div class="navMenu-main">
    <div id="menu" class="gn-icon-menu"><span></span></div>
</div>
<div class="top-menu">
    <div class="container">
        <div id="slidingMenu">
            <nav id="navMenu">
                <ul>
                    <li><a class="active" href="<?= BASE_URL ?>">Home</a></li>
                    <?php foreach (get_category($connect) as $l) :?>
                            <li><a href="#"><?= $l->name ?></a>
                                <ul>
                                    <?php 
                                        foreach(get_subcategory($l->id,$connect) as $sl){
                                            echo '<li><a href="#">'.$sl->name.'</a></li>';
                                        }
                                    ?>
                                </ul>
                            </li>
                    <?php endforeach;?>
                </ul>
            </nav>
        </div>
    </div>
</div>