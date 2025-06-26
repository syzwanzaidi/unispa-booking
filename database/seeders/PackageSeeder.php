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
            ['package_name' => 'AROMATHERAPHY', 'category' => 'Massage Therapies', 'package_desc' => 'A deeply relaxing full-body massage using custom-blended essential oils to soothe your senses and reduce stress.', 'package_price' => 120.00, 'duration' => '60 Minutes', 'capacity' => 3],
            ['package_name' => 'AROMATHERAPHY', 'category' => 'Massage Therapies', 'package_desc' => 'An extended aromatherapy session for enhanced relaxation, targeting deeper muscle tension with aromatic oils.', 'package_price' => 150.00, 'duration' => '90 Minutes', 'capacity' => 3],
            ['package_name' => 'Sport Massage', 'category' => 'Massage Therapies', 'package_desc' => 'A therapeutic massage focused on muscle recovery, flexibility, and tension release, ideal for active individuals.', 'package_price' => 120.00, 'duration' => '60 Minutes', 'capacity' => 2],
            ['package_name' => 'Sport Massage', 'category' => 'Massage Therapies', 'package_desc' => 'An intensive sport massage session to address chronic pain, muscle knots, and improve athletic performance.', 'package_price' => 150.00, 'duration' => '90 Minutes', 'capacity' => 2],
            ['package_name' => 'Stress Massage', 'category' => 'Massage Therapies', 'package_desc' => 'A gentle and soothing massage designed to alleviate mental and physical stress, promoting a sense of calm and well-being.', 'package_price' => 70.00, 'duration' => '60 Minutes', 'capacity' => 4],
            ['package_name' => 'Stress Massage', 'category' => 'Massage Therapies', 'package_desc' => 'An extended stress-relief massage focusing on areas of tension to achieve deeper relaxation and mental clarity.', 'package_price' => 90.00, 'duration' => '90 Minutes', 'capacity' => 4],
            ['package_name' => 'Foot Reflexology', 'category' => 'Massage Therapies', 'package_desc' => 'Targeted pressure on specific points of the feet to relieve tension, improve circulation, and promote overall body wellness.', 'package_price' => 60.00, 'duration' => '30 Minutes', 'capacity' => 5],
            ['package_name' => 'Foot Reflexology', 'category' => 'Massage Therapies', 'package_desc' => 'An extended foot reflexology session for comprehensive relief and relaxation, focusing on all reflex points.', 'package_price' => 90.00, 'duration' => '60 Minutes', 'capacity' => 5],

            // Facial Treatments
            ['package_name' => 'Signature Normal Facial', 'category' => 'Facial Treatments', 'package_desc' => 'A classic facial treatment to deeply cleanse, gently exfoliate, and nourish your skin, leaving it fresh and radiant.', 'package_price' => 50.00, 'duration' => '50 Minutes', 'capacity' => 2],
            ['package_name' => 'Deep Cleansing Facial', 'category' => 'Facial Treatments', 'package_desc' => 'A thorough facial treatment designed to deeply cleanse pores, remove impurities, and reduce breakouts for clearer skin.', 'package_price' => 100.00, 'duration' => '60 Minutes', 'capacity' => 2],
            ['package_name' => 'Anti-Aging Facial', 'category' => 'Facial Treatments', 'package_desc' => 'A rejuvenating facial treatment formulated with potent anti-aging ingredients to reduce fine lines, wrinkles, and improve skin elasticity.', 'package_price' => 150.00, 'duration' => '90 Minutes', 'capacity' => 1],
            ['package_name' => 'Hydrating Facial', 'category' => 'Facial Treatments', 'package_desc' => 'An intensive moisturizing facial designed to replenish and restore hydration levels, leaving dry and dull skin feeling soft and supple.', 'package_price' => 250.00, 'duration' => '90 Minutes', 'capacity' => 1],

            // Nail and Foot Care
            ['package_name' => 'Classic Manicure', 'category' => 'Nail and Foot Care', 'package_desc' => 'Includes nail shaping, cuticle care, a relaxing hand massage, and your choice of classic polish application.', 'package_price' => 55.00, 'duration' => 'N/A', 'capacity' => 4],
            ['package_name' => 'Spa Pedicure', 'category' => 'Nail and Foot Care', 'package_desc' => 'A luxurious foot treatment with a soothing soak, exfoliation, callous removal, foot massage, and nail care.', 'package_price' => 40.00, 'duration' => 'N/A', 'capacity' => 4],
            ['package_name' => 'Gel Polish Add-on', 'category' => 'Nail and Foot Care', 'package_desc' => 'Enhance your manicure or pedicure with a long-lasting, chip-resistant gel polish application.', 'package_price' => 20.00, 'duration' => 'N/A', 'capacity' => 4],
            ['package_name' => 'Foot Soak & Massage', 'category' => 'Nail and Foot Care', 'package_desc' => 'A refreshing foot soak followed by a tension-relieving foot and lower leg massage.', 'package_price' => 60.00, 'duration' => 'N/A', 'capacity' => 5],

            // Hair Services
            ['package_name' => 'Dry Hair Cut', 'category' => 'Hair Services', 'package_desc' => 'A quick and professional dry hair cut, styled to perfection for short hair.', 'package_price' => 25.00, 'duration' => 'N/A', 'capacity' => 3],
            ['package_name' => 'Dry Hair Cut', 'category' => 'Hair Services', 'package_desc' => 'A precise and professional dry hair cut for longer hair lengths, styled to enhance your features.', 'package_price' => 55.00, 'duration' => 'N/A', 'capacity' => 3],
            ['package_name' => 'Men Hair Cut', 'category' => 'Hair Services', 'package_desc' => 'A precision haircut tailored for men, including styling advice.', 'package_price' => 18.00, 'duration' => 'N/A', 'capacity' => 3],
            ['package_name' => 'Men Hair Cut', 'category' => 'Hair Services', 'package_desc' => 'A professional men\'s haircut followed by a refreshing wash and style.', 'package_price' => 28.00, 'duration' => 'N/A', 'capacity' => 3],
            ['package_name' => 'Head Spa Treatment', 'category' => 'Hair Services', 'package_desc' => 'A revitalizing scalp and hair treatment designed to cleanse, nourish, and relax, promoting healthy hair growth.', 'package_price' => 150.00, 'duration' => '45 Minutes', 'capacity' => 2],
            ['package_name' => 'Wash and Blow', 'category' => 'Hair Services', 'package_desc' => 'A relaxing hair wash followed by a professional blow-dry for short hair, leaving it sleek and voluminous.', 'package_price' => 35.00, 'duration' => 'N/A', 'capacity' => 3],
            ['package_name' => 'Wash and Blow', 'category' => 'Hair Services', 'package_desc' => 'A refreshing hair wash followed by an expert blow-dry for long hair, giving it body and a polished finish.', 'package_price' => 45.00, 'duration' => 'N/A', 'capacity' => 3],
            ['package_name' => 'Scalp Massage Aromatherapy', 'category' => 'Hair Services', 'package_desc' => 'An invigorating scalp massage combined with aromatherapy oils to stimulate circulation and promote relaxation.', 'package_price' => 25.00, 'duration' => 'N/A', 'capacity' => 3],
        ];

        foreach ($packages as $packageData) {
            Package::create($packageData);
        }
    }
}
