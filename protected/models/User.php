<?php
/**
 * This is the model class for table "user".
 */
class User extends ActiveRecord
{
	public $newPassword;

	public function tableName()
	{
		return 'user';
	}

	public function rules()
	{
		return array(
			array('username, password', 'required'),
			array('fname, lname, username, password, pw_reset_token, email, role', 'length', 'max'=>255),
			array('newPassword, create_date', 'safe'),
		);
	}

	protected function afterValidate()
	{
		if ($this->isNewRecord) {
			$this->password = UserIdentity::crypt($this->password);
		} else if ($this->newPassword) {
			$this->password = UserIdentity::crypt($this->newPassword);
		}
		return parent::afterValidate();
	}

	/*public function encrypt($value)
	{
		$enc = new bCrypt();
		return $enc->hash($value);
	}*/

	public function toJSON()
	{
		$attributes = parent::toJSON();
		unset($attributes['password']);
		unset($attributes['pw_reset_token']);
		unset($attributes['is_deleted']);
		return $attributes;
	}
}
