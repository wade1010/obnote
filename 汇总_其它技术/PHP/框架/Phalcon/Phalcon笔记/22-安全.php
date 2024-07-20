<?php

哈希密码
public function registerAction()
{
	$user = new Users();
	$login = $this->request->getPost('login');
	$password = $this->request->getPost('password');
	$user->login = $login;

	//Store the password hashed
	$user->password = $this->security->hash($password);
	$user->save();
}
public function loginAction()
{
	$login = $this->request->getPost('login');
	$password = $this->request->getPost('password');
	$user = Users::findFirstByLogin($login);
	if ($user) {
		if ($this->security->checkHash($password, $user->password)) {
			//The password is valid
		}
	}
}
security 需要openssl扩展
----------

CSRF防护
<?php echo Tag::form('session/login') ?>
    <!-- login and password inputs ... -->
    <input type="hidden" name="<?php echo $this->security->getTokenKey() ?>" value="<?php echo $this->security->getToken() ?>"/>
</form>

Then in the controller’s action you can check if the CSRF token is valid:
<?php
use Phalcon\Mvc\Controller;
class SessionController extends Controller
{
    public function loginAction()
    {
        if ($this->request->isPost()) {
            if ($this->security->checkToken()) {
                //The token is ok
            }
        }
    }
}
在DI里是要有SESSION适配器服务的.
------------

设置DI
$di->set('security', function(){
    $security = new Phalcon\Security();
    //Set the password hashing factor to 12 rounds
    $security->setWorkFactor(12);
    return $security;
}, true);