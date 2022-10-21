<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormGroupInputGroup extends Component
{
    /**
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
     * @var mixed
     */
    public $required;
    /**
     * @var mixed
     */
    public $step;
    /**
     * @var mixed
     */
    public $readonly;
    /**
     * @var mixed
     */
    public $groupText;
    /**
     * @var mixed
     */
    public $append;
    /**
     * @var mixed
     */
    public $groupIcon;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $label, $groupText, $type = null, $value = null, $col = null, $step = '', $required = true, $readonly = false, $append = true, $groupIcon = true)
    {
        $this->name = $name;
        $this->label = $label;
        $this->type = $type;
        $this->groupText = $groupText;
        $this->groupIcon = $groupIcon;
        $this->value = old($name, $value);
        $this->col = $col;
        $this->step = $step;
        $this->required = $required;
        $this->readonly = $readonly;
        $this->append = $append;
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

    /**
     * @return mixed
     */
    public function getGroupText()
    {
        if ($this->groupIcon) {
            return '<i class="' . $this->groupText . '"></i>';
        }
        return $this->groupText;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form-group-input-group');
    }
}
