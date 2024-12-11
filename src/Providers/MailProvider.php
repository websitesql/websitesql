<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Providers;

use WebsiteSQL\WebsiteSQL\App;

class MailProvider
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
     * This method sends an email
     * 
     * @param string $to
     * @param string $subject
     * @param string $message
     * @return bool
     */
    public function send(string $to, string $subject, string $message): bool
    {
        // Send email
        return mail($to, $subject, $message);
    }
}