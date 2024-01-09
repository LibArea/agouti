<?php
insert('/_block/add-js-css');
$post = $data['post'];
$blog = $data['blog'][0] ?? null;
?>

<main>
  <article class="indent-body<?php if ($post['post_is_deleted'] == 1) : ?> bg-red-200<?php endif; ?>">
    <?php if ($post['post_is_deleted'] == 0 || UserData::checkAdmin()) : ?>
      <?php if (!empty($data['united'])) : ?>
        <div class="box bg-lightyellow mb15 gray-600">
          <svg class="icons">
            <use xlink:href="/assets/svg/icons.svg#git-merge"></use>
          </svg>
          <?= __('app.post_merged'); ?>
          <?php foreach ($data['united'] as $merged) : ?>
            (id <?= $merged['post_id']; ?>):
            <?php if (UserData::checkAdmin()) : ?>
              <a href="/post/<?= $merged['post_id']; ?>"><?= $merged['post_title']; ?></a>
            <?php else : ?>
              <?= $merged['post_title']; ?>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <h1 class="m0"><?= $post['post_title']; ?>
        <?= insert('/content/post/post-title', ['post' => $post]); ?>
      </h1>

      <div class="text-sm flex gray-600 gap">
        <a class="gray-600" title="<?= $post['login']; ?>" href="<?= url('profile', ['login' => $post['login']]); ?>">
          <?= Img::avatar($post['avatar'], $post['login'], 'img-sm mr5', 'small'); ?>
          <span class="nickname"><?= $post['login']; ?></span>
        </a>

        <span class="lowercase">
          <?= Html::langDate($post['post_date']); ?>
        </span>
        <?php if ($post['modified']) : ?>
          <span class="mb-none">
            (<?= __('app.ed'); ?>)
          </span>
        <?php endif; ?>

        <?php if (UserData::checkActiveUser()) : ?>
          <?php if (Access::postAuthorAndTeam($post, $blog['facet_user_id'] ?? 0) == true || $post['post_draft'] == true) : ?>
            <a class="gray-600 lowercase" href="<?= url('content.edit', ['type' => 'post', 'id' => $post['post_id']]); ?>">
              <?= __('app.edit'); ?>
            </a>
          <?php endif; ?>
          <?= insert('/_block/admin-dropdown-post', ['post' => $post]); ?>
        <?php endif; ?>

      </div>

      <?php if ($post['post_thumb_img']) : ?>
        <div class="img-preview">
          <?= Img::image($post['post_thumb_img'], $post['post_title'],  'medium-zoom-image', 'post', 'thumbnails'); ?>
        </div>
      <?php endif; ?>

      <div class="max-w780">
        <div class="content-body mb15">
          <?= markdown($post['post_content'], 'text'); ?>
        </div>
        <?php if ($post['post_url_domain']) : ?>
          <div class="mb15">
            <a rel="nofollow noreferrer ugc" target="_blank" class="btn btn-outline-primary" href="<?= $post['post_url']; ?>">
              <?= __('app.details_here'); ?>
            </a>
          </div>
        <?php endif; ?>
        <?php if ($post['post_url_domain']) : ?>
          <div class="italic mb15 text-sm table gray">
            <?= __('app.source'); ?>:
            <svg class="icons">
              <use xlink:href="/assets/svg/icons.svg#link"></use>
            </svg>
            <a class="gray" href="<?= url('domain', ['domain' => $post['post_url_domain']]); ?>">
              <?= $post['post_url_domain']; ?>
            </a>
          </div>
        <?php endif; ?>
      </div>

      <?php if ($post['post_poll']) : ?>
        <?= insert('/content/poll/poll', ['poll' => $data['poll']]); ?>
      <?php endif; ?>

      <div class="flex flex-row items-center text-sm mt15">
        <?php if (!empty($data['facets'])) : ?>
          <svg class="icons gray-600">
            <use xlink:href="/assets/svg/icons.svg#hash"></use>
          </svg>
          <?php foreach ($data['facets'] as $topic) : ?>
            <a class="gray-600 mr15 lowercase" href="<?= url('topic', ['slug' => $topic['facet_slug']]); ?>"><?= $topic['facet_title']; ?></a>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <?php if (!empty($data['blog'])) : ?>
        <div class="flex flex-row items-center mt15 text-sm">
          <span class="gray-600 mr5 mb-none"><?= __('app.published'); ?> </span>
          <a title="<?= $blog['facet_title']; ?>" class="bg-white p5 brown" href="/blog/<?= $blog['facet_slug']; ?>">
            <?= $blog['facet_title']; ?>
          </a>
        </div>
      <?php endif; ?>

      <div class="mt15 mb15 items-center flex justify-between">
        <div class="items-center flex gap gray-600">
          <?= Html::votes($post, 'post'); ?>
          <div class="items-center flex gap-min">
            <svg class="icons">
              <use xlink:href="/assets/svg/icons.svg#eye"></use>
            </svg>
            <?= $post['post_hits_count'] == 0 ? 1 : $post['post_hits_count']; ?>
          </div>
        </div>
        <div class="items-center flex gap-max">
          <?php if (UserData::checkActiveUser()) : ?>
            <?php if (is_array($data['post_signed'])) : ?>
              <div data-id="<?= $post['post_id']; ?>" data-type="post" class="focus-id right mt5 gray-600">
                <?= __('app.unsubscribe'); ?>
              </div>
            <?php else : ?>
              <div data-id="<?= $post['post_id']; ?>" data-type="post" class="focus-id right mt5 red">
                + <?= __('app.read'); ?>
              </div>
            <?php endif; ?>
          <?php else : ?>
            <a class="right mt5 focus-id red" href="<?= url('login'); ?>">
              + <?= __('app.read'); ?>
            </a>
          <?php endif; ?>

          <div data-a11y-dialog-show="id-share">
            <svg class="icons gray-600">
              <use xlink:href="/assets/svg/icons.svg#share"></use>
            </svg>
          </div>

          <?= Html::favorite($post['post_id'], 'post', $post['tid'], 'text-2xl ml5'); ?>
        </div>
      </div>

      <?= insert('/_block/related-posts', ['related_posts' => $data['related_posts']]); ?>

      <?php
      $is_comments = 0;
      foreach ($data['facets'] as $ind => $facet) : ?>
        <?php if ($facet['facet_is_comments'] != 0) {
          $is_comments = $facet['facet_is_comments'];
        } ?>
      <?php endforeach; ?>

      <?php if (empty($is_comments)) : ?>

        <?php if (UserData::checkActiveUser()) : ?>
          <?php if ($post['post_feature'] == 0 && $post['post_draft'] == 0 && $post['post_closed'] == 0) : ?>

            <form action="<?= url('content.create', ['type' => 'comment']); ?>" accept-charset="UTF-8" method="post">
              <?= csrf_field() ?>

              <?= insert('/_block/form/editor', ['height'  => '170px', 'type' => 'comment', 'id' => $post['post_id']]); ?>

              <div class="clear mt5">
                <input type="hidden" name="post_id" value="<?= $post['post_id']; ?>">
                <input type="hidden" name="comment_id" value="0">
                <?= Html::sumbit(__('app.reply')); ?>
              </div>
            </form>

          <?php endif; ?>
        <?php endif; ?>

      <?php endif; ?>


    <?php else : ?>
      <div class="box center bg-red-200">
        <?= __('app.content_deleted', ['name' => __('app.post')]); ?>...
      </div>
    <?php endif; ?>
  </article>



  <?php if (empty($is_comments)) : ?>

    <div id="comment"></div>
    <?php if ($post['post_draft'] == 0) :
      $format = ($post['post_feature'] == 0) ? 'discussion' : 'qa';
      insert('/content/post/format-' . $format, ['data' => $data, 'post' => $post]);
    else :
      echo insert('/_block/no-content', ['type' => 'small', 'text' => __('app.this_draft'), 'icon' => 'closed']);
    endif; ?>

  <?php else : ?>
    <?php insert('/_block/no-content', ['type' => 'small', 'text' => __('app.topic_comments_disabled'), 'icon' => 'closed']); ?>
  <?php endif; ?>
</main>

<aside>
  <?php if (!empty($data['facets'])) : ?>
    <div class="box bg-lightgray">
      <h4 class="uppercase-box"><?= __('app.topics'); ?></h4>
      <?php foreach ($data['facets'] as $topic) : ?>
        <div class="flex justify-between items-center">
          <div class="mt5 mb5">
            <?= Img::image($topic['facet_img'], $topic['facet_title'], 'img-base mr5', 'logo', 'max'); ?>
            <a title="<?= $topic['facet_title']; ?>" class="black text-sm" href="<?= url('topic', ['slug' => $topic['facet_slug']]); ?>">
              <?= $topic['facet_title']; ?>
            </a>
          </div>
          <?php if (!$topic['signed_facet_id'] && UserData::getUserId()) : ?>
            <div data-id="<?= $topic['facet_id']; ?>" data-type="facet" class="focus-id right inline text-sm red">
              <?= __('app.read'); ?>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if ($post['post_content_img']) : ?>
    <div class="box br-lightgray img-preview">
      <img class="w-100" src="<?= Img::PATH['posts_cover'] . $post['post_content_img']; ?>" alt="<?= $post['post_title']; ?>">
    </div>
  <?php endif; ?>

  <?php if ($data['recommend']) : ?>
    <div class="box sticky top-sm">
      <h4 class="uppercase-box"><?= __('app.recommended'); ?></h4>
      <?php foreach ($data['recommend'] as  $rec_post) : ?>
        <div class="mb15 hidden flex gap text-sm">
          <a class="gray" href="<?= post_slug($rec_post['post_id'], $rec_post['post_slug']); ?>">
            <?php if ($rec_post['post_comments_count'] > 0) : ?>
              <div class="box-small bg-green">
                <?= $rec_post['post_comments_count'] ?>
              </div>
            <?php else : ?>
              <div class="box-small bg-lightgray">0</div>
            <?php endif; ?>
          </a>
          <a class="black" href="<?= post_slug($rec_post['post_id'], $rec_post['post_slug']); ?>">
            <?= $rec_post['post_title']; ?>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else : ?>
    <!-- мой блог -->
  <?php endif; ?>
</aside>
<script nonce="<?= $_SERVER['nonce']; ?>">
  document.addEventListener('DOMContentLoaded', () => {
    mediumZoom(document.querySelectorAll('.post img:not(.emoji), .content-body img:not(.emoji), .comment_body .ind-first-p img:not(.emoji)'));

    // Добавим цитирование    
    function get_text() {
      var text;
      if (window.getSelection) {
        text = window.getSelection().toString();
      } else if (document.getSelection) {
        // Старый способ
        text = document.getSelection();
      } else if (document.selection) {
        // IE, или уберем?
        text = document.selection.createRange().text;
      }

      if (text) {
        let comment = document.getElementById('qcomment');
        if (comment) {
          comment.innerHTML = '> ' + text + '\r\n';
        }
      }
    }
    // Применять эту функцию к тегам, содержащим text
    var p_arr = document.getElementsByTagName("p");
    for (var i = 0; i < p_arr.length; i++) {
      p_arr[i].onmouseup = get_text;
    }
  });
</script>
<?= insert('/_block/dialog/msg-flag'); ?>
<?= insert('/_block/dialog/share', ['title' => __('app.share_post'), 'url' => config('meta.url') . post_slug($post['post_id'], $post['post_slug'])]); ?>