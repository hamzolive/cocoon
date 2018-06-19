<?php //リセット設定に必要な定数や関数
/**
 * Cocoon WordPress Theme
 * @author: yhira
 * @link https://wp-cocoon.com/
 * @license: http://www.gnu.org/licenses/gpl-2.0.html GPL v2 or later
 */

//全ての設定をリセット
define('OP_RESET_ALL_SETTINGS', 'reset_all_settings');
if ( !function_exists( 'reset_all_settings' ) ):
function reset_all_settings(){
  //データベースから削除
  global $wpdb;
  $wpdb->delete( 'wp_options', array( 'option_name' => get_theme_mods_option_name() ) );
}
endif;

//確認用
define('OP_CONFIRM_RESET_ALL_SETTINGS', 'confirm_reset_all_settings');