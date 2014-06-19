</header>
</div>

<div id="left" style="margin-top:-52px">

    <div class="media user-media">
        <a class="user-link">
            <span class="face20 media-object img-thumbnail user-img" style="width: 64px;height: 64px"></span>
        </a>
        <div class="media-body">
            <h5 class="media-heading"><?=$_SESSION['user']['name']?></h5>
            <ul class="list-unstyled user-info">
                <li><?=$_SESSION['user']['etn']?></li>
                <li><a href="<?=url('logout','oaseadmin');?>">Logout</a></li>
            </ul>
        </div>
    </div>

    <ul id="menu" class="collapse">
        <li class="nav-header">Menu</li>
        <li class="nav-divider"></li>
        <?php
        foreach ($data['menu'] as $key => $value) {

            if($value['priority'] <= $_SESSION['priority']) continue;

            if ( array_key_exists($data['active'] , $value['submenu']) ){ ?>
        <li class="active">
        <?php }else{ ?>
                <li class="">
            <?php
            }
            ?>
            <a href="">
                <i class="fa fa-<?= $value['icon'] ?>"></i>&nbsp; <?php echo $key ?>
                <span class="fa arrow"></span>
                <?php
                if (!empty($value['submenu'])) {
                    ?>
                    <ul>
                        <?php
                        foreach ($value['submenu'] as $key => $value) {
                            if ($key == $data['active']) {
                                ?>
                                <li class="active">
                            <?php
                            } else {
                                ?>
                                <li class="">
                            <?php
                            }
                            ?>
                            <a href="<?=$value?>">
                                <i class="fa fa-angle-right"></i>&nbsp; <?=$key ?>
                            </a>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                <?php
                }
                ?>
            </a>
            </li>
            <?php
        }
        ?>
    </ul>
</div>