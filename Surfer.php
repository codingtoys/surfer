<?php

/**
 * @copyright Copyright &copy; Richard Jung, 2016
 * @package surfer
 * @version 1.0.0
 */

namespace codingtoys;

/**
 * A useful helper function for time calculations
 *
 * @author Richard Jung <richard@coding.toys>
 * @since 1.0
 */
class Surfer
{
    public $lastPage;
    public $client = false;
    public $cookiesJar;

    public function __construct()
    {
        $this->cookiesJar = new \GuzzleHttp\Cookie\CookieJar;
        $this->client = new \GuzzleHttp\Client();
    }

    public function open($url)
    {
        $res = $this->client->request('GET', $url, [

            'cookies' => $this->cookiesJar
        ]);
        $this->lastPage = Surfer\Page::createFromGuzzleResponse($res, $this);

        // echo $res->getStatusCode();
        // echo $res->getHeaderLine('content-type');
        // echo $res->getBody();

        return $this->lastPage;
    }

    public function send($url, $params)
    {
        $res = $this->client->request('POST', $url, [
        
            'form_params' => $params,
            'cookies' => $this->cookiesJar
        ]);

        $this->lastPage = Surfer\Page::createFromGuzzleResponse($res, $this);

        return $this->lastPage;
    }

    public function cookie($name)
    {
        $foundCookie = false;

        foreach ($this->cookiesJar as $cookie) {

            if ($cookie->getName() == $name) {

                $foundCookie = $cookie;
                break;
            }
        }

        if ($foundCookie === false) {

            throw new \codingtoys\SurferException('Could not find cookie from cookies jar with name  "'.$name.'" (attention: case-sensitive)', \codingtoys\SurferException::COOKIE_NOT_FOUND);
        }

        return $foundCookie;
    }
}
