<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Providers;

use Exception;
use League\Route\Http\Exception\UnauthorizedException;
use WebsiteSQL\WebsiteSQL\App;
use WebsiteSQL\WebsiteSQL\Exceptions\RoleDoesNotExistException;
use WebsiteSQL\WebsiteSQL\Exceptions\UserHasNoRoleException;
use WebsiteSQL\WebsiteSQL\Exceptions\UserNotFoundException;
use WebsiteSQL\WebsiteSQL\Exceptions\PermissionAlreadyRegisteredException;
use WebsiteSQL\WebsiteSQL\Exceptions\PermissionDoesNotExistException;
use WebsiteSQL\WebsiteSQL\Exceptions\InvalidPermissionTypeException;

class PermissionsProvider
{
    /*
     * This object holds the Medoo database connection
     * 
     * @var Medoo
     */
    private App $app;

    /*
     * This array holds the permissions registered by the app
     * 
     * @var array
     */
    private array $permissions = [];

    /*
     * Constructor
     * 
     * @param string $realm
     * @param Medoo $database
     */
    public function __construct(App $app)
    {
        $this->app = $app;

        // Initialize the permissions provider
        $this->init();
    }

    /*
     * This method initializes the permissions provider
     * 
     * @return void
     */
    public function init(): void
    {
        /*----------------------------------------*
         * App: Updates Routes
         *----------------------------------------*/
        $this->registerPermission('app', 'wsql.updates.read', 'Read the updates');

        /*----------------------------------------*
         * App: Media Routes
         *----------------------------------------*/
        $this->registerPermission('app', 'wsql.media.read', 'Read the media');

        /*----------------------------------------*
         * App: Account Routes
         *----------------------------------------*/
        $this->registerPermission('app', 'wsql.account.read', 'Read the currents users account', [
            'wsql.api.users.me.read'
        ]);
        $this->registerPermission('app', 'wsql.account.update', 'Update the currents users account', [
            'wsql.api.users.me.update',
            'wsql.api.users.me.reset-password'
        ]);

        /*----------------------------------------*
         * App: Settings User Routes
         *----------------------------------------*/
        $this->registerPermission('app', 'wsql.settings.users.create', '', [
            'wsql.api.users.create'
        ]);
        $this->registerPermission('app', 'wsql.settings.users.read', '', [
            'wsql.api.users.read'
        ]);
        $this->registerPermission('app', 'wsql.settings.users.update', '', [
            'wsql.api.users.single.read',
            'wsql.api.users.single.update'
        ]);
        $this->registerPermission('app', 'wsql.settings.users.delete', '', [
            'wsql.api.users.single.delete'
        ]);
        $this->registerPermission('app', 'wsql.settings.users.reset-password', '', [
            'wsql.api.users.single.reset-password'
        ]);

        /*----------------------------------------*
         * App: Settings Access Control Routes
         *----------------------------------------*/
        $this->registerPermission('app', 'wsql.settings.access-control.create', '', [
            'wsql.api.roles.create'
        ]);
        $this->registerPermission('app', 'wsql.settings.access-control.read', '', [
            'wsql.api.roles.read'
        ]);
        $this->registerPermission('app', 'wsql.settings.access-control.update', '', [
            'wsql.api.roles.single.read',
            'wsql.api.roles.single.update',
            'wsql.api.roles.single.permissions.read',
            'wsql.api.roles.single.permissions.update',
            'wsql.api.roles.single.permissions.delete'
        ]);
        $this->registerPermission('app', 'wsql.settings.access-control .delete', '', [
            'wsql.api.roles.single.delete'
        ]);

        
        /*----------------------------------------*
         * API: Media
         *----------------------------------------*/
        $this->registerPermission('api', 'wsql.api.media.upload', 'Upload media files');

        /*----------------------------------------*
         * API: Settings Routes
         *----------------------------------------*/
        $this->registerPermission('api', 'wsql.api.settings.logging.update', 'Update the logging settings');
        $this->registerPermission('api', 'wsql.api.customizations.read', 'Read the customizations');
        $this->registerPermission('api', 'wsql.api.settings.branding.read', 'Read the branding settings');
        $this->registerPermission('api', 'wsql.api.settings.branding.update', 'Update the branding settings');
        
        /*----------------------------------------*
         * API: User Routes
         *----------------------------------------*/
        $this->registerPermission('api', 'wsql.api.users.read', 'Read all users');
        $this->registerPermission('api', 'wsql.api.users.create', 'Create a user');
        $this->registerPermission('api', 'wsql.api.users.me.read', 'Allows the user to read their own user');
        $this->registerPermission('api', 'wsql.api.users.me.update', 'Allows the user to update their own user');
        $this->registerPermission('api', 'wsql.api.users.me.reset-password', 'Allows the user to reset their own password');
        $this->registerPermission('api', 'wsql.api.users.single.read', 'Read any single user');
        $this->registerPermission('api', 'wsql.api.users.single.update', 'Update any single user');
        $this->registerPermission('api', 'wsql.api.users.single.delete', 'Delete any single user');
        $this->registerPermission('api', 'wsql.api.users.single.reset-password', 'Reset the password of any single user');
        
        /*----------------------------------------*
         * API: Role Routes
         *----------------------------------------*/
        $this->registerPermission('api', 'wsql.api.roles.read', 'Read all roles');
        $this->registerPermission('api', 'wsql.api.roles.create', 'Create a role');
        $this->registerPermission('api', 'wsql.api.roles.single.read', 'Read any single role');
        $this->registerPermission('api', 'wsql.api.roles.single.update', 'Update any single role');
        $this->registerPermission('api', 'wsql.api.roles.single.delete', 'Delete any single role');
        $this->registerPermission('api', 'wsql.api.roles.single.permissions.create', 'Add new permissions to any single role');
        $this->registerPermission('api', 'wsql.api.roles.single.permissions.read', 'Read the permissions of any single role');
        $this->registerPermission('api', 'wsql.api.roles.single.permissions.update', 'Update the permissions of any single role');
        $this->registerPermission('api', 'wsql.api.roles.single.permissions.delete', 'Delete the permissions of any single role');
    }

    /*
     * This method registers a permission
     * 
     * @param string $type
     * @param string $permission
     * @param array $dependent (optional) If the permission is dependent on another permissions then add them here
     * @return void
     */
    public function registerPermission(string $type, string $permission, string $description = '', array $dependents = []): void{
        // Check if the permission is already registered
        if (in_array($permission, $this->permissions))
        {
            throw new PermissionAlreadyRegisteredException();
        }

        // Check if the type is valid
        if (!in_array($type, ['app', 'api']))
        {
            throw new InvalidPermissionTypeException();
        }

        // Add the permission to the array
        $this->permissions[] = [
            'type' => $type,
            'name' => $permission,
            'description' => empty($description) ? null : $description,
            'dependents' => empty($dependents) ? null : $dependents
        ];
    }

    /*
     * This method checks if a user has a permission
     * 
     * @param int $user
     * @param string $permission
     * @return bool
     */
    public function checkPermission(int $user, string $permission): bool
    {
        try {
            // Get the users role
            $roleData = $this->getRole($user);

            // Check if the users role is an admin
            if ($roleData['administrator'])
            {
                error_log('User is an admin');
                return true;
            }
            
            // Get the permissions for the role
            $permissionData = $this->app->getDatabase()->select($this->app->getStrings()->getTablePermissions(), '*', [
                'role' => $roleData['id'],
                'name' => $permission,
                'enabled' => true
            ]);

            // Check if the permission exists
            if (!$permissionData)
            {
                return false;
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /*
     * This method checks if a user has app access
     * 
     * @param int $user
     * @return bool
     */
    public function checkAppAccess(int $user): mixed
    {
        try {
            // Get the users role
            $roleData = $this->getRole($user);

            error_log('checkAppAccess('.$user.') roleData: '.print_r($roleData,true));

            // Check if the users role has app access
            if (!$roleData['app_access'])
            {
                throw new UnauthorizedException();
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /*
     * This method gets any filters for a permission
     * 
     * @param int $user
     * @param string $permission
     * @return array
     */
    public function checkFilters(int $user, string $permission): array
    {
        // Get the users role
        $roleData = $this->getRole($user);

        // Get the permissions for the role
        $permissionData = $this->app->getDatabase()->get($this->app->getStrings()->getTablePermissions(), '*', ['role' => $roleData['id'], 'name' => $permission]);

        // Check if the permission exists
        if (!$permissionData)
        {
            throw new PermissionDoesNotExistException();
        }

        // Get the filters
        return json_decode($permissionData['filters'], true);
    }

    /*
     * This method gets available permissions (pagnation support)
     * 
     * @param $page
     * @param $limit
     * @param $search
     * @return array
     */
    public function getPermissions($page = null, $limit = null, $search = null): array
    {
        // Get the permissions
        $permissions = $this->permissions;

        return $permissions;

    }

    /*
     * This method gets the user and returns the role
     * 
     * @param int $user
     * @return array
     */
    private function getRole(int $user): array
    {
        // Check the user exists
        $userData = $this->app->getDatabase()->get($this->app->getStrings()->getTableUsers(), ['id', 'role'], ['id' => $user]);
        if (!$userData)
        {
            throw new UserNotFoundException();
        }

        // Check if the user has a role
        if (!$userData['role'])
        {
            throw new UserHasNoRoleException();
        }

        // Get the users role
        $roleData = $this->app->getDatabase()->get($this->app->getStrings()->getTableRoles(), '*', ['id' => $userData['role']]);


        // Check the role exists
        if (!$roleData)
        {
            throw new RoleDoesNotExistException();
        }

        // Return the role data
        return $roleData;
    }
}