<?php
/**
 * Cocoon WordPress Theme
 * @author: yhira
 * @link: https://wp-cocoon.com/
 * @license: http://www.gnu.org/licenses/gpl-2.0.html GPL v2 or later
 */
if ( !defined( 'ABSPATH' ) ) exit; ?>
 <a href="<?php the_permalink(); ?>" class="related-entry-card-wrap a-wrap border-element cf" title="<?php echo esc_attr(get_the_title()); ?>">
<article class="related-entry-card e-card cf">

  <figure class="related-entry-card-thumb card-thumb e-card-thumb">
    <?php if ( has_post_thumbnail() ): // サムネイルを持っているとき ?>
    <?php
    //適切なサムネイルサイズの選択
    switch (get_related_entry_type()) {
      case 'vartical_card_3':
        $thumb_size = THUMB320;
        break;
      case 'mini_card':
        $thumb_size = THUMB120;
        break;
      default:
        $thumb_size = THUMB160;
        break;
    }
    echo get_the_post_thumbnail($post->ID, $thumb_size, array('class' => 'related-entry-card-thumb-image card-thumb-image', 'alt' => '') ); //サムネイルを呼び出す?>
    <?php else: // サムネイルを持っていないとき ?>
    <img src="<?php echo get_no_image_160x90_url(); ?>" alt="" class="no-image related-entry-card-no-image" width="<?php echo THUMB160WIDTH; ?>" height="<?php echo THUMB160HEIGHT; ?>" />
    <?php endif; ?>
    <?php the_nolink_category(); //カテゴリラベルの取得 ?>
  </figure><!-- /.related-entry-thumb -->

  <div class="related-entry-card-content card-content e-card-content">
    <h3 class="related-entry-card-title card-title e-card-title">
      <?php the_title(); //記事のタイトル?>
    </h3>
    <?php //スニペットの表示
    if (is_related_entry_card_snippet_visible()): ?>
    <div class="related-entry-card-snippet card-snippet e-card-snippet">
      <?php echo get_the_snipet( get_the_content(''), get_related_excerpt_max_length() ); //カスタマイズで指定した文字の長さだけ本文抜粋?>
    </div>
    <?php endif ?>
    <div class="related-entry-card-meta card-meta e-card-meta">
      <div class="related-entry-card-info e-card-info">
        <?php
        //更新日の取得
        $update_time = get_update_time(get_site_date_format());
        //echo $update_time;
        //投稿日の表示
        if (is_related_entry_card_post_date_visible() || (is_related_entry_card_post_date_or_update_visible() && !$update_time && is_related_entry_card_post_update_visible())): ?>
          <span class="post-date"><?php the_time(get_site_date_format()); ?></span>
        <?php endif ?>
        <?php //更新時の表示
        //_v(is_related_entry_card_post_update_visible());
        if (is_related_entry_card_post_update_visible() && $update_time && (get_the_time('U') < get_update_time('U'))): ?>
          <span class="post-update"><?php echo $update_time; ?></span>
        <?php endif ?>
        <?php //投稿者の表示
        if (is_related_entry_card_post_author_visible()): ?>
          <span class="post-author">
            <span class="post-author-image"><?php echo get_avatar( get_the_author_meta( 'ID' ), '16', null ); ?></span>
            <span class="post-author-name"><?php echo get_the_author(); ?></span>
          </span>
        <?php endif ?>
      </div>
    </div>

  </div><!-- /.related-entry-card-content -->



</article><!-- /.related-entry-card -->
</a><!-- /.related-entry-card-wrap -->
