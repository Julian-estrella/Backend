<?php
use App\Models\USer;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);
test('un usuario no puede eliminarse a si mismo', function () {
    // 1)Crear un usuario en la BD de pruebas
    $user = User::factory()->create(
        [
            'email_verified_at' => now()
        ]
    );

    //2)Simular que el usuario esta inicio sesion´
    $this->actingAs($user, "web");

    //3)Simular que intenta borrar un usuario
    $response = $this->delete(route('admin.users.destroy', $user));

    //4)Esperar a que el servidor bloquee esta accion
    //$response->assertStatus(403);

    //5)Verificamos que el usuario siga existiendo en BD
    $this->assertDatabaseHas('users',[
        'id'=> $user->id,
    ]);
});
