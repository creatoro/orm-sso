<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_User extends Model_Auth_User {

	/**
	 * Finds SSO user based on supplied data.
	 *
	 * @param   string  $provider_field
	 * @param   array   $data
	 * @return  ORM
	 */
	public function find_sso_user($provider_field, $data)
	{
		return $this->where($provider_field, '=', $data['id'])
			->or_where('email', '=', $data['email'])
			->find();
	}

	/**
	 * Sign-up using data from OAuth provider.
	 *
	 * Override this method to add your own sign up process.
	 *
	 * @param   ORM     $user
	 * @param   array   $data
	 * @param   string  $provider
	 * @return  ORM
	 */
	public function sso_signup(ORM $user, array $data, $provider_field)
    {
		if ( ! $user->loaded())
		{
			// Add user
			$user->$provider_field = $data['id'];

			// Set email if it's available via OAuth provider
			if (isset($data['email']))
			{
				$user->email = $data['email'];
			}

			// Save user
			$user->save();
		}
		elseif ($user->loaded() AND empty($user->$provider_field))
		{
			// If user is found, but provider id is missing add it to details.
			// We can do this merge, because this means user is found by email address,
			// that is already confirmed by this OAuth provider, so it's considered trusted.
			$user->$provider_field = $data['id'];

			// Save user
			$user->save();
		}

		// Return user
		return $user;
	}

} // End User Model