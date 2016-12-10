<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;
    public $imageFile;

    const SCENARIO_AJAX = 'ajax';
    const SCENARIO_SENT = 'sent';

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'subject', 'body', 'verifyCode'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha', 'on' => self::SCENARIO_SENT],
            // file upload
            ['imageFile', 'file', 'skipOnEmpty' => true, 'extensions' => 'jpg, gif'],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_AJAX] = ['name', 'subject', 'body'];
        $scenarios[self::SCENARIO_SENT] = ['name', 'email', 'subject', 'body', 'verifyCode'];
        return $scenarios;
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'email' => 'Емайл',
            'subject' => 'Тема сообщения',
            'body' => 'Текст сообщения',
            'verifyCode' => 'Проверочный код',
            'imageFile' => 'Загрузка файла',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
    public function contact($email)
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose()
                ->setTo($email)
                ->setFrom([$this->email => $this->name])
                ->setSubject($this->subject)
                ->setTextBody($this->body)
                ->send();

            return true;
        }
        return false;
    }

    public function upload()
    {
        if ($this->validate() and $this->imageFile !== null) {
            $this->imageFile->saveAs(__DIR__.'/../uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
            return true;
        } else {
            return false;
        }
    }
}
