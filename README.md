# SSO module driver for Kohana ORM

This driver will enable support for Kohana ORM in the [SSO module](https://github.com/creatoro/kohana-sso).

Installation
============================================
 1. Place the driver into your modules directory.
 2. Enable the driver just like any other module.


+1: Customize the sign up process
============================================

If the user wasn't found during the login process the current sign up method saves the user as a new user. It
also merges the OAuth account with a standard account if they share the same e-mail address.

You can define your own sign up method:

 1. Copy `orm_driver_directory/classes/model/user.php` to `application_directory/classes/model/user.php`.
 2. Customize the `sso_signup()` method` to your needs.