<?php

use Illuminate\Database\Seeder;
use App\Country;
use App\City;
class countriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $country1=Country::create([
            'country'=>'Bangladesh'
        ]);
        $country2=Country::create([
            'country'=>'India'
        ]);
        $country2=Country::create([
            'country'=>'Malaysia'
        ]);

        $city11=City::create([
            'city'=>'Dhaka',
            'country_id'=>'1'
        ]);
        $city12=City::create([
            'city'=>'Chittagong',
            'country_id'=>'1'
        ]);
        $city12=City::create([
            'city'=>'Sylhet',
            'country_id'=>'1'
        ]);
        $city13=City::create([
            'city'=>'Rajshahi',
            'country_id'=>'1'
        ]);

        $city21=City::create([
            'city'=>'Delhi',
            'country_id'=>'2'
        ]);
        $city22=City::create([
            'city'=>'Kalkutta',
            'country_id'=>'2'
        ]);
        $city22=City::create([
            'city'=>'Mombai',
            'country_id'=>'2'
        ]);
        $city23=City::create([
            'city'=>'Chennai',
            'country_id'=>'2'
        ]);

        $city31=City::create([
            'city'=>'Kualalampur',
            'country_id'=>'3'
        ]);
        $city32=City::create([
            'city'=>'Kuching',
            'country_id'=>'3'
        ]);
        $city32=City::create([
            'city'=>'Ipoch',
            'country_id'=>'3'
        ]);
        $city33=City::create([
            'city'=>'George Town',
            'country_id'=>'3'
        ]);

    }
}
