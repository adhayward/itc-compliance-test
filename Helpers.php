<?php
namespace app;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use stdClass;

/**
 * Helper methods for use in this application
 */
class Helpers {
    /**
     * Render a view file with the given parameters
     *
     * @param string $templateFile The filename of the view
     * @param array $vars Data to be passed to the view
     * @param string $layout The view layout filename
     * @return string The rendered output string
     */
    public static function render(string $templateFile, array $vars = array(), $layout = 'layout') : string
    {
        ob_start();
        extract($vars);
        require(__DIR__.'/views/'.$templateFile.'.php');

        $content = ob_get_clean();
        if ($layout) {
            return static::render('layout', ['content'=>$content], false);
        } else {
            return $content;
        }
    }
    /**
     * Sanitise a give value for display on screen. 
     *  - Array's will be combined to comma separated lists. 
     *  - Html Entities will be encoded
     *  - Blanks will display a default value
     *
     * @param string|Array $value The value to be sanitised - either a string value or an array of strings
     * @param string $default The value displayed for blank fields
     * @return string The sanitised string
     */
    public static function sanitiseString($value, string $default = '<em class="text-secondary">N/A</em>') :string {
        if (is_array($value)) {
            return implode(", ", array_map(function($v) {return static::sanitiseString($v);}, $value));
        }
        if ($value) {
            return htmlentities($value);
        } else {
            return $default;  
        }
    }

    const API_ENDPOINT = "https://www.itccompliance.co.uk/recruitment-webservice/api/";
    const API_RETRY_LIMIT = 10;
    private static Client $client;
    /**
     * Initialise a Guzzle Client 
     *
     * @return Client
     */
    private static function getClient() : Client {
        return static::$client ?? ($client = new Client(['base_uri' => static::API_ENDPOINT]));
    }
    /**
     * Fetch data from the API Endpoint
     *
     * @param string $method The API method to call
     * @param Array $data Query Parameters to be passed to the API
     * @param integer|bool $retry Retry counter - set to false to prevent retries
     * @return stdClass The decoded response data
     */
    public static function queryApi(string $method, Array $data, $retry = 0) : stdClass {
        
        try {
            $response = static::getClient()->request('GET', $method, ['query' => $data]);
            if ($responseData = json_decode($response->getBody())) {
                if (isset($responseData->error)) {
                    return static::retry($method, $data, $retry);
                } else {
                    return $responseData;
                }
            } else {
                return static::retry($method, $data, $retry);
            }
        } catch (ClientException $e) {
            return static::retry($method, $data, $retry);
        }
    }
    /**
     * Check the current retry attempt against the configured maximum and call queryAPI again
     * If the maximum attempts is exceeded return null.
     *
     * @param string $method The API method to call
     * @param Array $data Query Parameters to be passed to the API
     * @param integer|bool $retry Retry counter - set to false to prevent retries
     * @return stdClass The decoded response data
     */
    private static function retry(string $method, Array $data, $retry) : stdClass  {
        $retry++;
        if ($retry !== false && $retry < static::API_RETRY_LIMIT) {
            return static::queryApi($method, $data, $retry);
        } else {
            return null;
        }
    }
}