<?php include TEMPLATE_DIR . '/admin/header_admin.php'; ?>
<div class="admin">
    <div class="left-ots">
 
        <h1><?= $data['title']?></h1>
        
        <div class="t-table">
            <div class="t-th">
                <span class="t-td center">N</span>
                <span class="t-td">Данные</span>
                <span class="t-td">E-mail</span>
                <span class="t-td">IP регистрации</span>
                <span class="t-td">Создан</span>
                <span class="t-td center">Удален? / Забанен</span>
                <span class="t-td center">Действие</span>
            </div>

            <?php foreach($data['users'] as $ind => $user) {  ?>
        
            <div class="t-tr">
                <span class="t-td w-30 center">
                    <?= $user['id']; ?>
                </span>
                <span class="t-td">
                    <img class="ava" src="/uploads/avatar/small/<?= $user['avatar']; ?>">
                    <a href="/u/<?= $user['login']; ?>"><?= $user['login']; ?></a>
                    <?php if($user['name']) { ?>
                        (<?= $user['name']; ?>) 
                    <?php } ?>
                    <sup class="red">TL:<?= $user['trust_level']; ?></sup><br>
                </span>
                <span class="t-td">
                     <span class="date"><?= $user['email']; ?></span>
                </span>
                <span class="t-td">
                     <?= $user['reg_ip']; ?>  <?php if($user['replayIp'] > 1) { ?>
                       <sup class="red">(<?= $user['replayIp']; ?>)</sup>
                     <?php } ?>
                </span>
                <span class="t-td">
                     <?= $user['created_at']; ?> 
                </span>
                <span class="t-td center">
                    <?php if($user['deleted'] == 0) { ?>Нет<?php }else{ ?><span class="red">Да</span><?php } ?>
                     /
                    <?php if($user['ban_list'] == 0) { ?>Нет<?php }else{ ?><span class="red">Да</span><?php } ?>
                </span>   
                 <span class="t-td center">          
                    <?php if($user['isBan']) { ?>
                        <span class="user-ban" data-id="<?= $user['id']; ?>">
                            <span class="red">разбанить</span>
                        <span>
                    <?php } else { ?>
                        <span class="user-ban" data-id="<?= $user['id']; ?>">забанить<span>
                    <?php } ?>
                </span> 
            </div>

            <?php } ?>
        </div>
    </div>
</div> 
<?php include TEMPLATE_DIR . '/footer.php'; ?>