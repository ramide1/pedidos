<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});

test('authenticated admin users can visit the dashboard', function () {
    $this->actingAs($user = User::factory()->create(['admin' => true]));

    $this->get('/dashboard')->assertOk();
});

test('authenticated non-admin users are redirected to the home page', function () {
    $this->actingAs($user = User::factory()->create());

    $this->get('/dashboard')->assertRedirect('/');
});

test('non authenticated users can visit the home page', function () {
    $this->get('/')->assertOk();
});
