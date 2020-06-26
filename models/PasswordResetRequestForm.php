<?php
 
namespace app\models;
 
use Yii;
use yii\base\Model;
 
/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;
 
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\app\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'There is no user with such email.'
            ],
        ];
    }
 
    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);
 
        if (!$user) {
            return false;
        }
 
        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            var_dump($user);
            if (!$user->save()) {
                return false;
            }
        }

        // $mailer = Yii::$app->get('mailer');
        // $message = $mailer->compose(['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],['user' => $user])
        //             ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->name . ' robot'])
        //             ->setTo($this->email)
        //             ->setSubject('Password reset for ' . Yii::$app->name);
        // $logger = new Swift_Plugins_Loggers_ArrayLogger();
        // $mailer->getSwiftMailer()->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));
        // if (!$message->send()) {
        //     echo $logger->dump();
        // }
        // return true;
 
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Password reset for ' . Yii::$app->name)
            ->send();
    }
 
}