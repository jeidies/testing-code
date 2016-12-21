<?php
/**
 * Created by PhpStorm.
 * User: DanielSimangunsong
 * Date: 12/20/2016
 * Time: 10:16 AM
 */

namespace Webarq\Manager;


trait setPropertyManagerTrait
{
    protected function setup(array &$options = [])
    {
        if ([] !== $options) {
            if ([] !== ($vars = get_class_vars(get_called_class()))) {
                foreach (array_keys($vars) as $key) {
                    $this->{$key} = array_pull($options, $key, $this->{$key});
                }
            }
        }
    }
}