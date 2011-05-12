<?php defined('SYSPATH') or die('No direct access allowed.');

class Auth_ORM extends Kohana_Auth_ORM {

	/**
	 * Logs in a user via an OAuth provider.
	 *
	 * @param   string   $provider
	 * @return  boolean
	 */
	public function sso($provider)
	{
		return SSO::factory($provider, 'orm')->login();
	}

	/**
	 * Forces a user to be logged in when using SSO, without specifying a password.
	 *
	 * @param   ORM      $user
	 * @param   boolean  $mark_session_as_forced
	 * @return  boolean
	 */
	public function force_sso_login(ORM $user, $mark_session_as_forced = FALSE)
	{
		if ($mark_session_as_forced === TRUE)
		{
			// Mark the session as forced, to prevent users from changing account information
			$this->_session->set('auth_forced', TRUE);
		}

		// Token data
		$data = array(
			'user_id'    => $user->id,
			'expires'    => time() + $this->_config['lifetime'],
			'user_agent' => sha1(Request::$user_agent),
		);

		// Create a new autologin token
		$token = ORM::factory('user_token')
					->values($data)
					->create();

		// Set the autologin cookie
		Cookie::set('authautologin', $token->token, $this->_config['lifetime']);

		// Run the standard completion
		$this->complete_login($user);
	}

} // End Auth_ORM