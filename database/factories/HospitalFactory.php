<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hospital>
 */
class HospitalFactory extends Factory
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
            'name' => $this->faker->company . ' Hospital',
            'classification_hospital' => $this->faker->randomElement(['Level 1', 'Level 2', 'Level 3', 'Level 4', 'Level 5', 'Level 6']),
            'province' => $this->faker->state,
            'region' => $this->faker->city,
            'facility_level' => $this->faker->randomElement(['Primary', 'Secondary', 'Tertiary']),
            'address' => $this->faker->address,
            'status' => $this->faker->randomElement(['Public', 'Private']),
            'house_of_operations' => $this->faker->randomElement(['Yes', 'No']),
            'emergency_services' => $this->faker->randomElement(['Yes', 'No']),
            'outpatient_services_general' => $this->faker->randomElement(['Yes', 'No']),
            'inpatient_services_general' => $this->faker->randomElement(['Yes', 'No']),
            'number_of_beds' => $this->faker->numberBetween(10, 300),
            'population_catchment' => $this->faker->numberBetween(1000, 100000),
            'ownership' => $this->faker->randomElement(['Government', 'NGO', 'Private']),
            'telephone' => implode(', ', $this->faker->unique()->randomElements(
                [$this->faker->phoneNumber, $this->faker->phoneNumber, $this->faker->phoneNumber], rand(2, 3)
            )),
            'email' => $this->faker->unique()->safeEmail,
            'website' => $this->faker->url,
            'nearest_accommodations' => implode(', ', $this->faker->randomElements(
                [$this->faker->company, $this->faker->company, $this->faker->company], rand(2, 3)
            )),
            'latitude' => $this->faker->latitude(-11.0, -1.0), // Malaysia
            'longitude' => $this->faker->longitude(140.0, 155.0),
            'inpatient_services' => $this->faker->randomElement(['Yes', 'No']),
            'outpatient_services' => $this->faker->randomElement(['Yes', 'No']),
            'hr_ER_services' => $this->faker->randomElement(['Yes', 'No']),
            'ambulance' => $this->faker->randomElement(['Yes', 'No']),
            'helipad' => $this->faker->randomElement(['Yes', 'No']),
            'icu' => $this->faker->randomElement(['Yes', 'No']),
            'medical' => $this->faker->randomElement(['Yes', 'No']),
            'pediatric' => $this->faker->randomElement(['Yes', 'No']),
            'dental' => $this->faker->randomElement(['Yes', 'No']),
            'optical' => $this->faker->randomElement(['Yes', 'No']),
            'ioc' => $this->faker->randomElement(['Yes', 'No']),
            'laboratory' => $this->faker->randomElement(['Yes', 'No']),
            'pharmacy' => $this->faker->randomElement(['Yes', 'No']),
            'medical_imaging' => $this->faker->randomElement(['Yes', 'No']),
            'medical_student_training' => $this->faker->randomElement(['Yes', 'No']),
            'doctors' => $this->faker->numberBetween(1, 50),
            'nurses' => $this->faker->numberBetween(5, 100),
            'dental_therapist' => $this->faker->numberBetween(0, 5),
            'laboratory_assitent' => $this->faker->numberBetween(0, 10),
            'community_health_workers' => $this->faker->numberBetween(0, 30),
            'health_inspectors' => $this->faker->numberBetween(0, 10),
            'malaria_control_officers' => $this->faker->numberBetween(0, 5),
            'health_extension_officers' => $this->faker->numberBetween(0, 10),
            'casuals' => $this->faker->numberBetween(0, 20),
            'others' => $this->faker->numberBetween(0, 10),
            'nearest_airport' => $this->faker->city . ' Airport',
            'distance_with_airport' => $this->faker->numberBetween(1, 200) . ' km',
            'get_direction_airport' => $this->faker->url,
            'local_travel_support' => $this->faker->randomElement(['Yes', 'No']),
            'nearest_police_station' => $this->faker->company . ' Police Station',
            'distance_with_police_station' => $this->faker->numberBetween(1, 200) . ' km',
            'get_direction_police_station' => $this->faker->url,
            'address_police_station' => $this->faker->address,
            'phone_police_station' => $this->faker->phoneNumber,
            'hours_of_operation_police' => '08:00 - 17:00',
            'medical_support_websites' => implode(', ', $this->faker->randomElements(
                [$this->faker->url, $this->faker->url, $this->faker->url], rand(2, 3)
            )),
            'image' => $this->faker->randomElement([
                'https://devpolicy.org/wp-content/uploads/2018/05/Port-Moresby-General-Hospital-Credit-PMGH-Facebook-page.jpg',
                'https://pg.concordreview.com/wp-content/uploads/2025/03/Mosa-Urban-Clinic-1.jpg',
                'https://pg.concordreview.com/wp-content/uploads/2024/08/Foto_01.Misima-District-Hospital-Papua-Nugini-2024.jpg',
                'https://pg.concordreview.com/wp-content/uploads/2024/08/Telefomin-District-Hospital.jpg',
                'https://pg.concordreview.com/wp-content/uploads/2024/08/Foto_02.Kerema-Hospital.jpg',
            ]),
            'icon' => $this->faker->randomElement([
                'https://pg.concordreview.com/wp-content/plugins/w2gm/resources/images/map_icons/icons/_new/hospital_pin-tosca-m.png',
                'https://pg.concordreview.com/wp-content/plugins/w2gm/resources/images/map_icons/icons/_new/hospital_pin-orange-m.png',
                'https://pg.concordreview.com/wp-content/plugins/w2gm/resources/images/map_icons/icons/_new/hospital_pin-green-m.png',
                'https://pg.concordreview.com/wp-content/plugins/w2gm/resources/images/map_icons/icons/_new/hospital_pin-purple-m.png',
                'https://pg.concordreview.com/wp-content/plugins/w2gm/resources/images/map_icons/icons/_new/hospital_pin-blue-m.png',
                'https://pg.concordreview.com/wp-content/plugins/w2gm/resources/images/map_icons/icons/_new/hospital_pin-red-m.png',
            ]),
        ];
    }
}
