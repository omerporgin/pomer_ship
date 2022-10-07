<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $langId = 29; // Default
        if (!is_null($langRow = \App\Models\Language::where('active', 1)->inRandomOrder()->first())) {
            $langId = $langRow->id;
        }

        $userGroupId = 1;
        if (!is_null($langRow = \App\Models\UserGroup::inRandomOrder()->first())) {
            $userGroupId = $langRow->id;
        }

        $countryId = 225; // Turkey
        $stateId = 0;
        $cityId = 0;
        if (!is_null($state = \App\Models\LocationState::where('country_id', $countryId)->inRandomOrder()->first()
        )) {
            $stateId = $state->id;
        }

        if (!is_null($city = \App\Models\LocationCity::where('state_id', $stateId)->inRandomOrder()->first()
        )) {
            $cityId = $city->id;
        }

        return [
            'name' => $this->faker->firstName(),
            'surname' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'address' => $this->faker->address(),
            'password' => $this->faker->password(),
            'lang' => $langId,
            'permission_id' => '2',
            'created_at' => $this->faker->dateTimeBetween($startDate = '-2 years', $endDate = 'now', $timezone = null),
            'updated_at' => $this->faker->dateTimeBetween($startDate = '-2 years', $endDate = 'now', $timezone = null),
            'user_group_id' => $userGroupId,
            'country_id' => $countryId,
            'state_id' => $stateId,
            'city_id' => $cityId,

        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /**
     * Indicate that the user should have a personal team.
     *
     * @return $this
     */
    public function withPersonalTeam()
    {
        if (!Features::hasTeamFeatures()) {
            return $this->state([]);
        }

        return $this->has(
            Team::factory()
                ->state(function (array $attributes, User $user) {
                    return ['name' => $user->name . '\'s Team', 'user_id' => $user->id, 'personal_team' => true];
                }),
            'ownedTeams'
        );
    }
}
