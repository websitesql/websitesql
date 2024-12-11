<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Providers;

use WebsiteSQL\WebsiteSQL\Exceptions\UserAlreadyExistsException;
use WebsiteSQL\WebsiteSQL\App;

class UserProvider
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
     * This method registers a new user
     * 
     * @param string $firstname
     * @param string $lastname
     * @param string $email
     * @param string $password
     * @param bool $approved
     * @param bool $email_verified
     * @return bool
     */
    public function register(string $firstname, string $lastname, string $email, string $password, bool $approved = false, bool $email_verified = false): bool
    {
        // Check if the email is already in use
        $UserQuery = $this->app->getDatabase()->get($this->app->getStrings()->getTableUsers(), '*', ['email' => $email]);
        if ($UserQuery)
        {
            throw new UserAlreadyExistsException();
        }

        // Hash the password
        $password = password_hash($password, PASSWORD_ARGON2ID);

        // Insert the user into the database
        $this->app->getDatabase()->insert($this->app->getStrings()->getTableUsers(), [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'password' => $password,
            'approved' => $approved,
            'locked' => 0,
            'email_verified' => $email_verified,
            'created_at' => $this->app->getUtilities()->getDateTime(),
        ]);

        return true;
    }

    /*
     * This method gets a user by their ID
     * 
     * @param int $id
     * @return array
     */
    public function getUserById(int $id): array
    {
        return $this->app->getDatabase()->get($this->app->getStrings()->getTableUsers(), '*', ['id' => $id]);
    }

    /*
     * This method gets all users
     * 
     * @return array
     */
    public function getUsers(): array
    {
        return $this->app->getDatabase()->select($this->app->getStrings()->getTableUsers(), '*');
    }   
}