<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\User;
use app\models\People;
use app\models\ContactForm;
use app\models\SignupForm;
use app\models\AddPeopleForm;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use yii\db\Query;
use yii\data\ActiveDataProvider;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'addpeople'],
                'rules' => [
                    [
                        'actions' => ['logout', 'addpeople'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['login', 'signup'],
                        'roles' => ['?'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        // return $this->render('index');
        $dataProvider = new ActiveDataProvider([
            'query' => People::find()->orderBy('created_at DESC'),
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        // $this->view->title = 'Posts List';
        return $this->render('index', ['listDataProvider' => $dataProvider]);
    }

    /**
     * Displays signup form.
     *
     * @return string
     */
    public function actionSignup()
    {
        $model = new SignupForm();
 
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }
 
        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionAddpeople()
    {
        $model = new AddPeopleForm();
        
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->save()) {
                return $this->goHome();
            }
        }
 
        return $this->render('add_people', [
            'model' => $model,
        ]);
    }

    public function actionCityList($q = null) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query;
            $query->select('_cities.city_id, _cities.title_ru, _cities.region_ru, _cities.area_ru, _countries.title_ru AS country_title')
                ->from('_cities')
                ->innerJoin('_countries', '_cities.country_id = _countries.country_id')
                ->where(['ilike', '_cities.title_ru', $q . '%', false])
                ->orderBy('CHAR_LENGTH(_cities.title_ru)')
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $result = [];
            foreach($data as $c){
                $text = $c['country_title'] . ', ' . $c['title_ru'];
                if (!empty($c['area_ru'])){
                    $text .= ', ' . $c['area_ru'];
                }
                // if (!empty($c['region_ru'])){
                //     $text .= ', ' . $c['region_ru'];
                // }
                $result[] = [
                    'id' => $c['city_id'],
                    'text' => $text
                ];
            }
            $out['results'] = array_values($result);
        }
        
        return $out;
    }


    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
 
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }
 
        return $this->render('passwordResetRequestForm', [
            'model' => $model,
        ]);
    }
 
    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
 
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');
            return $this->goHome();
        }
 
        return $this->render('resetPasswordForm', [
            'model' => $model,
        ]);
    }
}
