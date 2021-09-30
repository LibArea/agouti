<?php if ($data['writers']) { ?>
  <div class="bg-white br-rd-5 border-box-1 p15">
    <?php foreach ($data['writers'] as  $writer) { ?>
      <div class="flex border-bottom">
        <div class="mr15 mt10">
          <?= $writer['sum']; ?>
          <span class="block size-15 gray lowercase"><?= lang('views-n'); ?></span>
        </div>
        <div class="p15">
          <?= user_avatar_img($writer['user_avatar'], 'max', $writer['user_login'], 'w54'); ?>
        </div>
        <div class="mt10">
          <a href="<?= getUrlByName('user', ['login' => $writer['user_login']]); ?>"><?= $writer['user_login']; ?></a>
          <div class="mr13 gray-light size-15 mr15">
            <?php if ($writer['user_about']) { ?>
              <?= $writer['user_about']; ?>
            <?php } else { ?>
              ...
            <?php } ?>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
<?php } else { ?>
  <?= includeTemplate('/_block/no-content', ['lang' => 'no']); ?>
<?php } ?>