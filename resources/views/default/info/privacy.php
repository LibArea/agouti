<div class="wrap">
  <main>
    <div class="white-box pt5 pr15 pb5 pl15">
      <?= breadcrumb('/', lang('Home'), '/info', lang('Info'), lang('Privacy Policy')); ?>
      <?= $data['content']; ?>
    </div>
  </main>
  <aside>
    <?php includeTemplate('/_block/info-page-menu', ['uid' => $uid]); ?>
  </aside>
</div>