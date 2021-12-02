<?php

use App\Position;
use Illuminate\Database\Seeder;

class PositionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $positions = Position::orderBy('id', 'asc')->get();
        $serialNumber = 1;
        foreach ($positions as $key => $position){
            $serialCode = str_pad($serialNumber, 3, '0', STR_PAD_LEFT);
            $position->code = $serialCode;
            $position->save();
            $serialNumber++;
        }
    }
}
