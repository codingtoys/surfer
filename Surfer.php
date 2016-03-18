<?php

/**
 * @copyright Copyright &copy; Richard Jung, 2016
 * @package surfer
 * @version 1.0.0
 */

namespace codingtoys\Surfer;

use Guzzle\Http\Client;
use Guzzle\Cookie\CookieJar;

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
        $this->cookiesJar = new CookieJar;
        $this->client = new Client([

            'cookies' => $this->cookiesJar
        ]);
    }

    public function open($url)
    {
        $request = $this->client->createRequest('GET', $url);

        $history = new Guzzle\Plugin\History\HistoryPlugin();
        $request->addSubscriber($history);

        $response = $this->client->send($request);

        $this->lastPage = \codingtoys\Surfer\Page::createFromGuzzleResponse($request, $response, $this);

        // echo $res->getStatusCode();
        // echo $res->getHeaderLine('content-type');
        // echo $res->getBody();

        return $this->lastPage;
    }

    public function send($url, $params)
    {
        $request = $this->client->createRequest('POST', $url);

        $history = new Guzzle\Plugin\History\HistoryPlugin();
        $request->addSubscriber($history);

        $response = $this->client->send($request, [

            'form_params' => $params,
        ]);

        $this->lastPage = \codingtoys\Surfer\Page::createFromGuzzleResponse($request, $response, $this);

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

            throw new \codingtoys\Surfer\SurferException('Could not find cookie from cookies jar with name  "'.$name.'" (attention: case-sensitive)', \codingtoys\Surfer\SurferException::COOKIE_NOT_FOUND);
        }

        return $foundCookie;
    }
}
