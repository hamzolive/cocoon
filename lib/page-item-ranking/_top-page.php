<?php //オリジナル設定ページ
/**
 * Cocoon WordPress Theme
 * @author: yhira
 * @link https://wp-cocoon.com/
 * @license: http://www.gnu.org/licenses/gpl-2.0.html GPL v2 or later
 */

if (isset($_POST['action']) && $_POST['action'] == 'delete') {
  if( isset($_POST[HIDDEN_DELETE_FIELD_NAME]) &&
    $_POST[HIDDEN_DELETE_FIELD_NAME] == 'Y' ){

    ///////////////////////////////////////
    // 内容の削除
    ///////////////////////////////////////
    require_once abspath(__FILE__).'posts-delete.php';
  }
} else {
  if( isset($_POST[HIDDEN_FIELD_NAME]) &&
    $_POST[HIDDEN_FIELD_NAME] == 'Y' ){

    ///////////////////////////////////////
    // 内容の保存
    ///////////////////////////////////////
    require_once abspath(__FILE__).'posts.php';
  }
}



///////////////////////////////////////
// 入力フォーム
///////////////////////////////////////
?>
<div class="wrap admin-settings">
<h1><?php _e( 'ランキング', THEME_NAME ) ?></h1>
    <!-- 使いまわしテキスト（関数テキスト） -->
    <div class="item-ranking metabox-holder">
      <div class="operation-buttons">
        <a href="<?php echo IR_LIST_URL; ?>"><?php _e( '一覧ページへ', THEME_NAME ) ?></a>
        <a href="<?php echo IR_NEW_URL; ?>"><?php _e( '新規追加', THEME_NAME ) ?></a>
      </div>

      <?php //一覧リストの表示
      $action = isset($_GET['action']) ? $_GET['action'] : null;
      if ($action == 'delete') {
        require_once abspath(__FILE__).'form-delete.php';
      } else {
        if (!isset($action)) {
          require_once abspath(__FILE__).'list.php';
        } else {//入力フォームの表示
          require_once abspath(__FILE__).'form.php';
        }
      }


      ?>
    </div><!-- /.metabox-holder -->
</div>
