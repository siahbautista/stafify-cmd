<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class TalentAcquisitionToolkitSeeder extends Seeder
{
    public function run()
    {
        DB::table('talent_acquisition_toolkit')->insert([
            [
                'user_id' => 2,
                'sales_title' => 'Staff Leasing',
                'icon' => 'Globe.gif',
                'type' => 'Talent Pipeline (Sheet)',
                'form_url' => '[{"title":"Back Office Form","url":"https://docs.google.com/forms/u/1/d/1R2RLrtSql_nzz9JgffJTeDYm3lyfz8eN7WEhHNdZMoA/viewform?usp=sharing&edit_requested=true","type":"form"},{"title":"Business Support Form","url":"https://docs.google.com/forms/u/0/d/1XBL6LfJvblk-xhv3oB32X_D9MPcoWYGmJUscU5MBVhU/viewform?usp=sharing&edit_requested=true","type":"form"},{"title":"Digital Marketing Form","url":"https://docs.google.com/forms/u/0/d/1l6wmhGXx9dysy0v0XE8aH9xcA-_SxAD8RpI0uWCL4Bs/viewform?usp=sharing&edit_requested=true","type":"form"},{"title":"Creative Services Form","url":"https://docs.google.com/forms/d/1K5l-SdorU5tkkAeCXS6f4U2NSrqbQ1ewePv1ICNqfsA/viewform?edit_requested=true","type":"form"},{"title":"Tech Development Form","url":"https://docs.google.com/forms/d/1zxlc-NELKCcq_lqs5BsWwyjGz3z_O6x7NAAJxNt1l20/viewform?edit_requested=true","type":"form"},{"title":"Specialized Roles Form","url":"https://docs.google.com/forms/d/1EhMZn7UpBawWjCLtIKCAzY5T6EaVsGk_pvV1Le9Ezvo/viewform?edit_requested=true","type":"form"},{"title":"Stafify ISP Sign-Up Form (Responses)","url":"https://docs.google.com/spreadsheets/d/19dlsEIW4yLBtr1kaJJTV7aUwAotEwGY50y6CnN1t_VA/edit?gid=981629452#gid=981629452","type":"sheet"}]',
                'response_url' => 'https://docs.google.com/spreadsheets/d/19dlsEIW4yLBtr1kaJJTV7aUwAotEwGY50y6CnN1t_VA/edit?gid=981629452#gid=981629452',
                'is_approved' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 2,
                'sales_title' => 'SOD & Tele-Consultation',
                'icon' => 'Globe.gif',
                'type' => 'Talent Pipeline (Sheet)',
                'form_url' => '[{"title":"SOD & Tele-Consultation Form","url":"https://docs.google.com/forms/d/1hlIABgzp6QtwAyGWr7k7mNC47ygN0u4f6KYWqR9MuHw/viewform?edit_requested=true","type":"form"},{"title":"SOD & Tele-Consultation Responses","url":"https://docs.google.com/spreadsheets/d/1x86Dd4cEQJaY9VDA9dtkKgIEBJwSO6Z82GWK_F4YGdA/edit?gid=731454214#gid=731454214","type":"sheet"}]',
                'response_url' => 'https://docs.google.com/spreadsheets/d/1x86Dd4cEQJaY9VDA9dtkKgIEBJwSO6Z82GWK_F4YGdA/edit#gid=277455928',
                'is_approved' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 2,
                'sales_title' => 'Service Marketplace',
                'icon' => 'Globe.gif',
                'type' => 'Talent Pipeline (Sheet)',
                'form_url' => '[{"title":"Service Marketplace Form","url":"https://docs.google.com/forms/d/e/1FAIpQLScy8TrDYKV9_WjBISnakmWx1DLWQ9pZwkvicI7XkasZYHCKuw/viewform","type":"form"},{"title":"Service Marketplace Responses","url":"https://docs.google.com/spreadsheets/d/1HgQ9H0ljrp9mGwlO0CnAj7n2XpkHoWaDesXWjw2zs0E/edit#gid=0","type":"sheet"}]',
                'response_url' => 'https://docs.google.com/spreadsheets/d/1HgQ9H0ljrp9mGwlO0CnAj7n2XpkHoWaDesXWjw2zs0E/edit#gid=0',
                'is_approved' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
