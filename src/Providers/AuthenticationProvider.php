<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Providers;

use WebsiteSQL\WebsiteSQL\Exceptions\MissingRequiredFieldsException;
use WebsiteSQL\WebsiteSQL\Exceptions\UserNotFoundException;
use WebsiteSQL\WebsiteSQL\Exceptions\IncorrectPasswordException;
use WebsiteSQL\WebsiteSQL\Exceptions\UserNotApprovedException;
use WebsiteSQL\WebsiteSQL\Exceptions\UserLockedOutException;
use WebsiteSQL\WebsiteSQL\Exceptions\EmailNotVerifiedException;
use WebsiteSQL\WebsiteSQL\App;

class AuthenticationProvider
{
    /*
     * This string holds the authentication realm for the application
     * 
     * @var string|null
     */
    private $realm;

    /*
     * This object holds the Medoo database connection
     * 
     * @var Medoo
     */
    private App $app;

    /*
     * Constructor
     * 
     * @param string $realm
     * @param Medoo $database
     */
    public function __construct(string|null $realm, App $app)
    {
        $this->realm = $realm;
        $this->app = $app;
    }

    /*
     * This method returns the authentication status
     * 
     * @return string
     */
    public function check($token): string
    {
        // Check if the token is empty
        if (!$token)
        {
            throw new MissingRequiredFieldsException();
        }

        // Check if the token exists in the database
        $TokenRow = $this->app->getDatabase()->get($this->app->getStrings()->getTableTokens(), '*', ['Token' => $token]);
        if (!$TokenRow)
        {
            throw new UserNotFoundException();
        }

        // Check if the token is expired
        $chktime = strtotime($TokenRow['TimeStamp']);
        $timenow = time();
        $time_diff = $timenow - $chktime;

        // If the token is expired, delete it from the database and return false
        if ($time_diff > 1800)
        {
            $this->app->getDatabase()->delete($this->app->getStrings()->getTableTokens(), ['Token' => $token]);
            throw new UserNotFoundException();
        }

        // Update the token timestamp and return true
        $this->app->getDatabase()->update($this->app->getStrings()->getTableTokens(), ['TimeStamp' => date('Y-m-d H:i:s')], ['Token' => $token]);
        
        // Calculate the new expiration date
        $expiresAtDateTime = new \DateTime();
        $expiresAtDateTime->modify('+30 minutes');

        // Return the cookie value
        return sprintf('access_token=%s; Domain=%s; Path=/; Expires=%s; HttpOnly; Secure',
            $TokenRow['Token'],
            $_ENV['COOKIE_DOMAIN'],
            $expiresAtDateTime->format(\DateTime::COOKIE)
        );
    }

    /* 
     * This method performs the authentication
     * 
     * @param string $email
     * @param string $password
     * @return string
     */
    public function authenticate(string $email, string $password): string
    {
        // Check if the email and password are empty
        if (!$email || !$password)
        {
            throw new MissingRequiredFieldsException();
        }

        // Check if the email exists in the database
        $UserRow = $this->app->getDatabase()->get($this->app->getStrings()->getTableUsers(), '*', ['email' => $email, 'realm' => $this->realm]);
        if (!$UserRow)
        {
            throw new UserNotFoundException();
        }

        // Check if the password is correct
        if (!password_verify($password, $UserRow['password']))
        {
            // Create a log entry
            $this->app->log('transaction', 'The user has failed too authenticated with the database. Reason: Password Incorrect', 'login.failure', $UserRow['id']);

            throw new IncorrectPasswordException();
        }
        
        // Check the user's email verification status
        if ($UserRow['email_verified'] != 1)
        {
            // Create a log entry
            $this->app->log('transaction', 'The user has failed too authenticated with the database. Reason: Email Unverified', 'login.failure', $UserRow['id']);

            throw new EmailNotVerifiedException();
        }

        // Check if the user is approved
        if ($UserRow['approved'] != 1)
        {
            // Create a log entry
            $this->app->log('transaction', 'The user has failed too authenticated with the database. Reason: User not approved', 'login.failure', $UserRow['id']);

            throw new UserNotApprovedException();
        }

        // Check if user is locked out
        if ($UserRow['locked'] == 1)
        {
            // Create a log entry
            $this->app->log('transaction', 'The user has failed too authenticated with the database. Reason: User locked out', 'login.failure', $UserRow['id']);

            throw new UserLockedOutException();
        }

        // Generate a token
        $token = $this->app->getUtilities()->randomString(32);

        // Insert the token into the database
        $this->app->getDatabase()->insert($this->app->getStrings()->getTableTokens(), ['Token' => $token, 'UserID' => $UserRow['id'], 'TimeStamp' => date('Y-m-d H:i:s')]);

        // Create a log entry
        $this->app->log('transaction', 'The user has successfully authenticated with the database.', 'login.success', $UserRow['id']);

        // Create the expiration date
        $expiresAtDateTime = new \DateTime();
        $expiresAtDateTime->modify('+30 minutes');

        // Return the cookie value
        return sprintf('access_token=%s; Domain=%s; Path=/; Expires=%s; HttpOnly; Secure',
            $token,
            $_ENV['COOKIE_DOMAIN'],
            $expiresAtDateTime->format(\DateTime::COOKIE)
        );
    }

    /*
     * This method destroys the user's session
     * 
     * @param string $token
     * @return bool
     */
    public function destroy($token): bool
    {
        // Get the user's ID from the database
        $TokenRow = $this->app->getDatabase()->get($this->app->getStrings()->getTableTokens(), '*', ['Token' => $token]);
        if (!$TokenRow)
        {
            return false;
        }

        // Delete the token from the database
        $this->app->getDatabase()->delete($this->app->getStrings()->getTableTokens(), ['Token' => $token]);

        // Create a log entry
        $this->app->log('transaction', 'The user has successfully logged out of the database.', 'successful_logout', $TokenRow['UserID']);

        // Return true
        return true;
    }

    /*
     * This method gets the user's ID from the token
     * 
     * @return int|null
     */
    public function getUserID($token): int|null
    {
        // Get the user's ID from the database
        $TokenRow = $this->app->getDatabase()->get($this->app->getStrings()->getTableTokens(), '*', ['Token' => $token]);
        if (!$TokenRow)
        {
            return null;
        }

        // Return the user's ID
        return $TokenRow['UserID'];
    }

    /*
     * This method gets the token from session storage
     * 
     * @return string|null
     */
    public function getSessionToken(): string|null
    {
        return $_SESSION['token'] ?? null;
    }

    /*
     * This method sets the token in session storage
     * 
     * @param string|null $token
     * @return void
     */
    public function setSessionToken(string|null $token): void
    {
        $_SESSION['token'] = $token;
    }
}