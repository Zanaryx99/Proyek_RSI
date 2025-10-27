<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class Header extends Component
{
    public $user;
    public $profileUrl;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->user = Auth::user();

        if ($this->user) {
            // Logika untuk menentukan URL profil berdasarkan peran
            // Asumsi Anda memiliki kolom 'peran' di model User
            if ($this->user->peran === 'Pemilik') {
                $this->profileUrl = route('profil.pemilik');
            } elseif ($this->user->peran === 'Penghuni') {
                $this->profileUrl = route('profil.penghuni');
            } else {
                // Fallback jika peran tidak terdefinisi
                $this->profileUrl = '#';
            }
        } else {
            $this->profileUrl = '#';
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.header');
    }
}
