<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Airport>
 */
class AirportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'province_id' => $this->faker->numberBetween(1, 22),
            'airport_name' => $this->faker->city . ' International Airport',
            'address' => $this->faker->address,
            'latitude' => $this->faker->latitude(-11.0, -1.0), // Malaysia
            'longitude' => $this->faker->longitude(140.0, 155.0),
            'telephone' => $this->faker->phoneNumber,
            'fax' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'website' => $this->faker->url,
            'category' => $this->faker->randomElement(['International', 'Domestic', 'Regional Domestic', 'Military', 'Combined', 'Private']),
            'classification' => $this->faker->randomElement(['A', 'B', 'C']),
            'iata_code' => strtoupper($this->faker->lexify('???')),
            'icao_code' => strtoupper($this->faker->lexify('????')),
            'hrs_of_operation' => '24/7',
            'distance_from' => $this->faker->numberBetween(1, 100) . ' km from city center',
            'time_zone' => $this->faker->timezone,
            'operator' => $this->faker->company,
            'magnetic_variation' => $this->faker->randomFloat(1, -30, 30) . '°',
            'beacon' => $this->faker->boolean ? 'Available' : 'Not Available',
            'max_aircraft_capability' => $this->faker->randomElement(['Boeing 747', 'Airbus A380', 'ATR 72']),
            'air_traffic' => $this->faker->randomElement(['High', 'Medium', 'Low']),
            'meteorological' => $this->faker->boolean ? 'Available' : 'Not Available',
            'air_fuel_depot' => $this->faker->boolean ? 'Available' : 'Not Available',
            'supplies_eqipment' => $this->faker->boolean ? 'Complete' : 'Limited',
            'internet' => $this->faker->boolean ? 'Wi-Fi Available' : 'No Wi-Fi',
            'public_facilities' => $this->faker->paragraph,
            'public_transportation' => $this->faker->paragraph,
            'other_airport_info' => implode(', ', $this->faker->randomElements(
                [$this->faker->url, $this->faker->url, $this->faker->url], rand(2, 3)
            )),
            'nearest_accommodation' => implode(', ', $this->faker->randomElements(
                [$this->faker->company, $this->faker->company, $this->faker->company], rand(2, 3)
            )),
            'flight_information' => $this->faker->paragraph,
            'nearest_medical_facility' => $this->faker->company . ' Clinic',
            'distance_with_airport' => $this->faker->numberBetween(1, 200) . ' km',
            'get_direction_medical_facility' => $this->faker->url,
            'nearest_police_station' => $this->faker->company . ' Police Station',
            'address_police_station' => $this->faker->address,
            'phone_police_station' => $this->faker->phoneNumber,
            'hours_of_operation_police' => '08:00 - 17:00',
            'get_direction_police_station' => $this->faker->url,
            'image' => $this->faker->randomElement([
                'https://pg.concordreview.com/wp-content/uploads/2025/03/Lab-Lab-LAB-Airport.jpg',
                'https://pg.concordreview.com/wp-content/uploads/2025/03/69.Safia-SFU-Airport.jpg',
                'https://pg.concordreview.com/wp-content/uploads/2025/03/14.Agali-ALI-Airport.jpg',
                'https://pg.concordreview.com/wp-content/uploads/2025/03/70.Ama-AMF-Airport0.jpg',
                'https://pg.concordreview.com/wp-content/uploads/2025/03/99.Sabah-SBV-Airport.jpg',
            ]),
            'icon' => $this->faker->randomElement([
                'https://pg.concordreview.com/wp-content/plugins/w2gm/resources/images/map_icons/icons/_new/civil-military%20airport.png',
                'https://pg.concordreview.com/wp-content/plugins/w2gm/resources/images/map_icons/icons/_new/regional%20airport.png',
                'https://pg.concordreview.com/wp-content/plugins/w2gm/resources/images/map_icons/icons/_new/International%20Airport.png',
                'https://pg.concordreview.com/wp-content/plugins/w2gm/resources/images/map_icons/icons/_new/military-airport-red.png',
                'https://pg.concordreview.com/wp-content/plugins/w2gm/resources/images/map_icons/icons/_new/private%20airport.png',
                'https://pg.concordreview.com/wp-content/plugins/w2gm/resources/images/map_icons/icons/_new/regional%20domestic%20airport.png',
            ]),
        ];
    }
}
