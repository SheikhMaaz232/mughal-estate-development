<?php

use App\Models\Notebook;
use App\Models\User;
use Spatie\Permission\Models\Permission;

function grantExecutiveReportsAccess(User $user): void
{
    $permission = Permission::findOrCreate('executive-reports.view', 'web');
    $user->givePermissionTo($permission);
}

test('notebooks are assigned to the signed-in user and cannot be reassigned in the request', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    grantExecutiveReportsAccess($user);

    $this->actingAs($user)
        ->withSession(['selected_company_id' => 1])
        ->post(route('executive-reports.notebooks.store'), [
            'name_en' => 'Quarterly report',
            'name_ur' => 'سہ ماہی رپورٹ',
            'description' => 'Notes for the quarter',
            'color' => '#aabbcc',
            'user_id' => $otherUser->id,
        ])
        ->assertRedirect(route('executive-reports.notebooks.index'));

    $this->assertDatabaseHas('notebooks', [
        'name_en' => 'Quarterly report',
        'name_ur' => 'سہ ماہی رپورٹ',
        'user_id' => $user->id,
        'color' => '#aabbcc',
    ]);
    $this->assertDatabaseMissing('notebooks', [
        'name_en' => 'Quarterly report',
        'user_id' => $otherUser->id,
    ]);
});

test('users cannot update another user notebook', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    grantExecutiveReportsAccess($otherUser);
    $notebook = Notebook::create([
        'user_id' => $owner->id,
        'name_en' => 'Private notebook',
        'name_ur' => 'نجی نوٹ بک',
        'description' => 'Private notes',
        'color' => '#aabbcc',
    ]);

    $this->actingAs($otherUser)
        ->withSession(['selected_company_id' => 1])
        ->put(route('executive-reports.notebooks.update', $notebook), [
            'name_en' => 'Changed notebook',
            'name_ur' => 'تبدیل شدہ نوٹ بک',
            'description' => 'Changed notes',
            'color' => '#112233',
        ])
        ->assertNotFound();

    $this->assertDatabaseHas('notebooks', [
        'id' => $notebook->id,
        'user_id' => $owner->id,
        'name_en' => 'Private notebook',
    ]);
});

test('notebooks can be searched by either English or Urdu name', function () {
    $user = User::factory()->create();
    grantExecutiveReportsAccess($user);

    Notebook::create([
        'user_id' => $user->id,
        'name_en' => 'Quarterly report',
        'name_ur' => 'سہ ماہی رپورٹ',
        'color' => '#aabbcc',
    ]);

    $this->actingAs($user)
        ->withSession(['selected_company_id' => 1])
        ->get(route('executive-reports.notebooks.index', ['search' => 'سہ ماہی']))
        ->assertOk()
        ->assertSeeText('Quarterly report');
});
