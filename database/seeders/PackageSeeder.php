<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Package;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        Package::truncate();

        $packages = [
            // Massage Therapies
            ['package_name' => 'AROMATHERAPHY', 'package_desc' => '60 Minutes', 'package_price' => 120.00, 'duration' => '60 Minutes', 'capacity' => 3],
            ['package_name' => 'AROMATHERAPHY', 'package_desc' => '90 Minutes', 'package_price' => 150.00, 'duration' => '90 Minutes', 'capacity' => 3],
            ['package_name' => 'Sport Massage', 'package_desc' => '60 Minutes', 'package_price' => 120.00, 'duration' => '60 Minutes', 'capacity' => 2],
            ['package_name' => 'Sport Massage', 'package_desc' => '90 Minutes', 'package_price' => 150.00, 'duration' => '90 Minutes', 'capacity' => 2],
            ['package_name' => 'Stress Massage', 'package_desc' => '60 Minutes', 'package_price' => 70.00, 'duration' => '60 Minutes', 'capacity' => 4],
            ['package_name' => 'Stress Massage', 'package_desc' => '90 Minutes', 'package_price' => 90.00, 'duration' => '90 Minutes', 'capacity' => 4],
            ['package_name' => 'Foot Reflexology', 'package_desc' => '30 Minutes', 'package_price' => 60.00, 'duration' => '30 Minutes', 'capacity' => 5],
            ['package_name' => 'Foot Reflexology', 'package_desc' => '60 Minutes', 'package_price' => 90.00, 'duration' => '60 Minutes', 'capacity' => 5],

            // Facial Treatments
            ['package_name' => 'Signature Normal Facial', 'package_desc' => '50 Minutes', 'package_price' => 50.00, 'duration' => '50 Minutes', 'capacity' => 2],
            ['package_name' => 'Deep Cleansing Facial', 'package_desc' => '60 Minutes', 'package_price' => 100.00, 'duration' => '60 Minutes', 'capacity' => 2],
            ['package_name' => 'Anti-Aging Facial', 'package_desc' => '90 Minutes', 'package_price' => 150.00, 'duration' => '90 Minutes', 'capacity' => 1],
            ['package_name' => 'Hydrating Facial', 'package_desc' => '90 Minutes', 'package_price' => 250.00, 'duration' => '90 Minutes', 'capacity' => 1],

            // Nail and Foot Care
            ['package_name' => 'Classic Manicure', 'package_desc' => 'Standard', 'package_price' => 55.00, 'duration' => 'N/A', 'capacity' => 4],
            ['package_name' => 'Spa Pedicure', 'package_desc' => 'Standard', 'package_price' => 40.00, 'duration' => 'N/A', 'capacity' => 4],
            ['package_name' => 'Gel Polish Add-on', 'package_desc' => 'Standard', 'package_price' => 20.00, 'duration' => 'N/A', 'capacity' => 4],
            ['package_name' => 'Foot Soak & Massage', 'package_desc' => 'Standard', 'package_price' => 60.00, 'duration' => 'N/A', 'capacity' => 5],

            // Hair Services
            ['package_name' => 'Dry Hair Cut', 'package_desc' => 'Short', 'package_price' => 25.00, 'duration' => 'N/A', 'capacity' => 3],
            ['package_name' => 'Dry Hair Cut', 'package_desc' => 'Long', 'package_price' => 55.00, 'duration' => 'N/A', 'capacity' => 3],
            ['package_name' => 'Men Hair Cut', 'package_desc' => 'Standard', 'package_price' => 18.00, 'duration' => 'N/A', 'capacity' => 3],
            ['package_name' => 'Men Hair Cut', 'package_desc' => 'With Wash', 'package_price' => 28.00, 'duration' => 'N/A', 'capacity' => 3],
            ['package_name' => 'Head Spa Treatment', 'package_desc' => '45 Minutes', 'package_price' => 150.00, 'duration' => '45 Minutes', 'capacity' => 2],
            ['package_name' => 'Wash and Blow', 'package_desc' => 'Short', 'package_price' => 35.00, 'duration' => 'N/A', 'capacity' => 3],
            ['package_name' => 'Wash and Blow', 'package_desc' => 'Long', 'package_price' => 45.00, 'duration' => 'N/A', 'capacity' => 3],
            ['package_name' => 'Scalp Massage Aromatherapy', 'package_desc' => 'Standard', 'package_price' => 25.00, 'duration' => 'N/A', 'capacity' => 3],
        ];

        foreach ($packages as $packageData) {
            Package::create($packageData);
        }
    }
}
