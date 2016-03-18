<?php

/**
 * @copyright Copyright &copy; Richard Jung, 2016
 * @package surfer
 * @version 1.0.0
 */

namespace codingtoys\Surfer;

use PhpQuery\PhpQuery as phpQuery;

/**
 * A useful helper function for time calculations
 *
 * @author Richard Jung <richard@coding.toys>
 * @since 1.0
 */
class Page
{
    public $response;
    public $request;
    public $surfer;

    public static function createFromGuzzleResponse($request, $response, &$surfer)
    {
        $page = new Page;
        $page->response = $response;
        $page->request = $request;
        $page->surfer = &$surfer;

        return $page;
    }

    public function testOn($text) {

        $regex = '/'.preg_quote($text, '/').'/i';
        $regex = str_replace(' ', '\s*', $regex);

        return $this->testOnRegex($regex);
    }

    public function hasSameBaseUrl(\codingtoys\Surfer\Page $page) {

        var_dump((string)$this->request->getUri());
        var_dump($this->response->getEffectiveUrl());

        exit;
    }

    public function testOnRegex($regex) {

        if (!preg_match($regex, (string)$this->response->getBody())) {

            return false;
        }

        return true;
    }

    public function form($formSelector = 'form')
    {
        $doc = phpQuery::newDocument($this->response->getBody());
        phpQuery::selectDocument($doc);

        $form = $doc[$formSelector];

        if ($form == '') {

            throw new \codingtoys\SurferException('Could not find form object with selector "'.$formSelector.'"', \codingtoys\SurferException::SELECTOR_NOT_FOUND);
        }

        $form = new \codingtoys\Surfer\Form($form);
        $form->surfer = &$this->surfer;

        return $form;
    }
}
