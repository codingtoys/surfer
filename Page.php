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
    public $surfer;

    public static function createFromGuzzleResponse($response, &$surfer)
    {
        $page = new Page($response);
        $page->response = $response;
        $page->surfer = &$surfer;

        return $page;
    }

    public function form($formSelector = 'form')
    {
        $doc = phpQuery::newDocument($this->response->getBody());
        phpQuery::selectDocument($doc);

        $form = $doc[$formSelector];

        if ($form == '') {

            throw new \codingtoys\SurferException('Could not find form object with selector "'.$formSelector.'"', \codingtoys\SurferException::SELECTOR_NOT_FOUND);
        }

        $form = new \codingtoys\Form($form);
        $form->surfer = &$this->surfer;

        return $form;
    }
}
