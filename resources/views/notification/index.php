<?php include TEMPLATE_DIR . '/header.php'; ?>
<main>
    <div class="left-ots">
        <h1><?= $data['title'] ?></h1>
        <?php if (!empty($data['list'])) { ?>

            <?php foreach ($data['list'] as  $notif) { ?>
                <div class="fl<?php if($notif['read_flag'] == 0) { ?> active<?php } ?>">
                <?php if($notif['action_type'] == 1) { ?>
                    <svg class="md-icon moon">
                        <use xlink:href="/assets/svg/icons.svg#mail"></use>
                    </svg>
                   
                    <a href="/u/<?= $notif['login'] ?>"><?= $notif['login'] ?></a> 
                    написал вам 
                    <a href="/messages/read/<?= $notif['connection_type'] ?>">сообщение</a>
                 
                    | <?= $notif['add_time']; ?>
                
                    <?php if($notif['read_flag'] == 0) { ?><sup class="red">✔</sup><?php } ?>
                
                <?php } ?>
                </div>
            <?php } ?>

        <?php } else { ?>
             Уведомлений пока нет...
        <?php } ?>
    </div>
</main>
<?php include TEMPLATE_DIR . '/footer.php'; ?>