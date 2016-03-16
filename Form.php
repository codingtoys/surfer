<?php

/**
 * @copyright Copyright &copy; Richard Jung, 2016
 * @package surfer
 * @version 1.0.0
 */

namespace codingtoys\Surfer;

use PhpQuery\PhpQuery as phpQuery;

/**
 * A class to represent a form html object of resulted page
 *
 * @author Richard Jung <richard@coding.toys>
 * @since 1.0
 */
class Form
{
    public $queryObject;
    public $params;
    public $action;
    public $surfer;

    private _isFilledOut = false;

    public function __construct(\PhpQuery\PhpQueryObject $formQueryObject) {

        $this->queryObject = &$formQueryObject;
    }

    /**
     * Fillout the given form. Fields found within the form will be extraced too.
     *
     * @param array $fields The fields to be filled out. Use an formatted array:
     * 
     * ~~~
     * [
     *      'SELECTOR' => 'VALUE',
     *      'SELECTOR' => 'VALUE',
     * ]
     * 
     * ~~~
     *
     * The selector is a jquery like css selector to find an "input" field in the given form.
     *
     * @return \codingtoys\Surfer\Form The form itself
     *
     * Example(s)
     *
     * ~~~
     * use codingtoys\Surfer;
     *
     * $surfer = new Surfer;
     * $loginpage = $surfer->open('https://www.example.com/login');
     *
     * $afterLoginSite = $loginpage->form()->fillout([
     *
     *      '#email' => 'test@example.com',
     *      '#pass' => 'mypassword'
     *
     * ])->submit();
     *
     * ~~~
     *
     */
    public function fillout($fields)
    {
        $form = &$this->queryObject;
        $this->action = $form->attr('action');

        if ($this->action == '') {

            throw new \codingtoys\Surfer\SurferException('Could not find form action of form with selector "'.$sel.'"', \codingtoys\Surfer\SurferException::SELECTOR_NOT_FOUND);
        }

        $this->params = [];

        foreach ($form['input'] as $input) {

            $name = $input->getAttribute('name');
            $value = $input->getAttribute('value');

            if ($name == '') {

                continue;
            }

            $this->params[$name] = $value;
        }

        foreach ($fields as $sel => $field) {

            $input = $form[$sel];

            if ($input == '') {

                throw new \codingtoys\Surfer\SurferException('Could not find field object with selector "'.$sel.'"', \codingtoys\Surfer\SurferException::SELECTOR_NOT_FOUND);
            }

            $name = $input->attr('name');

            if ($name == '') {

                throw new \codingtoys\Surfer\SurferException('Could not find input name of field with selector selector "'.$sel.'"', \codingtoys\Surfer\SurferException::SELECTOR_NOT_FOUND);
            }

            $this->params[$name] = $field;
        }

        $this->_isFilledOut = true;

        return $this;
    }

    /**
     * Submit a filled out form
     *
     * @param array $fields The fields to be filled out. Use an formatted array:
     * 
     * ~~~
     * [
     *      'SELECTOR' => 'VALUE',
     *      'SELECTOR' => 'VALUE',
     * ]
     * 
     * ~~~
     *
     * The selector is a jquery like css selector to find an "input" field in the given form.
     *
     * @return \codingtoys\Surfer\Form The form itself
     *
     * Example(s)
     *
     * ~~~
     * use codingtoys\Surfer;
     *
     * $surfer = new Surfer;
     * $loginpage = $surfer->open('https://www.example.com/login');
     *
     * $afterLoginSite = $loginpage->form()->fillout([
     *
     *      '#email' => 'test@example.com',
     *      '#pass' => 'mypassword'
     *
     * ])->submit();
     *
     * ~~~
     *
     */
    public function submit()
    {
        if (!$this->_isFilledOut) {

            throw new \codingtoys\Surfer\SurferException('The form has to be filled out before it can be submitted.', \codingtoys\Surfer\SurferException::FORM_NOT_FILLED_OUT);
        }

        return $this->surfer->send($this->action, $this->params);
    }
}
