<?php

use Illuminate\Database\Seeder;
use App\Photo;

class CoverSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$no_of_covers = 6;
		
		$covers = [];
		
		for($i = 1; $i <= $no_of_covers; $i++) {
			array_push($covers, [
				'category' => 'Cover',
				'title' => "cover-no-$i",
				'url' => 'covers/default-cover-1920x1080.jpg',
			]);
		}

		Photo::insert($covers);
	}
}
