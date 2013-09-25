<?php

class FeedsTableSeeder extends Seeder {

	public function run()
	{
		DB::table('feeds')->truncate();

		$feeds = array(
			array("title" => "ArsTechnica", "url" => "http://feeds.arstechnica.com/arstechnica/index"),
			array("title" => "ReadWrite", "url" => "http://www.readwriteweb.com/rss.xml"),
			array("title" => "TechCrunch", "url" => "http://feeds.feedburner.com/TechCrunch/"),
			array("title" => "io9", "url" => "http://feeds.gawker.com/io9/full"),
			array("title" => "Slashdot", "url" => "http://rss.slashdot.org/Slashdot/slashdot")
		);

		DB::table('feeds')->insert($feeds);
	}

}