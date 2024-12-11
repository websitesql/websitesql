/*
 * Website SQL v2.1.0
 * 
 * File: 	Variables
 * Author: 	Alan Tiller
 * Date: 	2024-11-30
 * Version: 2.1.0
 */

/*
 * Global Variables
 */

var public_url = window.location.origin;                                                    // Get the public URL of the website


/*
 * API Endpoints
 *
 * Set variables for the API endpoints
 */
const logout_endpoint = public_url + '/api/auth/logout';                                    // Logout endpoint
const users_me_endpoint = public_url + '/api/users/me';                                     // Get current user endpoint
const users_me_password_reset_endpoint = public_url + '/api/users/me/reset-password';       // Reset current users password endpoint
const users_endpoint = public_url + '/api/users';                                           // Users endpoint
const users_single_endpoint = public_url + '/api/users/{id}';                               // Users Single endpoint
const roles_endpoint = public_url + '/api/roles';                                           // Roles endpoint
const roles_single_endpoint = public_url + '/api/roles/{id}';                               // Roles Single endpoint
const roles_single_permissions_endpoint = public_url + '/api/roles/{id}/permissions';       // Roles Single Permissions endpoint
const permissions_endpoint = public_url + '/api/permissions';                               // Permissions endpoint


/*
 * Element Classes
 *
 * Set variables for the API endpoints
 */
const wsql_standard_button = 'flex items-center gap-2 h-10 py-2 px-5 bg-white dark:bg-zinc-900 hover:bg-gray-50 dark:hover:bg-zinc-800 text-gray-700 dark:text-white border border-gray-300 dark:border-white text-base font-baloo font-medium leading-3 rounded-xl shadow-sm transition-all duration-100 cursor-pointer disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50';