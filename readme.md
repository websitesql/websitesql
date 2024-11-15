# Website SQL V2

Welcome to Website SQL V2 a simple, easy to use, lightweight and customisable Content Management System.

## Application Structure

```
./
├── wsql-contents/
│   ├── assets/
│   ├── modules/
│   ├── themes/
│   └── uploads/
├── Modules/
|   ├── wsql-cron.php
|   ├── wsql-functions.php
|   └── wsql-strings.php
├── .htaccess
├── index.php
├── admin.php
├── api.php
└── web.config
```

## Change Log

```
Change Log 2.0.19
    - Removal of 'themes' folder
    + Addition of 'wsql-contents/themes' folder
    - Removal of 'uploads' folder
    + Addition of 'wsql-contents/uploads' folder
    - Removal of 'modules' folder
    + Addition of 'wsql-contents/modules' folder
    - Removal of assets from root directory
    + Addition of 'wsql-contents/assets' folder
    - Removal of 'strings.php
    + Addition of 'wsql-includes/wsql-strings.php'
    - Removal of 'functions.php'
    + Addition of 'wsql-includes/wsql-functions.php'
    + Updated '.htaccess' to remove admin rewrite rule, build a more robust and compatible rewrite rule and to stop access too 'wsql-includes' folder
    - Removal of 'cron.php'
    + Addition of 'wsql-includes/wsql-cron.php'
    - Removal of 'update.php'
    + Addition of 'composer.json'


Change Log 2.0.18
    + addition of function 'register_custom_editor_options' 
    + addition of function 'get_custom_editor_options'
    + added custom fields support on editor page

Change Log 2.0.17
    + Bug Fix 'b0001' 
    + Bug Fix 'b0002' 
    + Bug Fix 'b0003' 

Change Log 2.0.16
    + addition of function 'register_custom_editor_fields' 
    + addition of function 'get_custom_editor_fields' 
    + addition of function 'register_custom_dashboard_tiles' 
    + addition of function 'get_custom_dashboard_tiles' 
    + addition of function 'register_custom_menu_items' 
    + addition of function 'get_custom_menu_items'
    + addition of above functions as begginging of dynamic modules mainframe

Change Log 2.0.15
    - removal of physical logging
    + logging changed to database logging (DBLogging)
    - removal of dedicated upgrade licence
    + integrating updates into licence and adding system login on upgrade page
    + added the ability to update pages
    + added table 'WebsiteTempContent' in preparation for website preview function
    + added static value in 'web.config' for setting the custom filename of the admin file
    + added media view and upload functionality
    + added table 'WebsiteUploads'
    + added the value 'Logging' into 'web.config' to control whether mysql logs are taken when a page is loaded

Change Log 2.0.14
    + adding licencing functionality

Change Log 2.0.13
    + adding admin.php with basic login functionality
    + renamed table 'tbl_users' to 'WebsiteUsers'
    + renamed table 'tbl_tokens' to 'WebsiteTokens'
    + renamed table 'WebSQLSystemLog' to 'WebsiteTransactionLog'
    + added 'web.config' in place of 'connections.config' with multiple connection strings functionality

Change Log 2.0.0
    - discontinued Website Content Management System SQL Edition V1.0 (WebCoreV1)
    + base for WebSQLv2 created using MySQL as default SQL driver
```

## Bugs

```
Status: 'Resolved', Bug: 'b0001', Details: 'Update script will continuously loop and will never execute the SQL scripts - present in version 2.0.16', Resolution: '', ResolvedVer: ''
Status: 'Resolved', Bug: 'b0002', Details: 'People Plugin - No generation of output to site via shortcode but shortcode being detected and cleared.', Resolution: 'Var $WebsiteContent was missed in SQL queries', ResolvedVer: '2.0.17'
Status: 'Resolved', Bug: 'b0003', Details: 'Groups Plugin - No generation of output to site via shortcode but shortcode being detected and cleared.', Resolution: 'Var $WebsiteContent was missed in SQL queries', ResolvedVer: '2.0.17'
```