<?php
 
namespace app\models;
 
use Yii;
use yii\base\Model;
 
/**
 * Signup form
 */
class AddPeopleForm extends Model
{
    public $firstname;
    public $lastname;
    public $middlename;
    public $date_of_birth;
    public $date_of_death;
    public $country;
    public $city_id;
    public $description;
 
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['city_id', 'middlename', 'firstname', 'lastname', 'date_of_birth', 'date_of_death', 'description'], 'trim'],
            [['firstname', 'lastname', 'date_of_birth', 'date_of_death'], 'required'],
            ['firstname', 'string', 'max' => 255],
            ['lastname', 'string', 'max' => 255],
            [['date_of_death', 'date_of_birth'], 'date', 'format' => 'php:Y-m-d'],
            ['date_of_death','validateDateOfDeath'],
        ];
    }

    public function validateDateOfDeath(){
        if(strtotime($this->date_of_death) <= strtotime($this->date_of_birth)){
            $this->addError('date_of_death','Please give correct Date Of Birth and Date Of Death dates');
        }
    }
 
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function save()
    {
 
        if (!$this->validate()) {
            return null;
        }
 
        $people = new People();
        $people->firstname = $this->firstname;
        $people->lastname = $this->lastname;
        $people->middlename = $this->middlename;
        $people->date_of_birth = $this->date_of_birth;
        $people->date_of_death = $this->date_of_death;
        $people->city_id = $this->city_id;
        $people->user_id = Yii::$app->user->identity->id;
        $people->description = $this->description;
        $people->age = (strtotime($this->date_of_death) - strtotime($this->date_of_birth)) / 86400;
        // var_dump($_POST);var_dump($this);die();
        return $people->save() ? $people : null;
    }
 
}