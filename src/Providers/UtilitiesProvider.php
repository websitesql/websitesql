<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Providers;

class UtilitiesProvider
{
    /*
     * This method returns a random string of a specified length
     * 
     * @return string
     */
    public function randomString(int $length = 10): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    /*
     * This method returns a random number of a specified length
     * 
     * @return int
     */
    public function randomNumber(int $length = 10): int
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomNumber = '';

        for ($i = 0; $i < $length; $i++) {
            $randomNumber .= $characters[rand(0, $charactersLength - 1)];
        }

        return (int) $randomNumber;
    }
    
    /*
     * This method returns a slugified string
     * 
     * @return string
     */
    public function slugify(string $string): string
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
    }

    /*
     * This method returns a time ago string
     * 
     * @return string
     */
    public function timeAgo(string $datetime): string
    {
        $time = strtotime($datetime);
        $now = time();
        $ago = $now - $time;

        if ($ago < 60) {
            return 'just now';
        } elseif ($ago < 3600) {
            return round($ago / 60) . ' minutes ago';
        } elseif ($ago < 86400) {
            return round($ago / 3600) . ' hours ago';
        } elseif ($ago < 604800) {
            return round($ago / 86400) . ' days ago';
        } elseif ($ago < 2592000) {
            return round($ago / 604800) . ' weeks ago';
        } elseif ($ago < 31536000) {
            return round($ago / 2592000) . ' months ago';
        } else {
            return round($ago / 31536000) . ' years ago';
        }
    }

    /*
     * This method gets the current date and time
     * 
     * @param string $date
     * @return string
     */
    public function getDateTime(string $date = null): string
    {
        if (!$date) {
            return date('Y-m-d H:i:s');
        } else {
            return date('Y-m-d H:i:s', strtotime($date));
        }
    }
}