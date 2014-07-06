<?php
/**
 * UsersController
 *
 * @author        Takanori Kobashi kobashi@akane.waseda.jp
 * @since         1.0.0
 * @version       1.0.0
 * @copyright
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
App::import('Vendor', 'facebook/facebook');

class UsersController extends AppController
{
    public $name = 'Users';
    public $uses = array('User');
    public $layout = 'login';

    /**
     * beforeFilter
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(); //認証なしで入れるページ
    }

     /**
     * logout ログアウトページ
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function login()
    {
        // ログイン状態ならトップへ
        if ($this->me['is_login']) {
            $this->redirect(array('controller' => '/', 'action' => 'index'));
        }
    }

    /**
     * facebook Facebookログイン前処理ページ
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    function facebook()
    {
        $facebook = $this->createFacebook();
        $option = array(
            'redirect_uri' => HTTPS_FULL_BASE_URL . '/' . $this->base_dir . '/login/facebook',
        );
        $url = $facebook->getLoginUrl($option);
        $this->redirect($url);
    }

    /**
     * loginFacebook Facebookログイン用メソッド
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    function loginFacebook()
    {
        $this->facebook = $this->createFacebook();
        // ログイン状態ならトップへ
        if ($this->me['is_login']) {
            $this->redirect(array('controller' => '/', 'action' => 'index'));
        }

        // facebookの認証チェック
        $facebook_user_id = $this->facebook->getUser();
        if (!$facebook_user_id) {
            // facebook情報の取得が出来なかったので失敗。トップへ
            $this->redirect(array('controller' => '/', 'action' => 'register'));
        }

        // facebookのuser_idからGfのuser_idを逆引き
        $user_id = $this->User->findByFacebookUserId($facebook_user_id, array('id'));

        if (empty($user_id)) {
            // facebook_idからuser_idへの逆引き失敗
            $this->redirect(array('controller' => '/', 'action' => 'register'));
        }

        $data['User']['id'] = $user_id['User']['id'];
        $data['User']['facebook_user_id'] = $facebook_user_id;
        $data['User']['facebook_access_token'] = $this->facebook->getAccessToken();

        // トランザクション処理
        $this->User->begin();
        if (! $this->User->save($data['User'], true, array('id', 'facebook_connect', 'facebook_user_id', 'facebook_access_token'))) {
            $this->User->rollback();
            throw new InternalErrorException();
        }

        $this->User->commit();
        $this->Auth->fields = array(
            'username' => 'facebook_user_id',
            'password' => 'facebook_access_token'
        );
        // ログイン処理
        if ($this->Auth->login($data['User'])) {

            $this->redirect($this->request->referer());
        } else {
            // ログイン失敗
            $this->redirect(array('controller' => 'users', 'action' => 'login'));
        }
    }

    /**
     * logout ログアウトページ
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function logout()
    {
        //ログアウトの処理
        if($this->me['is_login']) {
            $this->Auth->logout();
            $this->redirect($this->request->referer()); // 元いたページにリダイレクト
        }
        $this->redirect(array('controller' => '/', 'action' => 'index'));
    }

}
