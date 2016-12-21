<?php
/**
 * Created by PhpStorm.
 * User: DanielSimangunsong
 * Date: 12/21/2016
 * Time: 5:05 PM
 */

namespace Webarq\Manager\Cms\HTML\Form;


use Webarq\Manager\HTML\FormManager;

class InputManager
{
    /**
     * @var FormManager
     */
    protected $form;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var mixed
     */
    protected $name;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @var object Webarq\Manager\HTML\Form\InputManager
     */
    protected $input;

    /**
     * @var array
     */
    protected $allowedArrayAttributeKeys = ['options'];

    /**
     * @param FormManager $form
     * @param $type
     * @param $name
     * @param null $value
     * @param array $attributes
     */
    public function __construct(FormManager $form, $type, $name, $value = null, array $attributes = [])
    {
        $this->form = $form;
        $this->type = $type;
        $this->name = $name;
        $this->value = $value;
        $this->attributes = $attributes;
// These item should not be in attributes
        $label = array_pull($this->attributes, 'label');
        $info = array_pull($this->attributes, 'info');
// Any value other than the specified attributes key should not be an array
        $this->normalizeAttributes();
// Initialize input manager
        $this->input = $this->buildInput();
        if (isset($label)) {
            $this->input->setLabel($label);
        }
        if (isset($info)) {
            $this->input->setInfo($info);
        }

    }

    /**
     * Any value other than the specified attributes key should not be an array
     *
     * @using $allowedArrayAttributeKeys
     */
    protected function normalizeAttributes()
    {
        foreach ($this->attributes as $key => &$value) {
            if (is_array($value) && !in_array($key, $this->allowedArrayAttributeKeys)) {
                $value = base64_encode(serialize($value));
            }
        }
    }

    protected function buildInput()
    {
        return $this->form->addCollection($this->type, $this->name, $this->value, $this->attributes);
    }
}