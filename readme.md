# **Website SQL V2.1**

Built from the ground up, **Website SQL V2.1** offers ultimate modular access, robust performance, and is designed with security at its core.

## **Installation Guide**

### **Prerequisites**
Ensure you have **Composer** installed to manage third-party dependencies. If Composer is not installed, download it from [getcomposer.org](https://getcomposer.org/).

### **Steps to Install**

1. **Download the Source Code**  
   Visit the [latest release](https://github.com/websitesql/websitesql/releases) and download the source code ZIP file.

2. **Extract the Files**  
   Extract the ZIP file to the desired location where you plan to use the application.

3. **Install Dependencies**  
   From the root directory of the application, open a terminal and run:  
   ```bash
   composer install
   ```

4. **Configure Hosting**  
   - Set your hosting root directory to the `/public` folder of the application.  
   - Navigate to your website URL in a web browser. You should be redirected to the installation wizard.

5. **Run the Installer**  
   - Enter your hosting details (e.g., database credentials) and desired admin account information.  
   - Click **Install** to complete the setup.  
   - If the setup is successful, you will be redirected to the login screen.

6. **Access the Application**  
   Log in using your admin credentials and begin enjoying the full suite of features!

## **Updating Website SQL**

Currently, it is not possible to update Website SQL through the application, so updates must be performed manually. Follow these steps to update:

1. **Take a Backup**  
   It is important to back up both the files and database before updating the application.  
   - Save a copy of all files from your existing application directory.  
   - Export your database using a tool like `mysqldump` or your hosting provider's backup utility.

2. **Download the Latest Code**  
   Visit the [latest release](https://github.com/websitesql/websitesql/releases) and download the updated source code ZIP file.

3. **Replace the Existing Code**  
   Extract the updated code and paste it over your existing installation directory. Ensure you replace all files while preserving any local configuration files (e.g., `.env`).

4. **Run Database Migrations**  
   Open a terminal in the root directory of the application and run:  
   ```bash
   php wsql migration:run
   ```

5. **Verify the Update**  
   Access your application in a browser to ensure everything is working as expected.  

## **Key Features**
- **Modular Design**: Extend and customize functionality effortlessly.
- **Security First**: Developed with best-in-class security practices to safeguard your data.

Enjoy the seamless experience of **Website SQL V2.1**!
