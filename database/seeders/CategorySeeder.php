<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Categorías principales
        $mainCategories = [
            [
                'name' => 'Comida Rápida',
                'description' => 'Hamburguesas, hot dogs, papas fritas y más',
                'icon' => '🍔',
                'color' => '#FF6B35',
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Pizza',
                'description' => 'Pizzas artesanales y tradicionales',
                'icon' => '🍕',
                'color' => '#E74C3C',
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Comida Asiática',
                'description' => 'Sushi, ramen, wok y comida oriental',
                'icon' => '🍜',
                'color' => '#8E44AD',
                'is_featured' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Comida Mexicana',
                'description' => 'Tacos, burritos, quesadillas y más',
                'icon' => '🌮',
                'color' => '#F39C12',
                'is_featured' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Comida Italiana',
                'description' => 'Pasta, lasagna, risotto y especialidades italianas',
                'icon' => '🍝',
                'color' => '#27AE60',
                'is_featured' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Bebidas',
                'description' => 'Refrescos, jugos, bebidas calientes y cócteles',
                'icon' => '🥤',
                'color' => '#3498DB',
                'is_featured' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Postres',
                'description' => 'Helados, tortas, chocolates y dulces',
                'icon' => '🍰',
                'color' => '#E91E63',
                'is_featured' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'Desayunos',
                'description' => 'Desayunos tradicionales y continentales',
                'icon' => '🥐',
                'color' => '#FF9800',
                'is_featured' => false,
                'sort_order' => 8,
            ],
            [
                'name' => 'Comida Saludable',
                'description' => 'Ensaladas, bowls, comida vegana y vegetariana',
                'icon' => '🥗',
                'color' => '#4CAF50',
                'is_featured' => false,
                'sort_order' => 9,
            ],
            [
                'name' => 'Parrilla',
                'description' => 'Carnes a la parrilla, chorizos y asados',
                'icon' => '🥩',
                'color' => '#795548',
                'is_featured' => false,
                'sort_order' => 10,
            ],
            [
                'name' => 'Mariscos',
                'description' => 'Pescados, camarones, ceviches y mariscos frescos',
                'icon' => '🦐',
                'color' => '#00BCD4',
                'is_featured' => false,
                'sort_order' => 11,
            ],
            [
                'name' => 'Comida Árabe',
                'description' => 'Shawarma, falafel, hummus y especialidades árabes',
                'icon' => '🥙',
                'color' => '#607D8B',
                'is_featured' => false,
                'sort_order' => 12,
            ],
        ];

        $createdCategories = [];

        foreach ($mainCategories as $categoryData) {
            $categoryData['slug'] = Str::slug($categoryData['name']);
            $categoryData['is_active'] = true;
            $category = Category::create($categoryData);
            $createdCategories[$categoryData['name']] = $category;
        }

        // Subcategorías para Comida Rápida
        $fastFoodSubs = [
            'Hamburguesas Clásicas', 'Hamburguesas Gourmet', 'Hot Dogs', 'Papas Fritas', 
            'Nuggets de Pollo', 'Alitas de Pollo', 'Wraps', 'Sándwiches'
        ];

        // Subcategorías para Pizza
        $pizzaSubs = [
            'Pizza Tradicional', 'Pizza Gourmet', 'Pizza Vegetariana', 'Pizza Vegana',
            'Pizza Familiar', 'Pizza Personal', 'Calzone', 'Focaccia'
        ];

        // Subcategorías para Comida Asiática
        $asianSubs = [
            'Sushi', 'Ramen', 'Wok', 'Arroz Frito', 'Dim Sum', 'Curry', 'Pad Thai', 'Pho'
        ];

        // Subcategorías para Comida Mexicana
        $mexicanSubs = [
            'Tacos', 'Burritos', 'Quesadillas', 'Nachos', 'Enchiladas', 'Fajitas', 'Guacamole', 'Tostadas'
        ];

        // Subcategorías para Comida Italiana
        $italianSubs = [
            'Pasta', 'Risotto', 'Lasagna', 'Gnocchi', 'Bruschetta', 'Antipasti', 'Osso Buco', 'Tiramisu'
        ];

        // Subcategorías para Bebidas
        $drinkSubs = [
            'Refrescos', 'Jugos Naturales', 'Bebidas Calientes', 'Cócteles Sin Alcohol',
            'Agua Saborizada', 'Energizantes', 'Smoothies', 'Bebidas Deportivas'
        ];

        // Subcategorías para Postres
        $dessertSubs = [
            'Helados', 'Tortas', 'Pasteles', 'Cookies', 'Brownies', 'Cheesecake', 'Flan', 'Mousse'
        ];

        // Subcategorías para Desayunos
        $breakfastSubs = [
            'Huevos', 'Pancakes', 'Waffles', 'Cereales', 'Tostadas', 'Croissants', 'Fruta', 'Yogurt'
        ];

        // Subcategorías para Comida Saludable
        $healthySubs = [
            'Ensaladas', 'Bowls', 'Comida Vegana', 'Comida Vegetariana', 'Quinoa', 'Tofu', 'Smoothie Bowls', 'Wraps Saludables'
        ];

        // Subcategorías para Parrilla
        $grillSubs = [
            'Carne de Res', 'Pollo a la Parrilla', 'Cerdo', 'Chorizos', 'Costillas', 'Cordero', 'Pinchos', 'Asado Mixto'
        ];

        // Subcategorías para Mariscos
        $seafoodSubs = [
            'Pescado Frito', 'Ceviche', 'Camarones', 'Langostinos', 'Pulpo', 'Cazuela de Mariscos', 'Salmón', 'Atún'
        ];

        // Subcategorías para Comida Árabe
        $arabSubs = [
            'Shawarma', 'Falafel', 'Hummus', 'Tabbouleh', 'Kebab', 'Baklava', 'Kofta', 'Labneh'
        ];

        $subcategoriesMap = [
            'Comida Rápida' => $fastFoodSubs,
            'Pizza' => $pizzaSubs,
            'Comida Asiática' => $asianSubs,
            'Comida Mexicana' => $mexicanSubs,
            'Comida Italiana' => $italianSubs,
            'Bebidas' => $drinkSubs,
            'Postres' => $dessertSubs,
            'Desayunos' => $breakfastSubs,
            'Comida Saludable' => $healthySubs,
            'Parrilla' => $grillSubs,
            'Mariscos' => $seafoodSubs,
            'Comida Árabe' => $arabSubs,
        ];

        foreach ($subcategoriesMap as $parentName => $subcategories) {
            $parentCategory = $createdCategories[$parentName];
            $sortOrder = 1;
            
            foreach ($subcategories as $subName) {
                $slug = Str::slug($subName);
                
                // Verificar si el slug ya existe y agregar un sufijo si es necesario
                $counter = 1;
                $originalSlug = $slug;
                while (Category::where('slug', $slug)->exists()) {
                    $slug = $originalSlug . '-' . $counter;
                    $counter++;
                }
                
                Category::create([
                    'name' => $subName,
                    'slug' => $slug,
                    'description' => "Especialidad en {$subName}",
                    'parent_id' => $parentCategory->id,
                    'sort_order' => $sortOrder++,
                    'is_active' => true,
                    'is_featured' => false,
                ]);
            }
        }
    }
}
