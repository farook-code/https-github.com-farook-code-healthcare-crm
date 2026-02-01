<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Plan;
use App\Models\Subscription;
use Database\Seeders\PlanSeeder;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed plans
        $this->seed(PlanSeeder::class);
    }

    public function test_subscription_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('subscriptions.index'));

        $response->assertStatus(200);
        $response->assertSee('Choose Your Plan');
        $response->assertSee('Basic');
        $response->assertSee('Pro');
    }

    public function test_user_can_subscribe_to_a_plan(): void
    {
        $user = User::factory()->create();
        $plan = Plan::where('slug', 'premium')->first();

        $response = $this->actingAs($user)->post(route('subscriptions.store'), [
            'plan_id' => $plan->id,
        ]);

        $response->assertRedirect(route('subscriptions.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('subscriptions', [
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => 'active',
        ]);
    }

    public function test_subscribing_cancels_previous_subscription(): void
    {
        $user = User::factory()->create();
        $basicPlan = Plan::where('slug', 'basic')->first();
        $proPlan = Plan::where('slug', 'pro')->first();

        // Subscribe to Basic
        $this->actingAs($user)->post(route('subscriptions.store'), [
            'plan_id' => $basicPlan->id,
        ]);

        // Assert Basic is active
        $this->assertDatabaseHas('subscriptions', [
            'user_id' => $user->id,
            'plan_id' => $basicPlan->id,
            'status' => 'active',
        ]);

        // Subscribe to Pro
        $this->actingAs($user)->post(route('subscriptions.store'), [
            'plan_id' => $proPlan->id,
        ]);

        // Assert Basic is cancelled
        $this->assertDatabaseHas('subscriptions', [
            'user_id' => $user->id,
            'plan_id' => $basicPlan->id,
            'status' => 'cancelled',
        ]);

        // Assert Pro is active
        $this->assertDatabaseHas('subscriptions', [
            'user_id' => $user->id,
            'plan_id' => $proPlan->id,
            'status' => 'active',
        ]);
    }
}
