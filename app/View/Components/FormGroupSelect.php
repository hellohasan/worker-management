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
    public $type;
    /**
     * @var mixed
     */
    public $label;
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
    public function __construct($name, $label, $type = null, $value = null, $col = null)
    {
        $this->name = $name;
        $this->label = $label;
        $this->type = $type;
        $this->value = $value;
        $this->col = $col;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        if ($this->value == null) {
            return old($this->name);
        }

        return $this->value;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        if ($this->type == null) {
            return 'text';
        }

        return $this->type;
    }

    public function render()
    {
        return view('components.form-group-select');
    }
}
