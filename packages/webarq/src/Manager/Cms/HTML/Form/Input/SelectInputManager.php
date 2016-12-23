<?php
/**
 * Created by PhpStorm.
 * User: DanielSimangunsong
 * Date: 12/21/2016
 * Time: 5:06 PM
 */

namespace Webarq\Manager\Cms\HTML\Form\Input;


use Webarq\Manager\Cms\HTML\Form\InputManager;

class SelectInputManager extends InputManager
{
    public function buildInput()
    {
        if (true === array_get($this->attributes, 'multiple') || in_array('multiple', $this->attributes)) {
            $this->name .= '[]';
        }
        return $this->form->addCollection(
                $this->type, $this->name, array_pull($this->attributes, 'options', []),
                $this->value, $this->attributes);
    }
}