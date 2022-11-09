<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DeleteButton extends Component
{
    public int $id;
    public string $text;
    public string $modal;
    public string $classBtn;
    public string $icon;
    public string $btnClass;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        int $id,
        string $text = 'Delete',
        string $modal = 'DeleteModal',
        string $classBtn = 'delete_button',
        string $icon = 'fas fa-trash',
        string $btnClass = 'btn-danger',
    ){
        $this->id = $id;
        $this->text = $text;
        $this->modal = $modal;
        $this->classBtn = $classBtn;
        $this->icon = $icon;
        $this->btnClass = $btnClass;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.delete-button');
    }
}
