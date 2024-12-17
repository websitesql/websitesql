<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Providers;

class StringsProvider
{
    /*
     * This method returns the WebsiteSQL version
     * 
     * @return string
     */
    public function getVersion(): string
    {
        return "2.1.0-RC8";
    }

    /*
     * This method returns the WebsiteSQL release ID
     * 
     * @return int
     */
    public function getReleaseID(): int
    {
        return 34;
    }

    /*
     * This method returns the WebsiteSQL admin file path
     * 
     * @return string
     */
    public function getAdminFilePath(): string
    {
        return "admin";
    }

    /*
     * This method returns the WebsiteSQL table activity
     * 
     * @return string
     */
    public function getTableActivity(): string
    {
        return "wsql_activity";
    }

    /*
     * This method returns the WebsiteSQL table customizations
     * 
     * @return string
     */
    public function getTableCustomizations(): string
    {
        return "wsql_customizations";
    }

    /*
     * This method returns the WebsiteSQL table media
     * 
     * @return string
     */
    public function getTableMedia(): string
    {
        return "wsql_media";
    }

    /*
     * This method returns the WebsiteSQL table modules
     * 
     * @return string
     */
    public function getTableModules(): string
    {
        return "wsql_modules";
    }

    /*
     * This method returns the WebsiteSQL table permissions
     * 
     * @return string
     */
    public function getTablePermissions(): string
    {
        return "wsql_permissions";
    }

    /*
     * This method returns the WebsiteSQL table roles
     * 
     * @return string
     */
    public function getTableRoles(): string
    {
        return "wsql_roles";
    }

    /*
     * This method returns the WebsiteSQL table tokens
     * 
     * @return string
     */
    public function getTableTokens(): string
    {
        return "wsql_tokens";
    }

    /*
     * This method returns the WebsiteSQL table users
     * 
     * @return string
     */
    public function getTableUsers(): string
    {
        return "wsql_users";
    }

    /*
     * This method returns the WebsiteSQL authentication message for missing required fields
     * 
     * @param string $lang
     * @return string
     */
    public function getMessageMissingRequiredFields($lang = null): string
    {
        switch ($lang) {
            case "es":
                return "Por favor, asegúrese de que todos los campos estén completos.";
                break;
            case "fr":
                return "Veuillez vous assurer que tous les champs sont remplis.";
                break;
            case "de":
                return "Bitte stellen Sie sicher, dass alle Felder ausgefüllt sind.";
                break;
            default:
                return "Please make sure all fields are populated.";
                break;
        }
    }

    /*
     * This method returns the WebsiteSQL message email or password incorrect
     * 
     * @param string $lang
     * @return string
     */
    public function getMessageEmailOrPasswordIncorrect($lang = null): string
    {
        switch ($lang) {
            case "es":
                return "¡Lo siento! ¡Tu correo electrónico/contraseña es incorrecto, por favor inténtalo de nuevo!";
                break;
            case "fr":
                return "Désolé! Votre e-mail/mot de passe est incorrect, veuillez réessayer!";
                break;
            case "de":
                return "Entschuldigung! Ihre E-Mail/Passwort ist falsch, bitte versuchen Sie es erneut!";
                break;
            default:
                return "Sorry! Your email/password is incorrect, please try again!";
                break;
        }
    }

    /*
     * This method returns the WebsiteSQL message login account locked
     * 
     * @param string $lang
     * @return string
     */
    public function getMessageLoginAccountLocked($lang = null): string
    {
        switch ($lang) {
            case "es":
                return "¡Lo siento! Su cuenta ha sido bloqueada por un administrador.";
                break;
            case "fr":
                return "Désolé! Votre compte a été bloqué par un administrateur.";
                break;
            case "de":
                return "Entschuldigung! Ihr Konto wurde von einem Administrator gesperrt.";
                break;
            default:
                return "Sorry! Your account has been locked by an administrator.";
                break;
        }
    }

    /*
     * This method returns the WebsiteSQL message login account inactive
     * 
     * @param string $lang
     * @return string
     */
    public function getMessageLoginAccountInactive($lang = null): string
    {
        switch ($lang) {
            case "es":
                return "¡Información! Su cuenta aún no ha sido activada.";
                break;
            case "fr":
                return "Info! Votre compte n'a pas encore été activé.";
                break;
            case "de":
                return "Info! Ihr Konto wurde noch nicht aktiviert.";
                break;
            default:
                return "Info! Your account has not been activated yet.";
                break;
        }
    }

    /*
     * This method returns the WebsiteSQL message login accounts email is not verified 
     * 
     * @param string $lang
     * @return string
     */
    public function getMessageLoginAccountEmailNotVerified($lang = null): string
    {
        switch ($lang) {
            case "es":
                return "¡Información! Su correo electrónico aún no ha sido verificado.";
                break;
            case "fr":
                return "Info! Votre e-mail n'a pas encore été vérifié.";
                break;
            case "de":
                return "Info! Ihre E-Mail wurde noch nicht verifiziert.";
                break;
            default:
                return "Info! Your email has not been verified yet.";
                break;
        }
    }

    /*
     * This method returns the WebsiteSQL message media upload forbidden file
     * 
     * @param string $lang
     * @return string
     */
    public function getMessageMediaUploadForbiddenFile($lang = null): string
    {
        switch ($lang) {
            case "es":
                return "El archivo que intentaste subir no era una imagen o un archivo permitido.";
                break;
            case "fr":
                return "Le fichier que vous avez essayé de télécharger n'était pas une image ou un fichier autorisé.";
                break;
            case "de":
                return "Die Datei, die Sie hochladen wollten, war kein Bild oder eine erlaubte Datei.";
                break;
            default:
                return "The file you've tried to upload was not an image or allowed file.";
                break;
        }
    }

    /*
     * This method returns the WebsiteSQL message media upload too big
     * 
     * @param string $lang
     * @return string
     */
    public function getMessageMediaUploadTooBig($lang = null): string
    {
        switch ($lang) {
            case "es":
                return "El archivo que intentaste subir excede el tamaño máximo de archivo.";
                break;
            case "fr":
                return "Le fichier que vous avez essayé de télécharger dépasse la taille maximale autorisée.";
                break;
            case "de":
                return "Die Datei, die Sie hochladen wollten, überschreitet die maximale Dateigröße.";
                break;
            default:
                return "The file you've tried to upload exeeds the max file size.";
                break;
        }
    }

    /*
     * This method returns the WebsiteSQL message media upload upload failure
     * 
     * @param string $lang
     * @return string
     */
    public function getMessageMediaUploadUploadFailure($lang = null): string
    {
        switch ($lang) {
            case "es":
                return "No pudimos subir el archivo, por favor inténtalo de nuevo. Código: 1";
                break;
            case "fr":
                return "Nous n'avons pas pu télécharger le fichier, veuillez réessayer. Code: 1";
                break;
            case "de":
                return "Wir konnten die Datei nicht hochladen, bitte versuchen Sie es erneut. Code: 1";
                break;
            default:
                return "We were not able to upload the file, please try again. Code: 1";
                break;
        }
    }

    /*
     * This method returns the WebsiteSQL message media upload insert failure
     * 
     * @param string $lang
     * @return string
     */
    public function getMessageMediaUploadInsertFailure($lang = null): string
    {
        switch ($lang) {
            case "es":
                return "No pudimos subir el archivo, por favor inténtalo de nuevo. Código: 2";
                break;
            case "fr":
                return "Nous n'avons pas pu télécharger le fichier, veuillez réessayer. Code: 2";
                break;
            case "de":
                return "Wir konnten die Datei nicht hochladen, bitte versuchen Sie es erneut. Code: 2";
                break;
            default:
                return "We were not able to upload the file, please try again. Code: 2";
                break;
        }
    }

    /*
     * This method returns the WebsiteSQL message media upload success
     * 
     * @param string $lang
     * @return string
     */
    public function getMessageMediaUploadSuccess($lang = null): string
    {
        switch ($lang) {
            case "es":
                return "¡Éxito! El archivo se subió correctamente.";
                break;
            case "fr":
                return "Succès! Le fichier a été téléchargé avec succès.";
                break;
            case "de":
                return "Erfolg! Die Datei wurde erfolgreich hochgeladen.";
                break;
            default:
                return "Success! The file was uploaded successfully.";
                break;
        }
    }

    /*
     * This method returns the WebsiteSQL message password mismatch
     * 
     * @param string $lang
     * @return string
     */
    public function getMessagePasswordMismatch($lang = null): string
    {
        switch ($lang) {
            case "es":
                return "¡Lo siento! Las contraseñas no coinciden, por favor inténtalo de nuevo.";
                break;
            case "fr":
                return "Désolé! Les mots de passe ne correspondent pas, veuillez réessayer.";
                break;
            case "de":
                return "Entschuldigung! Die Passwörter stimmen nicht überein, bitte versuchen Sie es erneut.";
                break;
            default:
                return "Sorry! Passwords do not match, please try again.";
                break;
        }
    }

    /*
     * This method returns the WebsiteSQL message for an invalid token
     * 
     * @param string $lang
     * @return string
     */
    public function getMessageInvalidToken($lang = null): string
    {
        switch ($lang) {
            case "es":
                return "¡Lo siento! El token es inválido, por favor inténtalo de nuevo.";
                break;
            case "fr":
                return "Désolé! Le jeton est invalide, veuillez réessayer.";
                break;
            case "de":
                return "Entschuldigung! Das Token ist ungültig, bitte versuchen Sie es erneut.";
                break;
            default:
                return "Sorry! The token is invalid, please try again.";
                break;
        }
    }

    /*
     * This method returns the WebsiteSQL message for a user that already exists
     * 
     * @param string $lang
     * @return string
     */
    public function getMessageUserAlreadyExists($lang = null): string
    {
        switch ($lang) {
            case "es":
                return "¡Lo siento! El usuario ya existe, por favor inténtalo de nuevo.";
                break;
            case "fr":
                return "Désolé! L'utilisateur existe déjà, veuillez réessayer.";
                break;
            case "de":
                return "Entschuldigung! Der Benutzer existiert bereits, bitte versuchen Sie es erneut.";
                break;
            default:
                return "Sorry! The user already exists, please try again.";
                break;
        }
    }

    /*
     * This method returns the WebsiteSQL message for an unknown error occurred
     * 
     * @param string $lang
     * @return string
     */
    public function getMessageUnknownErrorOccurred($lang = null): string
    {
        switch ($lang) {
            case "es":
                return "¡Lo siento! Se produjo un error desconocido, por favor inténtalo de nuevo más tarde.";
                break;
            case "fr":
                return "Désolé! Une erreur inconnue s'est produite, veuillez réessayer plus tard.";
                break;
            case "de":
                return "Entschuldigung! Ein unbekannter Fehler ist aufgetreten, bitte versuchen Sie es später erneut.";
                break;
            default:
                return "Sorry! An unknown error occurred, please try again later.";
                break;
        }
    }

    /*
     * This method returns the WebsiteSQL message for a module not found
     * 
     * @param string $lang
     * @return string
     */
    public function getMessageModuleNotFound($lang = null): string
    {
        switch ($lang) {
            case "es":
                return "Lo siento, no se pudo encontrar el módulo que estás buscando, por favor inténtalo de nuevo.";
                break;
            case "fr":
                return "Désolé, le module que vous recherchez n'a pas pu être trouvé, veuillez réessayer.";
                break;
            case "de":
                return "Entschuldigung, das von Ihnen gesuchte Modul konnte nicht gefunden werden, bitte versuchen Sie es erneut.";
                break;
            default:
                return "Sorry the module you are looking for could not be found, please try again.";
                break;
        }
    }
}