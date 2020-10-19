<?php

namespace Database\Seeders;

use App\Models\Channel;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ThreadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::factory()
                ->count(10)
                ->create();

        $channels = Channel::factory()
                    ->count(10)
                    ->create();

        foreach ($users as $user) {
            Thread::factory()
                        ->times(rand(1, 9))
                        ->create(['user_id' => $user->id, 'channel_id' => last($channels->toArray())['id']]);
            Arr::pull($channels, count($channels->toArray()) - 1);
        }

        $threads = Thread::get();

        foreach ($threads as $thread) {
            for ($i=0; $i < rand(0, 9); $i++) {
                $id = Arr::random($users->toArray())['id'];
                Reply::factory()
                        ->create(['user_id' => $id, 'thread_id' => $thread->id]);
            }
        }

        $users->push(User::factory()
                        ->create(['name' => 'Adrian Web', 'username' => 'adrian', 'email' => 'adrian@test.com', 'password' => '$2a$04$MJL4ZpY4Nrt1g8tjftUHB.ZOnJkTZstr5SEpwpJLMhdMDEjgYoK3O']));
    }
}
