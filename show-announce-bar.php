<?php
/*
Plugin Name: Show Announce Bar
Plugin URI:
Description: アナウンスバーのテキストを設定する(Show Announce Bar on the top of the website)
Author: Risa Ueda
Version: 1.0
Author URI: https://risa-ueda.com/portfolio/
*/
// 参考サイト: https://www.webopixel.net/wordpress/631.html
/**
 * How does it Work
 * 「update_option」でデータベースの「wp_options」テーブルにレコードの追加や更新をすることができる
 *
 */
 if ( ! defined( 'ABSPATH' ) ) exit;

class ShowText {
  public function __construct() {

    // クラス内のメソッドを指定する場合は第2引数は$thisを含めた配列
    add_action('admin_menu', array($this, 'add_new_menu'));
  }
  /**
   *
  */
  function get_text() {
    $opt = get_option('showtext_options');
    return isset($opt['text']) ? $opt['text']: null;
  }
  function add_new_menu() {
    add_menu_page(
      'アナウンスバー',
      'アナウンスバー',
      'level_8',
      __FILE__,
      array($this, 'show_text_option_page'),
      '',
      26
    );
  }

  function show_text_option_page() {
    // $_POST['showtext_options']があったら保存
    if (isset($_POST['showtext_options'])) {
      // 現在のリクエストが有効な nonce を持っているか、
      // または現在のリクエストが管理画面から参照されたものであるかをテスト
      check_admin_referer('shoptions');

      $opt = $_POST['showtext_options'];
      update_option('showtext_options', $opt);
        ?>
        <div class="updated fade">
          <p>
            <strong><?php _e('The options has been saved.'); ?></strong>
          </p>
        </div>
        <?php
    }
    ?>
      <div class="wrap">
        <div id="icon-options-general" class="icon32">
        </div>
        <h2>アナウンスバー設定</h2>

        <form action="" method="post">
          <?php
            wp_nonce_field('shoptions');
            $opt = get_option('showtext_options');
            $show_text = isset($opt['text']) ? $opt['text'] : null;
          ?>
          <table class="form-table">
            <tr valign="top">
              <th scope="row">
                <label for="inputtext">Text</label>
              </th>
              <td>
                <input name="showtext_options[text]" type="text"  id="inputtext"
                  value="<?php  echo $show_text ?>" class="regular-text" />
              </td>
            </tr>
          </table>
          <p class="submit">
            <input type="submit" name="Submit" class="button-primary" value="Save" />
          </p>
        </form>
      </div>
      <?php
  }
}
$stext = new ShowText;

/**
 * Show Announce bar
 * ref: https://www.vektor-inc.co.jp/post/wordpress-about-action-hook/
 */
function top_bar_text(){
  // プラグインから文字の読み込み
  $showtext = new ShowText();
  if($showtext->get_text()) {
    $banner = $showtext->get_text();
  } else {
    $banner = 'This is sample text.';
  }
  echo '<div class="my-campaign-text" style="background:#000; color:#fff; text-align:center;">
          <div class="container">'. esc_html($banner) .'</div>
       </div>';
}
// バナーテキストの表示
add_action( 'wp_body_open', 'top_bar_text' );
