<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Dataset;
use App\Models\IAModel;

class IAModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = IAModel::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'avatar' => $this->faker->word(),
            'ration' => $this->faker->randomFloat(0, 0, 9999999999.),
            'batch_size' => $this->faker->numberBetween(-10000, 10000),
            'shuffle' => $this->faker->boolean(),
            'training_epochs' => $this->faker->numberBetween(-10000, 10000),
            'dataset_id' => Dataset::factory(),
        ];
    }
}
