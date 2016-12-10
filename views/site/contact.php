<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Контакты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

        <div class="alert alert-success">
            Благодарим вас за обращение. Мы ответим как можно скорее.
        </div>

        <p>
            Обратите внимание, что если вы включите отладчик Yii, вы должны иметь возможность
            просматривать сообщения электронной почты на почтовой панели отладчика.
            <?php if (Yii::$app->mailer->useFileTransport): ?>
                Поскольку приложение находится в режиме разработки, электронная почта не отправляется,
                но сохраняется в виде файла в <code><?= Yii::getAlias(Yii::$app->mailer->fileTransportPath) ?></code>.
                Пожалуйста, присвойте <code>false</code> свойству <code>useFileTransport</code> компонента <code>mail</code>,
                чтобы включить отправку по электронной почте.
            <?php endif; ?>
        </p>

    <?php else: ?>

        <p>
            Если у вас есть деловое предложение или другие вопросы, пожалуйста, заполните следующую форму, чтобы связаться с нами. Спасибо.
        </p>

        <div class="row">
            <div class="col-lg-5">

                <?php $form = ActiveForm::begin(['id' => 'contact-form', 'enableAjaxValidation' => true, 'enableClientValidation' => false, 'validateOnSubmit' => false]); ?>

                    <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

                    <?= $form->field($model, 'email') ?>

                    <?= $form->field($model, 'subject') ?>

                    <?= $form->field($model, 'body')->textarea(['rows' => 6]) ?>

                    <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                        'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                    ]) ?>

                    <?= $form->field($model, 'imageFile')->fileInput() ?>

                    <div class="form-group">
                        <?= Html::submitButton('Отправить сообщение', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>

    <?php endif; ?>
</div>
