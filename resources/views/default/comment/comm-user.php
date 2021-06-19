<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding">
                <h1><?= $data['h1']; ?></h1>

                <?php if (!empty($comments)) { ?>

                    <?php foreach ($comments as $comm) { ?> 
                    
                        <?php if($comm['comment_del'] == 0) { ?>
                            <div class="comm-telo_bottom">
                                <div class="small">
                                    <img class="ava" alt="<?= $comm['login']; ?>" src="<?= user_avatar_url($comm['avatar'], 'small'); ?>">
                              
                                    <a class="date" href="/u/<?= $comm['login']; ?>"><?= $comm['login']; ?></a> 

                                    <span class="date"><?= $comm['date']; ?></span>
                              
                                    <span class="otst"> &#183; </span>
                                     <a href="/post/<?= $comm['post_id']; ?>/<?= $comm['post_slug']; ?>"><?= $comm['post_title']; ?></a>
                                </div>
                                <div class="comm-telo-body">
                                    <?= $comm['comment_content']; ?> 
                                </div>
                                <div class="post-full-footer date">
                                    + <?= $comm['comment_votes']; ?>
                                </div>
                            </div>  
                        <?php } else { ?>    
                            <div class="dell comm-telo_bottom"> 
                                <div class="voters"></div>
                                ~ <?= lang('Comment deleted'); ?>
                            </div>
                        <?php } ?>     
                                
                    <?php } ?>

                <?php } else { ?>
                    <div class="no-content"><i class="icon info"></i> <?= lang('no-comment'); ?>...</div>
                <?php } ?>
            </div>
        </div>
    </main>
    <aside>
        <?php include TEMPLATE_DIR . '/_block/user-menu.php'; ?>
    </aside>
</div>    
<?php include TEMPLATE_DIR . '/footer.php'; ?>