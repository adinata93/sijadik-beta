<?php
namespace app\models;

use yii\base\Model;
use app\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $nip;
    public $nama;
    public $role;
    public $username;
    public $email;
    public $password;

    public $id;
    public $status;
    public $created_at;
    public $updated_at;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['nip', 'required'],
            ['nip', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This nip has already been taken.'],
            ['nip', 'string', 'max' => 18],
            
            ['nama', 'required'],
            ['nama', 'string', 'max' => 100],
                        
            ['role', 'required'],
            ['role', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This role has already been taken.'],
            ['nama', 'string', 'max' => 100],

            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            [['id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['nip', 'nama', 'role', 'username', 'auth_key', 'password_hash', 'password_reset_token', 'email'], 'safe'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->nip = $this->nip;
        $user->nama = $this->nama;
        $user->role = $this->role;
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        
        return $user->save() ? $user : null;
    }
}
