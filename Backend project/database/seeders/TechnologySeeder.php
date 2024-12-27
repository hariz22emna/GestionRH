<?php
namespace Database\Seeders;
use App\Models\Technology;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
class TechnologySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $technologies = [
            'Javascript',
            'Angular',
            'Java',
            'Springboot',
            'Laravel',
            'Symfony',
            'PHP',
            'ReactJs',
            'CMS',
            'Prestashop',
            'Wordpress',
            'Shopify',
            'C#',
            'C++',
            'C',
            'Asp.net',
            'Vue.js',
            'Flutter',
            'Business intelligence',
            'Intelligence artificielle',
            'Express.js',
            'Node.js',
            'Nest.js',
            'Nuxt.js',
            'Python',
            'Ruby',
            'Swift',
            'Django',
            'Rust',






        ];
        foreach ($technologies as $technology) {
            Technology::create([
                'name' => $technology,
            ]);
        }
    }
}