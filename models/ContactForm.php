<?php

namespace app\models;

use Yii;
use yii\base\Model;
//use yii\web\UploadedFile;

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
    public $imageFiles;

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
            [['imageFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpg, gif', 'maxFiles' => 0],
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
            'imageFile' => 'Загрузка файлов',
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
            $mailer = Yii::$app->mailer->compose()
                ->setTo([$this->email => $this->name])
                ->setFrom($email)
                ->setSubject($this->subject)
                ->setTextBody($this->body);
            foreach ($this->imageFiles as $file) {
                $mailer->attach($file->tempName, ['fileName' => $file->name, 'contentType' => $file->type]);
            }
            $mailer->send();

            return true;
        }
        return false;
    }
}
