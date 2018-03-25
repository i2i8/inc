<?php 
namespace console\controllers;

use backend\models\Root;
//use yii\base\Exception;
class InitController extends \yii\console\Controller 
{
	public function actionRoot() 
	{
		echo "Create init User...\n";
		$username = $this->prompt('Input UserName :');
		$password = $this->prompt("Input password for $username :");
		$email = $this->prompt("Input Email for $username :");
		
		
		$model = new Root();
		$model->username = $username;
		$model->password = $password;
		$model->email = $email;		

		if (!$model->save()) {
			foreach ($model->getErrors() as $errors) {
				foreach ($errors as $e) {
					echo $e."\n";					
				}
			}
			return 1;
		}
		return 0;
	}
}
?>