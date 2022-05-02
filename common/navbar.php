<div class="navMenu-main">
    <div id="menu" class="gn-icon-menu"><span></span></div>
</div>
<div class="top-menu">
    <div class="container">
        <div id="slidingMenu">
            <nav id="navMenu">
                <ul class="nav">
                    <li><a href="<?= BASE_URL ?>" class="home">Home</a></li>
                    <?php foreach (get_category($connect) as $l) : ?>
                        <li><a href="<?= BASE_URL . 'category.php?cid=' . $l->id ?>"><?= $l->name ?></a>
                            <ul>
                                <?php
                                foreach (get_subcategory($l->id, $connect) as $sl) {
                                    echo '<li><a href="' . BASE_URL . 'category.php?cid=' . $l->id . '&sid=' . $sl->id . '">' . $sl->name . '</a></li>';
                                }
                                ?>
                            </ul>
                        </li>
                    <?php endforeach; ?>
                    <li><a href="#">Pages</a>
                        <ul>
                            <?php
                            foreach (get_category($connect) as $sl) {
                                echo '<li><a href="' . BASE_URL . 'category2' . $sl->slug_url .'">' . $sl->name . '</a></li>';
                            }
                            ?>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>