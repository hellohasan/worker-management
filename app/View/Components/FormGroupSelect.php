<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormGroupSelect extends Component
{/**
 * @var mixed
 */
    public $name;
    /**
     * @var mixed
     */
    public $label;
    /**
     * @var mixed
     */
    public $options;
    /**
     * @var mixed
     */
    public $value;
    /**
     * @var mixed
     */
    public $col;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $name, string $label, $options, $value = null, string $col = null)
    {
        $this->name = $name;
        $this->label = $label;
        $this->col = $col;
        $this->options = $options;
        $this->value = old($name, $value);
    }

    /**
     * @param string $optionName
     * @return mixed
     */
    public function isSelected($option)
    {
        return $option == $this->value ? true : false;
    }

    public function render()
    {
        return view('components.form-group-select');
    }
}
