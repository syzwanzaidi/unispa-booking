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
            ['package_name' => 'AROMATHERAPHY', 'category' => 'Massage Therapies', 'package_desc' => '60 Minutes', 'package_price' => 120.00, 'duration' => '60 Minutes', 'capacity' => 3],
            ['package_name' => 'AROMATHERAPHY', 'category' => 'Massage Therapies', 'package_desc' => '90 Minutes', 'package_price' => 150.00, 'duration' => '90 Minutes', 'capacity' => 3],
            ['package_name' => 'Sport Massage', 'category' => 'Massage Therapies', 'package_desc' => '60 Minutes', 'package_price' => 120.00, 'duration' => '60 Minutes', 'capacity' => 2],
            ['package_name' => 'Sport Massage', 'category' => 'Massage Therapies', 'package_desc' => '90 Minutes', 'package_price' => 150.00, 'duration' => '90 Minutes', 'capacity' => 2],
            ['package_name' => 'Stress Massage', 'category' => 'Massage Therapies', 'package_desc' => '60 Minutes', 'package_price' => 70.00, 'duration' => '60 Minutes', 'capacity' => 4],
            ['package_name' => 'Stress Massage', 'category' => 'Massage Therapies', 'package_desc' => '90 Minutes', 'package_price' => 90.00, 'duration' => '90 Minutes', 'capacity' => 4],
            ['package_name' => 'Foot Reflexology', 'category' => 'Massage Therapies', 'package_desc' => '30 Minutes', 'package_price' => 60.00, 'duration' => '30 Minutes', 'capacity' => 5],
            ['package_name' => 'Foot Reflexology', 'category' => 'Massage Therapies', 'package_desc' => '60 Minutes', 'package_price' => 90.00, 'duration' => '60 Minutes', 'capacity' => 5],

            // Facial Treatments
            ['package_name' => 'Signature Normal Facial', 'category' => 'Facial Treatments', 'package_desc' => '50 Minutes', 'package_price' => 50.00, 'duration' => '50 Minutes', 'capacity' => 2],
            ['package_name' => 'Deep Cleansing Facial', 'category' => 'Facial Treatments', 'package_desc' => '60 Minutes', 'package_price' => 100.00, 'duration' => '60 Minutes', 'capacity' => 2],
            ['package_name' => 'Anti-Aging Facial', 'category' => 'Facial Treatments', 'package_desc' => '90 Minutes', 'package_price' => 150.00, 'duration' => '90 Minutes', 'capacity' => 1],
            ['package_name' => 'Hydrating Facial', 'category' => 'Facial Treatments', 'package_desc' => '90 Minutes', 'package_price' => 250.00, 'duration' => '90 Minutes', 'capacity' => 1],

            // Nail and Foot Care
            ['package_name' => 'Classic Manicure', 'category' => 'Nail and Foot Care', 'package_desc' => 'Standard', 'package_price' => 55.00, 'duration' => 'N/A', 'capacity' => 4],
            ['package_name' => 'Spa Pedicure', 'category' => 'Nail and Foot Care', 'package_desc' => 'Standard', 'package_price' => 40.00, 'duration' => 'N/A', 'capacity' => 4],
            ['package_name' => 'Gel Polish Add-on', 'category' => 'Nail and Foot Care', 'package_desc' => 'Standard', 'package_price' => 20.00, 'duration' => 'N/A', 'capacity' => 4],
            ['package_name' => 'Foot Soak & Massage', 'category' => 'Nail and Foot Care', 'package_desc' => 'Standard', 'package_price' => 60.00, 'duration' => 'N/A', 'capacity' => 5],

            // Hair Services
            ['package_name' => 'Dry Hair Cut', 'category' => 'Hair Services', 'package_desc' => 'Short', 'package_price' => 25.00, 'duration' => 'N/A', 'capacity' => 3],
            ['package_name' => 'Dry Hair Cut', 'category' => 'Hair Services', 'package_desc' => 'Long', 'package_price' => 55.00, 'duration' => 'N/A', 'capacity' => 3],
            ['package_name' => 'Men Hair Cut', 'category' => 'Hair Services', 'package_desc' => 'Standard', 'package_price' => 18.00, 'duration' => 'N/A', 'capacity' => 3],
            ['package_name' => 'Men Hair Cut', 'category' => 'Hair Services', 'package_desc' => 'With Wash', 'package_price' => 28.00, 'duration' => 'N/A', 'capacity' => 3],
            ['package_name' => 'Head Spa Treatment', 'category' => 'Hair Services', 'package_desc' => '45 Minutes', 'package_price' => 150.00, 'duration' => '45 Minutes', 'capacity' => 2],
            ['package_name' => 'Wash and Blow', 'category' => 'Hair Services', 'package_desc' => 'Short', 'package_price' => 35.00, 'duration' => 'N/A', 'capacity' => 3],
            ['package_name' => 'Wash and Blow', 'category' => 'Hair Services', 'package_desc' => 'Long', 'package_price' => 45.00, 'duration' => 'N/A', 'capacity' => 3],
            ['package_name' => 'Scalp Massage Aromatherapy', 'category' => 'Hair Services', 'package_desc' => 'Standard', 'package_price' => 25.00, 'duration' => 'N/A', 'capacity' => 3],
        ];

        foreach ($packages as $packageData) {
            Package::create($packageData);
        }
    }
}
