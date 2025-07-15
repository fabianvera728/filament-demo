<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Franchise;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::whereNotNull('parent_id')->get();
        $franchises = Franchise::all();

        $productsByCategory = [
            'Hamburguesas Clásicas' => [
                ['name' => 'Hamburguesa Clásica', 'price' => 15000, 'description' => 'Carne de res, lechuga, tomate, cebolla y salsa especial'],
                ['name' => 'Hamburguesa con Queso', 'price' => 17000, 'description' => 'Nuestra hamburguesa clásica con queso cheddar derretido'],
                ['name' => 'Hamburguesa Doble Carne', 'price' => 22000, 'description' => 'Doble porción de carne con todos los ingredientes'],
                ['name' => 'Hamburguesa BBQ', 'price' => 19000, 'description' => 'Carne de res con salsa BBQ, cebolla caramelizada y tocino'],
                ['name' => 'Hamburguesa Crispy', 'price' => 18000, 'description' => 'Pollo crujiente, lechuga, tomate y mayonesa'],
            ],
            'Hamburguesas Gourmet' => [
                ['name' => 'Hamburguesa Angus Premium', 'price' => 28000, 'description' => 'Carne Angus, queso brie, rúgula y cebolla morada'],
                ['name' => 'Hamburguesa Trufa', 'price' => 35000, 'description' => 'Carne premium con salsa de trufa y queso gruyere'],
                ['name' => 'Hamburguesa Mediterránea', 'price' => 26000, 'description' => 'Cordero, queso feta, aceitunas y tomate seco'],
                ['name' => 'Hamburguesa Portobello', 'price' => 24000, 'description' => 'Hongos portobello, pesto, mozzarella y vegetales'],
                ['name' => 'Hamburguesa Salmón', 'price' => 32000, 'description' => 'Salmón fresco, crema de eneldo y pepino'],
            ],
            'Pizza Tradicional' => [
                ['name' => 'Pizza Margherita', 'price' => 25000, 'description' => 'Salsa de tomate, mozzarella fresca y albahaca'],
                ['name' => 'Pizza Pepperoni', 'price' => 28000, 'description' => 'Salsa de tomate, mozzarella y pepperoni'],
                ['name' => 'Pizza Hawaiana', 'price' => 27000, 'description' => 'Jamón, piña y mozzarella'],
                ['name' => 'Pizza Cuatro Quesos', 'price' => 30000, 'description' => 'Mozzarella, parmesano, gorgonzola y provolone'],
                ['name' => 'Pizza Napolitana', 'price' => 26000, 'description' => 'Anchoas, alcaparras, aceitunas y mozzarella'],
            ],
            'Pizza Gourmet' => [
                ['name' => 'Pizza Prosciutto', 'price' => 38000, 'description' => 'Prosciutto di Parma, rúgula, parmesano y aceite de oliva'],
                ['name' => 'Pizza Trufa Negra', 'price' => 45000, 'description' => 'Crema de trufa, hongos porcini y mozzarella búfala'],
                ['name' => 'Pizza de Salmón', 'price' => 42000, 'description' => 'Salmón ahumado, crema agria, eneldo y cebolla morada'],
                ['name' => 'Pizza Duck Confit', 'price' => 48000, 'description' => 'Pato confitado, cerezas, queso de cabra y nueces'],
                ['name' => 'Pizza Burrata', 'price' => 40000, 'description' => 'Burrata fresca, tomates cherry y pesto de albahaca'],
            ],
            'Sushi' => [
                ['name' => 'Sushi Salmón', 'price' => 8000, 'description' => 'Pieza de sushi con salmón fresco'],
                ['name' => 'Sushi Atún', 'price' => 9000, 'description' => 'Pieza de sushi con atún rojo'],
                ['name' => 'California Roll', 'price' => 22000, 'description' => 'Rollo con cangrejo, palta y pepino'],
                ['name' => 'Philadelphia Roll', 'price' => 26000, 'description' => 'Salmón, queso Philadelphia y pepino'],
                ['name' => 'Dragon Roll', 'price' => 35000, 'description' => 'Tempura de camarón, palta y anguila'],
                ['name' => 'Sashimi Variado', 'price' => 45000, 'description' => 'Selección de pescados frescos sin arroz'],
                ['name' => 'Chirashi Bowl', 'price' => 38000, 'description' => 'Bowl de arroz con variedad de pescados'],
            ],
            'Tacos' => [
                ['name' => 'Tacos al Pastor', 'price' => 12000, 'description' => 'Cerdo marinado con piña y cilantro'],
                ['name' => 'Tacos de Carnitas', 'price' => 13000, 'description' => 'Cerdo confitado con cebolla y salsa verde'],
                ['name' => 'Tacos de Pollo', 'price' => 11000, 'description' => 'Pollo a la plancha con guacamole'],
                ['name' => 'Tacos de Pescado', 'price' => 15000, 'description' => 'Pescado empanizado con salsa tártara'],
                ['name' => 'Tacos Vegetarianos', 'price' => 10000, 'description' => 'Hongos, frijoles negros y vegetales'],
            ],
            'Pasta' => [
                ['name' => 'Spaghetti Carbonara', 'price' => 22000, 'description' => 'Pasta con huevo, panceta, parmesano y pimienta'],
                ['name' => 'Fettuccine Alfredo', 'price' => 20000, 'description' => 'Pasta con salsa cremosa de mantequilla y parmesano'],
                ['name' => 'Lasagna Bolognesa', 'price' => 25000, 'description' => 'Lasagna con carne, bechamel y mozzarella'],
                ['name' => 'Ravioli de Ricotta', 'price' => 24000, 'description' => 'Ravioles rellenos de ricotta con salsa de tomate'],
                ['name' => 'Penne Arrabbiata', 'price' => 18000, 'description' => 'Pasta con salsa picante de tomate y ajo'],
            ],
            'Refrescos' => [
                ['name' => 'Coca Cola', 'price' => 4000, 'description' => 'Bebida gaseosa clásica 350ml'],
                ['name' => 'Pepsi', 'price' => 4000, 'description' => 'Bebida gaseosa 350ml'],
                ['name' => 'Sprite', 'price' => 4000, 'description' => 'Bebida gaseosa de limón 350ml'],
                ['name' => 'Fanta Naranja', 'price' => 4000, 'description' => 'Bebida gaseosa sabor naranja 350ml'],
                ['name' => 'Agua con Gas', 'price' => 3500, 'description' => 'Agua mineral con gas 500ml'],
            ],
            'Jugos Naturales' => [
                ['name' => 'Jugo de Naranja', 'price' => 6000, 'description' => 'Jugo de naranja recién exprimido'],
                ['name' => 'Jugo de Mango', 'price' => 7000, 'description' => 'Jugo natural de mango'],
                ['name' => 'Jugo de Maracuyá', 'price' => 6500, 'description' => 'Jugo natural de maracuyá'],
                ['name' => 'Limonada', 'price' => 5000, 'description' => 'Limonada natural con hierbabuena'],
                ['name' => 'Jugo Verde', 'price' => 8000, 'description' => 'Apio, pepino, limón, espinaca y manzana verde'],
            ],
            'Helados' => [
                ['name' => 'Helado de Vainilla', 'price' => 8000, 'description' => 'Helado cremoso de vainilla'],
                ['name' => 'Helado de Chocolate', 'price' => 8000, 'description' => 'Helado de chocolate belga'],
                ['name' => 'Helado de Fresa', 'price' => 8000, 'description' => 'Helado de fresa natural'],
                ['name' => 'Sundae de Caramelo', 'price' => 12000, 'description' => 'Helado con salsa de caramelo y nueces'],
                ['name' => 'Copa Brownie', 'price' => 15000, 'description' => 'Helado con brownie y salsa de chocolate'],
            ],
            'Ensaladas' => [
                ['name' => 'Ensalada César', 'price' => 16000, 'description' => 'Lechuga romana, crutones, parmesano y aderezo César'],
                ['name' => 'Ensalada Griega', 'price' => 18000, 'description' => 'Tomate, pepino, aceitunas, queso feta y aceite de oliva'],
                ['name' => 'Ensalada de Quinoa', 'price' => 20000, 'description' => 'Quinoa, vegetales frescos y vinagreta de limón'],
                ['name' => 'Ensalada Caprese', 'price' => 17000, 'description' => 'Tomate, mozzarella, albahaca y aceite de oliva'],
                ['name' => 'Bowl de Salmón', 'price' => 25000, 'description' => 'Salmón a la plancha, quinoa, palta y vegetales'],
            ],
            'Carne de Res' => [
                ['name' => 'Churrasco', 'price' => 35000, 'description' => 'Corte premium de carne a la parrilla'],
                ['name' => 'Bife de Chorizo', 'price' => 32000, 'description' => 'Jugoso corte de lomo a la parrilla'],
                ['name' => 'Costillas BBQ', 'price' => 38000, 'description' => 'Costillas glaseadas con salsa BBQ'],
                ['name' => 'Entraña', 'price' => 30000, 'description' => 'Entraña marinada a la parrilla'],
                ['name' => 'Picanha', 'price' => 36000, 'description' => 'Corte brasileño con sal gruesa'],
            ],
            'Ceviche' => [
                ['name' => 'Ceviche de Pescado', 'price' => 22000, 'description' => 'Pescado fresco marinado en limón'],
                ['name' => 'Ceviche Mixto', 'price' => 28000, 'description' => 'Pescado, camarones y pulpo'],
                ['name' => 'Ceviche de Camarón', 'price' => 25000, 'description' => 'Camarones frescos en leche de tigre'],
                ['name' => 'Tiradito', 'price' => 24000, 'description' => 'Pescado en finas láminas con ají amarillo'],
                ['name' => 'Leche de Tigre', 'price' => 8000, 'description' => 'Jugo concentrado de ceviche'],
            ],
            'Shawarma' => [
                ['name' => 'Shawarma de Pollo', 'price' => 14000, 'description' => 'Pollo marinado con vegetales y salsa tahini'],
                ['name' => 'Shawarma de Cordero', 'price' => 18000, 'description' => 'Cordero especiado con yogurt y menta'],
                ['name' => 'Shawarma Mixto', 'price' => 16000, 'description' => 'Pollo y cordero con vegetales frescos'],
                ['name' => 'Falafel Wrap', 'price' => 12000, 'description' => 'Croquetas de garbanzo con ensalada'],
                ['name' => 'Plato Shawarma', 'price' => 20000, 'description' => 'Shawarma servido con arroz y ensalada'],
            ],
        ];

        foreach ($productsByCategory as $categoryName => $products) {
            $category = $categories->where('name', $categoryName)->first();
            if (!$category) continue;

            foreach ($products as $index => $productData) {
                $franchise = $franchises->random();
                
                Product::create([
                    'name' => $productData['name'],
                    'slug' => Str::slug($productData['name']),
                    'description' => $productData['description'],
                    'short_description' => substr($productData['description'], 0, 100),
                    'sku' => strtoupper(Str::random(3)) . '-' . ($index + 1) . '-' . strtoupper(substr($categoryName, 0, 3)),
                    'barcode' => '789' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                    'price' => $productData['price'],
                    'cost_price' => $productData['price'] * 0.6,
                    'compare_price' => $productData['price'] * 1.2,
                    'category_id' => $category->id,
                    'franchise_id' => $franchise->id,
                    'stock_quantity' => rand(10, 100),
                    'min_stock_level' => rand(3, 10),
                    'track_inventory' => true,
                    'weight' => rand(100, 1000) / 100,
                    'is_active' => true,
                    'is_featured' => rand(0, 10) > 7,
                    'requires_shipping' => true,
                    'tags' => $this->generateTags($categoryName),
                    'attributes' => $this->generateFeatures($categoryName),
                    'sort_order' => $index + 1,
                    'rating_average' => rand(35, 50) / 10,
                    'rating_count' => rand(5, 150),
                    'view_count' => rand(50, 1000),
                    'purchase_count' => rand(5, 200),
                ]);
            }
        }
    }

    private function generateTags($categoryName): array
    {
        $baseTags = ['popular', 'recomendado', 'fresco'];
        
        $categoryTags = [
            'Hamburguesas Clásicas' => ['carne', 'tradicional', 'sabroso'],
            'Pizza' => ['italiana', 'hornos', 'queso'],
            'Sushi' => ['japonés', 'pescado', 'saludable'],
            'Tacos' => ['mexicano', 'picante', 'tradicional'],
            'Pasta' => ['italiana', 'casera', 'cremosa'],
            'Ensaladas' => ['saludable', 'fresco', 'ligero'],
            'Bebidas' => ['refrescante', 'natural'],
            'Postres' => ['dulce', 'cremoso', 'delicioso'],
        ];

        $tags = $baseTags;
        foreach ($categoryTags as $cat => $catTags) {
            if (strpos($categoryName, $cat) !== false || strpos($cat, $categoryName) !== false) {
                $tags = array_merge($tags, $catTags);
                break;
            }
        }

        return array_unique($tags);
    }

    private function generateFeatures($categoryName): array
    {
        $features = [];

        if (strpos($categoryName, 'Hamburguesa') !== false || strpos($categoryName, 'Pizza') !== false) {
            $features = ['Sin gluten disponible', 'Ingredientes frescos', 'Hecho al momento'];
        } elseif (strpos($categoryName, 'Sushi') !== false) {
            $features = ['Pescado del día', 'Arroz premium', 'Técnica tradicional'];
        } elseif (strpos($categoryName, 'Ensalada') !== false) {
            $features = ['Vegetales orgánicos', 'Bajo en calorías', 'Rico en nutrientes'];
        } elseif (strpos($categoryName, 'Bebida') !== false || strpos($categoryName, 'Jugo') !== false) {
            $features = ['Sin conservantes', 'Natural', 'Frío'];
        } else {
            $features = ['Preparado fresco', 'Ingredientes de calidad', 'Porción generosa'];
        }

        return $features;
    }
}
