<div id="contentWrapper" class="wrap wrap-max">
  <main class="w-100">
    <a class="text-sm" href="<?= url('web'); ?>">
      << <?= __('web.catalog'); ?></a>
        <span class="gray-600">/ <?= __('web.audits'); ?></span>

        <?php if (!empty($data['audit_count'])) : ?><span class="red">(<?= $data['audit_count']; ?>)</span><?php endif; ?>

        <div class="flex justify-between mt10 mb20">
          <?= insert('/content/item/admin/menu'); ?>
        </div>

        <?php if (!empty($data['items'])) : ?>
          <?= insert('/content/item/item-card', ['data' => $data, 'sort' => 'all']); ?>
        <?php else : ?>
          <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('web.no_website'), 'icon' => 'info']); ?>
        <?php endif; ?>
  </main>
  <?= insert('/content/item/admin/sidebar'); ?>
</div>