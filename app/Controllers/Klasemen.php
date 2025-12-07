<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KlasemenModel;

class Klasemen extends BaseController
{
    public function index()
    {
        $model = new KlasemenModel();
        
        $data = [
            'title'    => 'Klasemen Liga | SoccerMatch',
            // Kita ambil data dan kelompokkan berdasarkan nama Liga biar rapi
            'klasemen' => $this->group_by($model->getKlasemenLengkap(), 'liga') 
        ];

        return view('user/klasemen', $data);
    }

    // Fungsi helper sederhana untuk mengelompokkan array berdasarkan key (Liga)
    private function group_by($array, $key) {
        $return = array();
        foreach($array as $val) {
            $return[$val[$key]][] = $val;
        }
        return $return;
    }
}