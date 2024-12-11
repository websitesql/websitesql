<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Providers;

use WebsiteSQL\WebsiteSQL\Exceptions\MissingRequiredFieldsException;
use WebsiteSQL\WebsiteSQL\Exceptions\UserNotFoundException;
use WebsiteSQL\WebsiteSQL\Exceptions\IncorrectPasswordException;
use WebsiteSQL\WebsiteSQL\Exceptions\UserNotApprovedException;
use WebsiteSQL\WebsiteSQL\Exceptions\UserLockedOutException;
use WebsiteSQL\WebsiteSQL\Exceptions\EmailNotVerifiedException;
use WebsiteSQL\WebsiteSQL\Exceptions\SessionExpiredException;
use WebsiteSQL\WebsiteSQL\App;
use Exception;

class AuthenticationProvider
{
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
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /*
     * This method returns the authentication status
     * 
     * @param string $token
     * @return mixed
     */
    public function check($token): mixed
    {
        try {
            // Check if the token is empty
            if (!$token)
            {
                throw new MissingRequiredFieldsException();
            }

            // Check if the token exists in the database
            $tokenData = $this->app->getDatabase()->get($this->app->getStrings()->getTableTokens(), '*', ['token' => $token]);
            if (!$tokenData)
            {
                throw new UserNotFoundException();
            }

            // Check if the token is expired
            $currentExpiresAt = strtotime($tokenData['expires_at']);
            $timenow = time();

            // If the token is expired, delete it from the database and return false
            if ($timenow > $currentExpiresAt)
            {
                $this->app->getDatabase()->delete($this->app->getStrings()->getTableTokens(), ['token' => $token]);
                throw new SessionExpiredException();
            }

            // Calculate the new expiration date
            $expiresAtDateTime = new \DateTime();
            $expiresAtDateTime->modify('+30 minutes');

            // Update the token timestamp and return true
            $this->app->getDatabase()->update($this->app->getStrings()->getTableTokens(), [
                'expires_at' => $expiresAtDateTime->format('Y-m-d H:i:s'),
            ], ['id' => $tokenData['id']]);
            

            // Return the cookie value
            return sprintf('access_token=%s; Domain=%s; Path=/; Expires=%s; HttpOnly; Secure',
                $tokenData['token'],
                $this->app->getEnv('HOST'),
                $expiresAtDateTime->format(\DateTime::COOKIE)
            );
        } 
        catch (Exception $e)
        {
            return null;
        }
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
        $UserRow = $this->app->getDatabase()->get($this->app->getStrings()->getTableUsers(), '*', ['email' => $email]);
        if (!$UserRow)
        {
            throw new UserNotFoundException();
        }

        // Check if the password is correct
        if (!password_verify($password, $UserRow['password']))
        {
            // Create a log entry
            // $this->app->log('transaction', 'authenticate.failure.passwordincorrect', 'The user has failed too authenticated with the database. Reason: Password Incorrect', $UserRow['id']);

            throw new IncorrectPasswordException();
        }
        
        // Check the user's email verification status
        if ($UserRow['email_verified'] != 1)
        {
            // Create a log entry
            // $this->app->log('transaction', 'authenticate.failure.unverified', 'The user has failed too authenticated with the database. Reason: Email Unverified', $UserRow['id']);

            throw new EmailNotVerifiedException();
        }

        // Check if the user is approved
        if ($UserRow['approved'] != 1)
        {
            // Create a log entry
            // $this->app->log('transaction', 'authenticate.failure.unapproved', 'The user has failed too authenticated with the database. Reason: User not approved', $UserRow['id']);

            throw new UserNotApprovedException();
        }

        // Check if user is locked out
        if ($UserRow['locked'] == 1)
        {
            // Create a log entry
            // $this->app->log('transaction', 'authenticate.failure.locked', 'The user has failed too authenticated with the database. Reason: User locked out', $UserRow['id']);

            throw new UserLockedOutException();
        }

        // Generate a token
        $token = $this->app->getUtilities()->randomString(32);

        // Create a log entry
        // $this->app->log('transaction', 'authenticate.success', 'The user has successfully authenticated with the database.', $UserRow['id']);

        // Create the expiration date
        $expiresAtDateTime = new \DateTime();
        $expiresAtDateTime->modify('+30 minutes');

        // Insert the token into the database
        $this->app->getDatabase()->insert($this->app->getStrings()->getTableTokens(), [
            'token' => $token,
            'user' => $UserRow['id'],
            'expires_at' => $expiresAtDateTime->format('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        ]);


        // Return the cookie value
        return sprintf('access_token=%s; Domain=%s; Path=/; Expires=%s; HttpOnly; Secure',
            $token,
            $this->app->getEnv('HOST'),
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
        $TokenRow = $this->app->getDatabase()->get($this->app->getStrings()->getTableTokens(), '*', ['token' => $token]);
        if (!$TokenRow)
        {
            return false;
        }

        // Delete the token from the database
        $this->app->getDatabase()->delete($this->app->getStrings()->getTableTokens(), ['token' => $token]);

        // Create a log entry
        // $this->app->log('transaction', 'destroy.success', 'The user has successfully logged out of the database.', $TokenRow['UserID']);

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
        $tokenData = $this->app->getDatabase()->get($this->app->getStrings()->getTableTokens(), '*', ['token' => $token]);
        if (!$tokenData)
        {
            return null;
        }

        // Return the user's ID
        return $tokenData['user'];
    }
}