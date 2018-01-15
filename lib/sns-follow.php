<?php //SNS用の関数など

if ( !function_exists( 'fetch_feedly_count_raw' ) ):
function fetch_feedly_count_raw($url){
  $res = 0;
  $args = array( 'sslverify' => false );
  $subscribers = wp_remote_get( '.$urlhttp://cloud.feedly.com/v3/feeds/feed%2F'.$url, $args );
  if (!is_wp_error( $subscribers ) && $subscribers["response"]["code"] === 200) {
    $subscribers = json_decode( $subscribers['body'] );
    if ( $subscribers ) {
      $subscribers = $subscribers->subscribers;
      if ($subscribers) {
        $res = $subscribers;
      }
    }
  }
}
endif;

//feedlyの購読者数取得
if ( !function_exists( 'fetch_feedly_count' ) ):
function fetch_feedly_count(){

  $transient_id = TRANSIENT_FOLLOW_PREFIX.'feedly';
  //DBキャッシュからカウントの取得
  if (is_sns_follow_count_cache_enable()) {
    $count = get_transient( $transient_id );
    //_v($count);
    if ( is_numeric($count) ) {
      return $count;
    }
  }

  $feed_url = rawurlencode( get_bloginfo( 'rss2_url' ) );
  $res = fetch_feedly_count_raw($url);
  // $res = 0;
  // $args = array( 'sslverify' => false );
  // $subscribers = wp_remote_get( "http://cloud.feedly.com/v3/feeds/feed%2F$feed_url", $args );
  // if (!is_wp_error( $subscribers ) && $subscribers["response"]["code"] === 200) {
  //   $subscribers = json_decode( $subscribers['body'] );
  //   if ( $subscribers ) {
  //     $subscribers = $subscribers->subscribers;
  //     if ($subscribers) {
  //       $res = $subscribers;
  //     }
  //   }
  // }

  //DBキャッシュにカウントを保存
  if (is_sns_follow_count_cache_enable()) {
    set_transient( $transient_id, $res, 60 * 60 * get_sns_follow_count_cache_interval() );
  }

  return $res;
}
endif;

//feedlyの購読者数の取得
if ( !function_exists( 'get_feedly_count' ) ):
function get_feedly_count(){
  if (!is_sns_follow_buttons_count_visible())
    return null;

  if (is_scc_feedly_exists()) {
    return scc_get_follow_feedly();
  } else {
    return fetch_feedly_count();
  }
}
endif;

if ( !function_exists( 'fetch_push7_info_raw' ) ):
function fetch_push7_info_raw($app_no){
  $url = 'https://api.push7.jp/api/v1/'.$app_no.'/head';//要https:
  $args = array( 'sslverify' => false );
  //$args = array('sslverify' => false);
  $info = wp_remote_get( $url, $args );
  if (!is_wp_error( $info ) && $info["response"]["code"] === 200) {
    $info = json_decode( $info['body'] );
    if ( $info ) {
      //Push7情報をキャッシュに保存
      if (is_sns_follow_count_cache_enable()) {
        set_transient( $transient_id , $info, 60 * 60 * get_sns_follow_count_cache_interval() );
      }

      $res = $info;
    }
  }
}
endif;

//Push7情報取得
if ( !function_exists( 'fetch_push7_info' ) ):
function fetch_push7_info(){
  if (!is_sns_follow_buttons_count_visible())
    return null;

  $transient_id = TRANSIENT_FOLLOW_PREFIX.'_push7_info';
  //DBキャッシュからカウントの取得
  if (is_sns_follow_count_cache_enable()) {
    $info = get_transient( $transient_id  );
    if ( $info ) {
      return $info;
    }
  }

  $res = null;
  $app_no = get_push7_follow_app_no();
  if ( $app_no ) {
    $res = fetch_push7_info_raw($app_no);
    // $url = 'https://api.push7.jp/api/v1/'.$app_no.'/head';//要https:
    // $args = array( 'sslverify' => false );
    // //$args = array('sslverify' => false);
    // $info = wp_remote_get( $url, $args );
    // if (!is_wp_error( $info ) && $info["response"]["code"] === 200) {
    //   $info = json_decode( $info['body'] );
    //   if ( $info ) {
    //     //Push7情報をキャッシュに保存
    //     if (is_sns_follow_count_cache_enable()) {
    //       set_transient( $transient_id , $info, 60 * 60 * get_sns_follow_count_cache_interval() );
    //     }

    //     $res = $info;
    //   }
    // }
  }
  return $res;
}
endif;


// ユーザープロフィールの項目のカスタマイズ
if ( !function_exists( 'user_contactmethods_custom' ) ):
function user_contactmethods_custom($prof_items){
  //項目の追加
  $prof_items['twitter_url'] = __( 'Twitter URL', THEME_NAME );
  $prof_items['facebook_url'] = __( 'Facebook URL', THEME_NAME );
  $prof_items['google_plus_url'] = __( 'Google+ URL', THEME_NAME );
  $prof_items['hatebu_url'] = __( 'はてブ URL', THEME_NAME );
  $prof_items['instagram_url'] = __( 'Instagram URL', THEME_NAME );
  $prof_items['pinterest_url'] = __( 'Pinterest URL', THEME_NAME );
  $prof_items['youtube_url'] = __( 'YouTube URL', THEME_NAME );
  $prof_items['flickr_url'] = __( 'Flickr URL', THEME_NAME );
  $prof_items['line_at_url'] = __( 'LINE@ URL', THEME_NAME );
  $prof_items['github_url'] = __( 'GitHub URL', THEME_NAME );

  return $prof_items;
}
endif;
add_filter('user_contactmethods', 'user_contactmethods_custom');

//ユーザーIDの取得
if ( !function_exists( 'get_the_posts_author_id' ) ):
function get_the_posts_author_id(){
  $author_id = get_sns_default_follow_user();
  if (is_singular()) {
    global $post_id;
    $post = get_post($post_id);
    if ($post){
      $author = get_userdata($post->post_author);
      $author_id =$author->ID;
    }
  }
  return $author_id;
}
endif;

//プロフィール画面で設定したウェブサイトURLの取得
if ( !function_exists( 'get_the_author_website_url' ) ):
function get_the_author_website_url(){
  return esc_html(get_the_author_meta('url', get_the_posts_author_id()));
}
endif;

//プロフィール画面で設定したTwitter URLの取得
if ( !function_exists( 'get_the_author_twitter_url' ) ):
function get_the_author_twitter_url(){
  return esc_html(get_the_author_meta('twitter_url', get_the_posts_author_id()));
}
endif;

//プロフィール画面で設定したTwitter URLからTwitter IDの取得
if ( !function_exists( 'get_the_author_twitter_id' ) ):
function get_the_author_twitter_id($url = null){
  if (!$url) {
   $url = get_the_author_twitter_url();
  }
  $res = preg_match('/twitter\.com\/(.+?)\/?$/i', $url, $m);
  if ($res && $m && $m[1]) {
    return $m[1];
  }
}
endif;

//プロフィール画面で設定したFacebook URLの取得
if ( !function_exists( 'get_the_author_facebook_url' ) ):
function get_the_author_facebook_url(){
  return esc_html(get_the_author_meta('facebook_url', get_the_posts_author_id()));
}
endif;

//プロフィール画面で設定したGoogle+ URLの取得
if ( !function_exists( 'get_the_author_google_plus_url' ) ):
function get_the_author_google_plus_url(){
  return esc_html(get_the_author_meta('google_plus_url', get_the_posts_author_id()));
}
endif;

//プロフィール画面で設定したはてブ URLの取得
if ( !function_exists( 'get_the_author_hatebu_url' ) ):
function get_the_author_hatebu_url(){
  return esc_html(get_the_author_meta('hatebu_url', get_the_posts_author_id()));
}
endif;

//プロフィール画面で設定したInstagram URLの取得
if ( !function_exists( 'get_the_author_instagram_url' ) ):
function get_the_author_instagram_url(){
  return esc_html(get_the_author_meta('instagram_url', get_the_posts_author_id()));
}
endif;

//プロフィール画面で設定したPinterest URLの取得
if ( !function_exists( 'get_the_author_pinterest_url' ) ):
function get_the_author_pinterest_url(){
  return esc_html(get_the_author_meta('pinterest_url', get_the_posts_author_id()));
}
endif;

//プロフィール画面で設定したYouTube URLの取得
if ( !function_exists( 'get_the_author_youtube_url' ) ):
function get_the_author_youtube_url(){
  return esc_html(get_the_author_meta('youtube_url', get_the_posts_author_id()));
}
endif;

//プロフィール画面で設定した立夏 URLの取得
if ( !function_exists( 'get_the_author_flickr_url' ) ):
function get_the_author_flickr_url(){
  return esc_html(get_the_author_meta('flickr_url', get_the_posts_author_id()));
}
endif;

//プロフィール画面で設定したLINE@ URLの取得
if ( !function_exists( 'get_the_author_line_at_url' ) ):
function get_the_author_line_at_url(){
  return esc_html(get_the_author_meta('line_at_url', get_the_posts_author_id()));
}
endif;
//プロフィール画面で設定したTwitter URLからLINE IDの取得
if ( !function_exists( 'get_the_author_line_id' ) ):
function get_the_author_line_id($url = null){
  if (!$url) {
   $url = get_the_author_line_at_url();
  }
  $res = preg_match('{.*@([^/]+)/?$}i', $url, $m);
  //URLでなかった場合は文字列をそのままIDとして取得
  if (!$res) {
    return $url;
  }
  if ($res && $m && $m[1]) {
    return $m[1];
  }
}
endif;

//プロフィール画面で設定したGitHub URLの取得
if ( !function_exists( 'get_the_author_github_url' ) ):
function get_the_author_github_url(){
  return esc_html(get_the_author_meta('github_url', get_the_posts_author_id()));
}
endif;

//全てのフォローボタンのうちどれかが表示されているか
if ( !function_exists( 'is_any_sns_follow_buttons_exist' ) ):
function is_any_sns_follow_buttons_exist(){
  return get_the_author_website_url() || get_the_author_twitter_url() || get_the_author_facebook_url() || get_the_author_google_plus_url() || get_the_author_hatebu_url() || get_the_author_instagram_url() || get_the_author_pinterest_url() || get_the_author_youtube_url() || get_the_author_flickr_url() || get_the_author_line_at_url() || get_the_author_github_url() || is_feedly_follow_button_visible() || is_rss_follow_button_visible();
}
endif;

